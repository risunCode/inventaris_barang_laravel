<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Commodity;
use App\Models\Disposal;
use App\Models\Location;
use App\Models\Maintenance;
use App\Models\Transfer;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\View\View;

class ReportController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:reports.view'),
        ];
    }

    /**
     * Tampilkan halaman menu laporan.
     */
    public function index(): View
    {
        return view('reports.index');
    }

    /**
     * Laporan inventaris lengkap.
     */
    public function inventory(Request $request)
    {
        $query = Commodity::with(['category', 'location']);

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('location_id')) {
            $query->where('location_id', $request->location_id);
        }

        if ($request->filled('condition')) {
            $query->where('condition', $request->condition);
        }

        if ($request->filled('year')) {
            $query->whereYear('created_at', $request->year);
        }

        $commodities = $query->orderBy('name')->get();
        $categories = Category::active()->get();
        $locations = Location::active()->get();

        if ($request->has('print') || $request->get('export') === 'pdf') {
            $pdf = Pdf::loadView('reports.pdf.inventory', [
                'commodities' => $commodities,
                'title' => 'Laporan Inventaris Barang',
                'date' => now()->format('d F Y'),
                'filters' => $request->only(['category_id', 'location_id', 'condition', 'year']),
            ]);
            $pdf->setPaper('A4', 'landscape');
            return $pdf->stream('laporan-inventaris.pdf');
        }

        return view('reports.inventory', compact('commodities', 'categories', 'locations'));
    }

    /**
     * Laporan per kategori.
     */
    public function byCategory(Request $request)
    {
        $categories = Category::withCount('commodities')
            ->with(['commodities' => fn($q) => $q->orderBy('name')])
            ->orderBy('name')
            ->get();

        $totalValue = Commodity::sum('purchase_price');
        $totalItems = Commodity::count();

        if ($request->has('print') || $request->get('export') === 'pdf') {
            $pdf = Pdf::loadView('reports.pdf.by-category', [
                'categories' => $categories,
                'totalValue' => $totalValue,
                'totalItems' => $totalItems,
                'title' => 'Laporan Barang Per Kategori',
                'date' => now()->format('d F Y'),
            ]);
            $pdf->setPaper('A4', 'portrait');
            return $pdf->stream('laporan-per-kategori.pdf');
        }

        return view('reports.by-category', compact('categories', 'totalValue', 'totalItems'));
    }

    /**
     * Laporan per lokasi.
     */
    public function byLocation(Request $request)
    {
        $locations = Location::withCount('commodities')
            ->with(['commodities' => fn($q) => $q->orderBy('name')])
            ->orderBy('name')
            ->get();

        $totalValue = Commodity::sum('purchase_price');
        $totalItems = Commodity::count();

        if ($request->has('print') || $request->get('export') === 'pdf') {
            $pdf = Pdf::loadView('reports.pdf.by-location', [
                'locations' => $locations,
                'totalValue' => $totalValue,
                'totalItems' => $totalItems,
                'title' => 'Laporan Barang Per Lokasi',
                'date' => now()->format('d F Y'),
            ]);
            $pdf->setPaper('A4', 'portrait');
            return $pdf->stream('laporan-per-lokasi.pdf');
        }

        return view('reports.by-location', compact('locations', 'totalValue', 'totalItems'));
    }

    /**
     * Laporan per kondisi.
     */
    public function byCondition(Request $request)
    {
        // Get all commodities untuk detailed list
        $commodities = Commodity::with(['category', 'location'])
            ->orderBy('condition')
            ->orderBy('name')
            ->get()
            ->groupBy('condition');

        // Calculate real counts dari actual data
        $conditionStats = [
            'baik' => $commodities->get('baik', collect())->count(),
            'rusak_ringan' => $commodities->get('rusak_ringan', collect())->count(),
            'rusak_berat' => $commodities->get('rusak_berat', collect())->count(),
        ];

        // Add total counts untuk summary
        $totalValue = Commodity::sum('purchase_price');
        $totalItems = Commodity::count();

        // Get data per kategori untuk detail table
        $categoryConditions = Category::withCount([
            'commodities',
            'commodities as baik' => function ($query) {
                $query->where('condition', 'baik');
            },
            'commodities as rusak_ringan' => function ($query) {
                $query->where('condition', 'rusak_ringan');
            },
            'commodities as rusak_berat' => function ($query) {
                $query->where('condition', 'rusak_berat');
            }
        ])
        ->having('commodities_count', '>', 0) // Hanya kategori yang punya barang
        ->orderBy('name')
        ->get()
        ->map(function ($category) {
            $category->total_items = $category->commodities_count;
            return $category;
        });

        if ($request->has('print') || $request->get('export') === 'pdf') {
            $pdf = Pdf::loadView('reports.pdf.by-condition', [
                'conditionStats' => $conditionStats,
                'commodities' => $commodities,
                'title' => 'Laporan Barang Per Kondisi',
                'date' => now()->format('d F Y'),
            ]);
            $pdf->setPaper('A4', 'landscape');
            return $pdf->stream('laporan-per-kondisi.pdf');
        }

        return view('reports.by-condition', compact('conditionStats', 'commodities', 'totalValue', 'totalItems', 'categoryConditions'));
    }

    /**
     * Laporan transfer.
     */
    public function transfers(Request $request)
    {
        $query = Transfer::with(['commodity', 'fromLocation', 'toLocation', 'requester', 'approver']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        $transfers = $query->orderBy('created_at', 'desc')->get();

        if ($request->has('print') || $request->get('export') === 'pdf') {
            $pdf = Pdf::loadView('reports.pdf.transfers', [
                'transfers' => $transfers,
                'title' => 'Laporan Transfer Barang',
                'date' => now()->format('d F Y'),
            ]);
            $pdf->setPaper('A4', 'landscape');
            return $pdf->stream('laporan-transfer.pdf');
        }

        return view('reports.transfers', compact('transfers'));
    }

    /**
     * Laporan penghapusan.
     */
    public function disposals(Request $request)
    {
        $query = Disposal::with(['commodity', 'requester', 'approver']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        $disposals = $query->orderBy('created_at', 'desc')->get();

        if ($request->has('print') || $request->get('export') === 'pdf') {
            $pdf = Pdf::loadView('reports.pdf.disposals', [
                'disposals' => $disposals,
                'title' => 'Laporan Penghapusan Barang',
                'date' => now()->format('d F Y'),
            ]);
            $pdf->setPaper('A4', 'landscape');
            return $pdf->stream('laporan-penghapusan.pdf');
        }

        return view('reports.disposals', compact('disposals'));
    }

    /**
     * Laporan maintenance.
     */
    public function maintenance(Request $request)
    {
        $query = Maintenance::with(['commodity', 'creator']);

        if ($request->filled('from_date')) {
            $query->whereDate('maintenance_date', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('maintenance_date', '<=', $request->to_date);
        }

        $logs = $query->orderBy('maintenance_date', 'desc')->get();
        $totalCost = $logs->sum('cost');

        if ($request->has('print') || $request->get('export') === 'pdf') {
            $pdf = Pdf::loadView('reports.pdf.maintenance', [
                'logs' => $logs,
                'totalCost' => $totalCost,
                'title' => 'Laporan Pemeliharaan Barang',
                'date' => now()->format('d F Y'),
            ]);
            $pdf->setPaper('A4', 'landscape');
            return $pdf->stream('laporan-maintenance.pdf');
        }

        return view('reports.maintenance', compact('logs', 'totalCost'));
    }

    /**
     * Cetak KIB (Kartu Inventaris Barang).
     */
    public function kib(Request $request)
    {
        $request->validate([
            'commodity_id' => ['required', 'exists:commodities,id'],
        ]);

        $commodity = Commodity::with(['category', 'location', 'images', 'creator', 'maintenances'])
            ->findOrFail($request->commodity_id);

        $pdf = Pdf::loadView('reports.pdf.kib', [
            'commodity' => $commodity,
            'title' => 'Kartu Inventaris Barang',
            'date' => now()->format('d F Y'),
        ]);

        $pdf->setPaper('A4', 'portrait');
        return $pdf->stream("kib-{$commodity->item_code}.pdf");
    }
}
