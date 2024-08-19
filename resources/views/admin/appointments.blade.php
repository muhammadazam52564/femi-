@extends('layouts.admin.app')
@section('content')
<div class="container bg-white cus-shadow">
    <div class="row">
    <div class="col-md-12 py-4 pb-3 d-flex justify-content-between">
        <h5>Appointment Management</h5>
    </div>
        <div class="col-md-12 overflow-auto">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th class="text-center">Name</th>
                        <th class="text-center">Phone</th>
                        <th class="text-center">Email</th>
                        <th class="text-center" style="min-width:300px">Note</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if(count($appointments) == 0)
                        <tr>
                            <th colspan="6" class="text-center"> No data Found </th>
                        </tr>
                    @else
                        @foreach($appointments as $appointment)
                        <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                            <td class="text-center">{{ $appointment->name }}</td>
                            <td class="text-center">{{ $appointment->phone }}</td>
                            <td class="text-center">{{ $appointment->email }}</td>
                            <td class="text-center">{{ $appointment->note }}</td>
                            <td class="text-center">
                                <a href="{{ route('admin.delete-appointment', $appointment->id) }}" class="btn btn-sm btn-danger" title="Remove Appointment">
                                    <i class="fa fa-times"></i>
                                </a>
                                <!-- <a class="btn btn-sm btn-primary" title="Edit Appointment" href="{{ route('admin.edit-scad', $appointment->id) }}">
                                    <i class="fa fa-edit"></i>
                                </a> -->
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