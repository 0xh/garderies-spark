<template>
    <div class="card card-default mb-4">
        <div class="card-header bg-dark text-white">Employés</div>
        <div class="card-body">
            <table class="table table-borderless table-striped table-responsive-sm">
                <thead>
                <tr>
                    <th>Nom et prénom</th>
                    <th>Téléphone</th>
                    <th class="d-none d-lg-table-cell">E-mail</th>
                    <th v-if="!nursery">Garderie</th>
                    <th class="d-none d-lg-table-cell">Réseaux</th>
                </tr>
                </thead>
                <tbody>
                    <tr v-for="user in users">
                        <td><a :href="user.link">{{user.name}}</a></td>
                        <td>{{user.phone}}</td>
                        <td class="d-none d-lg-table-cell">{{user.email}}</td>
                        <td v-if="!nursery"><a :href="user.nursery.link">{{user.nursery.name}}</a></td>
                        <td class="d-none d-lg-table-cell">
                            <ul class="list-inline" v-if="user.networks.length">
                                <li class="list-inline-item" v-for="network in user.networks">{{network.name}}</li>
                            </ul>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>

    let data = {
        users: {}
    };

    export default {

        data() {
            return data;
        },
        props: {
            nursery: {
                type: Number,
                default: 0
            },
            network: {
                type: Number,
                default: 0
            }
        },
        mounted() {
            console.log('Component mounted.');

            axios.get('/api/users', {
                params: {
                    nursery: this.nursery,
                    network: this.network
                }
            })
            .then(function (response) {
                console.log(response);
                data.users = response.data;
            });
        }
    }
</script>
