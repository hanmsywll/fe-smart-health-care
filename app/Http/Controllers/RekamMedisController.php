<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MedicalRecord;

class MedicalRecordController extends Controller
{
    public function index(Request $request)
    {
        $query = MedicalRecord::query();

        if ($request->filled('search')) {
            $q = $request->get('search');
            $query->where('patient_id', 'like', "%{$q}%")
                ->orWhere('doctor_name', 'like', "%{$q}%")
                ->orWhere('hospital', 'like', "%{$q}%");
        }

        if ($request->filled('hospital')) {
            $query->where('hospital', $request->get('hospital'));
        }

        $records = $query->orderBy('date_of_visit', 'desc')->paginate(12);

        return view('medicalrecords.index', compact('records'));
    }

    public function create()
    {
        return view('medicalrecords.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id_pasien' => 'required|string|max:255',
            'id_dokter' => 'required|string|max:255',
            'id_janji_temu' => 'required|string|max:255',
            'tanggal_kunjungan' => 'nullable|date',
            'diagnosis' => 'nullable|string',
            'tindakan' => 'nullable|string',
            'catatan' => 'nullable|string',
        ]);

        $prescriptions = [];
        if (!empty($data['prescriptions_text'])) {
            $prescriptions = array_values(array_filter(array_map('trim', explode("\n", $data['prescriptions_text']))));
        }

        MedicalRecord::create([
            'id_pasien' => $data['id_pasien'],
            'id_dokter' => $data['id_dokter'],
            'id_janji_temu' => $data['id_janji_temu'],
            'tanggal_kunjungan' => $data['tanggal_kunjungan'],
            'diagnosis' => $data['diagnosis'],
            'tindakan' => $data['tindakan'],
            'catatan' => $data['catatan'] ?? null,
        ]);

        return redirect()->route('medical-records.index')->with('success', 'Medical record added.');
    }

    public function edit($id)
    {
        $record = MedicalRecord::findOrFail($id);
        return view('medicalrecords.edit', compact('record'));
    }

    public function update(Request $request, $id)
    {
        $record = MedicalRecord::findOrFail($id);

        $data = $request->validate([
            'id_pasien' => 'required|string|max:255',
            'id_dokter' => 'required|string|max:255',
            'id_janji_temu' => 'required|string|max:255',
            'tanggal_kunjungan' => 'nullable|date',
            'diagnosis' => 'nullable|string',
            'tindakan' => 'nullable|string',
            'catatan' => 'nullable|string',
        ]);

        $prescriptions = [];
        if (!empty($data['prescriptions_text'])) {
            $prescriptions = array_values(array_filter(array_map('trim', explode("\n", $data['prescriptions_text']))));
        }

        $record->update([
            'id_pasien' => $data['id_pasien'],
            'id_dokter' => $data['id_dokter'],
            'id_janji_temu' => $data['id_janji_temu'],
            'tanggal_kunjungan' => $data['tanggal_kunjungan'],
            'diagnosis' => $data['diagnosis'],
            'tindakan' => $data['tindakan'],
            'catatan' => $data['catatan'] ?? null,
        ]);

        return redirect()->route('medical-records.index')->with('success', 'Medical record updated.');
    }

    public function destroy($id)
    {
        $record = MedicalRecord::findOrFail($id);
        $record->delete();

        return redirect()->route('medical-records.index')->with('success', 'Medical record deleted.');
    }
}
