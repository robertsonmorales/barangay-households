@extends('layouts.app')
@section('title', $title)

@section('content')

@include('includes.alerts')

<div class="row no-gutters align-items-start mx-4">
    @include('pages.account_settings.sidebar')

    <div class="card col-md-7 col-lg-6 mb-4 p-4">
        <div class="w-100">
            <h5>Delete Account</h5>
        </div>

        <div class="input-group">
            <p class="text-muted font-size-sm">Once you delete your account, you will loose all data associated with it.</p>
        </div>

        <div class="actions w-100">                        
            <button type="button" class="btn btn-danger" id="btn-delete">Delete Account</button>
        </div>
    </div>
</div>

<!-- The Modal -->
<form class="modal" form action="{{ route('account_settings.destroy', Auth::id()) }}" method="post" id="settings-form">
    @csrf
    @method('DELETE')

    <div class="modal-content">
        <div class="modal-header">      
            <div class="modal-icon modal-icon-error">
                <i data-feather="alert-triangle"></i>
            </div>

            <div class="modal-body">
                <h5>Delete Your Account</h5>
                <p>Are you sure? This action cannot be undone.</p>
            </div>

        </div>

        <div class="modal-footer">
            <button type="submit" class="btn btn-danger" id="btn-remove">Yes Do It!</button>
            <button type="button" class="btn btn-outline-secondary" id="btn-cancel">Cancel</button>
        </div>
    </div>

</form>
<!-- Ends here -->
@endsection

@section('scripts')
<script type="text/javascript">
$(document).ready(function(){
    $("#btn-delete").on('click', function(){
        $('.modal').attr('style', 'display: flex;');
    });

    $('#btn-cancel').on('click', function(){
        $('.modal').hide();
    });

    $('#settings-form').on('submit', function(){
        $('#btn-remove').prop('disabled', true);
        $('#btn-remove').html("Deleting Account..");
    });
});
</script>
@endsection