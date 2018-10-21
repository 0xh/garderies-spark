<?php

namespace App\Providers;

use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Notification;
use Laravel\Cashier\Cashier;
use Laravel\Spark\Exceptions\IneligibleForPlan;
use Laravel\Spark\Spark;
use Laravel\Spark\Providers\AppServiceProvider as ServiceProvider;
use Laravel\Spark\Events\Teams\UserInvitedToTeam;
use App\Notifications\NewUserInvitedToTeam;

class SparkServiceProvider extends ServiceProvider
{
    /**
     * Your application and company details.
     *
     * @var array
     */
    protected $details = [
        'vendor'    => 'DevWeb',
        'product'   => 'Garderies',
        'street'    => 'PO Box 111',
        'location'  => 'Your Town, NY 12345',
        'phone'     => '555-555-5555',
    ];

    /**
     * The address where customer support e-mails should be sent.
     *
     * @var string
     */
    protected $sendSupportEmailsTo = 'support@garderies.ch';

    /**
     * All of the application developer e-mail addresses.
     *
     * @var array
     */
    protected $developers = [
        'simon@devweb.ch',
        'henrique@devweb.ch',
    ];

    /**
     * Indicates if the application will expose an API.
     *
     * @var bool
     */
    protected $usesApi = false;

    /**
     * Finish configuring Spark for the application.
     *
     * @return void
     */
    public function booted()
    {
        // Currency
        Cashier::useCurrency('chf', 'CHF ');

        // Define roles
        Spark::useRoles([
            'director'      => __('Director'),
            'substitute'    => __('Substitute'),
        ]);
        Spark::$defaultRole = 'substitute'; // default role

        // Trial days
        Spark::useStripe()->noCardUpFront()->trialDays(10);

        // TVA Collect
        //Spark::collectEuropeanVat('CH');

        /**
         * Define monthly plans
         */
        // 10 employees - CHF 20 per employee
        Spark::plan("Garderies (10 employés)", 'garderies-10')
            ->price(199)
            ->maxTeams(1)
            ->maxCollaborators(10)
            ->features([
                "<strong>CHF 19.90</strong> / utilisateur / mois",
            ]);

        // 20 employees - CHF 19 per employee
        Spark::plan("Garderies (20 employés)", 'garderies-20')
            ->price(380)
            ->maxTeams(2)
            ->maxCollaborators(20)
            ->features([
                "<strong>CHF 19.-</strong> / utilisateur / mois"
            ]);

        // 40 employees - CHF 18 per employee
        Spark::plan("Garderies (40 employés)", 'garderies-40')
            ->price(699)
            ->maxTeams(4)
            ->maxCollaborators(40)
            ->features([
                "<strong>CHF 17.50</strong> / utilisateur / mois"
            ]);

        // 80 employees - CHF 17 per employee
        Spark::plan("Garderies (80 employés)", 'garderies-80')
            ->price(1360)
            ->maxTeams(8)
            ->maxCollaborators(80)
            ->features([
                "<strong>CHF 17.-</strong> / utilisateur / mois"
            ]);

        // 100 employees - CHF 16 per employee
        Spark::plan("Garderies (100 employés)", 'garderies-100')
            ->price(1599)
            ->maxTeams(10)
            ->maxCollaborators(100)
            ->features([
                "<strong>CHF 16.-</strong> / utilisateur / mois"
            ]);

        // 200 employees - CHF 15 per employee
        Spark::plan("Garderies (200 employés)", 'garderies-200')
            ->price(2999)
            ->maxTeams(20)
            ->maxCollaborators(200)
            ->features([
                "<strong>CHF 15.-</strong> / utilisateur / mois"
            ]);


        /**
         * Define yearly plans
         */
        // 10 employees - CHF 20 per employee
        Spark::plan("Garderies (10 employés)", 'garderies-10-yearly')
            ->yearly()
            ->price(2268)
            ->maxTeams(1)
            ->maxCollaborators(10)
            ->features([
                "<span class='badge badge-success'><strong>5% de rabais</strong></span>",
                "CHF 18.90 / utilisateur / mois"
            ]);

        // 20 employees - CHF 19 per employee
        Spark::plan("Garderies (20 employés)", 'garderies-20-yearly')
            ->yearly()
            ->price(4332)
            ->maxTeams(2)
            ->maxCollaborators(20)
            ->features([
                "<span class='badge badge-success'><strong>5% de rabais</strong></span>",
                "CHF 18.- / utilisateur / mois"
            ]);

        // 40 employees - CHF 18 per employee
        Spark::plan("Garderies (40 employés)", 'garderies-40-yearly')
            ->yearly()
            ->price(7549)
            ->maxTeams(4)
            ->maxCollaborators(40)
            ->features([
                "<span class='badge badge-success'><strong>10% de rabais</strong></span>",
                "CHF 15.70 / utilisateur / mois"
            ]);

        // 80 employees - CHF 17 per employee
        Spark::plan("Garderies (80 employés)", 'garderies-80-yearly')
            ->yearly()
            ->price(14688)
            ->maxTeams(8)
            ->maxCollaborators(80)
            ->features([
                "<span class='badge badge-success'><strong>10% de rabais</strong></span>",
                "CHF 15.30 / utilisateur / mois"
            ]);

        // 100 employees - CHF 16 per employee
        Spark::plan("Garderies (100 employés)", 'garderies-100-yearly')
            ->yearly()
            ->price(17269)
            ->maxTeams(10)
            ->maxCollaborators(100)
            ->features([
                "<span class='badge badge-success'><strong>10% de rabais</strong></span>",
                "CHF 14.40 / utilisateur / mois"
            ]);

        // 200 employees - CHF 15 per employee
        Spark::plan("Garderies (200 employés)", 'garderies-200-yearly')
            ->yearly()
            ->price(32389)
            ->maxTeams(20)
            ->maxCollaborators(200)
            ->features([
                "<span class='badge badge-success'><strong>10% de rabais</strong></span>",
                "CHF 13.50 / utilisateur / mois"
            ]);


        /**
         * Handles plans eligibility, constraints on plans attributes
         */
        Spark::checkPlanEligibilityUsing(function ($user, $plan) {

            $msg_too_much_members = "Vous avez trop de membres d'équipes. Vous devez avoir un maximum de %s membres pour pouvoir utiliser ce plan.";

            if ($plan->id == 'garderies-10' && $user->hasTeams() && $user->currentTeam()->users()->count() > 10) {
                throw IneligibleForPlan::because(sprintf($msg_too_much_members, 10));
            }
            if ($plan->id == 'garderies-20' && $user->hasTeams() && $user->currentTeam()->users()->count() > 20) {
                throw IneligibleForPlan::because(sprintf($msg_too_much_members, 20));
            }
            if ($plan->id == 'garderies-40' && $user->hasTeams() && $user->currentTeam()->users()->count() > 40) {
                throw IneligibleForPlan::because(sprintf($msg_too_much_members, 40));
            }
            if ($plan->id == 'garderies-80' && $user->hasTeams() && $user->currentTeam()->users()->count() > 80) {
                throw IneligibleForPlan::because(sprintf($msg_too_much_members, 80));
            }
            if ($plan->id == 'garderies-100' && $user->hasTeams() && $user->currentTeam()->users()->count() > 100) {
                throw IneligibleForPlan::because(sprintf($msg_too_much_members, 100));
            }
            if ($plan->id == 'garderies-200' && $user->hasTeams() && $user->currentTeam()->users()->count() > 200) {
                throw IneligibleForPlan::because(sprintf($msg_too_much_members, 200));
            }

            return true;
        });

        // override the invite notification process
        Spark::swap('SendInvitation@handle', function ($team, $email, $role) {
            $invitedUser = Spark::user()->where('email', $email)->first();

            $role = array_key_exists($role, Spark::roles()) ? $role : Spark::defaultRole();

            $invitation = $this->createInvitation($team, $email, $invitedUser, $role);

            if ($invitedUser) {
                // dispatch an event when the user exists
                event(new UserInvitedToTeam($team, $invitedUser));
            } else {
                // send manual notification if the user doesn't exists
                Notification::route('mail', $email)
                    ->notify(new NewUserInvitedToTeam($invitation));
            }

            return $invitation;
        });

        /**
         * Override account creation to include the account type
         *
         */
        Spark::validateUsersWith(function () {
            return [
                'account_type'  => 'required',
                'name'          => 'required|max:255',
                'email'         => 'required|email|max:255|unique:users',
                'password'      => 'required|confirmed|min:6',
                'terms'         => 'required|accepted',
            ];
        });

        Spark::createUsersWith(function ($request) {
            $user = Spark::user();

            $data = $request->all();

            // disable the trial period for substitutes
            $trial = ($data['account_type'] == 'network') ? Carbon::now()->addDays(Spark::trialDays()) : null;
            $birthdate = ($data['account_type'] == 'substitute') ? Carbon::parse($data['birthdate']) : null;

            $user->forceFill([
                'name'          => $data['name'],
                'email'         => $data['email'],
                'password'      => bcrypt($data['password']),
                'birthdate'     => $birthdate,
                'trial_ends_at' => $trial,
            ])->save();

            return $user;
        });
    }

    public function register()
    {
        Spark::prefixTeamsAs('liste');
    }
}
