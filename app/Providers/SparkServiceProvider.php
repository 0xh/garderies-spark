<?php

namespace App\Providers;

use App\User;
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

        /**
         * Define plans
         */
        Spark::plan('Petit (10 employés)', 'plan-small')
            ->price(110)
            ->maxTeams(1)
            ->maxCollaborators(10)
            ->features(['CHF 11.- / utilisateur', 'Second', 'Third']);

        Spark::plan('Moyen (20 employés)', 'plan-medium')
            ->price(200)
            ->maxTeams(2)
            ->maxCollaborators(20)
            ->features(['CHF 10.- / utilisateur', 'Second', 'Third']);

        Spark::plan('Large (50 employés)', 'plan-large')
            ->price(450)
            ->maxTeams(5)
            ->maxCollaborators(30)
            ->features(['CHF 9.- / utilisateur', 'Second', 'Third']);

        /**
         * Handles plans eligibility, constraints on plans attributes
         */
        Spark::checkPlanEligibilityUsing(function ($user, $plan) {

            $msg_too_much_members = "Vous avez trop de membres d'équipes. Vous devez avoir un maximum de %s membres pour pouvoir utiliser ce plan.";

            if ($plan->id == 'plan-small' && $user->currentTeam()->users()->count() > 2) {
                throw IneligibleForPlan::because(sprintf($msg_too_much_members, 2));
            }
            if ($plan->id == 'plan-medium' && $user->currentTeam()->users()->count() > 4) {
                throw IneligibleForPlan::because(sprintf($msg_too_much_members, 4));
            }
            if ($plan->id == 'plan-large' && $user->currentTeam()->users()->count() > 30) {
                throw IneligibleForPlan::because(sprintf($msg_too_much_members, 30));
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

    }

    public function register()
    {
        Spark::prefixTeamsAs('equipe');
    }
}
