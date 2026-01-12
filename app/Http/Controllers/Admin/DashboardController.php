<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\MedicalRecord;
use App\Models\Invoice;
use App\Models\Queue;
use App\Models\Inventory;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        // Statistics
        $stats = [
            'patients_today' => Queue::whereDate('queue_date', $today)->count(),
            'total_patients' => Patient::where('is_active', true)->count(),
            'revenue_today' => Invoice::whereDate('invoice_date', $today)
                ->where('status', 'paid')
                ->sum('total'),
            'pending_invoices' => Invoice::where('status', 'pending')->count(),
        ];

        // Today's queue
        $queues = Queue::with('patient')
            ->whereDate('queue_date', $today)
            ->orderBy('queue_number')
            ->limit(10)
            ->get();

        // Low stock items
        $lowStockItems = Inventory::where('is_active', true)
            ->whereColumn('stock', '<=', 'min_stock')
            ->limit(5)
            ->get();

        // Upcoming deliveries (HPL terdekat)
        $upcomingDeliveries = MedicalRecord::with('patient')
            ->where('type', 'ANC')
            ->whereNotNull('hpl')
            ->whereBetween('hpl', [$today, $today->copy()->addDays(30)])
            ->orderBy('hpl')
            ->limit(5)
            ->get();

        // Recent activities (latest medical records)
        $recentRecords = MedicalRecord::with(['patient', 'examiner'])
            ->latest('exam_date')
            ->limit(5)
            ->get();

        return view('admin.dashboard.index', compact(
            'stats',
            'queues',
            'lowStockItems',
            'upcomingDeliveries',
            'recentRecords'
        ));
    }
}
