@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-semibold text-gray-800">Medical Records</h1>
            <p class="text-gray-500">Manage patient medical records</p>
        </div>
        <a href="{{ route('medical-records.create') }}" class="inline-flex items-center px-4 py-2 bg-emerald-300 text-emerald-900 rounded shadow">+ Add Medical Record</a>
    </div>

    <form method="GET" action="{{ route('medical-records.index') }}" class="mb-6 bg-white p-4 rounded shadow">
        <div class="flex gap-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Enter Patient ID to search..." class="flex-1 border rounded p-2" />
            <select name="hospital" class="border rounded p-2">
                <option value="">All Hospitals</option>
                @foreach(\App\Models\MedicalRecord::select('hospital')->distinct()->pluck('hospital') as $h)
                    @if($h)
                        <option value="{{ $h }}" {{ request('hospital') == $h ? 'selected' : '' }}>{{ $h }}</option>
                    @endif
                @endforeach
            </select>
            <button class="px-4 py-2 bg-emerald-500 text-white rounded">Filter</button>
        </div>
    </form>

    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        @forelse($records as $record)
        <div class="bg-white shadow rounded overflow-hidden">
            <div class="p-4 bg-emerald-50 border-b">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="font-semibold">Record #{{ $record->id }}</div>
                        <div class="text-sm text-gray-500">Patient ID: {{ $record->patient_id }}</div>
                    </div>
                    <div class="text-xs bg-emerald-100 text-emerald-700 px-2 py-1 rounded">Blockchain</div>
                </div>
            </div>
            <div class="p-4">
                <div class="text-sm text-gray-700"><strong>Hospital:</strong> {{ $record->hospital ?? 'Unknown Hospital' }}</div>
                <div class="text-sm text-gray-700"><strong>Doctor:</strong> {{ $record->doctor_name ?? '-' }}</div>
                <div class="text-sm text-gray-700"><strong>Date:</strong> {{ $record->date_of_visit?->format('Y-m-d') ?? 'Invalid Date' }}</div>

                <div class="mt-3 bg-gray-50 p-3 rounded">
                    <div class="text-xs uppercase text-gray-500">Diagnosis</div>
                    <div class="text-gray-700 mt-2">{{ Str::limit($record->diagnosis, 200) }}</div>
                </div>

                @if($record->prescriptions && count($record->prescriptions))
                <div class="mt-3 bg-blue-50 p-3 rounded">
                    <div class="text-xs uppercase text-blue-500">Prescriptions ({{ count($record->prescriptions) }})</div>
                    <ul class="mt-2 text-sm text-gray-700">
                        @foreach($record->prescriptions as $p)
                            <li>- {{ $p }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <div class="mt-4 flex gap-2">
                    <a href="{{ route('medical-records.edit', $record->id) }}" class="px-3 py-1 bg-emerald-200 text-emerald-900 rounded">Edit</a>
                    <form action="{{ route('medical-records.destroy', $record->id) }}" method="POST" onsubmit="return confirm('Delete this record?');">
                        @csrf
                        @method('DELETE')
                        <button class="px-3 py-1 bg-red-100 text-red-700 rounded">Delete</button>
                    </form>
                </div>
            </div>
        </div>
        @empty
            <div class="col-span-3 text-center text-gray-500">No records found.</div>
        @endforelse
    </div>

    <div class="mt-6">{{ $records->withQueryString()->links() }}</div>
</div>
@endsection
