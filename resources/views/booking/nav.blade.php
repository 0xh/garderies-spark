<nav class="nav-lateral">
    <ul>
        <li>
            <a href="{{route('booking-requests.index')}}" data-toggle="tooltip" data-placement="left" title="Demandes de remplacement en attente"><i class="fas fa-user-check icon"></i> Demandes</a>
        </li>
        <li>
            <a href="{{route('bookings.index')}}" data-toggle="tooltip" data-placement="left" title="Remplacement planifiés"><i class="fas fa-user-clock icon"></i> Remplacements</a>
        </li>
        <li>
            <a href="{{route('bookings.create')}}" data-toggle="tooltip" data-placement="left" title="Ajouter manuellement un remplacement"><i class="fas fa-plus icon"></i> Ajouter</a>
        </li>
    </ul>
</nav>
