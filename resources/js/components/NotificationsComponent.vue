<template>
    <div class="notifications float-right d-none d-lg-block">
        <div class="icon-wrapper">
            <a href="#" class="btn btn-link text-dark" data-toggle="popover" data-placement="bottom" title="Vos notifications">
                <span :class="['badge badge-pill', {'badge-danger': count > 0}]" v-show="count > 0" v-html="count">0</span>
                <i class="fas fa-bell"></i>
            </a>
        </div>
        <div id="notification-content" class="d-none">
            <ul class="list-unstyled m-0" v-show="requests.length">
                <li class="mb-2" v-for="item in requests">
                    <a :href="item.url">Demande de remplacement pour le {{item.start_date_formatted}}</a>
                </li>
            </ul>
            <p v-show="!requests.length" class="m-0">Il n'y a pas de notification pour le moment.</p>
        </div>
    </div>
</template>

<script>

    let vm;
    let data = {
        count: 0,
        requests: []
    };

    export default {
        data() { return data; },
        mounted() {
            vm = this;
            axios.get('/api/booking-requests')
                .then(function (response) {
                    data.count = response.data.count;
                    data.requests = response.data.requests;

                    // wait for the DOM to update
                    Vue.nextTick(function() {
                        $('[data-toggle="popover"]').popover({
                            trigger: 'click',
                            content: $('#notification-content').html(),
                            html: true
                        });
                    });
                });

        },
        methods: {}
    }
</script>

<style lang="scss">
    .notifications {
        .icon-wrapper {
            position: relative;
        }
        .badge {
            position: absolute;
            right: 0;
        }
    }
</style>