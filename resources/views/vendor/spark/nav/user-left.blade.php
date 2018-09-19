<!-- Left Side Of Navbar -->
@can('index', 'App\Network')
<li class="nav-item link-networks {{\App\Http\Controllers\Controller::activeRouteClass('networks.index')}}">
    <a class="nav-link" href="{{route('networks.index')}}" title="Réseaux"><i class="fas fa-sitemap"></i> Réseaux</a>
</li>
@endcan
<li class="nav-item link-nurseries {{\App\Http\Controllers\Controller::activeRouteClass('nurseries.index')}}">
    <a class="nav-link" href="{{route('nurseries.index')}}" title="Garderies"><i class="fas fa-building"></i> Garderies</a>
</li>
<li class="nav-item link-users {{\App\Http\Controllers\Controller::activeRouteClass('users.index')}}">
    <a class="nav-link" href="{{route('users.index')}}" title="Employés"><i class="fas fa-users"></i> Employés</a>
</li>
<li class="nav-item link-bookings {{\App\Http\Controllers\Controller::activeRouteClass('bookings.index')}}">
    <a class="nav-link" href="{{route('booking-requests.index')}}" title="Remplacements"><i class="fas fa-user-clock"></i> Remplacements</a>
</li>
@can('search', 'App\Availability')
<li class="nav-item link-availabilities {{\App\Http\Controllers\Controller::activeRouteClass('availabilities.search')}}">
    <a class="nav-link" href="{{route('availabilities.search')}}" title="Recherche"><i class="fas fa-search"></i> Recherche</a>
</li>
@endcan
