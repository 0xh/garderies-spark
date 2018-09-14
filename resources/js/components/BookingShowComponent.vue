<script>
    import swal from 'sweetalert2';
    import 'sweetalert2/dist/sweetalert2.min.css';
    import vueStars from 'vue-stars';

    let vm;
    let data = {
        feedback: {
            name: '',
            description: '',
            rating: 0
        }
    };

    export default {
        data() {
            return data;
        },
        props: {
            bookingId: Number,
            userId: Number,
            substituteId: Number
        },
        mounted() {
            vm = this;
        },
        methods: {
            validateBooking: function (booking) {
                axios.post('/api/bookings/approve/' + booking)
                    .then(function (response) {
                        console.log(response);

                        swal({
                            title: 'Confirmé',
                            text: "Le remplacement a bien été validé.",
                            type: 'success',
                        }).then((result) => {
                            location.reload();
                        });
                    });
            },
            deleteBooking: function (booking) {
                if (!booking) {
                    return;
                }
                swal({
                    title: 'Attention !',
                    text: "Vous êtes sur le point de supprimer définitivement ce remplacement.",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Supprimer',
                    cancelButtonText: 'Annuler'
                }).then((result) => {
                    if (result.value) {
                        axios.delete('/api/bookings/' + booking)
                            .then(function (response) {
                                console.log(response);
                                window.location.replace(response.data.redirect);
                            });
                    }
                });
            },
            addFeedback: function () {
                $('.feedback-modal').modal('show');
            },
            saveFeedback: function () {
                axios.post('/api/feedbacks', {
                        params: {
                            feedback: data.feedback,
                            bookingId: vm.bookingId,
                            userId: vm.userId,
                            substituteId: vm.substituteId
                        }
                    })
                    .then(function (response) {
                        // close the modal
                        $('.feedback-modal').modal('hide');

                        // display a success alert
                        swal({
                            title: "Rapport sauvegardé",
                            text: "Le rapport a bien été enregistré pour ce remplacement.",
                            type: "success"
                        })
                    })
            }
        },
        components: {
            vueStars
        }
    }
</script>
<style lang="scss">
    .vue-stars label {
        margin: 0;
        cursor: pointer;
        font-size: 1.3em; }
</style>