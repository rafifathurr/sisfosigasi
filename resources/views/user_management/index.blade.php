@extends('layouts.main')
@section('content')
    <section class="content py-3">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title font-weight-bold">Manajemen User</h3>
                        </div>
                        <div class="card-body">
                            <a href="{{ route('user-management.create') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus mr-1"></i>
                                Tambah User
                            </a>
                            <div class="table-responsive mt-3">
                                <table class="table table-bordered table-hover w-100 datatable">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama</th>
                                            <th>Email</th>
                                            <th>Telepon</th>
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
        </div>
    </section>
    @push('javascript-bottom')
        @include('js.user_management.script')
    @endpush
@endsection
