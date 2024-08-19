@extends('layouts.admin.app')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 pt-3 px-md-3">
            <div class="row justify-content-center py-2">
                <div class="col-10">
                    <h4>Order stats</h4>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-md-2 p-2">
                    <div class="w-100 py-3 bg-white shadow rounded">
                        <div class="d-flex justify-content-center">
                            <!-- <i class="fa fa-eidt">123</i> -->
                            <div class="rounded-circle bg-light p-3 d-flex justify-content-center">
                                <img src="{{ asset('images/order.png') }}" width="20px" height="20px" />
                            </div>
                        </div>
                        <div class="d-flex justify-content-center">
                            <b> Total  </b>
                        </div>
                        <div class="d-flex justify-content-center" >
                            <h3 class="mb-0">{{ $all }}</h3>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-2 p-2">
                    <div class="w-100 py-3 bg-white shadow rounded">
                        <div class="d-flex justify-content-center">
                            <!-- <i class="fa fa-eidt">123</i> -->
                            <div class="rounded-circle bg-light p-3 d-flex justify-content-center">
                                <img src="{{ asset('images/completed.png') }}" width="20px" height="20px" />
                            </div>
                        </div>
                        <div class="d-flex justify-content-center">
                            <b> Completed  </b>
                        </div>
                        <div class="d-flex justify-content-center" >
                            <h3 class="mb-0">{{ $completed }}</h3>
                        </div>
                    </div>
                </div>

                <div class="col-md-2 p-2">
                    <div class="w-100 py-3 bg-white shadow rounded">
                        <div class="d-flex justify-content-center">
                            <!-- <i class="fa fa-eidt">123</i> -->
                            <div class="rounded-circle bg-light p-3 d-flex justify-content-center">
                                <img src="{{ asset('images/pending.png') }}" width="20px" height="20px" />
                            </div>
                        </div>
                        <div class="d-flex justify-content-center">
                            <b> Pending </b>
                        </div>
                        <div class="d-flex justify-content-center" >
                            <h3 class="mb-0">{{ $pending }}</h3>
                        </div>
                    </div>
                </div>

                <div class="col-md-2 p-2">
                    <div class="w-100 py-3 bg-white shadow rounded">
                        <div class="d-flex justify-content-center">
                            <!-- <i class="fa fa-eidt">123</i> -->
                            <div class="rounded-circle bg-light p-3 d-flex justify-content-center">
                                <img src="{{ asset('images/ongoing.png') }}" width="20px" height="20px" />
                            </div>
                        </div>
                        <div class="d-flex justify-content-center">
                            <b> Ongoing </b>
                        </div>
                        <div class="d-flex justify-content-center" >
                            <h3 class="mb-0">{{ $ongoing }}</h3>
                        </div>
                    </div>
                </div>

                <div class="col-md-2 p-2">
                    <div class="w-100 px-5 py-3 bg-white shadow rounded">
                        <div class="d-flex justify-content-center">
                            <!-- <i class="fa fa-eidt">123</i> -->
                            <div class="rounded-circle bg-light p-3 d-flex justify-content-center">
                                <img src="{{ asset('images/canceled.png') }}" width="20px" height="20px" />
                            </div>
                        </div>
                        <div class="d-flex justify-content-center">
                            <b> Cancelled </b>
                        </div>
                        <div class="d-flex justify-content-center" >
                            <h3 class="mb-0">{{ $canceled }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>

        <div class="col-md-12 pt-3 px-md-3">
            <div class="row justify-content-center py-2">
                <div class="col-10">
                    <h4>Overall stats</h4>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-md-2 p-2">
                    <div class="w-100 py-3 bg-white shadow rounded">
                        <div class="d-flex justify-content-center">
                            <b> Revenue </b>
                        </div>
                        <div class="d-flex justify-content-center" >
                            <h4 class="mb-0">£ 5.12 K</h4>
                        </div>
                    </div>
                </div>

                <div class="col-md-2 p-2">
                    <div class="w-100 py-3 bg-white shadow rounded">
                        <div class="d-flex justify-content-center">
                            <b> Earnings </b>
                        </div>
                        <div class="d-flex justify-content-center" >
                            <h4 class="mb-0">£ 5.12 K</h4>
                        </div>
                    </div>
                </div>

                <div class="col-md-2 p-2">
                    <div class="w-100 px-5 py-3 bg-white shadow rounded">
                        <div class="d-flex justify-content-center">
                            <b> Preppers </b>
                        </div>
                        <div class="d-flex justify-content-center" >
                            <h4 class="mb-0">77</h4>
                        </div>
                    </div>
                </div>

                <div class="col-md-2 p-2">
                    <div class="w-100  py-3 bg-white shadow rounded">
                        <div class="d-flex justify-content-center">
                            <b> Users </b>
                        </div>
                        <div class="d-flex justify-content-center" >
                            <h4 class="mb-0">77</h4>
                        </div>
                    </div>
                </div>

                <div class="col-md-2 p-2">
                    <div class="w-100 py-3 bg-white shadow rounded">
                        <div class="d-flex justify-content-center">
                            <b> Drivers </b>
                        </div>
                        <div class="d-flex justify-content-center" >
                            <h4 class="mb-0">77</h4>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="col-md-12 pt-3 px-md-3">
            <div class="row justify-content-center py-2">
                <div class="col-10">
                    <h4>Overall stats</h4>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-md-10 px-2">
                    <div class="w-100  py-5 bg-white shadow rounded">

                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

