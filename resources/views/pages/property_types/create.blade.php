@extends('layouts.app')
@section('title', $title)

@section('content')
<form action="{{ ($mode == 'update') ? route('property_types.update', $data->id) : route('property_types.store') }}"
    method="POST"
    class="d-flex flex-column align-items-center mx-4"
    id="card-form"
    enctype="multipart/form-data">
    <div class="mb-4 card col-md-6 p-4">
        @csrf

        <div class="w-100">
            <h5>{{ ucfirst($mode).' '.\Str::Singular($title) }}</h5>
        </div>

        <div class="input-group">
            <label for="name">Name</label>
            <input type="text"
            name="name" 
            id="name" 
            autocomplete="name" 
            class="form-control @error('name') is-invalid @enderror"
            value="{{($mode == 'update') ? $data->name : old('name')}}"
            required autofocus>

            @error('name')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>

        <div class="input-group">
            <label for="code">Code</label>
            <input type="text"
            name="code" 
            id="code" 
            autocomplete="code" 
            class="form-control @error('code') is-invalid @enderror"
            value="{{($mode == 'update') ? $data->code : old('code')}}"
            required autofocus>

            @error('code')
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
                <option value="0" {{ ($mode == 'update' && $data->status == 0) ? 'selected' : '' }}>In-active</option>
            </select>

            @error('status')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>

        @if ($mode == 'update')
        @method('PUT')
        <input type="hidden" name="id" value="{{ ($mode == 'update') ? $data->id: ''}}">
        @endif

        <div class="actions w-100">
            <button type="button" class="btn btn-outline-primary mr-1" id="btn-back">Back</button>
            <button type="reset" class="btn btn-outline-primary mr-1" id="btn-reset">Reset</button>
            <button type="submit" class="btn btn-primary" id="btn-submit">{{ ($mode == 'update') ? 'Submit Changes' : 'Submit' }}</button>
        </div>
    </div>
</form>
@endsection
@section('scripts')
<script type="text/javascript">
$(document).ready(function(){
    $('#btn-back').on('click', function(){
        window.location.href = "{{ route('property_types.index') }}";
    });

    $('#card-form').on('submit', function(event){
        var mode = "{{ $mode }}";
        $('.actions button').prop('disabled', true);
        $('.actions button').css('cursor', 'not-allowed');        

        $('#btn-submit').html((mode == "update") ? "Submitting Changes.." : "Submitting..");

        $(this).submit();
    });
});
</script>
@endsection