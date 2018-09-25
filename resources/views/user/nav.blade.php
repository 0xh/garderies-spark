<nav class="nav-lateral">
    <ul>
        <li>
            <a href="{{route('users.index')}}" data-toggle="tooltip" data-placement="left" title="Tous les employés de vos équipes"><i class="fas fa-users icon"></i> Employés</a>
        </li>
        <li>
            <a href="/settings/equipe/{{auth()->user()->currentTeam()->id}}#/membership" data-toggle="tooltip" data-placement="left" title="Ajouter des utilisateurs à vos équipes"><i class="fas fa-plus icon"></i> Ajouter</a>
        </li>
    </ul>
</nav>
