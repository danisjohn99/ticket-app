<div class="col-sm-3 sidenav hidden-xs">
       <h3><b>{{ucfirst($authUser->role)}}'s Dashboard</b></h3>
      <ul class="nav nav-pills nav-stacked">
        <li><a href="/home">Home</a></li>
        @can('isAdmin')
        <li><a href="/users-list">Users</a></li>
        @endcan
        <li><a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
        document.getElementById('logout-form').submit();">{{ __('Logout') }}</a>
        </li>
      </ul>
      <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
       @csrf
       </form>
    <br>
  </div>
  <br>