@extends('layouts.app')

@section('title', 'Edit Inspection')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <h1>Edit Inspection</h1>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('inspections.update', $inspection->seriesno) }}">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Plate Number</label>
                        <input type="text" name="plateno" class="form-control" value="{{ $inspection->plateno }}" disabled>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Vehicle Type</label>
                        <input type="text" name="vehicletype" class="form-control" value="{{ $inspection->vehicletype }}" disabled>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Department <span class="text-danger">*</span></label>
                        <select name="dept_id" class="form-control @error('dept_id') is-invalid @enderror" required>
                            <option value="">Select Department</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}" {{ ($inspection->dept_id == $dept->id) ? 'selected' : '' }}>
                                    {{ $dept->title }}
                                </option>
                            @endforeach
                        </select>
                        @error('dept_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Inspect Date <span class="text-danger">*</span></label>
                        <input type="date" name="inspectdate" class="form-control @error('inspectdate') is-invalid @enderror" 
                               value="{{ $inspection->inspectdate ? date('Y-m-d', strtotime($inspection->inspectdate)) : '' }}" required>
                        @error('inspectdate')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Inspector <span class="text-danger">*</span></label>
                        <input type="text" name="inspector" class="form-control @error('inspector') is-invalid @enderror" 
                               value="{{ $inspection->inspector }}" required>
                        @error('inspector')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Test Result <span class="text-danger">*</span></label>
                        <select name="testresult" class="form-control @error('testresult') is-invalid @enderror" required>
                            <option value="">Select Result</option>
                            <option value="1" {{ ($inspection->testresult == '1') ? 'selected' : '' }}>Passed</option>
                            <option value="0" {{ ($inspection->testresult == '0') ? 'selected' : '' }}>Failed</option>
                            <option value="" {{ ($inspection->testresult == '') ? 'selected' : '' }}>Pending</option>
                        </select>
                        @error('testresult')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Owner</label>
                        <input type="text" name="owner" class="form-control" value="{{ $inspection->owner }}">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Conclusion</label>
                        <textarea name="conclusion" class="form-control @error('conclusion') is-invalid @enderror" rows="3">{{ $inspection->conclusion ?? '' }}</textarea>
                        @error('conclusion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Save Changes
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
