@extends('layouts.app')
@section('title', $title)

@section('content')

@include('includes.alerts')

<div class="row no-gutters align-items-start mx-4">
    @include('pages.account_settings.sidebar')
    @include('pages.account_settings.email.form')
</div>

@endsection

@section('scripts')
<script type="text/javascript">
$(document).ready(function(){
    $('#settings-form').on('submit', function(){
        $('#btn-save').prop('disabled', true);
        $('#btn-reset').prop('disabled', true);
        $('#btn-save').css('cursor', 'not-allowed');
        $('#btn-reset').css('cursor', 'not-allowed');

        $('#btn-save').html('Saving Changes..');
        
        $(this).submit();
    });
});
</script>
@endsection