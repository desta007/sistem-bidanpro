<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MedicalRecord;
use App\Models\Patient;
use App\Models\Queue;
use Illuminate\Http\Request;
use Carbon\Carbon;

class MedicalRecordController extends Controller
{
    /**
     * Display a listing of medical records.
     */
    public function index(Request $request)
    {
        $query = MedicalRecord::with(['patient', 'examiner']);

        // Filter by patient
        if ($request->filled('patient_id')) {
            $query->where('patient_id', $request->patient_id);
        }

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('exam_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('exam_date', '<=', $request->date_to);
        }

        $records = $query->latest('exam_date')->paginate(15)->withQueryString();

        return view('admin.medical-records.index', compact('records'));
    }

    /**
     * Show the form for creating a new medical record.
     */
    public function create(Request $request)
    {
        $patients = Patient::where('is_active', true)->orderBy('name')->get();
        $selectedPatient = $request->get('patient_id');
        $queueId = $request->get('queue_id');
        $type = $request->get('type', 'Umum');

        $patient = $selectedPatient ? Patient::find($selectedPatient) : null;

        return view('admin.medical-records.form', [
            'record' => null,
            'patients' => $patients,
            'selectedPatient' => $selectedPatient,
            'patient' => $patient,
            'queueId' => $queueId,
            'type' => $type,
        ]);
    }

    /**
     * Store a newly created medical record.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'exam_date' => 'required|date',
            'type' => 'required|in:ANC,INC,PNC,KB,Imunisasi,Umum',
            // Vital signs
            'blood_pressure_systolic' => 'nullable|numeric',
            'blood_pressure_diastolic' => 'nullable|numeric',
            'weight' => 'nullable|numeric',
            'height' => 'nullable|numeric',
            'temperature' => 'nullable|numeric',
            'pulse' => 'nullable|integer',
            // Diagnosis
            'complaint' => 'nullable|string',
            'diagnosis' => 'nullable|string',
            'icd_code' => 'nullable|string|max:20',
            'treatment' => 'nullable|string',
            'notes' => 'nullable|string',
            // ANC
            'hpht' => 'nullable|date',
            'pregnancy_week' => 'nullable|integer',
            'fetal_heart_rate' => 'nullable|integer',
            'fundal_height' => 'nullable|numeric',
            'fetal_position' => 'nullable|in:kepala,sungsang,lintang',
            // KB
            'kb_method' => 'nullable|string',
            'kb_next_visit' => 'nullable|date',
            // Imunisasi
            'vaccine_type' => 'nullable|string',
            'vaccine_batch' => 'nullable|string',
            'next_vaccine_date' => 'nullable|date',
        ]);

        $validated['user_id'] = auth()->id();

        // Calculate HPL if HPHT provided
        if (!empty($validated['hpht'])) {
            $validated['hpl'] = Carbon::parse($validated['hpht'])->addDays(280);
        }

        $record = MedicalRecord::create($validated);

        // Update queue if exists
        if ($request->filled('queue_id')) {
            Queue::find($request->queue_id)?->update(['status' => 'done', 'finished_at' => now()]);
        }

        return redirect()->route('admin.medical-records.show', $record)
            ->with('success', 'Rekam medis berhasil disimpan.');
    }

    /**
     * Display the specified medical record.
     */
    public function show(MedicalRecord $medicalRecord)
    {
        $medicalRecord->load(['patient', 'examiner', 'invoice']);

        return view('admin.medical-records.show', ['record' => $medicalRecord]);
    }

    /**
     * Show the form for editing the medical record.
     */
    public function edit(MedicalRecord $medicalRecord)
    {
        $patients = Patient::where('is_active', true)->orderBy('name')->get();

        return view('admin.medical-records.form', [
            'record' => $medicalRecord,
            'patients' => $patients,
            'selectedPatient' => $medicalRecord->patient_id,
            'patient' => $medicalRecord->patient,
            'queueId' => null,
            'type' => $medicalRecord->type,
        ]);
    }

    /**
     * Update the specified medical record.
     */
    public function update(Request $request, MedicalRecord $medicalRecord)
    {
        $validated = $request->validate([
            'exam_date' => 'required|date',
            'type' => 'required|in:ANC,INC,PNC,KB,Imunisasi,Umum',
            'blood_pressure_systolic' => 'nullable|numeric',
            'blood_pressure_diastolic' => 'nullable|numeric',
            'weight' => 'nullable|numeric',
            'height' => 'nullable|numeric',
            'temperature' => 'nullable|numeric',
            'pulse' => 'nullable|integer',
            'complaint' => 'nullable|string',
            'diagnosis' => 'nullable|string',
            'icd_code' => 'nullable|string|max:20',
            'treatment' => 'nullable|string',
            'notes' => 'nullable|string',
            'hpht' => 'nullable|date',
            'pregnancy_week' => 'nullable|integer',
            'fetal_heart_rate' => 'nullable|integer',
            'fundal_height' => 'nullable|numeric',
            'fetal_position' => 'nullable|in:kepala,sungsang,lintang',
            'kb_method' => 'nullable|string',
            'kb_next_visit' => 'nullable|date',
            'vaccine_type' => 'nullable|string',
            'vaccine_batch' => 'nullable|string',
            'next_vaccine_date' => 'nullable|date',
        ]);

        if (!empty($validated['hpht'])) {
            $validated['hpl'] = Carbon::parse($validated['hpht'])->addDays(280);
        }

        $medicalRecord->update($validated);

        return redirect()->route('admin.medical-records.show', $medicalRecord)
            ->with('success', 'Rekam medis berhasil diperbarui.');
    }

    /**
     * Remove the specified medical record.
     */
    public function destroy(MedicalRecord $medicalRecord)
    {
        $patientId = $medicalRecord->patient_id;
        $medicalRecord->delete();

        return redirect()->route('admin.patients.show', $patientId)
            ->with('success', 'Rekam medis berhasil dihapus.');
    }
}
