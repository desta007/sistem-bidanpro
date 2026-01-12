<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\MedicalRecord;
use App\Models\Invoice;
use App\Models\Queue;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $thisMonth = Carbon::now()->startOfMonth();

        $stats = [
            'patients_today' => Queue::whereDate('queue_date', $today)->count(),
            'patients_month' => Queue::whereBetween('queue_date', [$thisMonth, $today])->count(),
            'revenue_today' => Invoice::whereDate('invoice_date', $today)->where('status', 'paid')->sum('total'),
            'revenue_month' => Invoice::whereBetween('invoice_date', [$thisMonth, $today])->where('status', 'paid')->sum('total'),
        ];

        // Monthly trends
        $monthlyPatients = [];
        $monthlyRevenue = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $monthlyPatients[] = [
                'month' => $month->format('M'),
                'count' => Queue::whereYear('queue_date', $month->year)->whereMonth('queue_date', $month->month)->count(),
            ];
            $monthlyRevenue[] = [
                'month' => $month->format('M'),
                'total' => Invoice::whereYear('invoice_date', $month->year)->whereMonth('invoice_date', $month->month)->where('status', 'paid')->sum('total'),
            ];
        }

        // Service distribution
        $serviceDistribution = MedicalRecord::selectRaw('type, COUNT(*) as count')
            ->groupBy('type')
            ->get()
            ->pluck('count', 'type')
            ->toArray();

        return view('admin.reports.index', compact('stats', 'monthlyPatients', 'monthlyRevenue', 'serviceDistribution'));
    }

    public function daily(Request $request)
    {
        $date = $request->get('date', date('Y-m-d'));

        $queues = Queue::with('patient')
            ->whereDate('queue_date', $date)
            ->orderBy('queue_number')
            ->get();

        $invoices = Invoice::with('patient')
            ->whereDate('invoice_date', $date)
            ->get();

        $records = MedicalRecord::with('patient')
            ->whereDate('exam_date', $date)
            ->get();

        $stats = [
            'total_patients' => $queues->count(),
            'total_revenue' => $invoices->where('status', 'paid')->sum('total'),
            'pending_payment' => $invoices->where('status', 'pending')->sum('total'),
        ];

        return view('admin.reports.daily', compact('date', 'queues', 'invoices', 'records', 'stats'));
    }

    public function monthly(Request $request)
    {
        $month = $request->get('month', date('Y-m'));
        $startDate = Carbon::parse($month)->startOfMonth();
        $endDate = Carbon::parse($month)->endOfMonth();

        $stats = [
            'total_patients' => Queue::whereBetween('queue_date', [$startDate, $endDate])->count(),
            'unique_patients' => Queue::whereBetween('queue_date', [$startDate, $endDate])->distinct('patient_id')->count('patient_id'),
            'total_revenue' => Invoice::whereBetween('invoice_date', [$startDate, $endDate])->where('status', 'paid')->sum('total'),
            'total_examinations' => MedicalRecord::whereBetween('exam_date', [$startDate, $endDate])->count(),
        ];

        $serviceBreakdown = MedicalRecord::selectRaw('type, COUNT(*) as count')
            ->whereBetween('exam_date', [$startDate, $endDate])
            ->groupBy('type')
            ->get();

        $dailyPatients = Queue::selectRaw('DATE(queue_date) as date, COUNT(*) as count')
            ->whereBetween('queue_date', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return view('admin.reports.monthly', compact('month', 'stats', 'serviceBreakdown', 'dailyPatients'));
    }

    public function cohort(Request $request)
    {
        $type = $request->get('type', 'ibu');
        $month = $request->get('month', date('Y-m'));
        $startDate = Carbon::parse($month)->startOfMonth();
        $endDate = Carbon::parse($month)->endOfMonth();

        if ($type === 'ibu') {
            // Kohort Ibu - ANC records
            $records = MedicalRecord::with('patient')
                ->where('type', 'ANC')
                ->whereBetween('exam_date', [$startDate, $endDate])
                ->orderBy('exam_date')
                ->get();
        } else {
            // Kohort Bayi - Imunisasi records
            $records = MedicalRecord::with('patient')
                ->where('type', 'Imunisasi')
                ->whereBetween('exam_date', [$startDate, $endDate])
                ->orderBy('exam_date')
                ->get();
        }

        return view('admin.reports.cohort', compact('type', 'month', 'records'));
    }

    public function export(Request $request, $type)
    {
        // TODO: Implement Excel/PDF export
        return back()->with('info', 'Fitur export akan segera tersedia.');
    }
}
