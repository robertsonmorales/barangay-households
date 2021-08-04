@extends('layouts.app')
@section('title', $title)

@section('content')
<form action="{{ ($mode == 'update') ? route('properties.update', $data->id) : route('properties.store') }}"
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
            <label for="owner_code">Property Owner</label>

            <select name="owner_code" 
            id="owner_code" 
            class="custom-select form-control @error('owner_code') is-invalid @enderror" 
            required
            autofocus>
                <option value="" style="display: none;">Select Property Owner...</option>
                @foreach($owners as $owner)
                <option value="{{ $owner['code'] }}" {{ ($mode == 'update' && $owner['code'] == $data->owner_code) ? 'selected' : '' }}>{{ Crypt::decryptString($owner['name']) }}</option>
                @endforeach
            </select>

            @error('owner_code')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>

        <div class="input-group">
            <label>Property Image</label>
            <div class="custom-file w-100">
                <input type="file" 
                class="custom-file-input @error('property_image') is-invalid @enderror"
                id="property_image" 
                name="property_image" 
                accept="image/*" 
                {{ ($mode == 'create') ? 'required' : '' }}
                style="cursor: pointer;">
                <label class="custom-file-label font-size-sm rounded" for="validatedCustomFile">{{ ($mode == 'update') ? $data->property_image : "Choose file..." }}</label>
            </div>

            @error('property_image')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>

        <div class="input-group w-100 overflow-hidden {{ ($mode == 'create') ? 'my-0' : '' }}">
            <img src="{{ ($mode == 'update') ? asset('uploads/properties/'.$data->property_image) : '' }}"
            id="image-preview"
            class="rounded img-fluid">
        </div>

        <div class="input-group">
            <label for="name">Name</label>
            <input type="text"
            name="name" 
            id="name" 
            autocomplete="name" 
            class="form-control @error('name') is-invalid @enderror"
            value="{{($mode == 'update') ? $data->name : old('name')}}"
            required>

            @error('name')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>

        <div class="input-group">
            <label for="name">Code</label>
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
            <label for="name">Property Type</label>
            <select name="property_type_code" 
            id="property_type_code" 
            class="custom-select form-control @error('property_type_code') is-invalid @enderror" 
            required>
                <option value="" style="display: none;">Select Property Type...</option>
                @foreach($types as $type)
                <option value="{{ $type['code'] }}" {{ ($mode == 'update' && $type['code'] == $data->property_type_code) ? 'selected' : '' }}>{{ $type['name'] }}</option>
                @endforeach
            </select>

            @error('property_type_code')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>

        <div class="input-group">
            <label for="name">Price</label>
            <input type="text"
            name="price" 
            id="price" 
            autocomplete="price" 
            class="form-control @error('price') is-invalid @enderror"
            value="{{($mode == 'update') ? $data->price : old('price')}}"
            required autofocus>

            @error('price')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>

        <div class="input-group">
            <label for="name">Amenities</label>

            <div class="d-block">
                <button class="btn btn-primary btn-sm" id="btn-amenity" type="button">Add New Row</button>
            </div>

            <div id="amenities-group">
                @if($mode == 'create')
                <div class="row no-gutters mt-3">
                    <div class="col-12">
                        <input type="text" class="form-control" name="amenities[]" autocomplete="off">
                    </div>
                </div>
                @elseif($mode == 'update')
                @php
                $amenities = json_decode($data->amenities, true);
                @endphp
                @foreach($amenities as $key => $amenity)
                <div class="row no-gutters mt-3">
                    <div class="col-10">
                        <input type="text" class="form-control" name="amenities[]" autocomplete="off" value="{{ $amenity }}">
                    </div>
                    @if($key != 0)
                    <div class="col-1"></div>
                    <div class="col-1">
                        <button title="Remove Row" class="btn btn-danger btn-sm btn-remove btn-amenity-remove" type="button">
                            <i data-feather="x"></i>
                        </button>
                    </div>
                    @endif
                </div>
                @endforeach
                @endif

            </div>
        </div>

        <div class="input-group">
            <label for="name">Landmarks</label>

            <div class="d-block">
                <button class="btn btn-primary btn-sm" id="btn-landmark" type="button">Add New Row</button>
            </div>

            <div id="landmarks-group">
                @if($mode == 'create')
                <div class="row no-gutters mt-3">
                    <div class="col-12">
                        <input type="text" class="form-control" name="landmarks[]" autocomplete="off">
                    </div>
                </div>
                @elseif($mode == 'update')
                @php
                $landmarks = json_decode($data->landmarks, true);
                @endphp
                @foreach($landmarks as $key => $landmark)
                <div class="row no-gutters mt-3">
                    <div class="col-10">
                        <input type="text" class="form-control" name="landmarks[]" autocomplete="off" value="{{ $landmark }}">
                    </div>
                    @if($key != 0)
                    <div class="col-1"></div>
                    <div class="col-1">
                        <button title="Remove Row" class="btn btn-danger btn-sm btn-remove btn-landmark-remove" type="button">
                            <i data-feather="x"></i>
                        </button>
                    </div>
                    @endif
                </div>
                @endforeach
                @endif
            </div>
        </div>

        <div class="input-group">
            <label for="name">Rules & Regulations</label>

            <textarea name="rules_and_regulations"
            class="form-control @error('rules_and_regulations') is-invalid @enderror" 
            id="rules_and_regulations" 
            cols="30" 
            rows="5"
            required>{{ ($mode == 'update') ? $data->rules_and_regulations : '' }}</textarea>
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

