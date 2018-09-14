<template>
    <div class="card card-default">
        <div class="card-header bg-dark text-white">Garderies</div>
        <div class="card-body">
            <table class="table table-borderless table-striped">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th width="50">Employés</th>
                        <th class="d-none">Remplacements</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="nursery in nurseries" :key="nursery.id">
                        <td><a :href="nursery.link">{{nursery.name}}</a></td>
                        <td class="text-center">{{nursery.employees_count}}</td>
                        <td class="d-none">{{nursery.bookings_count}}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
    let data = {
        nurseries: {}
    };

    export default {

        data() {
            return data;
        },
        mounted() {
            console.log('Component mounted.');

            axios.get('/api/nurseries')
                .then(function(response){
                    console.log(response);
                    data.nurseries = response.data.data;
                });
        }
    }
</script>
