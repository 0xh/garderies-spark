<nav class="nav-lateral">
    <ul>
        <li>
            <a href="{{route('networks.index')}}"><i class="fas fa-sitemap icon"></i> Réseaux</a>
        </li>
        @if( isset($network) )
            <li>
                <a href="{{route('networks.ads', $network)}}"><i class="fas fa-ad icon"></i> Annonces</a>
            </li>
        @endif
        <li>
            <a href="{{route('networks.create')}}"><i class="fas fa-plus icon"></i> Ajouter</a>
        </li>
    </ul>
</nav>
