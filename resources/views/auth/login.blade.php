@extends('layouts.app')

@section('content')
    <div class="container-fluid">

        <div class="row full-height justify-content-center">
            <div class="col-12 text-center align-self-center py-5">
                <div class="section pb-5 pt-5 pt-sm-2 text-center">
                    <div class="phopixelTitleContainer">
              <span data-title="Phopixel" class="text">
                Phopixel
              </span>
                    </div>
                    <h6 class="mb-0 pb-3 text-black"><span>Log In </span><span>Sign Up</span></h6>
                    <input class="checkbox" type="checkbox" id="reg-log" name="reg-log"/>
                    <label for="reg-log"></label>
                    <div class="card-3d-wrap mx-auto">
                        <form class="card-3d-wrapper" method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="card-front">
                                <div class="center-wrap">
                                    <div class="section text-center">
                                        <h4 class="mb-4 pb-3">Log In</h4>
                                        <div class="form-group">
                                            <input id="email" type="email" placeholder="Email Address"
                                                   class="form-style @error('email') is-invalid @enderror" name="email"
                                                   value="{{ old('email') }}" required autocomplete="email">
                                            @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                            <i class="input-icon uil uil-at"></i>
                                        </div>
                                        <div class="form-group mt-2">
                                            <input id="password" type="password" placeholder="Password"
                                                   class="form-style @error('password') is-invalid @enderror"
                                                   name="password" required autocomplete="current-password">
                                            @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                            <i class="input-icon uil uil-lock-alt"></i>
                                        </div>
                                        <button type="submit" class="btn mt-4">{{ __('Login') }}</button>
                                        <p class="mb-0 mt-4 text-center">
                                            Forgot your password?
                                        </p>
                                        @if (Route::has('password.request'))
                                            <a href="{{ route('password.request') }}"
                                               class="link"><u>{{ __('Click Here') }}</u></a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </form>

                        <form class="card-3d-wrapper" method="POST" action="{{ route('register') }}">
                            @csrf
                            <div class="card-back">
                                <div class="center-wrap">
                                    <div class="section text-center">
                                        <h4 class="mb-4 pb-3">Sign Up</h4>
                                        <div class="form-group">
                                            <input id="logname" type="text" placeholder="Name"
                                                   class="form-style @error('name') is-invalid @enderror" name="name"
                                                   value="{{ old('name') }}" required autocomplete="name" autofocus>
                                            @error('name')
                                            <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                            <i class="input-icon uil uil-user"></i>
                                        </div>
                                        <div class="form-group mt-2">
                                            <input id="email" type="email" placeholder="Email"
                                                   class="form-style @error('email') is-invalid @enderror" name="email"
                                                   value="{{ old('email') }}" required autocomplete="email">
                                            @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                            <i class="input-icon uil uil-at"></i>
                                        </div>

                                        <div class="form-group mt-2">
                                            <input id="password" type="password" placeholder="Password"
                                                   class="form-style @error('password') is-invalid @enderror"
                                                   name="password" required autocomplete="new-password">

                                            @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                            <i class="input-icon uil uil-lock-alt"></i>
                                        </div>

                                        <div class="form-group mt-2">
                                            <input id="password" type="password" placeholder="Confirm Password"
                                                   class="form-style @error('password') is-invalid @enderror"
                                                   name="password_confirmation" required autocomplete="new-password">
                                            <i class="input-icon uil uil-lock-alt"></i>
                                        </div>
                                        <button type="submit" class="btn mt-4">{{ __('Register') }}</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
