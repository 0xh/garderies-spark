<spark-teams :user="user" :teams="teams" inline-template>
    <div>
        <!-- Pending Invitations -->
        @include('spark::settings.teams.pending-invitations')

        <!-- Current Teams -->
        <div v-if="user && teams.length > 0">
            @include('spark::settings.teams.current-teams')
        </div>

        <!-- Create Team -->
        @if (Spark::createsAdditionalTeams())
            @include('spark::settings.teams.create-team')
        @endif
    </div>
</spark-teams>
