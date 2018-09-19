var base = require('settings/teams/pending-invitations');

Vue.component('spark-pending-invitations', {
    mixins: [base],
    created() {
        // reload the page after a delay, to display the appropriate nav
        Bus.$on('updateTeams', function () {
            setTimeout(function() {
                location.reload();
            }, 500);
        });
    }
});
