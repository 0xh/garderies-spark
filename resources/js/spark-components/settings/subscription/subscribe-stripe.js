var base = require('settings/subscription/subscribe-stripe');

import swal from 'sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';

Vue.component('spark-subscribe-stripe', {
    mixins: [base, require('./../mixins/vat')],
    mounted() {
        this.form.country = 'CH';

        Bus.$on('updateUser', function () {

            swal({
                type: "success",
                text: "Nous vous remercions pour votre souscription, veuillez patienter quelques secondes le temps que nous préparions votre compte.",
                timer: 5000,
                onOpen: () => {
                    swal.showLoading();
                },
                onAfterClose: () => {
                    window.location.reload();
                }
            });
        });
    }
});
