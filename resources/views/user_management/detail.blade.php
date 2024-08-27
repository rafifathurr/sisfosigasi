@extends('layouts.main')
@section('content')
    <section class="content py-3">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title font-weight-bold">User Profil</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="table-responsive">
                                        <table class="table table-borderless w-100">
                                            <tr>
                                                <th width="40%" style="text-align: left;">Name</th>
                                                <td width="60%">{{ $user->name }}</td>
                                            </tr>
                                            <tr>
                                                <th width="40%" style="text-align: left;">Phone</th>
                                                <td width="60%">{{ $user->phone }}</td>
                                            </tr>
                                            <tr>
                                                <th width="40%" style="text-align: left;">Address</th>
                                                <td width="60%">{{ $user->address ?? '-' }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="table-responsive">
                                        <table class="table table-borderless w-100">
                                            <tr>
                                                <th width="40%" style="text-align: left;">Username</th>
                                                <td>{{ $user->username }}</td>
                                            </tr>
                                            <tr>
                                                <th width="40%" style="text-align: left;">Email</th>
                                                <td>{{ $user->email }}</td>
                                            </tr>
                                            <tr>
                                                <th width="40%" style="text-align: left;">User Role</th>
                                                <td>{{ $user_role }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('javascript-bottom')
    @include('js.user_management.script')
@endpush
