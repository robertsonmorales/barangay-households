@extends('layouts.app')
@section('title', $title)

@section('content')
<form action="{{ ($mode == 'update') ? route('houses.update', $data->id) : route('houses.store') }}"
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
                {{ ($mode == 'update' && $data->barangay_id == $val->id) ? 'selected' : '' }}>{{ $val->barangay_name }}</option>
                @endforeach
            </select>

            @error('barangay_id')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>

        <div class="input-group">
            <label for="house_no">House No.</label>
            <input type="text" 
            name="house_no" 
            id="house_no" 
            autocomplete="house_no"
            class="form-control @error('house_no') is-invalid @enderror"
            value="{{ ($mode == 'update') ? $data->house_no : old('house_no') }}"
            required>

            @error('house_no')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>

        <div class="input-group">
            <label for="house_roof">House Roof</label>
            <select name="house_roof" 
            id="house_roof" 
            class="custom-select form-control @error('house_roof') is-invalid @enderror"
            required>
                <option value="" style="display: none;">Select House Roof...</option>

                @foreach($house_details as $key => $details)
                <option value="{{ $key }}" {{ ($mode == 'update' && $data->house_roof == $key) ? 'selected' : '' }}>{{ $details }}</option>
                @endforeach
            </select>

            @error('house_roof')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>

        <div class="input-group">
            <label for="house_wall">House Wall</label>

            <select name="house_wall" 
            id="house_wall" 
            class="custom-select form-control @error('house_wall') is-invalid @enderror"
            required>
                <option value="" style="display: none;">Select House Wall...</option>
                @foreach($house_details as $key => $details)
                <option value="{{ $key }}" {{ ($mode == 'update' && $data->house_roof == $key) ? 'selected' : '' }}>{{ $details }}</option>
                @endforeach
            </select>

            @error('house_wall')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>

        <div class="input-group">
            <div class="custom-control custom-checkbox mr-sm-2">
                <input type="checkbox" 
                class="custom-control-input @error('building_permit') is-invalid @enderror"
                name="building_permit" 
                id="building_permit" 
                style="cursor: pointer;"
                {{ ($mode == 'update' && $data->building_permit == 'on') ? 'checked' : old('building_permit') }}>
                <label class="custom-control-label font-size-sm" for="building_permit">Has Building Permit?</label>
            </div>

            @error('building_permit')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>

        <div class="input-group">
            <div class="custom-control custom-checkbox mr-sm-2">
                <input type="checkbox" 
                class="custom-control-input @error('occupancy_permit') is-invalid @enderror"
                name="occupancy_permit" 
                id="occupancy_permit" 
                style="cursor: pointer;"
                {{ ($mode == 'update' && $data->occupancy_permit == 'on') ? 'checked' : old('occupancy_permit') }}>
                <label class="custom-control-label font-size-sm" for="occupancy_permit">Has Occupancy Permit?</label>
            </div>

            @error('occupancy_permit')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>

        <div class="input-group">
            <label for="date_constructed">Date Constructed</label>
            <input type="date" 
            name="date_constructed" 
            id="date_constructed" 
            autocomplete="date_constructed"
            class="form-control @error('date_constructed') is-invalid @enderror"
            value="{{ ($mode == 'update') ? $data->date_constructed : old('date_constructed') }}"
            required>

            @error('date_constructed')
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
            <a href="{{ route('houses.index') }}" class="btn btn-outline-primary mr-1" id="btn-back">Back</a>
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

</script>
@endsection