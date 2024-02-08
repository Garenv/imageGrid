@extends('layouts.app')

@section('content')

    <nav class="navbar navbar-light bg-light">
        <div class="container-fluid d-flex justify-content-center">
            <div class="txt" id="txt">
                <h1 class="phopixel-title">Phopixel</h1>
            </div>
        </div>
    </nav>

    <section class="loginRegisterSection">
        <div class="formContainer">
            <div class="user signinBx">
                <div class="imgBx"><img src="https://phopixel.s3.amazonaws.com/assets/logo/pho_logo_540x675-v2.png" alt="" /></div>
                <div class="formBx">
                    <form action="{{ route('login') }}" method="POST" class="loginForm">
                        @csrf
                        <h2>Sign In</h2>

                        <input
                            data-cy="login-email-input"
                            class="form-style login-email-input @error('email') is-invalid @enderror" name="email"
                            id="email"
                            type="email"
                            placeholder="Email Address"
                            value="{{ old('email') }}"
                            autocomplete="email"
                            required
                        />

                        <input
                            data-cy="login-password-input"
                            class="form-style login-password-input @error('password') is-invalid @enderror"
                            id="password"
                            type="password"
                            placeholder="Password"
                            name="password"
                            autocomplete="current-password"
                            required
                        />

                        <input
                            data-cy="login-button"
                            class="btn"
                            type="submit"
                            value="Login"
                            placeholder={{ __('Login') }}
                        />

                        <p class="signup">Don't have an account?<a data-cy="sign-up-link" href="#" onclick="toggleForm();"> Sign Up.</a></p>
                        <p class="mb-0 mt-4 text-center">Forgot your password?</p>

                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="link blue">Click Here</a>
                        @endif
                    </form>
                </div>
            </div>

            <div class="user signupBx">
                <div class="formBx">
                    <form action="{{ route('register') }}" method="POST" id="registerForm" class="registerForm">
                        @csrf
                        <h2>Create an Account</h2>

                        <input
                            data-cy="name-input"
                            class="form-style name-input @error('name') is-invalid @enderror"
                            type="text"
                            name="name"
                            placeholder="Username"
                            value="{{ old('name') }}"
                            autocomplete="name"
                            required
                        />

                        <input
                            data-cy="register-email-input"
                            class="form-style register-email-input @error('email') is-invalid @enderror"
                            type="email"
                            name="email"
                            placeholder="Email"
                            value="{{ old('email') }}"
                            autocomplete="email"
                            required
                        />

                        <input
                            data-cy="register-password-input"
                            class="form-style register-password-input @error('registerPassword') is-invalid @enderror"
                            type="password"
                            name="registerPassword"
                            placeholder="Password"
                            autocomplete="new-password"
                            required
                        />

                        <input
                            data-cy="password-confirm-input"
                            class="form-style password-confirm-input @error('registerPassword_confirmation') is-invalid @enderror"
                            type="password"
                            name="registerPassword_confirmation"
                            placeholder="Confirm Password"
                            autocomplete="new-password"
                            required
                        />

                        <div class="form-check">
                            <input data-cy="agreement-input" type="checkbox" value="" id="defaultCheck1" required>
                            <label data-cy="agreement-label" class="form-check-label" for="defaultCheck1">
                                I agree to the <a href="/terms-and-conditions">Terms & Conditions</a> and <a href="/privacy-policy">Privacy Policy</a>
                            </label>
                        </div>

                        <input
                            class="btn mt-4 g-recaptcha"
                            type="submit"
                            value="{{ __('Register') }}"
                            data-sitekey="{{ config('services.recaptcha_v3.siteKey') }}"
                            data-action="submitRegisterForm"
                            data-callback="submitRegisterForm"
                            data-cy="register-button"
                        />

                        <p class="signup">Already have an account?<a href="#" onclick="toggleForm();"> Sign in.</a></p>

                    </form>
                </div>

                <div class="imgBx"><img src="https://phopixel.s3.amazonaws.com/assets/pho_camera_500x500.png" alt="Phopixel Camera" /></div>
            </div>

            <div class="container-fluid fixed-bottom bg-light py-2">
                <div class="text-center">
                    <small class="text-muted">
                        This site is protected by reCAPTCHA and the <strong><span style="font-family: 'Product Sans',sans-serif;"><span class="g-blue">G</span><span class="o-red">o</span><span class="o-yellow">o</span><span class="g-blue">g</span><span class="l-green">l</span><span class="o-red e-red">e</span></span></strong>
                        <a href="https://policies.google.com/privacy">Privacy Policy</a> and
                        <a href="https://policies.google.com/terms">Terms of Service</a> apply.
                    </small>
                </div>
            </div>
        </div>
    </section>

    <script>

        function toggleForm() {
            const formContainer = document.querySelector('.formContainer');
            formContainer.classList.toggle('active');
        }

        document.addEventListener('DOMContentLoaded', function () {
            @if($errors->any())
                @foreach($errors->all() as $error)
                    showToast("{{ $error }}")
                @endforeach
            @endif

            @if(session('userTryingToCreateMultipleAccountsError'))
                showToast("{{ session('userTryingToCreateMultipleAccountsError') }}")
            @endif

            @if(session('profanityNameWhenRegistering'))
                showToast("{{ session('profanityNameWhenRegistering') }}")
            @endif
        });

        function showToast(message) {
            Toastify({
                text: message,
                duration: 6000,
                close: true,
                gravity: 'top',
                position: 'right',
                style: {
                    background: 'linear-gradient(to right, #ff5f6d, #ffc371)',
                },
            }).showToast();
        }

        // the ata-callback bypasses the browser's built-in validation for the `required` fields for registering
        // we need to check the form's validity before submitting it
        function submitRegisterForm(token) {
            var form = document.getElementById('registerForm');

            if (form.checkValidity()) {
                form.submit();
            } else {
                form.reportValidity();
            }
        }


    </script>
@endsection

