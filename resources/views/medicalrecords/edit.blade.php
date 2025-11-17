@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-3xl mx-auto bg-white p-6 rounded shadow">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-2xl font-semibold">Edit Medical Record</h2>
            <a href="{{ route('medical-records.index') }}" class="text-sm text-gray-500">Back to list</a>
        </div>

        @if($errors->any())
            <div class="mb-4 p-3 bg-red-50 text-red-700 rounded">
                <ul>
                    @foreach($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('medical-records.update', $record->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 gap-4">
                <label class="block">
                    <div class="text-sm text-gray-600">Patient ID</div>
                    <input type="text" name="patient_id" value="{{ old('patient_id', $record->patient_id) }}" class="w-full border p-2 rounded" required />
                </label>

                <label class="block">
                    <div class="text-sm text-gray-600">Hospital</div>
                    <input type="text" name="hospital" value="{{ old('hospital', $record->hospital) }}" class="w-full border p-2 rounded" />
                </label>

                <label class="block">
                    <div class="text-sm text-gray-600">Doctor</div>
                    <input type="text" name="doctor_name" value="{{ old('doctor_name', $record->doctor_name) }}" class="w-full border p-2 rounded" />
                </label>

                <label class="block">
                    <div class="text-sm text-gray-600">Date of Visit</div>
                    <input type="date" name="date_of_visit" value="{{ old('date_of_visit', $record->date_of_visit?->format('Y-m-d')) }}" class="w-full border p-2 rounded" />
                </label>

                <label class="block">
                    <div class="text-sm text-gray-600">Diagnosis</div>
                    <textarea name="diagnosis" rows="4" class="w-full border p-2 rounded">{{ old('diagnosis', $record->diagnosis) }}</textarea>
                </label>

                <label class="block">
                    <div class="text-sm text-gray-600">Prescriptions (one per line)</div>
                    <textarea name="prescriptions_text" rows="3" class="w-full border p-2 rounded">{{ old('prescriptions_text', is_array($record->prescriptions) ? implode("\n", $record->prescriptions) : '') }}</textarea>
                </label>

                <label class="block">
                    <div class="text-sm text-gray-600">Notes</div>
                    <textarea name="notes" rows="3" class="w-full border p-2 rounded">{{ old('notes', $record->notes) }}</textarea>
                </label>

                <div class="flex justify-end">
                    <button class="px-4 py-2 bg-emerald-500 text-white rounded">Save Changes</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
