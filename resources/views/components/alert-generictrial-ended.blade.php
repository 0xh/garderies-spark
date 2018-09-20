@if (!auth()->user()->subscribed() && !auth()->user()->onGenericTrial())
    <div class="alert alert-info">
        Votre période d'essai est terminée, consultez nos abonnement pour trouver la solution adaptée à vos besoins.
        <a href="/settings#/subscription" class="btn btn-primary btn-sm float-right">Voir les abonnements</a>
    </div>
@endif
