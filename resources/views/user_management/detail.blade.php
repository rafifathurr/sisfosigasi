@extends('layouts.main')
@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">User Profil</h3>
                        </div>

                        <div class="card-body">
                            <div class="row">
                                <div class="col-6">
                                    <table class="table table-bordered">
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
                                <div class="col-6">
                                    <table class="table table-bordered">
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
    </section>
@endsection
@push('javascript-bottom')
    @include('java_script.user_management.script')
@endpush
