var base = require('auth/register-stripe');

Spark.forms.register = {
    'account_type': 'substitute'
};

Vue.component('spark-register-stripe', {
    mixins: [base]
});
