@extends('layouts.app')
@section('title', $title)

@section('content')
<form action="{{ ($mode == 'update') ? route('property_owners.update', $data->id) : route('property_owners.store') }}"
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
            <label>Profile Image</label>
            <div class="custom-file w-100">
                <input type="file" 
                class="custom-file-input @error('profile_image') is-invalid @enderror"
                id="profile_image" 
                name="profile_image" 
                accept="image/*" 
                {{ ($mode == 'create') ? 'required' : '' }}
                style="cursor: pointer;">
                <label class="custom-file-label font-size-sm rounded" for="profile_image">Choose file...</label>
            </div>

            @error('profile_image')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>

        <div class="input-group align-items-center w-100 overflow-hidden {{ ($mode == 'create') ? 'my-0' : '' }}">
            <img src="{{ ($mode == 'update') ? asset('/uploads/property_owners/'.$data->profile_image) : '' }}"
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
            value="{{($mode == 'update') ? \Crypt::decryptString($data->name) : old('name')}}"
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
            <label for="contact_number">Contact Number</label>
            <input type="text"
            name="contact_number" 
            id="contact_number" 
            autocomplete="contact_number" 
            class="form-control @error('contact_number') is-invalid @enderror"
            value="{{($mode == 'update') ? \Crypt::decryptString($data->contact_number) : old('contact_number')}}"
            required autofocus>

            @error('contact_number')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>

        <div class="input-group">
            <label for="email">Email Address</label>
            <input type="text"
            name="email" 
            id="email" 
            autocomplete="email" 
            class="form-control @error('email') is-invalid @enderror"
            value="{{($mode == 'update') ? \Crypt::decryptString($data->email) : old('email')}}"
            required autofocus>

            @error('email')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>

        <div class="input-group">
            <label for="address">Address</label>
            <textarea name="address" 
            id="address" 
            class="form-control @error('address') is-invalid @enderror"
            cols="30" 
            rows="3" 
            autocomplete="address"
            required
            autofocus>{{ ($mode == 'update') ? $data->address : old('address') }}</textarea>

            @error('address')
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
    $('#profile_image').on('change', function(){
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
        window.location.href = "{{ route('property_types.index') }}";
    });

    $('#btn-reset').on('click', function(){
        let mode = "{{ $mode }}";
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