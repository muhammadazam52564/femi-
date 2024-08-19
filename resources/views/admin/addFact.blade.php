@extends('layouts.admin.app')
@section('content')
<form method="POST" action="{{ route('admin.add-fact') }}" enctype="multipart/form-data">
    @csrf
    <div class="container bg-white shadow min-height">
        <div class="row p-4">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <h4>New  Daily Fact </h4>
                    </div>
                </div>
                <div class="row p-0 m-0">
                    <div class="col-md-9">
                        <div class="row border p-4">
                            <div class="col-md-12">
                                <label for="title">Title</label>
                                <input type="text" name="title" id="title" class="form-control" placeholder="daily fami fect" />
                            </div>
                            <!-- <div class="col-md-6 ">
                                <label for="email">Email</label>
                                <input type="email" name="email" id="email" class="form-control" placeholder="admin@muraadmin.com" value="{{ Auth::user()->email }}" />
                            </div> -->
                        </div>
                    </div>
                </div>

                <div class="row p-0 m-0 mt-3">
                    <div class="col-md-9">
                        <div class="row border p-4">
                            <div class="col-md-6 mt-4">
                                <label for="fact" class="btn btn-primary btn-lg">Fact Image </label>
                                <input type="file" name="image" id="fact" class="form-control d-none" onchange="previewImage(event, '#fact-preview')" />
                            </div>
                            <div class="col-md-6 ">
                                <img src="" class="d-none" width="120px" height="120px" id="fact-preview"> 
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row p-4">
            <div class="col-md-9 d-flex flex-row-reverse">
                <button type="submit" class="btn primary-btn px-4 rounded-pill"> Save</button>
            </div>
        </div>
    </div>
</form>
@endsection
