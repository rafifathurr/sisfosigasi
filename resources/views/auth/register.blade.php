@include('layouts.head')
<!-- Font Awesome CSS -->
<style>
    .input-group-text {
        cursor: pointer;
    }
</style>
<div class="container p-3">
    <div class="row justify-content-center" style="height: 100vh;">
        <div class="col-md-6 d-flex align-items-center justify-content-center">
            <div class="shadow card w-100">
                <div class="card-header">
                    <h3 class="card-title font-weight-bold"><i class="fas fa-user-plus mr-2"></i>Registrasi</h3>
                </div>
                <div class="card-body pb-1">
                    <form method="POST" action="{{ route('store-user') }}">
                        @csrf
                        <div class="form-group">
                            <label for="name">Nama Lengkap</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fas fa-user"></i>
                                    </span>
                                </div>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    id="name" name="name" value="{{ old('name') }}" required autofocus>
                            </div>
                            @error('name')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="phone">Telepon</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fas fa-phone-alt"></i>
                                    </span>
                                </div>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                    id="phone" name="phone" value="{{ old('phone') }}" required autofocus>
                            </div>
                            @error('phone')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="email">Email</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fas fa-envelope"></i>
                                    </span>
                                </div>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                    id="email" name="email" value="{{ old('email') }}" required>
                            </div>
                            @error('email')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="username">Username</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fas fa-user"></i>
                                    </span>
                                </div>
                                <input type="text" class="form-control @error('username') is-invalid @enderror"
                                    id="username" name="username" value="{{ old('username') }}" required autofocus>
                            </div>
                            @error('username')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="password">Password</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                </div>
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                    id="password" name="password" required>
                            </div>
                            @error('password')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="password-confirm">Konfirmasi Password</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                </div>
                                <input type="password" class="form-control" id="password-confirm"
                                    name="password_confirmation" required>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary btn-block font-weight-bold">
                                Registasi
                            </button>
                        </div>
                    </form>
                </div>
                <div class="card-footer bg-white border-top text-center">
                    <p class="mb-0">Punya Akun? <a href="{{ route('login') }}">Login Disini</a></p>
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
