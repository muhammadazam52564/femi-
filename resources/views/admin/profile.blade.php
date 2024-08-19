@extends('layouts.admin.app')
@section('content')
<form method="POST" action="{{ route('admin.profile') }}" enctype="multipart/form-data">
    @csrf
    <div class="container bg-white shadow min-height">
        <div class="row p-4">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <h4>Profile Info </h4>
                    </div>
                </div>
                <div class="row p-0 m-0">
                    <div class="col-md-9">
                        <div class="row border p-4">
                            <div class="col-md-6 ">
                                <label for="name">Name</label>
                                <input type="text" name="name" id="name" class="form-control" placeholder="Admin" value="{{ Auth::user()->name }}" />
                            </div>
                            <div class="col-md-6 ">
                                <label for="email">Email</label>
                                <input type="email" name="email" id="email" class="form-control" placeholder="admin@muraadmin.com" value="{{ Auth::user()->email }}" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row p-0 m-0 mt-3">
                    <div class="col-md-9">
                        <div class="row border p-4">
                            <div class="col-md-6 mt-4">
                                <label for="profile" class="btn btn-primary btn-lg">Change Image </label>
                                <input type="file" name="image" id="profile" class="form-control d-none" onchange="previewImage(event, '#profile-preview')" />
                            </div>
                            <div class="col-md-6 ">
                                <img src="../{{ Auth::user()->profile_image }}" class="rounded-circle" width="120px" height="120px" id="profile-preview"> 
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row p-4">
            <div class="col-md-9 d-flex flex-row-reverse">
                <button type="submit" class="btn primary-btn px-4 rounded-pill"> Save Changes</button>
            </div>
        </div>
    </div>
</form>
@endsection
