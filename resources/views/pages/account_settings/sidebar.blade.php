<div class="col-md-4 col-lg-3 mb-4 mb-md-0 mr-md-4">
    <div class="list-group px-2">
        <a href="/account_settings" class="list-group-item list-group-item-action">
            <span class="mr-2"><i data-feather="user"></i></span>
            <span>Profile Information</span>
        </a>
        <a href="{{ route('account_settings.email') }}" class="list-group-item list-group-item-action">
            <span class="mr-2"><i data-feather="mail"></i></span>
            <span>Email</span>
        </a>

        <a href="{{ route('account_settings.password') }}" class="list-group-item list-group-item-action">
            <span class="mr-2"><i data-feather="lock"></i></span>
            <span>Password</span>
        </a>

        <!-- <a href="#" class="list-group-item list-group-item-action">
            <span class="mr-2"><i data-feather="sliders"></i></span>
            <span>Preferences</span>
        </a> -->

        <!-- <a href="#" class="list-group-item list-group-item-action">
            <span class="mr-2"><i data-feather="monitor"></i></span>
            <span>Browser Sessions</span>
        </a> -->

        <a href="{{ route('account_settings.delete_account') }}" class="list-group-item list-group-item-action" id="delete-account">
            <span class="mr-2"><i data-feather="trash"></i></span>
            <span>Delete Account</span>
        </a>
    </div>
</div>