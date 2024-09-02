@include('layouts.head')
<!-- Font Awesome CSS -->
<style>
    .input-group-text {
        cursor: pointer;
    }
</style>
<div class="container">
    <div class="row justify-content-center"style="height: 100vh;">
        <div class="col-md-6 d-flex align-items-center justify-content-center">
            <div class="shadow card w-100">
                <div class="card-header">
                    <h3 class="card-title font-weight-bold"><i class="fas fa-sign-in-alt mr-2"></i>Login</h3>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('authenticate') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input id="username" type="text"
                                class="form-control @error('username') is-invalid @enderror" name="username"
                                value="{{ old('username') }}" required autofocus>
                            @error('username')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <div class="input-group">
                                <input id="password_hash" type="password"
                                    class="form-control @error('password') is-invalid @enderror" name="password"
                                    required value="{{ old('password') }}">
                                <span class="input-group-text" id="togglePassword" onclick="togglePasswordVisibility()">
                                    <i class="fas fa-eye" id="eye-icon"></i>
                                </span>
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="mt-4 mb-3">
                            <button type="submit" class="btn btn-sm btn-primary w-100 font-weight-bold">
                                Login
                            </button>
                        </div>
                        <div class="text-center">

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@include('layouts.script')
@if (session('success_message'))
    <script>
        Swal.fire({
            icon: 'info',
            title: 'Info',
            text: '{{ session('success_message') }}',
            customClass: {
                confirmButton: 'btn btn-outline-primary',
            },
            buttonsStyling: false,
        });
    </script>
@endif

<script>
    function togglePasswordVisibility() {
        var passwordInput = document.getElementById("password_hash");
        var eyeIcon = document.getElementById("eye-icon");

        if (passwordInput.type === "password") {
            passwordInput.type = "text";
            eyeIcon.classList.remove("fa-eye");
            eyeIcon.classList.add("fa-eye-slash");
        } else {
            passwordInput.type = "password";
            eyeIcon.classList.remove("fa-eye-slash");
            eyeIcon.classList.add("fa-eye");
        }
    }
</script>
