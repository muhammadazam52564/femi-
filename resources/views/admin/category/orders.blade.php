@extends('layouts.admin.app')
@section('title')
    Categories
@endsection
@section('content')
<div class="container bg-white cus-shadow">
    <div class="row">
    <div class="col-md-12 py-4 pb-3 px-5 d-flex justify-content-between">
        <h5> Terms & Conditions Management</h5>
        <a href="{{ route('admin.add-tc') }}" class="btn btn-success fa fa-plus"></a>
    </div>
        <div class="col-md-12 overflow-auto">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col" class="text-center">#</th>
                        <th scope="col" class="text-center">Title</th>
                        <th scope="col" class="text-center">Content</th>
                        <th scope="col" class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if(count($tcs) <= 0)
                        <tr>
                            <th colspan="8" class="text-center"> Data Not Found </th>
                        </tr>
                    @else
                        @foreach($tcs as $tc)
                        <tr>
                            <td class="text-center">{{ $tc->id }}</td>
                            <td class="text-center">{{ $tc->title }}</td>
                            <td class="text-center">{{ $tc->content }}</td>
                            <td class="text-center">
                                <a href="{{ route('admin.delete-tc', $tc->id) }}" class="btn btn-sm btn-danger" title="remove">
                                    <i class="fa fa-times"></i>
                                </a>
                                <a href="{{ route('admin.edit-tc', $tc->id) }}" class="btn btn-sm btn-primary" title="edit event">
                                    <i class="fa fa-edit"></i>
                                </a>
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
