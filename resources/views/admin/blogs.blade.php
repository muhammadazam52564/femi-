@extends('layouts.admin.app')
@section('content')
<div class="container bg-white cus-shadow">
    <div class="row">
    <div class="col-md-12 py-4 pb-3 d-flex justify-content-between">
        <h5>Blog Management</h5>
        <a href="{{ route('admin.add-blog') }}" class="btn btn-success fa fa-plus"></a>
    </div>
        <div class="col-md-12 overflow-auto">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th class="text-center">title</th>
                        <th class="text-center">Image Preview</th>
                        <th class="text-center">Content</th>
                        <th class="text-center" style="width: 100px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if(count($blogs) <= 0)
                        <tr>
                            <th colspan="5" class="text-center"> No data Found </th>
                        </tr>
                    @else
                        @foreach($blogs as $blog)
                        <tr>
                            <td class="text-center">{{ $blog->id }}</td>
                            <td class="text-center">{{ $blog->title }}</td>
                            <td class="text-center">
                                <img src="/{{ $blog->image }}" style="max-width:100px; max-height:80px"/>
                            </td>
                            <td class="text-center">{{ substr($blog->content, 0, 190) }}...</td>
                            <td class="text-center">
                                <a href="{{ route('admin.delete-blog', $blog->id) }}" class="btn btn-sm btn-danger" title="remove">
                                    <i class="fa fa-times"></i>
                                </a>
                                <a href="{{ route('admin.edit-blog', $blog->id) }}" class="btn btn-sm btn-primary" title="edit Blog">
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