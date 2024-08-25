@extends('layouts.main')
@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Create User</h3>
                        </div>

                        <div class="card-body">
                            <div>
                                <form action="{{ route('user-management.update') }}" method="POST">
                                    @csrf
                                    <div class="form-row">
                                        <!-- Kolom Kiri -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="name">Name:</label>
                                                <input type="text" value="{{ $user->id }}" name="id" hidden>
                                                <input type="text" name="name"
                                                    class="form-control @error('name') is-invalid @enderror"
                                                    value="{{ $user->name }}">
                                                @error('name')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror

                                            </div>

                                            <div class="form-group">
                                                <label for="phone">Phone:</label>
                                                <input type="text" name="phone"
                                                    class="form-control @error('phone') is-invalid @enderror"
                                                    value="{{ $user->phone }}">
                                                @error('phone')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror

                                            </div>

                                            <div class="form-group">
                                                <label for="username">Username:</label>
                                                <input type="text" name="username"
                                                    class="form-control @error('username') is-invalid @enderror"
                                                    value="{{ $user->username }}">
                                                @error('username')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror

                                            </div>

                                            <div class="form-group">
                                                <label for="email">Email:</label>
                                                <input type="email" name="email"
                                                    class="form-control @error('email') is-invalid @enderror"
                                                    value="{{ $user->email }}">
                                                @error('email')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror

                                            </div>
                                        </div>
                                        <!-- Kolom Kanan -->
                                        <div class="col-md-6">

                                            <div class="form-group">
                                                <label for="password">Password:</label>
                                                <input type="password" name="password"
                                                    class="form-control @error('password') is-invalid @enderror"
                                                    value="{{ old('password') }}">
                                                @error('password')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror

                                            </div>

                                            <div class="form-group">
                                                <label for="password_confirmation">Password:</label>
                                                <input type="password" name="password_confirmation"
                                                    class="form-control @error('password_confirmation') is-invalid @enderror"
                                                    value="{{ old('password_confirmation') }}">
                                                @error('password_confirmation')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror

                                            </div>

                                            <div class="form-group">
                                                <label for="role">Role:</label>
                                                <select name="role"
                                                    class="form-control @error('role') is-invalid @enderror">
                                                    @error('role')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                    <option value="" hidden>-- Select Role --</option>
                                                    @foreach ($roles as $role)
                                                        <option value="{{ $role->id }}" {{ $role->id == $user_role ? 'selected' : '' }}>{{ $role->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label>Address</label>
                                                <textarea name="address" class="form-control @error('address') is-invalid @enderror" rows="3" placeholder="">{{ old('address', $user->address) }}</textarea>
                                                @error('address')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Create User</button>
                                    </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('javascript-bottom')
    @include('java_script.user_management.script')
@endpush
