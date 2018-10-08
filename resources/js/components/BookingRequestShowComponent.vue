<script>
    import swal from 'sweetalert2';
    import 'sweetalert2/dist/sweetalert2.min.css';

    let data = {};

    export default {
        data() {
            return data;
        },
        mounted() {},
        methods: {
            validateBookingRequest: function (request) {
                axios.post('/api/booking-requests/approve/' + request)
                    .then(function (response) {
                        swal({
                            title: 'Confirmé',
                            text: "La demande a bien été validée.",
                            type: 'success',
                        }).then((result) => {
                            location.reload();
                        });

                        //window.location.replace(response.data.redirect);
                    });
            },
            deleteBookingRequest: function (request) {
                if (!request) {
                    return;
                }
                swal({
                    title: 'Attention !',
                    text: "Vous êtes sur le point de supprimer définitivement cette demande.",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Supprimer',
                    cancelButtonText: 'Annuler'
                }).then((result) => {
                    if (result.value) {
                        axios.delete('/api/booking-requests/' + request)
                            .then(function (response) {
                                window.location.replace(response.data.redirect);
                            });
                    }
                });
            }
        }
    }
</script>
