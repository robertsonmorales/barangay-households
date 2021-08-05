@extends('layouts.app')
@section('title', $title)

@section('content')
<form action="{{ ($mode == 'update') ? route('individuals.update', $data->id) : route('individuals.store') }}"
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
            <label for="family_no">Family</label>

            <select name="family_id" 
            id="family_id" 
            class="custom-select form-control @error('family_id') is-invalid @enderror" 
            required>
                @foreach($families as $val)
                <option value="{{ $val->id }}" {{ ($mode == 'update' && $data->family_id == $val->id) ? 'selected' : '' }}>{{ $val->family_name .' ('.$val->family_name.')' }}</option>
                @endforeach
            </select>

            @error('family_id')
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
            <a href="{{ route('individuals.index') }}" class="btn btn-outline-primary mr-1" id="btn-back">Back</a>
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
    loadHouseholds();
    loadFamilies();
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
    loadFamilies();
}).select2({
    placeholder: "Select House No...",
    templateSelection: function (data, container) {
        // Add custom attributes to the <option> tag for the selected option
        $(data.element).attr('data-custom-attribute', data.customValue);
        return data.text;
    }
});

$('#household_id').on('change', function(){
    loadFamilies();
}).select2({
    placeholder: "Select Household No...",
    templateSelection: function (data, container) {
        // Add custom attributes to the <option> tag for the selected option
        $(data.element).attr('data-custom-attribute', data.customValue);
        return data.text;
    }
});

function loadHouses(){
    var houses = @json($houses);
    var barangay_id = $('#barangay_id').val();
    var house_options = "";

    $('#house_id').empty();
    for (var i = houses.length - 1; i >= 0; i--) {
        if (barangay_id == houses[i].barangay_id) {
            house_options += '<option value="'+ houses[i].id +'">'+ houses[i].house_no +'</option>';
        }
    }

    $('#house_id').append(house_options);

    loadHouseholds();
    
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

    loadFamilies();
}

function loadFamilies(){
    var families = @json($families);
    var household_id = $('#household_id').val();

    var families_options = "";

    $('#family_id').empty();

    for (var j = families.length - 1; j >= 0; j--) {
        if(household_id == families[j].household_id){
            families_options += '<option value="'+ families[j].id +'">'+ families[j].family_name + ' (' + families[j].family_no + ')</option>';
        }
    }

    $('#family_id').append(families_options);
}

loadHouses();

</script>
@endsection