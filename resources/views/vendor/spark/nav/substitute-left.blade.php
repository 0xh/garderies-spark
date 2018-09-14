<!-- Left Side Of Navbar -->
<li class="nav-item link-user {{\App\Http\Controllers\Controller::activeRouteClass('users.show')}}">
    <a class="nav-link" href="{{route('users.show', auth()->user())}}" title="Recherche"><i class="fas fa-user"></i> Mon profil</a>
</li>
<li class="nav-item link-availabilities {{\App\Http\Controllers\Controller::activeRouteClass('availabilities.search')}}">
    <a class="nav-link" href="{{route('availabilities.search')}}" title="Recherche"><i class="fas fa-search"></i> Recherche de remplacant</a>
</li>

<!--
<li class="nav-item">
    <a class="nav-link" href="#">Your Link</a>
</li>
-->