var base = require('settings/subscription/subscribe-stripe');

Vue.component('spark-subscribe-stripe', {
    mixins: [base, require('./../mixins/vat')],
    mounted() {
        this.form.country = 'CH';
    }
});
