import {mask} from 'vue-the-mask';

var base = require('auth/register-stripe');

Spark.forms.register = {
    'account_type': 'substitute',
    'birthdate_day': '',
    'birthdate_month': '',
    'birthdate_year': ''
};

let data = {};

Vue.component('spark-register-stripe', {
    data() {
        return data;
    },
    mounted: function() {},
    props: [],
    mixins: [base],
    components: {}
});
