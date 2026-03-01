@extends('layouts.app')

@section('title', 'Create Inspection')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <h1>Create New Inspection</h1>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('inspections.store') }}">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Vehicle <span class="text-danger">*</span></label>
                        <select name="vehicle_id" class="form-control @error('vehicle_id') is-invalid @enderror" required>
                            <option value="">Select Vehicle</option>
                            @foreach($vehicles as $vehicle)
                                <option value="{{ $vehicle->id }}">{{ $vehicle->plateno }} - {{ $vehicle->makeofvehicle }}</option>
                            @endforeach
                        </select>
                        @error('vehicle_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Department <span class="text-danger">*</span></label>
                        @include('partials.dept-select', [
                            'name'     => 'dept_id',
                            'selected' => old('dept_id'),
                            'allLabel' => 'Select Department',
                            'class'    => 'form-control' . ($errors->has('dept_id') ? ' is-invalid' : ''),
                        ])
                        @error('dept_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Inspect Date <span class="text-danger">*</span></label>
                        <input type="date" name="inspectdate" class="form-control @error('inspectdate') is-invalid @enderror" value="{{ old('inspectdate') }}" required>
                        @error('inspectdate')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Inspector <span class="text-danger">*</span></label>
                        <input type="text" name="inspector" class="form-control @error('inspector') is-invalid @enderror" value="{{ old('inspector') }}" required>
                        @error('inspector')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Conclusion</label>
                        <textarea name="conclusion" class="form-control @error('conclusion') is-invalid @enderror" rows="3">{{ old('conclusion') }}</textarea>
                        @error('conclusion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Create Inspection
                    </button>
                    <a href="{{ route('inspections.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
