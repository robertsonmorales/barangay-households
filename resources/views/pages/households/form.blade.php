@extends('layouts.app')
@section('title', $title)

@section('content')
<form action="{{ ($mode == 'update') ? route('households.update', $data->id) : route('households.store') }}"
    method="POST"
    class="d-flex flex-column align-items-center mx-4"
    id="card-form">

    <div class="mb-4 card col-md-6 p-4">    
        @csrf
        <div class="w-100">
            <h5>{{ ucfirst($mode).' '.\Str::Singular($title) }}</h5>
        </div>
        
        <div class="input-group">
            <label for="barangay_id">Barangays</label>
            <select name="barangay_id"
            id="barangay_id" 
            class="custom-select form-control @error('barangay_id') is-invalid @enderror"
            required>
                <option value="" style="display: none;">Select Barangay...</option>

                @foreach($barangays as $val)
                <option value="{{ $val->id }}" 
                {{ ($mode == 'update' && $barangay_id == $val->id) ? 'selected' : '' }}>{{ $val->barangay_name }}</option>
                @endforeach
            </select>

            @error('barangay_id')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>

        <div class="input-group">
            <label for="house_id">House Numbers</label>
            <select name="house_id" 
            id="house_id" 
            class="custom-select form-control @error('house_id') is-invalid @enderror" required disabled>
                @foreach($houses as $val)
                <option value="{{ $val->id }}" {{ ($mode == 'update' && $data->house_id == $val->id) ? 'selected' : '' }}>{{ $val->house_id }}</option>
                @endforeach
            </select>

            @error('house_id')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>

        <div class="input-group">
            <label for="household_no">Household No.</label>

            <input type="text" 
            name="household_no" 
            id="household_no" 
            autocomplete="household_no"
            class="form-control @error('household_no') is-invalid @enderror"
            value="{{ ($mode == 'update') ? $data->household_no : old('household_no') }}"
            required>

            @error('household_no')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>

        <div class="input-group">
            <label for="land_ownership">Land Ownership</label>

            <input type="text" 
            name="land_ownership" 
            id="land_ownership" 
            autocomplete="land_ownership"
            class="form-control @error('land_ownership') is-invalid @enderror"
            value="{{ ($mode == 'update') ? $data->land_ownership : old('land_ownership') }}"
            required>

            @error('land_ownership')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>

        <div class="input-group">
            <label for="cr">CR</label>

            <input type="text" 
            name="cr" 
            id="cr" 
            autocomplete="cr"
            class="form-control @error('cr') is-invalid @enderror"
            value="{{ ($mode == 'update') ? $data->cr : old('cr') }}"
            required>

            @error('cr')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>

        <div class="input-group">
            <label for="shared_to">Shared to</label>

            <input type="text" 
            name="shared_to" 
            id="shared_to" 
            autocomplete="shared_to"
            class="form-control @error('shared_to') is-invalid @enderror"
            value="{{ ($mode == 'update') ? $data->shared_to : old('shared_to') }}"
            required>

            @error('shared_to')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>

        <div class="input-group">
            <label for="electricity_connection">Electricity Connection</label>

            <input type="text" 
            name="electricity_connection" 
            id="electricity_connection" 
            autocomplete="electricity_connection"
            class="form-control @error('electricity_connection') is-invalid @enderror"
            value="{{ ($mode == 'update') ? $data->electricity_connection : old('electricity_connection') }}"
            required>

            @error('electricity_connection')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>

        <div class="input-group">
            <div class="custom-control custom-checkbox mr-sm-2">
                <input type="checkbox" 
                class="custom-control-input @error('disaster_kit') is-invalid @enderror"
                name="disaster_kit" 
                id="disaster_kit" 
                style="cursor: pointer;"
                {{ ($mode == 'update' && $data->disaster_kit == 'on') ? 'checked' : old('disaster_kit') }}>
                <label class="custom-control-label font-size-sm" for="disaster_kit">Has Disaster Kit?</label>
            </div>

            @error('disaster_kit')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>

        <div class="input-group">
            <div class="custom-control custom-checkbox mr-sm-2">
                <input type="checkbox" 
                class="custom-control-input @error('praticing_waste_segregation') is-invalid @enderror"
                name="praticing_waste_segregation" 
                id="praticing_waste_segregation" 
                style="cursor: pointer;"
                {{ ($mode == 'update' && $data->praticing_waste_segregation == 'on') ? 'checked' : old('praticing_waste_segregation') }}>
                <label class="custom-control-label font-size-sm" for="praticing_waste_segregation">Practicing Waste Segregation?</label>
            </div>

            @error('praticing_waste_segregation')
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
            <a href="{{ route('households.index') }}" class="btn btn-outline-primary mr-1" id="btn-back">Back</a>
        </div>
    </div>
</form>
@endsection

@section('vendors-style')
<link rel="stylesheet" href="{{ asset('vendors/select2/select2.min.css') }}">
@endsection

@section('vendors-script')
<script src="{{ asset('vendors/select2/select2.min.js') }}"></script>
@endsection

@section('scripts')
<script type="text/javascript">
$(document).ready(function(){
    $('.select2-selection--single').addClass('form-control');

    $('#card-form').on('submit', function(){
        var mode = "{{ $mode }}";

        $('#btn-submit').css('cursor', 'not-allowed').prop('disabled', true);
        $('#btn-submit').html((mode == "update") ? "Submitting Changes.." : "Submitting..");

        $(this).submit();
    });
});

$('#barangay_id').select2({
    placeholder: "Select Barangay...",
    templateSelection: function (data, container) {
        // Add custom attributes to the <option> tag for the selected option
        $(data.element).attr('data-custom-attribute', data.customValue);
        return data.text;
    }
});

// Retrieve custom attribute value of the first selected element
$('#barangay_id').find(':selected').data('custom-attribute');

$('#barangay_id').on('change', function(){
    loadHouses();
});

function loadHouses(){
    var houses = @json($houses);
    var barangay_id = $('#barangay_id').val();
    
    var options = "";

    $('#house_id').empty();
    for (var i = houses.length - 1; i >= 0; i--) {
        if (barangay_id == houses[i].barangay_id) {
            options += '<option value="'+ houses[i].id +'">'+ houses[i].house_no +'</option>';
        }
    }

    $('#house_id').prop('disabled', false);
    $('#house_id').append(options);
}

loadHouses();

</script>
@endsection