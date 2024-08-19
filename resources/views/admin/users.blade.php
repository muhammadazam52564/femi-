@extends('layouts.admin.app')
@section('content')
<div class="container bg-white cus-shadow">
    <div class="row">
    <div class="col-md-12 py-4 pb-3 d-flex justify-content-between">
        <h5>Users Management</h5>
    </div>
        <div class="col-md-12 overflow-auto">
            <table class="table table-striped">
                <thead>
                    <tr>
                    <th scope="col" class="text-center" >#</th>
                    <th scope="col" class="text-center" >Name</th>
                    <th scope="col" class="text-center" >Email</th>
                    <th scope="col" class="text-center" >Status</th>
                    <th scope="col" class="text-center" >Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if(count($users) <= 0)
                        <tr>
                            <th colspan="5" class="text-center"> No data Found </th>
                        </tr>
                    @else
                        @foreach($users as $user)
                        <tr>
                            <td class="text-center">{{ $user->id }}</td>
                            <td class="text-center">{{ $user->name }}</td>
                            <td class="text-center">{{ $user->email }}</td>
                            <td class="text-center">
                                @if($user->status == 1)
                                    <b class="btn btn-sm btn-success" title="user email verified" disabled="true">
                                        verified
                                    </b>
                                @elseif($user->status == 2)
                                    <b class="btn btn-sm btn-danger" title="user is not blocked" disabled="true">
                                        Blocked
                                    </b>
                                @elseif($user->status == 0)
                                    <b class="btn btn-sm btn-danger" title="user is not verified" disabled="true">
                                        unverified
                                    </b>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('admin.delete-user', $user->id) }}" class="btn btn-sm btn-danger" title="remove user">
                                    <i class="fa fa-times"></i>
                                </a>
                                @if($user->status == 1)
                                    <a href="{{ route('admin.u-st', [$user->id, 2]) }}" class="btn btn-sm btn-danger" title="block user">
                                        <i class="fa fa-lock"></i>
                                    </a>
                                @elseif($user->status == 2)
                                    <a href="{{ route('admin.u-st', [$user->id, 1]) }}" class="btn btn-sm btn-success" title="unblock user">
                                        <i class="fa fa-lock-open"></i>
                                    </a>
                                @elseif($user->status == 0)
                                    <a href="{{ route('admin.u-st', [$user->id, 1]) }}" class="btn btn-sm btn-success" title="verify user">
                                        <i class="fa fa-check-circle"></i>
                                    </a>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
