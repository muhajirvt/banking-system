@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card" id="loginSection">
                <div class="card-header">{{ __('Login') }}</div>

                <div class="card-body">
                    <form method="POST" id="loginForm" url="{{ route('send.otp') }}">
                        @csrf

                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        {{-- <div class="row mb-3">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
                                </div>
                            </div>
                        </div> --}}

                        <div class="row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="button" class="btn btn-primary" onclick="login()">
                                    {{ __('Login') }}
                                </button>

                                {{-- @if (Route::has('password.request'))
                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                @endif --}}
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    @if (session('success'))
        returnMessage('Registered', '{{ session('success') }}', 'success');
    @endif
    function login(){
        var form = $('#loginForm');
        var url = form.attr('url');
        var method = form.attr('method');
        var params = form.serialize();
        commonAjax(url, method, params);
    }
    function showOtpForm(userId, bcryptOtp){
      var loginContent = $('#loginSection');
      loginContent.empty();
      loginContent.html('<input type="hidden" id="user_id" value="'+userId+'">\<input type="hidden" id="valid_otp" value="'+bcryptOtp+'">\
      <div class="card-header">2FA Authentication</div>\
        <div class="card-body">\
            <div class="row mb-3">\
                <label for="otp" class="col-md-4 col-form-label text-md-end">OTP</label>\
                <div class="col-md-6">\
                    <input id="otp" type="text" class="form-control" name="otp" required autofocus>\
                </div>\
            </div>\
        </div>\
        <div class="row mb-0" style="padding:30px">\
            <div class="col-md-8 offset-md-4">\
                <button type="button" class="btn btn-primary" onclick="verify()">\Verify</button>\
            </div>\
        </div>'
        );
    }
    function verify(){
        var params = {
            "_token" : '{{ csrf_token() }}',
            "user_id" : $('#user_id').val(),    
            "valid_otp" : $('#valid_otp').val(),
            "otp"   : $('#otp').val()
        };
        commonAjax('{{ route('verify.otp') }}', "POST", params);
    }
</script>
@endsection


