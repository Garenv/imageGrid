@extends('layouts.app')

@section('content')
    <div class="container-fluid loginPageParent">
        <div class="row full-height justify-content-center">
            <div class="col-12 text-center align-self-center">
                <div class="section pb-5 pt-5 pt-sm-2 text-center">
                    <div class="phopixelTitleContainer">
                        <span data-title="Phopixel" class="text">Phopixel</span>
                        <img src="https://phopixel.s3.amazonaws.com/assets/Phopixel_camera.png" class="cameraImg img-fluid" alt="">
                    </div>
                    <h6 class="mb-0 pb-3 text-black"><span class="text-white">Log In </span><span class="text-white">Sign Up</span></h6>
                    <input class="checkbox" type="checkbox" id="reg-log" name="reg-log"/>
                    <label for="reg-log"></label>
                    <div class="card-3d-wrap mx-auto">
                        <form class="card-3d-wrapper" method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="card-front">
                                <div class="center-wrap">
                                    <div class="section text-center">
                                        <h4 class="mb-4 pb-3 text-white">Log In</h4>
                                        <div class="form-group">
                                            <input id="email" type="email" placeholder="Email Address"
                                                   class="form-style @error('email') is-invalid @enderror" name="email"
                                                   value="{{ old('email') }}" required autocomplete="email">
                                            <i class="input-icon uil uil-mailbox"></i>
                                        </div>
                                        <div class="form-group mt-2">
                                            <input id="password" type="password" placeholder="Password"
                                                   class="form-style @error('password') is-invalid @enderror"
                                                   name="password" required autocomplete="current-password">
                                            <i class="input-icon uil uil-lock-alt"></i>
                                        </div>
                                        <button type="submit" class="btn mt-4">{{ __('Login') }}</button>
                                        <p class="mb-0 mt-4 text-center text-white">
                                            Forgot your password?
                                        </p>
                                        @if (Route::has('password.request'))
                                            <a href="{{ route('password.request') }}" class="link blue">Click Here</a>
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
                                        <h4 class="mb-4 pb-3 text-white">Sign Up</h4>
                                        <div class="form-group">
                                            <input id="logname" type="text" placeholder="Username"
                                                   class="form-style @error('name') is-invalid @enderror" name="name"
                                                   value="{{ old('name') }}" required autocomplete="name" autofocus>

                                            <i class="input-icon uil uil-user"></i>
                                        </div>
                                        <div class="form-group mt-2">
                                            <input id="email" type="email" placeholder="Email"
                                                   class="form-style @error('email') is-invalid @enderror" name="email"
                                                   value="{{ old('email') }}" required autocomplete="email">

                                            <i class="input-icon uil uil-mailbox"></i>
                                        </div>

                                        <div class="form-group mt-2">
                                            <input id="password" type="password" placeholder="Password"
                                                   class="form-style @error('registerPassword') is-invalid @enderror"
                                                   name="registerPassword" required autocomplete="new-password">

                                            <i class="input-icon uil uil-lock-alt"></i>
                                        </div>

                                        <div class="form-group mt-2">
                                            <input id="password-confirmation" type="password" placeholder="Confirm Password"
                                                   class="form-style @error('registerPassword_confirmation') is-invalid @enderror"
                                                   name="registerPassword_confirmation" required autocomplete="new-password">

                                            <i class="input-icon uil uil-lock-alt"></i>
                                        </div>

                                        <div style="display: flex; align-items: center;">
                                            <input type="checkbox" id="agreementCheck" name="agreementCheck" value="ag" required>
                                            <label for="agreementCheck" style="margin-left: 10px; margin-top: 23px; color: #FFFFFF;">I agree to the <a href="/terms-and-conditions">Terms & Conditions</a> and <a href="/privacy-policy">Privacy Policy</a></label>
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

    <script>

        window.onload = function() {

            var toggleCheckbox = document.getElementById('reg-log');

            toggleCheckbox.addEventListener('change', function () {
                if (this.checked) {
                    console.log('Checkbox is checked');
                } else {
                    console.log('Checkbox is not checked');
                }
            })

            if ({{ $errors->has('registerPassword') || $errors->has('registerPassword_confirmation') ? 'true' : 'false' }}) {
                // If there are registration errors, show the register form
                toggleCheckbox.checked = true; // Adjust this based on how your checkbox works
            } else {
                // Otherwise, show the login form
                toggleCheckbox.checked = false; // Adjust this based on how your checkbox works
            }

            toggleCheckbox.addEventListener('change', function () {
                localStorage.setItem('toggleState', this.checked ? 'checked' : 'unchecked');
            });
        }

        document.addEventListener('DOMContentLoaded', function () {
            @if($errors->any())
                @foreach($errors->all() as $error)
                    Toastify({
                        text: "{{ $error }}",
                        duration: 6000,
                        close: true,
                        gravity: "top", // "top" or "bottom"
                        position: "right", // "left", "center" or "right"
                        style: {
                            background: "linear-gradient(to right, #ff5f6d, #ffc371)",
                        },
                    }).showToast();
                @endforeach
            @endif

            @if(session('userTryingToCreateMultipleAccountsError'))
                Toastify({
                    text: "{{ session('userTryingToCreateMultipleAccountsError') }}",
                    duration: 6000,
                    close: true,
                    gravity: "top", // "top" or "bottom"
                    position: "right", // "left", "center" or "right"
                    style: {
                        background: "linear-gradient(to right, #ff5f6d, #ffc371)",
                    },
                }).showToast();
            @endif

            @if(session('profanityNameWhenRegistering'))
                Toastify({
                    text: "{{ session('profanityNameWhenRegistering') }}",
                    duration: 6000,
                    close: true,
                    gravity: "top", // "top" or "bottom"
                    position: "right", // "left", "center" or "right"
                    style: {
                        background: "linear-gradient(to right, #ff5f6d, #ffc371)",
                    },
                }).showToast();
            @endif
        });


    </script>
@endsection

