@extends('layouts.admin.app')
@section('title')
    Categories
@endsection
@section('content')
<div class="container bg-white cus-shadow">
    <div class="row">
    <div class="col-md-12 py-4 pb-3 px-5 d-flex justify-content-between">
        <h5> Order Management</h5>
    </div>
        <div class="col-md-12 overflow-auto">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col" class="text-center">#</th>
                        <th scope="col" class="text-center">User Id</th>
                        <th scope="col" class="text-center">User Name</th>
                        <th scope="col" class="text-center">Product</th>
                        <th scope="col" class="text-center">Amount</th>
                        <th scope="col" class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if(count($orders) <= 0)
                        <tr>
                            <th colspan="8" class="text-center"> Data Not Found </th>
                        </tr>
                    @else
                        @foreach($orders as $order)
                        <tr>
                            <td class="text-center">{{ $order->id }}</td>
                            <td class="text-center">{{ $order->user_id }}</td>
                            <td class="text-center">{{ $order->user->name }}</td>
                            <td class="text-center">{{ $order->product->name }}</td>
                            <td class="text-center">{{ $order->amount }}</td>
                            <td class="text-center">
                                <a class="btn btn-sm btn-success" title="show order" href="{{ route('admin.showOrder', ['orderId' => $order->id]) }}">
                                    <i class="fa fa-eye"></i>
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
