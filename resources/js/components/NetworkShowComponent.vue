<script>
    import swal from 'sweetalert2';
    import 'sweetalert2/dist/sweetalert2.min.css';

    let data = {};

    export default {
        data() {
            return data;
        },
        mounted() {
            console.log('Network show component mounted.');
        },
        methods: {
            deleteNetwork: function (network) {
                console.log('try to delete');
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
                            console.log(response);
                            window.location.replace(response.data.redirect);
                        });
                    }
                });
            }
        }
    }
</script>
