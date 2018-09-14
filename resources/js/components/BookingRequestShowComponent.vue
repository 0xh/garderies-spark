<script>
    import swal from 'sweetalert2';
    import 'sweetalert2/dist/sweetalert2.min.css';

    let data = {};

    export default {
        data() {
            return data;
        },
        mounted() {
            console.log('Booking request show component mounted.');
        },
        methods: {
            validateBookingRequest: function (request) {
                console.log('validate this shit');
                axios.post('/api/booking-requests/approve/' + request)
                    .then(function (response) {
                        console.log(response);

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
                console.log('try to delete');
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
                                console.log(response);
                                window.location.replace(response.data.redirect);
                            });
                    }
                });
            }
        }
    }
</script>
