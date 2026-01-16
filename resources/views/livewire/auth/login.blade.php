<x-layouts.auth>
    <main>
        <div class="container {{ old('form') === 'register' ? 'active' : '' }}">

            {{-- LOGIN --}}
            <div class="form-box login">
                <form method="POST" action="{{ route('login.store') }}">
                    @csrf
                    <input type="hidden" name="form" value="login">

                    <h1>Login</h1>

                    <x-input.auth-input type="email" name="email" placeholder="Email" icon="bxs-user" :value="old('form') === 'login' ? old('email') : ''"
                        required autofocus form="login" />

                    <x-input.auth-input type="password" name="password" placeholder="Password" required toggleable
                        form="login" />

                    @if (Route::has('password.request'))
                        <div class="forgot-link">
                            <a href="{{ route('password.request') }}">Forgot password?</a>
                        </div>
                    @endif

                    <button type="submit" class="btn">Login</button>
                </form>
            </div>

            {{-- REGISTER --}}
            <div class="form-box register">
                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    <input type="hidden" name="form" value="register">

                    <h1>Register</h1>

                    <x-input.auth-input name="name" placeholder="Name" icon="bxs-user" :value="old('name')" required />

                    <x-input.auth-input name="username" placeholder="Username" icon="bxs-user" :value="old('username')"
                        required />

                    <x-input.auth-input type="email" name="email" placeholder="Email" icon="bxs-envelope"
                        :value="old('email')" required />

                    <x-input.auth-input type="password" name="password" placeholder="Password" required toggleable />

                    <x-input.auth-input type="password" name="password_confirmation" placeholder="Confirm Password"
                        required toggleable />

                    <button type="submit" class="btn">Register</button>
                </form>
            </div>

            {{-- TOGGLE --}}
            <div class="toggle-box">
                <div class="toggle-panel toggle-left">
                    <h1>Hello, Welcome!</h1>
                    <p>Don't have an account?</p>
                    <button type="button" class="btn register-btn">Register</button>
                </div>

                <div class="toggle-panel toggle-right">
                    <h1>Welcome Back!</h1>
                    <p>Already have an account?</p>
                    <button type="button" class="btn login-btn">Login</button>
                </div>
            </div>

        </div>
    </main>
</x-layouts.auth>
