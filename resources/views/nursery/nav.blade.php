<nav class="nav-lateral">
    <ul>
        <li>
            <a href="{{route('nurseries.index')}}"><i class="fas fa-building icon"></i> Garderies</a>
        </li>
        @if (isset($nursery))
            <li>
                <a href="{{route('nurseries.scheduling', $nursery)}}" data-toggle="tooltip" data-placement="left" title="Planning des employés"><i class="fas fa-list-alt icon"></i> Planning employés</a>
            </li>
            @can('planning', $nursery)
                <li>
                    <a href="{{route('nurseries.planning', $nursery)}}" data-toggle="tooltip" data-placement="left" title="Planning à imprimer des remplacements mensuel"><i class="fas fa-list-alt icon"></i> Planning</a>
                </li>
            @endcan
            @can('ads', $nursery)
                <li>
                    <a href="{{route('nurseries.ads', $nursery)}}" data-toggle="tooltip" data-placement="left" title="Annonces pour cette garderie"><i class="fas fa-ad icon"></i> Annonces</a>
                </li>
            @endcan
        @endif
        @can ('create', 'App\Nursery')
        <li>
            <a href="{{route('nurseries.create')}}" data-toggle="tooltip" data-placement="left" title="Ajouter une garderie"><i class="fas fa-plus icon"></i> Ajouter</a>
        </li>
        @endcan
    </ul>
</nav>
