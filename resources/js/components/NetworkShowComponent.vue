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
            deleteNetwork: function (network) {
                if (!network) { return; }
                swal({
                    title: 'Attention !',
                    text: "Vous êtes sur le point de supprimer définitivement ce réseau.",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Supprimer',
                    cancelButtonText: 'Annuler'
                }).then((result) => {
                    if (result.value) {
                        axios.delete('/api/networks/' + network)
                        .then(function(response){
                            window.location.replace(response.data.redirect);
                        });
                    }
                });
            }
        }
    }
</script>
