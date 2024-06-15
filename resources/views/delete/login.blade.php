@extends('layouts.login')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card shadow-none bg-transparent" id="login_acnt_with_otp_card">
                    <div class="card-header">
                        {{ __('To Delete Account Please Login With Mobile Number') }}
                    </div>
                    <div class="card-body">
                        <form id="login_otp_form" action="{{ route('tmp-loginWithOtp') }}" method="POST">
                            <input type="hidden" class="target" name="intended" value="{{ url()->previous() }}">
                            <div class="row mb-3">
                                <label for="phone" class="col-md-4 col-form-label text-md-end">
                                    {{ __('Phone Number') }}
                                </label>
                                <div class="col-md-6">
                                    <input type="number" name="phone" id="phone" class="form-control"
                                        value="{{ old('phone') }}" required autocomplete="phone" autofocus>
                                    <span class="invalid-feedback error" role="alert" id="error-phone">
                                    </span>
                                </div>
                            </div>
                            <div class="row justify-content-center mt-5 mb-5">
                                <button type="submit" class="btn font-weight-bold btn-purple">{{ __('Continue') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card" id="otp_verification_card" style="display: none">
                    <div class="card-header">
                        {{ __('OTP Verify') }}
                    </div>
                    <div class="card-body" id="otp_verification_card">
                        <form id="otp_verification_form" method="POST" action="{{ route('tmp-otpVerify') }}">
                            <input type="hidden" class="target" name="intended" value="{{ url()->previous() }}">
                            <input type="hidden" name="user_id" id="user_id">
                            <input type="hidden" name="type" id="login_type">
                            @csrf
                            <div class="row mb-3">
                                <label for="verify_otp" class="col-md-12 col-form-label text-md-end mb-5">
                                    We sent a 4 digit OTP to your <span id="response-phone"></span> . Enter this OTP
                                    here to verify your account.
                                </label>
                                <div class="col-md-12">
                                    <input type="text" name="otp" id="verify_otp" class="form-control" required
                                        autocomplete="off" autofocus>
                                </div>
                                <div class="error text-danger" id="otp-error-otp"></div>
                            </div>
                            <div class="row justify-content-center mt-5 mb-5">
                                <button type="submit" class="btn font-weight- btn-purple">{{ __('Verify OTP') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
