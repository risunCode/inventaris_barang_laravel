<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Commodity;
use App\Models\Disposal;
use App\Models\User;
use App\Notifications\DisposalRequested;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\View\View;

class DisposalController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:disposals.view', only: ['index', 'show']),
            new Middleware('permission:disposals.create', only: ['create', 'store']),
            new Middleware('permission:disposals.approve', only: ['approve', 'reject']),
            new Middleware('permission:disposals.delete', only: ['destroy']),
        ];
    }

    /**
     * Tampilkan daftar pengajuan penghapusan.
     */
    public function index(Request $request): View
    {
        $query = Disposal::with(['commodity', 'requester', 'approver']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('disposal_number', 'like', "%{$search}%")
                    ->orWhereHas('commodity', fn($q2) => $q2->where('name', 'like', "%{$search}%"));
            });
        }

        $perPage = min($request->get('per_page', 15), 100); // Max 100 per page
        $disposals = $query->latest()->paginate($perPage)->withQueryString();
        
        // Data untuk modal
        $commodities = Commodity::orderBy('name')->get();

        return view('disposals.index', compact('disposals', 'commodities'));
    }

    /**
     * Tampilkan form pengajuan penghapusan.
     */
    public function create(Request $request): View
    {
        $commodities = Commodity::orderBy('name')->get();
        $selectedCommodity = $request->commodity_id ? Commodity::find($request->commodity_id) : null;

        return view('disposals.create', compact('commodities', 'selectedCommodity'));
    }

    /**
     * Simpan pengajuan penghapusan.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'commodity_id' => ['required', 'exists:commodities,id'],
            'reason' => ['required', 'in:rusak_berat,hilang,usang,dicuri,dijual,dihibahkan,lainnya'],
            'description' => ['required', 'string'],
            'estimated_value' => ['nullable', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string'],
        ]);

        // Cek apakah barang sudah ada pengajuan pending
        if (Disposal::where('commodity_id', $validated['commodity_id'])->where('status', 'pending')->exists()) {
            return back()->withErrors(['commodity_id' => 'Barang ini sudah memiliki pengajuan penghapusan yang belum diproses.']);
        }

        $disposal = Disposal::create([
            'commodity_id' => $validated['commodity_id'],
            'reason' => $validated['reason'],
            'description' => $validated['description'],
            'estimated_value' => $validated['estimated_value'] ?? 0,
            'notes' => $validated['notes'],
            'requested_by' => Auth::id(),
            'status' => 'pending',
        ]);

        ActivityLog::log('created', "Mengajukan penghapusan: {$disposal->disposal_number}", $disposal);

        // Send notification to admin users about disposal request
        $adminUsers = User::where('role', 'admin')->get();
        Notification::send($adminUsers, new DisposalRequested($disposal, Auth::user()));

        return redirect()->route('disposals.show', $disposal)
            ->with('success', 'Pengajuan penghapusan berhasil dibuat.');
    }

    /**
     * Tampilkan detail penghapusan.
     */
    public function show(Disposal $disposal): View
    {
        $disposal->load(['commodity.images', 'commodity.location', 'requester', 'approver']);
        return view('disposals.show', compact('disposal'));
    }

    /**
     * Setujui penghapusan.
     */
    public function approve(Disposal $disposal): RedirectResponse
    {
        if (!$disposal->canBeApproved()) {
            return back()->with('error', 'Pengajuan tidak bisa disetujui.');
        }

        // Check if commodity still exists
        if (!$disposal->commodity) {
            return back()->with('error', 'Tidak dapat menyetujui penghapusan. Barang sudah tidak ada dalam sistem.');
        }

        $disposal->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
            'disposal_date' => now(),
        ]);

        // Soft delete barang
        $disposal->commodity->delete();

        ActivityLog::log('approved', "Menyetujui penghapusan: {$disposal->disposal_number}", $disposal);

        return back()->with('success', 'Penghapusan berhasil disetujui. Barang telah dihapus dari inventaris.');
    }

    /**
     * Tolak penghapusan.
     */
    public function reject(Request $request, Disposal $disposal): RedirectResponse
    {
        if (!$disposal->canBeRejected()) {
            return back()->with('error', 'Pengajuan tidak bisa ditolak.');
        }

        $request->validate([
            'rejection_reason' => ['required', 'string'],
        ]);

        $disposal->update([
            'status' => 'rejected',
            'approved_by' => Auth::id(),
            'rejection_reason' => $request->rejection_reason,
        ]);

        ActivityLog::log('rejected', "Menolak penghapusan: {$disposal->disposal_number}", $disposal);

        return back()->with('success', 'Pengajuan penghapusan berhasil ditolak.');
    }

    /**
     * Hapus pengajuan (hanya pending).
     */
    public function destroy(Disposal $disposal): RedirectResponse
    {
        if ($disposal->status !== 'pending') {
            return back()->with('error', 'Hanya pengajuan dengan status pending yang bisa dibatalkan.');
        }

        if ($disposal->requested_by !== Auth::id() && Auth::user()->role !== 'admin') {
            return back()->with('error', 'Anda tidak memiliki izin untuk membatalkan pengajuan ini.');
        }

        $disposal->delete();

        ActivityLog::log('deleted', "Membatalkan pengajuan penghapusan: {$disposal->disposal_number}");

        return redirect()->route('disposals.index')
            ->with('success', 'Pengajuan berhasil dibatalkan.');
    }
}
