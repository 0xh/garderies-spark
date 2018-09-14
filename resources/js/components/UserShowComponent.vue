<script>
    import swal from 'sweetalert2';
    import 'sweetalert2/dist/sweetalert2.min.css';

    let data = {
        favorite: false
    };

    let vm;

    export default {
        data() {
            return data;
        },
        props: {
            isFavorite: Boolean,
            user: Object
        },
        mounted() {
            vm = this;
            data.favorite = this.isFavorite;
        },
        methods: {
            deleteUser: function (user) {
                if (!user) { return; }
                swal({
                    title: 'Attention !',
                    text: "Vous êtes sur le point de supprimer définitivement cet utilisateur.",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Supprimer',
                    cancelButtonText: 'Annuler'
                }).then((result) => {
                    if (result.value) {
                        axios.delete('/api/users/' + user)
                        .then(function(response){
                            window.location.replace(response.data.redirect);
                        });
                    }
                });
            },
            addToFavorite: function (selectedUser) {
                axios.post('/api/users/favorites', {
                    params: {
                        userId: this.user.id,
                        substituteId: selectedUser
                    }
                })
                .then(function (response) {
                    let attached = response.data;
                    data.favorite = attached;

                    let message = "Ajouté aux favoris";
                    if (!attached) {
                        message ="Supprimé des favoris";
                    }

                    swal({
                        type: "success",
                        toast: true,
                        title: message,
                        position: "top-right",
                        showConfirmButton: false,
                        timer: 2000
                    });

                });
            }
        }
    }
</script>
