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
        // 10 employees - CHF 20 per employee
        Spark::plan('Garderies (10 employés)', 'garderies-10')
            ->price(199)
            ->maxTeams(1)
            ->maxCollaborators(10)
            ->features(["CHF 20.- / utilisateur / mois", "1 équipe"])
            ->attributes(['fake_limit' => 40]);

        // 20 employees - CHF 19 per employee
        Spark::plan('Garderies (20 employés)', 'garderies-20')
            ->price(380)
            ->maxTeams(2)
            ->maxCollaborators(20)
            ->features(["CHF 19.- / utilisateur / mois"]);

        // 40 employees - CHF 18 per employee
        Spark::plan('Garderies (40 employés)', 'garderies-40')
            ->price(699)
            ->maxTeams(4)
            ->maxCollaborators(40)
            ->features(["CHF 18.- / utilisateur / mois"]);

        // 80 employees - CHF 17 per employee
        Spark::plan('Garderies (80 employés)', 'garderies-80')
            ->price(1360)
            ->maxTeams(8)
            ->maxCollaborators(80)
            ->features(["CHF 17.- / utilisateur / mois"]);

        // 100 employees - CHF 16 per employee
        Spark::plan('Garderies (100 employés)', 'garderies-100')
            ->price(1599)
            ->maxTeams(10)
            ->maxCollaborators(100)
            ->features(["CHF 16.- / utilisateur / mois"]);

        // 200 employees - CHF 15 per employee
        Spark::plan('Garderies (200 employés)', 'garderies-200')
            ->price(2999)
            ->maxTeams(20)
            ->maxCollaborators(200)
            ->features(["CHF 15.- / utilisateur / mois"]);


        /**
         * Handles plans eligibility, constraints on plans attributes
         */
        Spark::checkPlanEligibilityUsing(function ($user, $plan) {

            $msg_too_much_members = "Vous avez trop de membres d'équipes. Vous devez avoir un maximum de %s membres pour pouvoir utiliser ce plan.";

            if ($plan->id == 'garderies-10' && $user->currentTeam()->users()->count() > 10) {
                throw IneligibleForPlan::because(sprintf($msg_too_much_members, 10));
            }
            if ($plan->id == 'garderies-20' && $user->currentTeam()->users()->count() > 20) {
                throw IneligibleForPlan::because(sprintf($msg_too_much_members, 20));
            }
            if ($plan->id == 'garderies-40' && $user->currentTeam()->users()->count() > 40) {
                throw IneligibleForPlan::because(sprintf($msg_too_much_members, 40));
            }
            if ($plan->id == 'garderies-80' && $user->currentTeam()->users()->count() > 80) {
                throw IneligibleForPlan::because(sprintf($msg_too_much_members, 80));
            }
            if ($plan->id == 'garderies-100' && $user->currentTeam()->users()->count() > 100) {
                throw IneligibleForPlan::because(sprintf($msg_too_much_members, 100));
            }
            if ($plan->id == 'garderies-200' && $user->currentTeam()->users()->count() > 200) {
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

    }

    public function register()
    {
        Spark::prefixTeamsAs('equipe');
    }
}
