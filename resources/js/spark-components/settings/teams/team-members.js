var base = require('settings/teams/team-members');

Vue.component('spark-team-members', {
    mixins: [base],
    methods: {
        teamMemberRole(member) {
            if (this.roles.length == 0) {
                return '';
            }

            if (member.pivot.role == 'owner') {
                return Spark.translations.Owner;
            }

            const role = _.find(this.roles, role => role.value == member.pivot.role);

            if (typeof role !== 'undefined') {
                return role.text;
            }
        }
    }
});
