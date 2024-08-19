<nav id="sidebar" class="sticky-top">
    <div class="sidebar-header d-flex">
        <div  class="d-flex justify-content-center align-items-center">
            <img src="{{ asset('images/logo.png') }}" width="215px" height="67px">
        </div>
        <!-- <div class="pt-2 d-flex align-items-center">
            <h3 class="ml-3">FEMI APP</h3>
        </div> -->
    </div>
    <ul class="list-unstyled components">
        <li class="{{ Request::is('admin/dashboard') ? 'li-active':'' }}">
            <a href="{{ route('admin.dashboard') }}">
                <i class="fas fa-home-lg-alt mr-2"></i>
                Dashboard
            </a>
        </li>

        <li class="{{ Request::is('admin/users') ? 'li-active':'' }}">
            <a href="{{ route('admin.users') }}">
            <i class="fas fa-users mr-2"></i>
                User Management
            </a>
        </li>

        <li class="{{ Request::is('admin/facts') ? 'li-active':'' }}">
            <a href="{{ route('admin.facts') }}">
            <i class="fas fa-book mr-2"></i>
                Fact Management 
            </a>
        </li>

        <li class="{{ Request::is('admin/blogs') ? 'li-active':'' }}">
            <a href="{{ route('admin.blogs') }}">
            <i class="fas fa-book mr-2"></i>
                Blog Management 
            </a>
        </li>

        <li class="{{ Request::is('admin/products') ? 'li-active':'' }}">
            <a href="{{ route('admin.products') }}">
            <i class="fas fa-book mr-2"></i>
                Product Management 
            </a>
        </li>

        <li class="{{ Request::is('admin/orders') ? 'li-active':'' }}">
            <a href="{{ route('admin.orders') }}">
            <i class="fas fa-book mr-2"></i>
                Order Management 
            </a>
        </li>
        <li class="{{ Request::is('admin/appointments') ? 'li-active':'' }}">
        <a href="{{ route('admin.appointments') }}">
            <i class="fas fa-book mr-2"></i>
                Appointments
            </a>
        </li>

        <li class="{{ Request::is('admin/text-days') ? 'li-active':'' }}">
            <a href="{{ route('admin.text-day') }}">
            <i class="fas fa-book mr-2"></i>
                Message Management 
            </a>
        </li>

        <li class="{{ Request::is('admin/scads') ? 'li-active':'' }}">
            <a href="{{ route('admin.scads') }}">
            <i class="fas fa-book mr-2"></i>
                Health Schedule 
            </a>
        </li>

    </ul>

</nav>
<div class="py-5 my-5"></div>

<form method="post" action="{{ route('logout')}}" class="d-none">
    @csrf
    <input  type="submit" id="logout">
</form>


