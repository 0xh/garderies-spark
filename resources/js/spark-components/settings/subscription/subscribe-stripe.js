var base = require('settings/subscription/subscribe-stripe');

Vue.component('spark-subscribe-stripe', {
    mixins: [base],
    mounted() {
        this.form.country = 'CH';
    }
});
