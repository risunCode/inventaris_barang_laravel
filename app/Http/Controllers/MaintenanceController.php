<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Commodity;
use App\Models\Maintenance;
use App\Models\User;
use App\Notifications\MaintenanceScheduled;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\View\View;

class MaintenanceController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:maintenance.view', only: ['index', 'show']),
            new Middleware('permission:maintenance.create', only: ['create', 'store']),
            new Middleware('permission:maintenance.edit', only: ['edit', 'update']),
            new Middleware('permission:maintenance.delete', only: ['destroy']),
        ];
    }

    /**
     * Tampilkan daftar maintenance.
     */
    public function index(Request $request): View
    {
        $query = Maintenance::with(['commodity', 'creator']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('commodity', fn($q) => $q->where('name', 'like', "%{$search}%"));
        }

        // Filter by commodity
        if ($request->filled('commodity_id')) {
            $query->where('commodity_id', $request->commodity_id);
        }

        // Filter: overdue
        if ($request->boolean('overdue')) {
            $query->overdue();
        }

        // Filter: upcoming
        if ($request->boolean('upcoming')) {
            $query->upcoming();
        }

        $perPage = min($request->get('per_page', 15), 100); // Max 100 per page
        $maintenances = $query->latest()->paginate($perPage)->withQueryString();
        
        // Data untuk modal
        $commodities = Commodity::orderBy('name')->get();

        // Stats
        $overdueCount = Maintenance::overdue()->count();
        $upcomingCount = Maintenance::upcoming()->count();

        return view('maintenance.index', compact('maintenances', 'commodities', 'overdueCount', 'upcomingCount'));
    }

    /**
     * Tampilkan form tambah maintenance.
     */
    public function create(Request $request): View
    {
        $commodities = Commodity::orderBy('name')->get();
        $selectedCommodity = $request->commodity_id ? Commodity::find($request->commodity_id) : null;

        return view('maintenance.create', compact('commodities', 'selectedCommodity'));
    }

    /**
     * Simpan log maintenance.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'commodity_id' => ['required', 'exists:commodities,id'],
            'maintenance_date' => ['required', 'date'],
            'maintenance_type' => ['nullable', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'cost' => ['nullable', 'numeric', 'min:0'],
            'performed_by' => ['nullable', 'string', 'max:255'],
            'vendor' => ['nullable', 'string', 'max:255'],
            'next_maintenance_date' => ['nullable', 'date', 'after:maintenance_date'],
            'condition_after' => ['nullable', 'in:baik,rusak_ringan,rusak_berat'],
        ]);

        $validated['created_by'] = Auth::id();
        $validated['cost'] = $validated['cost'] ?? 0;

        $maintenanceLog = Maintenance::create($validated);

        // Update kondisi barang jika diisi
        if (!empty($validated['condition_after']) && $maintenanceLog->commodity) {
            $maintenanceLog->commodity->update(['condition' => $validated['condition_after']]);
        }

        ActivityLog::log('created', "Menambah log maintenance untuk barang: " . ($maintenanceLog->commodity->name ?? 'Barang tidak ditemukan'), $maintenanceLog);

        // Send notification to admin users about maintenance
        $adminUsers = User::where('role', 'admin')->get();
        Notification::send($adminUsers, new MaintenanceScheduled($maintenanceLog, Auth::user()));

        return redirect()->route('maintenance.show', $maintenanceLog)
            ->with('success', 'Log maintenance berhasil ditambahkan.');
    }

    /**
     * Tampilkan detail maintenance.
     */
    public function show(Maintenance $maintenance): View
    {
        $maintenance->load(['commodity.images', 'creator']);
        return view('maintenance.show', compact('maintenance'));
    }

    /**
     * Tampilkan form edit maintenance.
     */
    public function edit(Maintenance $maintenance): View
    {
        $commodities = Commodity::orderBy('name')->get();
        return view('maintenance.edit', compact('maintenance', 'commodities'));
    }

    /**
     * Update maintenance.
     */
    public function update(Request $request, Maintenance $maintenance): RedirectResponse
    {
        $validated = $request->validate([
            'commodity_id' => ['required', 'exists:commodities,id'],
            'maintenance_date' => ['required', 'date'],
            'maintenance_type' => ['nullable', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'cost' => ['nullable', 'numeric', 'min:0'],
            'performed_by' => ['nullable', 'string', 'max:255'],
            'vendor' => ['nullable', 'string', 'max:255'],
            'next_maintenance_date' => ['nullable', 'date', 'after:maintenance_date'],
            'condition_after' => ['nullable', 'in:baik,rusak_ringan,rusak_berat'],
        ]);

        $validated['cost'] = $validated['cost'] ?? 0;

        $oldValues = $maintenance->toArray();
        $maintenance->update($validated);

        ActivityLog::log('updated', "Mengubah log maintenance: {$maintenance->id}", $maintenance, $oldValues, $maintenance->toArray());

        return redirect()->route('maintenance.show', $maintenance)
            ->with('success', 'Log maintenance berhasil diperbarui.');
    }

    /**
     * Hapus maintenance.
     */
    public function destroy(Maintenance $maintenance): RedirectResponse
    {
        $maintenance->delete();

        ActivityLog::log('deleted', "Menghapus log maintenance: {$maintenance->id}");

        return redirect()->route('maintenance.index')
            ->with('success', 'Log maintenance berhasil dihapus.');
    }
}
