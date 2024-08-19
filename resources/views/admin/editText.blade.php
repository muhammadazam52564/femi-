@extends('layouts.admin.app')
@section('title')
    Add Category
@endsection
@section('content')
<form method="POST" action="{{ route('admin.update-text-day', $text->id) }}" enctype="multipart/form-data">
    @csrf
    <div class="container bg-white shadow">
        <div class="row p-4">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <h4> Edit Day Text  </h4>
                    </div>
                </div>
                <div class="row p-0 m-0">
                    <div class="col-md-12">
                        <div class="row  p-4">
                            <div class="col-md-12">
                                <label for="day">Day</label>
                                <input type="number" name="day" id="day" class="form-control" placeholder="1"  value="{{ $text->day}}"/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row p-0 m-0">
                    <div class="col-md-12">
                        <div class="row  p-4">
                            <div class="col-md-12">
                                <label for="content">Message Content</label>
                                <textarea class="form-control" name="content" placeholder="type users terms...">{{ $text->text }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row p-4">
            <div class="col-md-12 d-flex flex-row-reverse">
                <button type="submit" class="btn primary-btn px-4 rounded-pill">Update Text</button>
            </div>
        </div>
    </div>
</form>
@endsection

