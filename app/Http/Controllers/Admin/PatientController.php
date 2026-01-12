<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    /**
     * Display a listing of patients.
     */
    public function index(Request $request)
    {
        $query = Patient::query();

        // Search by NIK or name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nik', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $patients = $query->orderBy('name')->paginate(15)->withQueryString();

        return view('admin.patients.index', compact('patients'));
    }

    /**
     * Show the form for creating a new patient.
     */
    public function create()
    {
        return view('admin.patients.form', [
            'patient' => null,
        ]);
    }

    /**
     * Store a newly created patient.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nik' => 'required|string|size:16|unique:patients,nik',
            'name' => 'required|string|max:255',
            'gender' => 'required|in:L,P',
            'birth_date' => 'nullable|date',
            'birth_place' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'blood_type' => 'nullable|string|max:5',
            'allergy' => 'nullable|string',
            'bpjs_number' => 'nullable|string|max:20',
            'husband_name' => 'nullable|string|max:255',
        ]);

        Patient::create($validated);

        return redirect()->route('admin.patients.index')
            ->with('success', 'Pasien berhasil ditambahkan.');
    }

    /**
     * Display the specified patient.
     */
    public function show(Patient $patient)
    {
        $patient->load([
            'medicalRecords' => function ($query) {
                $query->latest('exam_date')->limit(10);
            },
            'invoices' => function ($query) {
                $query->latest('invoice_date')->limit(10);
            }
        ]);

        return view('admin.patients.show', compact('patient'));
    }

    /**
     * Show the form for editing the patient.
     */
    public function edit(Patient $patient)
    {
        return view('admin.patients.form', compact('patient'));
    }

    /**
     * Update the specified patient.
     */
    public function update(Request $request, Patient $patient)
    {
        $validated = $request->validate([
            'nik' => 'required|string|size:16|unique:patients,nik,' . $patient->id,
            'name' => 'required|string|max:255',
            'gender' => 'required|in:L,P',
            'birth_date' => 'nullable|date',
            'birth_place' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'blood_type' => 'nullable|string|max:5',
            'allergy' => 'nullable|string',
            'bpjs_number' => 'nullable|string|max:20',
            'husband_name' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        $patient->update($validated);

        return redirect()->route('admin.patients.index')
            ->with('success', 'Data pasien berhasil diperbarui.');
    }

    /**
     * Remove the specified patient.
     */
    public function destroy(Patient $patient)
    {
        // Soft delete - just deactivate
        $patient->update(['is_active' => false]);

        return redirect()->route('admin.patients.index')
            ->with('success', 'Pasien berhasil dinonaktifkan.');
    }
}
