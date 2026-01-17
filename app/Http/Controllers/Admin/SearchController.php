<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\MedicalRecord;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    /**
     * Perform global search across patients and medical records.
     */
    public function search(Request $request)
    {
        $query = $request->input('q', '');

        if (strlen($query) < 2) {
            return response()->json([
                'success' => false,
                'message' => 'Query must be at least 2 characters'
            ]);
        }

        $results = [
            'patients' => [],
            'medical_records' => []
        ];

        // Search patients
        $patients = Patient::where('name', 'LIKE', "%{$query}%")
            ->orWhere('nik', 'LIKE', "%{$query}%")
            ->orWhere('phone', 'LIKE', "%{$query}%")
            ->limit(5)
            ->get(['id', 'name', 'nik', 'phone']);

        foreach ($patients as $patient) {
            $results['patients'][] = [
                'id' => $patient->id,
                'name' => $patient->name,
                'description' => "NIK: {$patient->nik} | HP: {$patient->phone}",
                'url' => route('admin.patients.show', $patient->id)
            ];
        }

        // Search medical records
        $medicalRecords = MedicalRecord::with('patient')
            ->whereHas('patient', function ($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                    ->orWhere('nik', 'LIKE', "%{$query}%");
            })
            ->orWhere('diagnosis', 'LIKE', "%{$query}%")
            ->orWhere('complaint', 'LIKE', "%{$query}%")
            ->limit(5)
            ->get();

        foreach ($medicalRecords as $record) {
            $results['medical_records'][] = [
                'id' => $record->id,
                'name' => $record->patient->name ?? 'Unknown',
                'description' => $record->diagnosis ? "Diagnosa: " . \Illuminate\Support\Str::limit($record->diagnosis, 40) : ($record->complaint ? "Keluhan: " . \Illuminate\Support\Str::limit($record->complaint, 40) : 'Rekam Medis #' . $record->id),
                'date' => $record->created_at->format('d M Y'),
                'url' => route('admin.medical-records.show', $record->id)
            ];
        }

        $totalResults = count($results['patients']) + count($results['medical_records']);

        return response()->json([
            'success' => true,
            'total' => $totalResults,
            'results' => $results
        ]);
    }
}
