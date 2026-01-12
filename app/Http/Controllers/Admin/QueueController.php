<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Queue;
use App\Models\Patient;
use Illuminate\Http\Request;
use Carbon\Carbon;

class QueueController extends Controller
{
    /**
     * Display today's queue.
     */
    public function index(Request $request)
    {
        $date = $request->get('date', date('Y-m-d'));

        $queues = Queue::with('patient')
            ->whereDate('queue_date', $date)
            ->orderBy('queue_number')
            ->get();

        $stats = [
            'total' => $queues->count(),
            'waiting' => $queues->where('status', 'waiting')->count(),
            'examining' => $queues->where('status', 'examining')->count(),
            'done' => $queues->where('status', 'done')->count(),
        ];

        return view('admin.queues.index', compact('queues', 'date', 'stats'));
    }

    /**
     * Show the form for creating a new queue entry.
     */
    public function create(Request $request)
    {
        $patients = Patient::where('is_active', true)->orderBy('name')->get();
        $selectedPatient = $request->get('patient_id');
        $nextNumber = Queue::getNextNumber();

        return view('admin.queues.form', [
            'queue' => null,
            'patients' => $patients,
            'selectedPatient' => $selectedPatient,
            'nextNumber' => $nextNumber,
        ]);
    }

    /**
     * Store a newly created queue entry.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'service_type' => 'required|in:ANC,INC,PNC,KB,Imunisasi,Umum',
            'notes' => 'nullable|string',
        ]);

        $validated['queue_date'] = date('Y-m-d');
        $validated['queue_number'] = Queue::getNextNumber();
        $validated['status'] = 'waiting';

        Queue::create($validated);

        return redirect()->route('admin.queues.index')
            ->with('success', 'Pasien berhasil ditambahkan ke antrean.');
    }

    /**
     * Call the next patient.
     */
    public function call(Queue $queue)
    {
        // Set all examining to waiting first
        Queue::where('queue_date', $queue->queue_date)
            ->where('status', 'called')
            ->update(['status' => 'waiting']);

        $queue->update([
            'status' => 'called',
            'called_at' => now(),
        ]);

        return back()->with('success', 'Pasien ' . $queue->patient->name . ' dipanggil.');
    }

    /**
     * Start examining the patient.
     */
    public function show(Queue $queue)
    {
        $queue->update(['status' => 'examining']);

        return redirect()->route('admin.medical-records.create', ['patient_id' => $queue->patient_id, 'queue_id' => $queue->id])
            ->with('info', 'Silakan lakukan pemeriksaan.');
    }

    /**
     * Mark queue as finished.
     */
    public function finish(Queue $queue)
    {
        $queue->update([
            'status' => 'done',
            'finished_at' => now(),
        ]);

        return back()->with('success', 'Pemeriksaan pasien ' . $queue->patient->name . ' selesai.');
    }

    /**
     * Cancel queue entry.
     */
    public function destroy(Queue $queue)
    {
        $queue->update(['status' => 'cancelled']);

        return back()->with('success', 'Antrean dibatalkan.');
    }
}
