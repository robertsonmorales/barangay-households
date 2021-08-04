@extends('layouts.app')
@section('title', $title)

@section('content')
<form action="{{ ($mode == 'update') ? route('barangays.update', $data->id) : route('barangays.store') }}"
    method="POST"
    class="d-flex flex-column align-items-center mx-4"
    id="card-form">

    <div class="mb-4 card col-md-6 p-4">    
        @csrf
        <div class="w-100">
            <h5>{{ ucfirst($mode).' '.\Str::Singular($title) }}</h5>
        </div>
        
        <div class="input-group">
            <label for="barangay_code">Barangay Code</label>
            <input type="text" 
            name="barangay_code" 
            id="barangay_code" 
            autocomplete="barangay_code"
            class="form-control @error('barangay_code') is-invalid @enderror"
            value="{{ ($mode == 'update') ? $data->barangay_code : old('barangay_code') }}"
            required>

            @error('barangay_code')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>

        <div class="input-group">
            <label for="barangay_name">Barangay Name</label>
            <input type="text" 
            name="barangay_name" 
            id="barangay_name" 
            autocomplete="barangay_name"
            class="form-control @error('barangay_name') is-invalid @enderror"
            value="{{ ($mode == 'update') ? $data->barangay_name : old('barangay_name') }}"
            required>

            @error('barangay_name')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>

        <div class="input-group">
            <label for="status">Status</label>
            <select name="status" 
            id="status" 
            class="custom-select form-control @error('status') is-invalid @enderror"
            required>
                <option value="1" {{ ($mode == 'update' && $data->status == 1) ? 'selected' : '' }}>Active</option>
                <option value="0" {{ ($mode == 'update' &&  $data->status == 0) ? 'selected' : '' }}>Deactivate</option>
            </select>

            @error('status')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>

        @if ($mode == 'update')
        @method('PUT')
        <input type="hidden" name="id" value="{{ ($mode == 'update') ? $data->id : ''}}">
        @endif

        <div class="actions w-100">
            <button type="submit" class="btn btn-primary" id="btn-submit">{{ ($mode == 'update') ? 'Submit Changes' : 'Submit' }}</button>
            <button type="reset" class="btn btn-outline-primary mr-1" id="btn-reset">Reset</button>
            <a href="{{ route('barangays.index') }}" class="btn btn-outline-primary mr-1" id="btn-back">Back</a>
        </div>
    </div>
</form>
@endsection

@section('vendors-style')
<link rel="stylesheet" href="{{ asset('/vendors/select2/select2.min.css') }}">
@endsection

@section('vendors-script')
<script src="{{ asset('/vendors/select2/select2.min.js') }}"></script>
@endsection

@section('scripts')
<script type="text/javascript">
$(document).ready(function(){
    $('#card-form').on('submit', function(){
        var mode = "{{ $mode }}";

        $('#btn-submit').css('cursor', 'not-allowed').prop('disabled', true);
        $('#btn-submit').html((mode == "update") ? "Submitting Changes.." : "Submitting..");

        $(this).submit();
    });
});
</script>
@endsection