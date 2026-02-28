@extends('layouts.app')

@section('title', 'Edit Vehicle')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <h1>Edit Vehicle</h1>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('vehicles.update', $vehicle->id) }}">
                @csrf
                @method('PUT')

                <div class="alert alert-info">
                    <strong>Note:</strong> The following fields are read-only and cannot be edited: Plate Number, Vehicle Type, Make, Model, Engine Number, Chassis Number, License Type, and Fuel Type.
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">License Plate Number</label>
                        <input type="text" class="form-control" value="{{ $vehicle->plateno }}" disabled>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Vehicle Type</label>
                        <input type="text" class="form-control" value="{{ $vehicle->vehicletype }}" disabled>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Make of Vehicle</label>
                        <input type="text" class="form-control" value="{{ $vehicle->makeofvehicle }}" disabled>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Model</label>
                        <input type="text" class="form-control" value="{{ $vehicle->model }}" disabled>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Engine Number</label>
                        <input type="text" class="form-control" value="{{ $vehicle->engineno }}" disabled>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Chassis Number</label>
                        <input type="text" class="form-control" value="{{ $vehicle->chassisno }}" disabled>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Owner Name <span class="text-danger">*</span></label>
                        <input type="text" name="owner" class="form-control @error('owner') is-invalid @enderror" value="{{ old('owner', $vehicle->owner) }}" required>
                        @error('owner')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Address</label>
                        <input type="text" name="address" class="form-control @error('address') is-invalid @enderror" value="{{ old('address', $vehicle->address) }}">
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Phone Number</label>
                        <input type="text" name="phoneno" class="form-control @error('phoneno') is-invalid @enderror" value="{{ old('phoneno', $vehicle->phoneno) }}">
                        @error('phoneno')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Net Weight (kg)</label>
                        <input type="number" step="0.01" name="netweight" class="form-control @error('netweight') is-invalid @enderror" value="{{ old('netweight', $vehicle->netweight) }}">
                        @error('netweight')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Gross Weight (kg)</label>
                        <input type="number" step="0.01" name="grossweight" class="form-control @error('grossweight') is-invalid @enderror" value="{{ old('grossweight', $vehicle->grossweight) }}">
                        @error('grossweight')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Authorized to Carry (kg)</label>
                        <input type="number" step="0.01" name="authorizedtocarry" class="form-control @error('authorizedtocarry') is-invalid @enderror" value="{{ old('authorizedtocarry', $vehicle->authorizedtocarry) }}">
                        @error('authorizedtocarry')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Persons to Carry</label>
                        <input type="number" name="personstocarry" class="form-control @error('personstocarry') is-invalid @enderror" value="{{ old('personstocarry', $vehicle->personstocarry) }}">
                        @error('personstocarry')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Save Changes
                    </button>
                    <a href="{{ route('vehicles.show', $vehicle->id) }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