@section('vendors-style')
<link rel="stylesheet" href="{{ asset('/vendors/select2/select2.min.css') }}">
@endsection

@section('vendors-script')
<script src="{{ asset('/vendors/select2/select2.min.js') }}"></script>
@endsection

@section('scripts')
<script type="text/javascript">
$(function(){
    var mode = "{{ $mode }}";

    $('.select2-selection--single').addClass('form-control');

    $('#property_image').on('change', function(){
        var file = $(this).get(0).files[0];
        $('.custom-file-label').text(file.name);

        if(file){
            var reader = new FileReader();

            reader.onload = function(){
                $("#image-preview").attr("src", reader.result);
                $("#image-preview").show(500);
                $("#image-preview").parent().removeClass("my-0");
            }

            reader.readAsDataURL(file);
        }
    });

    $('#btn-back').on('click', function(){
        window.location.href = "{{ route('properties.index') }}";
    });

    $('#btn-reset').on('click', function(){
        if(mode == "create") {
            $('#profile_image').val();
            $('.custom-file-label').text("Choose file...");
            $('#image-preview').hide(500);            
            $("#image-preview").parent().addClass("my-0");
        }else{
            if($('#image-preview').attr('src')){
                $('#image-preview').show(500);
            }
        }
    });

    $('#btn-amenity').on('click', function(){
        var x = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>';
        var btnRemovesLength = document.getElementsByClassName('btn-remove').length;

        var newRow = '\
        <div class="row no-gutters mt-3">\
            <div class="col-10">\
                <input type="text" class="form-control" name="amenities[]" autocomplete="off">\
            </div>\
            <div class="col-1"></div>\
            <div class="col-1">\
                <button title="Remove Row" class="btn btn-danger btn-sm btn-remove btn-remove-'+btnRemovesLength+'" onclick="removeAmenityRow('+btnRemovesLength+')" type="button">'+ x +'</button>\
            </div>\
        </div>';

        $('#amenities-group').append(newRow);
    });

    $('#btn-landmark').on('click', function(){
        var x = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>';
        var btnRemovesLength = document.getElementsByClassName('btn-remove').length;

        var newRow = '\
        <div class="row no-gutters mt-3">\
            <div class="col-10">\
                <input type="text" class="form-control" name="landmarks[]" autocomplete="off">\
            </div>\
            <div class="col-1"></div>\
            <div class="col-1">\
                <button title="Remove Row" class="btn btn-danger btn-sm btn-remove btn-remove-'+btnRemovesLength+'" onclick="removeLandmarkRow('+btnRemovesLength+')" type="button">'+ x +'</button>\
            </div>\
        </div>';

        $('#landmarks-group').append(newRow);
    });

    $('.btn-amenity-remove').on('click', function(){
        $(this).parent().parent().remove();
    });

    $('.btn-landmark-remove').on('click', function(){
        $(this).parent().parent().remove();
    });

    $('#card-form').on('submit', function(event){        
        $('.actions button').prop('disabled', true);
        $('.actions button').css('cursor', 'not-allowed');

        $('#btn-submit').html((mode == "update") ? "Submitting Changes.." : "Submitting..");

        $(this).submit();
    });
});

$('#owner_code').select2({
    placeholder: "Select Owner..."
});

$('#property_type_code').select2({
    placeholder: "Select Type..."
});

function removeAmenityRow(rowIndex){
    $('.btn-remove-'+rowIndex).parent().parent().remove();
}

function removeLandmarkRow(rowIndex){
    $('.btn-remove-'+rowIndex).parent().parent().remove();
}

</script>
@endsection