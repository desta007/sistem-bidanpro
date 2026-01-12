<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\MedicalRecord;
use App\Models\Invoice;
use App\Models\Queue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class PatientAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('frontend.pages.login');
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'phone' => 'required|string',
            'password' => 'required|string',
        ]);

        $patient = Patient::where('phone', $validated['phone'])
            ->where('is_active', true)
            ->first();

        if ($patient && Hash::check($validated['password'], $patient->password)) {
            Auth::guard('patient')->login($patient, true);
            return redirect()->route('patient.dashboard')
                ->with('success', 'Selamat datang, ' . $patient->name . '!');
        }

        return back()->withErrors(['phone' => 'Nomor HP atau password salah.'])->withInput();
    }

    public function showRegisterForm()
    {
        return view('frontend.pages.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'nik' => 'required|string|size:16|unique:patients,nik',
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20|unique:patients,phone',
            'password' => 'required|string|min:6|confirmed',
            'gender' => 'required|in:L,P',
            'birth_date' => 'nullable|date',
            'address' => 'nullable|string',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        $patient = Patient::create($validated);

        Auth::guard('patient')->login($patient, true);

        return redirect()->route('patient.dashboard')
            ->with('success', 'Pendaftaran berhasil! Selamat datang, ' . $patient->name);
    }

    public function logout()
    {
        Auth::guard('patient')->logout();
        return redirect()->route('home')->with('success', 'Anda telah keluar.');
    }

    public function dashboard()
    {
        $patient = Auth::guard('patient')->user();

        $upcomingAppointments = Queue::where('patient_id', $patient->id)
            ->whereDate('queue_date', '>=', today())
            ->whereIn('status', ['waiting', 'called'])
            ->orderBy('queue_date')
            ->limit(3)
            ->get();

        $recentRecords = MedicalRecord::where('patient_id', $patient->id)
            ->latest('exam_date')
            ->limit(3)
            ->get();

        $pendingInvoices = Invoice::where('patient_id', $patient->id)
            ->where('status', 'pending')
            ->count();

        return view('frontend.pages.dashboard', compact('patient', 'upcomingAppointments', 'recentRecords', 'pendingInvoices'));
    }

    public function records()
    {
        $patient = Auth::guard('patient')->user();
        $records = MedicalRecord::where('patient_id', $patient->id)
            ->latest('exam_date')
            ->paginate(10);

        return view('frontend.pages.records', compact('records'));
    }

    public function showRecord(MedicalRecord $record)
    {
        $patient = Auth::guard('patient')->user();

        if ($record->patient_id !== $patient->id) {
            abort(403);
        }

        return view('frontend.pages.record-detail', compact('record'));
    }

    public function appointments()
    {
        $patient = Auth::guard('patient')->user();
        $appointments = Queue::where('patient_id', $patient->id)
            ->orderByDesc('queue_date')
            ->paginate(10);

        return view('frontend.pages.appointments', compact('appointments'));
    }

    public function invoices()
    {
        $patient = Auth::guard('patient')->user();
        $invoices = Invoice::where('patient_id', $patient->id)
            ->latest('invoice_date')
            ->paginate(10);

        return view('frontend.pages.invoices', compact('invoices'));
    }

    public function showInvoice(Invoice $invoice)
    {
        $patient = Auth::guard('patient')->user();

        if ($invoice->patient_id !== $patient->id) {
            abort(403);
        }

        $invoice->load('items');

        return view('frontend.pages.invoice-detail', compact('invoice'));
    }

    public function profile()
    {
        $patient = Auth::guard('patient')->user();
        return view('frontend.pages.profile', compact('patient'));
    }

    public function updateProfile(Request $request)
    {
        $patient = Auth::guard('patient')->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string',
            'birth_date' => 'nullable|date',
        ]);

        $patient->update($validated);

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    public function notifications()
    {
        return view('frontend.pages.notifications');
    }
}
