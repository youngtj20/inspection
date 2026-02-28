@extends('layouts.app')

@section('title', 'Register New Vehicle')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <h1>Register New Vehicle</h1>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('vehicles.store') }}">
                @csrf

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">License Plate Number <span class="text-danger">*</span></label>
                        <input type="text" name="plateno" class="form-control @error('plateno') is-invalid @enderror" value="{{ old('plateno') }}" required>
                        @error('plateno')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Vehicle Type <span class="text-danger">*</span></label>
                        <input type="text" name="vehicletype" class="form-control @error('vehicletype') is-invalid @enderror" maxlength="4" value="{{ old('vehicletype') }}" required>
                        @error('vehicletype')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Make of Vehicle <span class="text-danger">*</span></label>
                        <input type="text" name="makeofvehicle" class="form-control @error('makeofvehicle') is-invalid @enderror" value="{{ old('makeofvehicle') }}" required>
                        @error('makeofvehicle')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Model <span class="text-danger">*</span></label>
                        <input type="text" name="model" class="form-control @error('model') is-invalid @enderror" value="{{ old('model') }}" required>
                        @error('model')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Engine Number <span class="text-danger">*</span></label>
                        <input type="text" name="engineno" class="form-control @error('engineno') is-invalid @enderror" value="{{ old('engineno') }}" required>
                        @error('engineno')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Chassis Number <span class="text-danger">*</span></label>
                        <input type="text" name="chassisno" class="form-control @error('chassisno') is-invalid @enderror" value="{{ old('chassisno') }}" required>
                        @error('chassisno')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">License Type <span class="text-danger">*</span></label>
                        <select name="licencetype" class="form-control @error('licencetype') is-invalid @enderror" required>
                            <option value="">Select License Type</option>
                            <option value="A" {{ old('licencetype') == 'A' ? 'selected' : '' }}>A</option>
                            <option value="B" {{ old('licencetype') == 'B' ? 'selected' : '' }}>B</option>
                            <option value="C" {{ old('licencetype') == 'C' ? 'selected' : '' }}>C</option>
                            <option value="D" {{ old('licencetype') == 'D' ? 'selected' : '' }}>D</option>
                        </select>
                        @error('licencetype')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Fuel Type <span class="text-danger">*</span></label>
                        <select name="fueltype" class="form-control @error('fueltype') is-invalid @enderror" required>
                            <option value="">Select Fuel Type</option>
                            <option value="P" {{ old('fueltype') == 'P' ? 'selected' : '' }}>Petrol</option>
                            <option value="D" {{ old('fueltype') == 'D' ? 'selected' : '' }}>Diesel</option>
                            <option value="G" {{ old('fueltype') == 'G' ? 'selected' : '' }}>Gas</option>
                            <option value="E" {{ old('fueltype') == 'E' ? 'selected' : '' }}>Electric</option>
                        </select>
                        @error('fueltype')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Owner Name <span class="text-danger">*</span></label>
                        <input type="text" name="owner" class="form-control @error('owner') is-invalid @enderror" value="{{ old('owner') }}" required>
                        @error('owner')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Address</label>
                        <input type="text" name="address" class="form-control @error('address') is-invalid @enderror" value="{{ old('address') }}">
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Phone Number</label>
                        <input type="text" name="phoneno" class="form-control @error('phoneno') is-invalid @enderror" value="{{ old('phoneno') }}">
                        @error('phoneno')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Registration Date</label>
                        <input type="date" name="registerdate" class="form-control @error('registerdate') is-invalid @enderror" value="{{ old('registerdate') }}">
                        @error('registerdate')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Product Date</label>
                        <input type="date" name="productdate" class="form-control @error('productdate') is-invalid @enderror" value="{{ old('productdate') }}">
                        @error('productdate')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Axis Number</label>
                        <input type="number" name="axisnumber" class="form-control @error('axisnumber') is-invalid @enderror" value="{{ old('axisnumber') }}">
                        @error('axisnumber')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Net Weight (kg)</label>
                        <input type="number" step="0.01" name="netweight" class="form-control @error('netweight') is-invalid @enderror" value="{{ old('netweight') }}">
                        @error('netweight')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Gross Weight (kg)</label>
                        <input type="number" step="0.01" name="grossweight" class="form-control @error('grossweight') is-invalid @enderror" value="{{ old('grossweight') }}">
                        @error('grossweight')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Authorized to Carry (kg)</label>
                        <input type="number" step="0.01" name="authorizedtocarry" class="form-control @error('authorizedtocarry') is-invalid @enderror" value="{{ old('authorizedtocarry') }}">
                        @error('authorizedtocarry')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Persons to Carry</label>
                        <input type="number" name="personstocarry" class="form-control @error('personstocarry') is-invalid @enderror" value="{{ old('personstocarry') }}">
                        @error('personstocarry')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Register Vehicle
                    </button>
                    <a href="{{ route('vehicles.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
