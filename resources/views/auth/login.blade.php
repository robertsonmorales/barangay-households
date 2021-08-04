@extends('layouts.auth')

@section('title', 'Login Page')

@section('auth')
<div class="row justify-content-center align-items-center vh-100">
    <div class="col-md-6 col-lg-8">
        <div class="row no-gutters auth-card">
            <div class="col-md d-none d-lg-flex login-banner p-4">
                <img src="{{ asset('images/svg/login.svg') }}" alt="login-banner" class="img-fluid">
            </div>
            <div class="col card p-2">
                <div class="card-header border-0 bg-white">
                    @include('includes.alerts')

                    <div class="font-weight-bold h2">{{ __('Sign in') }}</div>
                    <div class="font-weight-lighter h6 mb-0">{{ __("Welcome back, enter your credentials to start.") }}</div>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}" id="auth-form">
                        @csrf

                        <div class="form-group row flex-column inputs">
                            <label for="username" 
                            class="col">{{ __('Username') }}</label>

                            <div class="col">
                                <input id="username" 
                                type="username" 
                                class="form-control @error('username') is-invalid @enderror" name="username" value="{{ old('username') }}" 
                                required 
                                autocomplete="off">
                                <span class="position-absolute icon text-muted">
                                    <i data-feather="user"></i>
                                </span>

                                @error('username')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row flex-column inputs">
                            <label for="password" 
                            class="col">{{ __('Password') }}</label>

                            <div class="col">
                                <input id="password" 
                                type="password" 
                                class="form-control @error('password') is-invalid @enderror" 
                                name="password" 
                                required 
                                autocomplete="off">
                                <span class="position-absolute icon text-muted">
                                    <i data-feather="lock"></i>
                                </span>
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col">
                                <div class="custom-control custom-checkbox mr-sm-2">
                                    <input type="checkbox" class="custom-control-input" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }} style="cursor: pointer;">
                                    <label class="custom-control-label mb-0 font-size-sm" for="remember">{{ __('Remember Me') }}</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col">
                                <button type="submit" id="btn-sign-in" class="btn btn-primary font-weight-normal w-100">{{ __('Sign in') }}</button>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col text-center">
                                @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="font-size-sm">{{ __('Forgot Your Password?') }}</a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>  
    </div>
</div>
@endsection

@section('scripts')
<script type="text/javascript">
    $(document).ready(function() {
        $('#spinner').hide();

        $('#auth-form').on('submit', function(){
            $('#spinner').show();
            $('#btn-sign-in').prop('disabled', true);
            $('#btn-sign-in').css('cursor', 'not-allowed');
            $('#btn-sign-in').html('Signing in...');

            $(this).submit();
        });
    });
</script>
@endsection