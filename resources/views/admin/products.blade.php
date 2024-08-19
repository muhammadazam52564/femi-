@extends('layouts.admin.app')
@section('content')
<div class="container bg-white cus-shadow">
    <div class="row">
    <div class="col-md-12 py-4 pb-3 d-flex justify-content-between">
        <h5>Product Management</h5>
        <a href="{{ route('admin.add-product') }}" class="mr-3 fa fa-plus btn btn-success"></a>
    </div>
        <div class="col-md-12 overflow-auto">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col" class="text-center" >#</th>
                        <th scope="col" class="text-center" >name</th>
                        <th scope="col" class="text-center" >Image Preview</th>
                        <th scope="col" class="text-center" >Price</th>
                        <th scope="col" class="text-center" >Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if(count($products) <= 0)
                        <tr>
                            <th colspan="5" class="text-center"> No data Found </th>
                        </tr>
                    @else
                        @foreach($products as $product )
                            <tr>
                                <td class="text-center">{{ $product->id }}</td>
                                <td class="text-center" >{{ $product->name }}</td>
                                <td class="text-center" >
                                    @if(! empty($product->images[0]))
                                        <img src="../../{{ $product->images[0]->image }}" width="90px" height="50px" class="rounded">
                                    @endif
                                </td>
                                <td class="text-center" >{{ $product->price }}</td>
                                <td class="text-center" >
                                <a href="{{ route('admin.delete-prod', $product->id) }}" class="btn btn-sm btn-danger" title="remove">
                                        <i class="fa fa-times"></i>
                                    </a>
                                    <!-- <a class="btn btn-sm btn-primary" title="edit Blog">
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

