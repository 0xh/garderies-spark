<form role="form">
    @if (Spark::usesTeams() && Spark::onlyTeamPlans())
        <!-- Team Name -->
        <div class="form-group row" v-if=" ! invitation">
            <label class="col-md-4 col-form-label text-md-right">{{ __('teams.team_name') }}</label>

            <div class="col-md-6">
                <input type="text" class="form-control" name="team" v-model="registerForm.team" :class="{'is-invalid': registerForm.errors.has('team')}" autofocus>

                <span class="invalid-feedback" v-show="registerForm.errors.has('team')">
                    @{{ registerForm.errors.get('team') }}
                </span>
            </div>
        </div>

        @if (Spark::teamsIdentifiedByPath())
            <!-- Team Slug (Only Shown When Using Paths For Teams) -->
            <div class="form-group row" v-if=" ! invitation">
                <label class="col-md-4 col-form-label text-md-right">{{ __('teams.team_slug') }}</label>

                <div class="col-md-6">
                    <input type="text" class="form-control" name="team_slug" v-model="registerForm.team_slug" :class="{'is-invalid': registerForm.errors.has('team_slug')}" autofocus>

                    <small class="form-text text-muted" v-show="! registerForm.errors.has('team_slug')">
                        {{__('teams.slug_input_explanation')}}
                    </small>

                    <span class="invalid-feedback" v-show="registerForm.errors.has('team_slug')">
                        @{{ registerForm.errors.get('team_slug') }}
                    </span>
                </div>
            </div>
        @endif
    @endif

    <!-- Account type -->
    <div class="form-group row">
        <label class="col-md-4 col-form-label text-md-right">Créer un compte</label>
        <div class="col-md-6 pt-1">
            <div class="form-check form-check-inline">
                <input type="radio" class="form-check-input" name="account_type" v-model="registerForm.account_type" id="account_type_substitute" value="substitute" checked>
                <label for="account_type_substitute" class="form-check-label">Remplaçant</label>
            </div>
            <div class="form-check form-check-inline">
                <input type="radio" class="form-check-input" name="account_type" v-model="registerForm.account_type" id="account_type_network" value="network">
                <label for="account_type_network" class="form-check-label">Réseau / Garderie</label>
            </div>
            <p class="text-muted">Sélectionnez quel type de compte vous avez besoin, selon votre rôle.</p>
        </div>
    </div>

    <div class="row" v-if="registerForm.account_type == 'network'">
        <div class="col-md-4"></div>
        <div class="col-md-6">
            <p class="text-muted">Veuillez renseigner les informations de la personne responsable de la gestion de la facturation.</p>
        </div>
    </div>


    <!-- Name -->
    <div class="form-group row">
        <label class="col-md-4 col-form-label text-md-right">Nom et prénom</label>

        <div class="col-md-6">
            <input type="text" class="form-control" name="name" v-model="registerForm.name" :class="{'is-invalid': registerForm.errors.has('name')}" autofocus>

            <span class="invalid-feedback" v-show="registerForm.errors.has('name')">
                @{{ registerForm.errors.get('name') }}
            </span>
        </div>
    </div>

    <!-- Birthdate -->
    <div class="form-group row" v-if="registerForm.account_type == 'substitute'">
        <label class="col-md-4 col-form-label text-md-right">{{__('Birthdate')}}</label>

        <div class="col-md-6">
            <input type="date" max="{{now()->subYears(16)->format('Y-m-d')}}" class="form-control" name="birthdate" v-model="registerForm.birthdate" :class="{'is-invalid': registerForm.errors.has('birthdate')}" autofocus>

            <span class="invalid-feedback" v-show="registerForm.errors.has('birthdate')">
                @{{ registerForm.errors.get('birthdate') }}
            </span>
        </div>
    </div>

    <!-- E-Mail Address -->
    <div class="form-group row">
        <label class="col-md-4 col-form-label text-md-right">{{__('E-Mail Address')}}</label>

        <div class="col-md-6">
            <input type="email" class="form-control" name="email" v-model="registerForm.email" :class="{'is-invalid': registerForm.errors.has('email')}">

            <span class="invalid-feedback" v-show="registerForm.errors.has('email')">
                @{{ registerForm.errors.get('email') }}
            </span>
        </div>
    </div>

    <!-- Password -->
    <div class="form-group row">
        <label class="col-md-4 col-form-label text-md-right">{{__('Password')}}</label>

        <div class="col-md-6">
            <input type="password" class="form-control" name="password" v-model="registerForm.password" :class="{'is-invalid': registerForm.errors.has('password')}">

            <span class="invalid-feedback" v-show="registerForm.errors.has('password')">
                @{{ registerForm.errors.get('password') }}
            </span>
        </div>
    </div>

    <!-- Password Confirmation -->
    <div class="form-group row">
        <label class="col-md-4 col-form-label text-md-right">{{__('Confirm Password')}}</label>

        <div class="col-md-6">
            <input type="password" class="form-control" name="password_confirmation" v-model="registerForm.password_confirmation" :class="{'is-invalid': registerForm.errors.has('password_confirmation')}">

            <span class="invalid-feedback" v-show="registerForm.errors.has('password_confirmation')">
                @{{ registerForm.errors.get('password_confirmation') }}
            </span>
        </div>
    </div>

    <!-- Terms And Conditions -->
    <div v-if=" ! selectedPlan || selectedPlan.price == 0">
        <div class="form-group row">
            <div class="col-md-6 offset-md-4">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="terms" :class="{'is-invalid': registerForm.errors.has('terms')}" v-model="registerForm.terms">
                    <label class="form-check-label" for="terms">
                        {!! __('I Accept :linkOpen The Terms Of Service :linkClose', ['linkOpen' => '<a href="/terms" target="_blank">', 'linkClose' => '</a>']) !!}
                    </label>
                    <div class="invalid-feedback" v-show="registerForm.errors.has('terms')">
                        <strong>@{{ registerForm.errors.get('terms') }}</strong>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="form-group row mb-0">
            <div class="col-md-6 offset-md-4">
                <button class="btn btn-primary" @click.prevent="register" :disabled="registerForm.busy">
                    <span v-if="registerForm.busy">
                        <i class="fa fa-btn fa-spinner fa-spin"></i> {{__('Registering')}}
                    </span>

                    <span v-else>
                        <i class="fa fa-btn fa-check-circle"></i> {{__('Register')}}
                    </span>
                </button>
            </div>
        </div>
    </div>
</form>
