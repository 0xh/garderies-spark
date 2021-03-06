<div class="card mb-4" v-if="teams.length == 0">
    <div class="card-body">
        <p>
            Créez une liste d'employés afin de pouvoir ajouter des employés / remplaçants à votre réseau de garderies. Le nombre d'employés que vous pouvez ajouter dépend de <a href="/settings#/subscription">l'abonnement que vous avez choisi.</a>
        </p>
        <p class="mb-0">
            Les listes vous permettent de compartimenter vos employés, vous n'êtes pas obligé de les compartimenter mais au moins une liste est requise. <strong>Le nom de cette liste sera affiché dans les e-mails d'invitations.</strong>
        </p>
    </div>
</div>
@if (!auth()->user()->isSuperAdmin() && !auth()->user()->hasTeams() && !auth()->user()->subscribed() && !auth()->user()->onGenericTrial())
    <div class="card mb-4">
        <div class="card-body">
            <p class="m-0">Vous devez souscrire à un abonnement afin de pouvoir ajouter des équipes.</p>
        </div>
    </div>
@endif
@if (auth()->user()->isSuperAdmin() || auth()->user()->subscribed() || auth()->user()->onGenericTrial())
<spark-create-team inline-template>
    <div class="card card-default mb-4" v-if="canCreateMoreTeams">
        <div class="card-header">{{__('teams.create_team')}}</div>

        <div class="card-body">
            <form role="form" v-if="canCreateMoreTeams">
                <!-- Name -->
                <div class="form-group row">
                    <label class="col-md-4 col-form-label text-md-right">{{__('teams.team_name')}}</label>

                    <div class="col-md-6">
                        <input type="text" id="create-team-name" class="form-control" name="name" v-model="form.name" :class="{'is-invalid': form.errors.has('name')}">

                        <span class="invalid-feedback" v-if="hasTeamLimit">
                            <?php echo __('teams.you_have_x_teams_remaining', ['teamCount' => '{{ remainingTeams }}']); ?>
                        </span>

                        <span class="invalid-feedback" v-show="form.errors.has('name')">
                            @{{ form.errors.get('name') }}
                        </span>
                    </div>
                </div>

                @if (Spark::teamsIdentifiedByPath())
                <!-- Slug (Only Shown When Using Paths For Teams) -->
                <div class="form-group row">
                    <label class="col-md-4 col-form-label text-md-right">{{__('teams.team_slug')}}</label>

                    <div class="col-md-6">
                        <input type="text" id="create-team-slug" class="form-control" name="slug" v-model="form.slug" :class="{'is-invalid': form.errors.has('slug')}">

                        <small class="form-text text-muted" v-show=" ! form.errors.has('slug')">
                            {{__('teams.slug_input_explanation')}}
                        </small>

                        <span class="invalid-feedback" v-show="form.errors.has('slug')">
                            @{{ form.errors.get('slug') }}
                        </span>
                    </div>
                </div>
                @endif

                <!-- Create Button -->
                <div class="form-group row mb-0">
                    <div class="offset-md-4 col-md-6">
                        <button type="submit" class="btn btn-primary"
                                @click.prevent="create"
                                :disabled="form.busy">

                            {{__('Create')}}
                        </button>
                    </div>
                </div>
            </form>

            <div v-else>
                <span class="text-danger">
                    {{__('teams.plan_allows_no_more_teams')}},
                    <a href="{{ url('/settings#/subscription') }}">{{__('please upgrade your subscription')}}</a>.
                </span>
            </div>
        </div>
    </div>
</spark-create-team>
@endif