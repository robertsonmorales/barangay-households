<div class="mx-4 mt-3 position-sticky sticky-top">
    <nav class="px-3 py-2">
        <div class="row no-gutters align-items-center">
            <button class="btn btn-menu rounded-circle" 
            type="button" 
            id="btn-menu" 
            data-toggle="tooltip" 
            data-placement="top" 
            title="Hide sidebar">
                <i data-feather="menu"></i>
            </button>
        </div>

        <div class="nav-icons">
            <!-- alerts center -->
            <!-- <div class="btn-group mr-2">
                <button class="btn btn-dropdown rounded-circle" 
                data-toggle="dropdown"
                title="Alerts">
                    <span><i data-feather="bell"></i></span>
                    <span class="badge badge-danger badge-pill badge-position">2</span>
                </button>

                <div class="dropdown-menu dropdown-menu-right mt-2 py-2">
                    <div class="dropdown-item-text d-flex align-items-center px-3 py-2">
                        <span class="mr-2"><i data-feather="bell"></i></span>
                        <span class="font-size-sm font-weight-600">ALERTS CENTER</span>
                    </div>
                    
                    <div class="dropdown-divider"></div>
                    
                    <button class="dropdown-item d-flex align-items-center px-3 py-2" type="button">
                        <span class="dropdown-image rounded-circle mr-2">
                            <img class="rounded-circle" src="https://ui-avatars.com/api/?background=dc3545&color=fff&name=Shaine&format=svg&rounded=true&bold=true&font-size=0.4&length=1">
                        </span>

                        <span class="dropdown-info">
                            <span class="subtitle font-weight-bold">Jan Vincent</span>
                            <span class="description font-weight-bold text-truncate" style="width: 220px;">Lorem ipsum dolor sit amet consectetur adipisicing elit. Possimus, perferendis.</span>
                            <span class="description text-muted">Just now</span>
                        </span>
                    </button>
                    <button class="dropdown-item d-flex align-items-center px-3 py-2" type="button">
                        <span class="dropdown-image rounded-circle mr-2">
                            <img class="rounded-circle" src="https://ui-avatars.com/api/?background=dc3545&color=fff&name=Shaine&format=svg&rounded=true&bold=true&font-size=0.4&length=1">
                        </span>

                        <span class="dropdown-info">
                            <span class="subtitle font-weight-bold">Jan Vincent</span>
                            <span class="description font-weight-bold text-truncate" style="width: 220px;">Lorem ipsum dolor sit amet consectetur adipisicing elit. Possimus, perferendis.</span>
                            <span class="description text-muted">Just now</span>
                        </span>
                    </button>

                    <div class="dropdown-divider"></div>

                    <button class="dropdown-item py-2 bg-light text-center" type="button">
                        <span>View All Alerts</span>
                    </button>
                </div>
            </div> -->

            <!-- message center -->
            <!-- <div class="btn-group mr-4">
                <button class="btn btn-dropdown rounded-circle" 
                data-toggle="dropdown"
                title="Messages">
                    <span><i data-feather="message-circle"></i></span>
                    <span class="badge badge-danger badge-pill badge-position">2</span>
                </button>

                <div class="dropdown-menu dropdown-menu-right mt-2 py-2">
                    <div class="dropdown-item-text d-flex align-items-center px-3 py-2">
                        <span class="mr-2"><i data-feather="message-circle"></i></span>
                        <span class="font-size-sm font-weight-600">MESSAGES CENTER</span>
                    </div>

                    <div class="dropdown-divider"></div>

                    <button class="dropdown-item d-flex align-items-center px-3 py-2" type="button">
                        <span class="dropdown-image rounded-circle mr-2">
                            <img class="rounded-circle" src="https://ui-avatars.com/api/?background=3f51b5&color=fff&name=Jan&format=svg&rounded=true&bold=true&font-size=0.4&length=1">
                        </span>
                        <span class="dropdown-info">
                            <span class="subtitle font-weight-bold">Jan Vincent</span>
                            <span class="description font-weight-bold text-truncate" style="width: 220px;">Lorem ipsum dolor sit amet consectetur adipisicing elit. Possimus, perferendis.</span>
                            <span class="description text-muted">Just now</span>
                        </span>
                    </button>
                    <button class="dropdown-item d-flex align-items-center px-3 py-2" type="button">
                        <span class="dropdown-image rounded-circle mr-2">
                            <img class="rounded-circle" src="https://ui-avatars.com/api/?background=dc3545&color=fff&name=Shaine&format=svg&rounded=true&bold=true&font-size=0.4&length=1">
                        </span>

                        <span class="dropdown-info">
                            <span class="subtitle font-weight-bold">Jan Vincent</span>
                            <span class="description font-weight-bold text-truncate" style="width: 220px;">Lorem ipsum dolor sit amet consectetur adipisicing elit. Possimus, perferendis.</span>
                            <span class="description text-muted">Just now</span>
                        </span>
                    </button>

                    <div class="dropdown-divider"></div>

                    <button class="dropdown-item py-2 bg-light text-center" type="button">
                        <span>View All Messages</span>
                    </button>
                </div>
            </div> -->

            <!-- user settings -->
            <div class="btn-group">
                @php
                    $user = ucfirst(Crypt::decryptString(Auth::user()->first_name)). ' '.ucfirst(Crypt::decryptString(Auth::user()->last_name));
                @endphp
                
                <button class="btn btn-light btn-dropdown rounded-circle overflow-hidden" data-toggle="dropdown">
                    <img class="rounded-circle"
                    src="{{ (is_null(Auth::user()->profile_image))
                        ? "https://ui-avatars.com/api/?background=0061f2&color=fff&name=".Crypt::decryptString(Auth::user()->first_name)."&format=svg&rounded=true&bold=true&font-size=0.4&length=1"
                        : asset('uploads/user_accounts/'.Auth::user()->profile_image) }}"
                    alt="{{ Crypt::decryptString(Auth::user()->first_name) }}">
                </button>

                <div class="dropdown-menu dropdown-menu-right mt-2 py-2">

                    <div class="dropdown-item-text">
                        <span class="dropdown-image rounded-circle mr-2 overflow-hidden">
                            <img class="rounded-circle" 
                            src="{{ (is_null(Auth::user()->profile_image))
                                ? "https://ui-avatars.com/api/?background=0061f2&color=fff&name=".Crypt::decryptString(Auth::user()->first_name)."&format=svg&rounded=true&bold=true&font-size=0.4&length=1"
                                : asset('uploads/user_accounts/'.Auth::user()->profile_image) }}"
                            alt="{{ Crypt::decryptString(Auth::user()->first_name) }}">
                        </span>

                        <span class="dropdown-info">
                            <span class="subtitle">{{ ucfirst(Crypt::decryptString(Auth::user()->first_name)). ' '.ucfirst(Crypt::decryptString(Auth::user()->last_name)) }}</span>
                            <span class="description text-muted font-size-sm">{{ Crypt::decryptString(Auth::user()->email) }}</span>
                        </span>
                    </div>

                    <div class="dropdown-divider"></div>

                    <button onclick="window.location.href='{{ route('account_settings.index') }}'" class="dropdown-item py-2" type="button">
                        <span class="mr-2"><i data-feather="settings"></i></span>
                        <span>Account Settings</span>
                    </button>
                    
                    <button onclick="document.getElementById('logout-form').submit();" class="dropdown-item py-2" type="button">
                        <span class="mr-2"><i data-feather="log-out"></i></span>
                        <span>Sign out</span>
                    </button>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </div>
            
        </div>
    </nav>
</div>