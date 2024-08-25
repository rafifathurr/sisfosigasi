@extends('layouts.main')
<style>
    .btn_create {
        float: right;
    }
</style>
@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">User Management</h3>
                            <a href="{{ route('user-management.create') }}" class="btn btn-primary btn-sm btn_create">Create
                                User</a>
                        </div>

                        <div class="card-body">
                            <table class="table table-bordered table-hover datatable">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>Email</th>
                                        <th>Telephone</th>
                                        <th>Role</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
    @push('javascript-bottom')
        @include('java_script.user_management.script')
    @endpush
@endsection
