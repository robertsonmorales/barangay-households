@extends('layouts.app')
@section('title', $title)

@section('content')
<form action="{{ ($mode == 'update') ? route('families.update', $data->id) : route('families.store') }}"
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
            <label for="house_id">House No.</label>
            <select name="house_id" 
            id="house_id" 
            class="custom-select form-control @error('house_id') is-invalid @enderror" 
            required>
                @foreach($houses as $val)
                <option value="{{ $val->id }}" {{ ($mode == 'update' && $house_id == $val->id) ? 'selected' : '' }}>{{ $val->house_id }}</option>
                @endforeach
            </select>

            @error('house_id')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>

        <div class="input-group">
            <label for="household_id">Household No.</label>

            <select name="household_id" 
            id="household_id" 
            class="custom-select form-control @error('household_id') is-invalid @enderror" 
            required>
                @foreach($households as $val)
                <option value="{{ $val->id }}" {{ ($mode == 'update' && $data->household_id == $val->id) ? 'selected' : '' }}>{{ $val->household_no }}</option>
                @endforeach
            </select>

            @error('household_id')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>

        <div class="input-group">
            <label for="family_no">Family No.</label>

            <input type="text" 
            name="family_no" 
            id="family_no" 
            autocomplete="family_no"
            class="form-control @error('family_no') is-invalid @enderror"
            value="{{ ($mode == 'update') ? $data->family_no : old('family_no') }}"
            required>

            @error('family_no')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>

        <div class="input-group">
            <label for="family_name">Family Name</label>

            <input type="text" 
            name="family_name" 
            id="family_name" 
            autocomplete="family_name"
            class="form-control @error('family_name') is-invalid @enderror"
            value="{{ ($mode == 'update') ? $data->family_name : old('family_name') }}"
            required>

            @error('family_name')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>

        <div class="input-group">
            <div class="custom-control custom-checkbox mr-sm-2">
                <input type="checkbox" 
                class="custom-control-input @error('have_cell_radio_tv') is-invalid @enderror"
                name="have_cell_radio_tv" 
                id="have_cell_radio_tv" 
                style="cursor: pointer;"
                {{ ($mode == 'update' && $data->have_cell_radio_tv == 'on') ? 'checked' : old('have_cell_radio_tv') }}>
                <label class="custom-control-label font-size-sm" for="have_cell_radio_tv">Have Cellphone/Radio/TV?</label>
            </div>

            @error('have_cell_radio_tv')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>

        <div class="input-group">
            <div class="custom-control custom-checkbox mr-sm-2">
                <input type="checkbox" 
                class="custom-control-input @error('have_vehicle') is-invalid @enderror"
                name="have_vehicle" 
                id="have_vehicle" 
                style="cursor: pointer;"
                {{ ($mode == 'update' && $data->have_vehicle == 'on') ? 'checked' : old('have_vehicle') }}>
                <label class="custom-control-label font-size-sm" for="have_vehicle">Have Vehicle?</label>
            </div>

            @error('have_vehicle')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>

        <div class="input-group" id="vehicle-is-checked">
            <fieldset>
                <legend class="h5">Vehicle Types</legend>
                <div class="custom-control custom-checkbox mr-sm-2">
                    <input type="checkbox" 
                    class="custom-control-input @error('have_bicycle') is-invalid @enderror"
                    name="have_bicycle" 
                    id="have_bicycle" 
                    style="cursor: pointer;"
                    {{ ($mode == 'update' && $data->have_bicycle == 'on') ? 'checked' : old('have_bicycle') }}>
                    <label class="custom-control-label font-size-sm" for="have_bicycle">Bicycle</label>
                </div>

                <div class="custom-control custom-checkbox mr-sm-2">
                    <input type="checkbox" 
                    class="custom-control-input @error('have_pedicab') is-invalid @enderror"
                    name="have_pedicab" 
                    id="have_pedicab" 
                    style="cursor: pointer;"
                    {{ ($mode == 'update' && $data->have_pedicab == 'on') ? 'checked' : old('have_pedicab') }}>
                    <label class="custom-control-label font-size-sm" for="have_pedicab">Pedicab</label>
                </div>

                <div class="custom-control custom-checkbox mr-sm-2">
                    <input type="checkbox" 
                    class="custom-control-input @error('have_motorcycle') is-invalid @enderror"
                    name="have_motorcycle" 
                    id="have_motorcycle" 
                    style="cursor: pointer;"
                    {{ ($mode == 'update' && $data->have_motorcycle == 'on') ? 'checked' : old('have_motorcycle') }}>
                    <label class="custom-control-label font-size-sm" for="have_motorcycle">Motorcycle</label>
                </div>

                <div class="custom-control custom-checkbox mr-sm-2">
                    <input type="checkbox" 
                    class="custom-control-input @error('have_tricycle') is-invalid @enderror"
                    name="have_tricycle" 
                    id="have_tricycle" 
                    style="cursor: pointer;"
                    {{ ($mode == 'update' && $data->have_tricycle == 'on') ? 'checked' : old('have_tricycle') }}>
                    <label class="custom-control-label font-size-sm" for="have_tricycle">Tricycle</label>
                </div>

                <div class="custom-control custom-checkbox mr-sm-2">
                    <input type="checkbox" 
                    class="custom-control-input @error('have_four_wheeled') is-invalid @enderror"
                    name="have_four_wheeled" 
                    id="have_four_wheeled" 
                    style="cursor: pointer;"
                    {{ ($mode == 'update' && $data->have_four_wheeled == 'on') ? 'checked' : old('have_four_wheeled') }}>
                    <label class="custom-control-label font-size-sm" for="have_four_wheeled">Four Wheeled</label>
                </div>

                <div class="custom-control custom-checkbox mr-sm-2">
                    <input type="checkbox" 
                    class="custom-control-input @error('have_truck') is-invalid @enderror"
                    name="have_truck" 
                    id="have_truck" 
                    style="cursor: pointer;"
                    {{ ($mode == 'update' && $data->have_truck == 'on') ? 'checked' : old('have_truck') }}>
                    <label class="custom-control-label font-size-sm" for="have_truck">Truck</label>
                </div>

                <div class="custom-control custom-checkbox mr-sm-2">
                    <input type="checkbox" 
                    class="custom-control-input @error('have_motor_boat') is-invalid @enderror"
                    name="have_motor_boat" 
                    id="have_motor_boat" 
                    style="cursor: pointer;"
                    {{ ($mode == 'update' && $data->have_motor_boat == 'on') ? 'checked' : old('have_motor_boat') }}>
                    <label class="custom-control-label font-size-sm" for="have_motor_boat">Motor Boat</label>
                </div>

                <div class="custom-control custom-checkbox mr-sm-2">
                    <input type="checkbox" 
                    class="custom-control-input @error('have_boat') is-invalid @enderror"
                    name="have_boat" 
                    id="have_boat" 
                    style="cursor: pointer;"
                    {{ ($mode == 'update' && $data->have_boat == 'on') ? 'checked' : old('have_boat') }}>
                    <label class="custom-control-label font-size-sm" for="have_boat">Boat</label>
                </div>

            </fieldset>
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
            <a href="{{ route('families.index') }}" class="btn btn-outline-primary mr-1" id="btn-back">Back</a>
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
$(function(){
    $('.select2-selection--single').addClass('form-control');
});

