var base = require('settings/teams/send-invitation');

Vue.component('spark-send-invitation', {
    mixins: [base],

    created() {
        Spark.defaultRole = 'substitute'; // fix the role reinitialization after inviting someone
    }
});
