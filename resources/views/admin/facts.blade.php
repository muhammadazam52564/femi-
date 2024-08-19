@extends('layouts.admin.app')
@section('content')
<div class="container bg-white cus-shadow">
    <div class="row">
    <div class="col-md-12 py-4 pb-3 d-flex justify-content-between">
        <h5>Facts Management</h5>
        <a href="{{ route('admin.add-fact') }}" class="btn btn-success fa fa-plus">  </a>
    </div>
        <div class="col-md-12 overflow-auto">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col" class="text-center">#</th>
                        <th scope="col" class="text-center">title</th>
                        <th scope="col" class="text-center">Image Preview</th>
                        <th scope="col" class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if(count($facts) <= 0)
                        <tr>
                            <th colspan="4" class="text-center"> No data Found </th>
                        </tr>
                    @else
                        @foreach($facts as $fact)
                        <tr>
                            <td class="text-center">{{ $fact->id }}</td>
                            <td class="text-center">{{ $fact->title }}</td>
                            <td class="text-center">
                                <img src="../{{ $fact->image }}" style="max-width:100px; max-height:80px"/>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('admin.delete-fact', $fact->id) }}" class="btn btn-sm btn-danger" title="remove fact">
                                    <i class="fa fa-times"></i>
                                </a>
                                <a class="btn btn-sm btn-primary" title="edit fact" href="{{ route('admin.edit-fact', $fact->id) }}">
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