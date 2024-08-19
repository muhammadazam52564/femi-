<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="_token" content="{{ csrf_token() }}" />
        <title>{{ config('app.name') }}</title>
        <link rel="dns-prefetch" href="//fonts.gstatic.com">
        <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css"/>
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        <link href="{{ asset('css/style.css') }}" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" />
        <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css" />
        <style>
            .cus-shadow{
                box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
            }
        </style>
    </head>
    <body>
        <div class="wrapper" >
            <!-- Sidebar  -->
            @include('layouts.admin.nav')
            <!-- Page Content  -->
            <div id="content" class="container-fluid">
                <div class="row px-0">
                    <div class="col-12 p-0">
                        <nav class="navbar navbar-expand-lg navbar-light">
                            <div class="container-fluid">
                                <div class="row w-100">
                                    <div class="col-md-12 d-flex justify-content-between">
                                        <button type="button" id="sidebarCollapse"
                                            class="align-self-center mr-2 p-0"
                                            style="font-size: 28px; background-color: transparent; border:0; color:#c97a67;"
                                        >
                                            <!-- <i class="fas fa-caret-left" style="color: #8C5FD7; font-size: 22px;"></i> -->
                                            <i class="fa fa-bars"></i>
                                        </button>
                                        <div class="d-flex justify-content-between">
                                            <div class="dropdown show">
                                                <b class=" dropdown-toggle" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <img src="../{{ Auth::user()->profile_image }}" class="rounded-circle" alt="Profile Settings" width="40" height="40">
                                                    <img src="{{ asset('images/down.png') }}"  width="10px">
                                                </b>
                                                <div class="drop_down dropdown-menu shadow p-3 mb-5 bg-white rounded" aria-labelledby="dropdownMenuLink">
                                                    <a class="dropdown-item" href="{{ route('admin.profile') }}">Profile</a>
                                                    <a class="dropdown-item" href="#">App Settings</a>
                                                    <a class="dropdown-item" href="#">
                                                        <label id="label" for="logout"  style="cursor: pointer">
                                                            Logout
                                                        </label>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </nav>
                    </div>
                    <div class="col-12 p-3 px-md-4">
                        @yield('content')
                    </div>
                </div>
            </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js" type="text/javascript"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
        <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
        <script src="{{ asset('js/app.js') }}"></script>

        @if(session()->has('msg'))
            <script>
                toastr.success("{{ Session::get('msg') }}")
            </script>
        @endif
        <script>
            $(document).ready(function() {
                jQuery.noConflict();
                $('#revenuePrepperTable').DataTable();
                $('#ordersTable').DataTable();
                $('#customersTable').DataTable();
                $('#preppersTable').DataTable();
                $('#driversTable').DataTable();
                $('#paymentsPreppersTable').DataTable();
                $('#paymentsDriversTable').DataTable();
                $('#revenuePreppersTable').DataTable();
                $('#revenueDriversTable').DataTable();
                $('#revenueOrdersTable').DataTable();
            });

            $(document).ready(function () {
                $('#sidebarCollapse').on('click', function () {
                    $('#sidebar').toggleClass('active');
                });
            });

            $( document ).ready(function(){
                $("#btn-open").click(()=>{
                    $('#sidebar').animate({marginLeft: "0px"});
                })
                $("#btn-close").click(()=>{
                    $('#sidebar').animate({marginLeft: "-300px"});
                })
            });

            $( document ).ready(function(){
                var parts = window.location.pathname.split('/');
                var lastSegment = parts.pop() || parts.pop();  // handle potential trailing slash
                $('#menu_area li').removeClass('active')
                $(`#${lastSegment}`).addClass('active')
            });

            // Image preview funtion
            function previewImage(event, id) {
                imgInp = event.target;
                const [file] = imgInp.files
                $(id).removeClass('d-none')
                $(id).attr("src", URL.createObjectURL(file));
            }
            
        </script>
        @stack('scripts')
    </body>
</html>
