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
            deleteNursery: function (nursery) {
                if (!nursery) { return; }
                swal({
                    title: 'Attention !',
                    text: "Vous êtes sur le point de supprimer définitivement cette garderie.",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Supprimer',
                    cancelButtonText: 'Annuler'
                }).then((result) => {
                    if (result.value) {
                        axios.delete('/api/nurseries/' + nursery)
                        .then(function(response){
                            console.log(response);
                            window.location.replace(response.data.redirect);
                        });
                    }
                });
            }
        }
    }
</script>
