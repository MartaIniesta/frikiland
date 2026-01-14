<x-layouts.auth>
    <main>
        <div class="container {{ old('form') === 'register' ? 'active' : '' }}">

            {{-- LOGIN --}}
            <div class="form-box login">
                <form method="POST" action="{{ route('login.store') }}">
                    @csrf
                    <input type="hidden" name="form" value="login">

                    <h1>Login</h1>

                    {{-- Email --}}
                    <div class="input-box">
                        <input
                            type="email"
                            name="email"
                            value="{{ old('form') === 'login' ? old('email') : '' }}"
                            placeholder="Email"
                            required
                            autofocus
                        >
                        <i class="bx bxs-user"></i>

                        @if (old('form') === 'login')
                            @error('email')
                                <small class="error-text">{{ $message }}</small>
                            @enderror
                        @endif
                    </div>

                    {{-- Password --}}
                    <div class="input-box">
                        <input
                            type="password"
                            name="password"
                            id="password"
                            placeholder="Password"
                            required
                        >
                        <i class="bx bxs-lock-alt"></i>

                        <label class="show-password">
                            <input type="checkbox" onclick="togglePassword()" style="width:12px">
                            Show Password
                        </label>

                        @if (old('form') === 'login')
                            @error('password')
                                <small class="error-text">{{ $message }}</small>
                            @enderror
                        @endif
                    </div>

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

                    {{-- Name --}}
                    <div class="input-box">
                        <input
                            type="text"
                            name="name"
                            value="{{ old('name') }}"
                            placeholder="Name"
                            required
                        >
                        <i class="bx bxs-user"></i>

                        @error('name')
                            <small class="error-text">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- Username --}}
                    <div class="input-box">
                        <input
                            type="text"
                            name="username"
                            value="{{ old('username') }}"
                            placeholder="Username"
                            required
                        >
                        <i class="bx bxs-user"></i>

                        @error('username')
                            <small class="error-text">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div class="input-box">
                        <input
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            placeholder="Email"
                            required
                        >
                        <i class="bx bxs-envelope"></i>

                        @error('email')
                            <small class="error-text">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div class="input-box">
                        <input
                            type="password"
                            name="password"
                            placeholder="Password"
                            required
                        >
                        <i class="bx bxs-lock-alt"></i>

                        @error('password')
                            <small class="error-text">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- Confirm Password --}}
                    <div class="input-box">
                        <input
                            type="password"
                            name="password_confirmation"
                            placeholder="Confirm Password"
                            required
                        >
                        <i class="bx bxs-lock-alt"></i>

                        @error('password_confirmation')
                            <small class="error-text">{{ $message }}</small>
                        @enderror
                    </div>

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
