import {mask} from 'vue-the-mask';
import flatPickr from 'vue-flatpickr-component';
import 'flatPickr/dist/flatpickr.css';
import {French} from 'flatPickr/dist/l10n/fr';

var base = require('auth/register-stripe');

Spark.forms.register = {
    'account_type': 'substitute'
};

let data = {
    flatPickrConfig: {
        wrap: true,
        dateFormat: 'd.m.Y',
        enableTime: false,
        locale: French,
        maxDate: null
    },
};

Vue.component('spark-register-stripe', {
    data() {
        return data;
    },
    mounted: function() {
        data.flatPickrConfig.maxDate = this.maxDate;
    },
    props: ['maxDate'],
    mixins: [base],
    components: {flatPickr}
});