$('#card-form').on('submit', function(){
    var mode = "{{ $mode }}";

    $('#btn-submit').css('cursor', 'not-allowed').prop('disabled', true);
    $('#btn-submit').html((mode == "update") ? "Submitting Changes.." : "Submitting..");

    $(this).submit();
});

// Retrieve custom attribute value of the first selected element
$('#barangay_id').find(':selected').data('custom-attribute');

$('#barangay_id').on('change', function(){
    loadHouses();
}).select2({
    placeholder: "Select Barangay...",
    templateSelection: function (data, container) {
        // Add custom attributes to the <option> tag for the selected option
        $(data.element).attr('data-custom-attribute', data.customValue);
        return data.text;
    }
});

$('#house_id').on('change', function(){
    loadHouseholds();
}).select2({
    placeholder: "Select House No...",
    templateSelection: function (data, container) {
        // Add custom attributes to the <option> tag for the selected option
        $(data.element).attr('data-custom-attribute', data.customValue);
        return data.text;
    }
});

$('#have_vehicle').on('change', function(){
    haveVehicle();
});

function haveVehicle(){
    if ($('#have_vehicle').is(':checked')) {
        $('#vehicle-is-checked').show(500);
    }else{
        $('#vehicle-is-checked').hide(350);

        $('#vehicle-is-checked .custom-control-input').prop('checked', false);
    }
}

function loadHouses(){
    var houses = @json($houses);
    // var households = @json($households);

    var barangay_id = $('#barangay_id').val();
    // var house_id = $('#house_id').val();

    var house_options = "";
    // var household_options = "";

    $('#house_id').empty();
    // $('#household_id').empty();

    for (var i = houses.length - 1; i >= 0; i--) {
        if (barangay_id == houses[i].barangay_id) {
            house_options += '<option value="'+ houses[i].id +'">'+ houses[i].house_no +'</option>';

            // var house_id = houses[i].id;
            // for (var j = households.length - 1; j >= 0; j--) {
            //     if(house_id == households[j].house_id){
            //         household_options += '<option value="'+ households[j].id +'">'+ households[j.household_no +'</option>';
            //     }
            // }
        }
    }

    $('#house_id').append(house_options);
    // $('#household_id').append(household_options);
}

function loadHouseholds(){
    var households = @json($households);
    var house_id = $('#house_id').val();

    var household_options = "";

    $('#household_id').empty();

    for (var j = households.length - 1; j >= 0; j--) {
        if(house_id == households[j].house_id){
            household_options += '<option value="'+ households[j].id +'">'+ households[j].household_no +'</option>';
        }
    }

    $('#household_id').append(household_options);
}

haveVehicle();
loadHouses();
loadHouseholds();

</script>
@endsection