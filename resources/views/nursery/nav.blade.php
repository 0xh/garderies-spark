<nav class="nav-lateral">
    <ul>
        <li>
            <a href="{{route('nurseries.index')}}"><i class="fas fa-building icon"></i> Garderies</a>
        </li>
        @if (isset($nursery))
        <li>
            <a href="{{route('nurseries.planning', $nursery)}}"><i class="fas fa-list-alt icon"></i> Planning</a>
        </li>
        <li>
            <a href="{{route('nurseries.ads', $nursery)}}"><i class="fas fa-ad icon"></i> Annonces</a>
        </li>
        @endif
        <li>
            <a href="{{route('nurseries.create')}}"><i class="fas fa-plus icon"></i> Ajouter</a>
        </li>
    </ul>
</nav>
