<template>
    <div class="card card-default mb-4">
        <div class="card-header text-white" v-bind:class="this.cardBgClass ? this.cardBgClass : 'bg-dark'">
            <div class="row">
                <div class="col-sm-12 col-md-6">{{this.title}}</div>
                <div class="col-sm-12 col-md-6"><filter-bar v-on:filter="setFilter"></filter-bar></div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <vuetable ref="vuetable"
                          :api-url="this.apiUrl"
                          :fields="this.fields"
                          :query-params="{sort: 'sort', page: 'page', perPage: 'per_page', simon: 'simon'}"
                          pagination-path=""
                          :css="css.table"
                          :multi-sort="true"
                          detail-row-component="my-detail-row"
                          :append-params="moreParams"
                          @vuetable:pagination-data="onPaginationData"
                          no-data-template="Aucune donnée disponible">

                    <template slot="photo" slot-scope="props">
                        <img :src="props.rowData.photo_url" alt="photo" class="user-profile-picture">
                    </template>

                    <template slot="favorite" slot-scope="props">
                        <i class="far fa-star text-warning"></i>
                    </template>

                    <template slot="nurserylink" slot-scope="props">
                        <a :href="'/nurseries/' + props.rowData.slug">{{props.rowData.name}}</a>
                    </template>

                    <template slot="network" slot-scope="props">
                        <span class="badge badge-pill text-white" v-bind:style="{background: props.rowData.color, verticalAlign: 'middle'}">{{props.rowData.name}}</span>
                    </template>

                    <template slot="networklink" slot-scope="props">
                        <a :href="'/networks/' + props.rowData.slug">
                            <span class="badge badge-pill text-white" v-bind:style="{background: props.rowData.color, verticalAlign: 'middle'}">{{props.rowData.name}}</span>
                        </a>
                    </template>

                    <template slot="userlink" slot-scope="props">
                        <a :href="'/users/' + props.rowData.id">{{props.rowData.name}}</a>
                    </template>

                    <template slot="userbookinglink" slot-scope="props">
                        <a v-if="props.rowData.user" :href="'/users/' + props.rowData.user.id">{{props.rowData.user.name}}</a>
                        <span v-if="!props.rowData.user" class="text-muted">Aucun</span>
                    </template>

                    <template slot="substitutelink" slot-scope="props">
                        <a v-if="props.rowData.substitute" :href="'/users/' + props.rowData.substitute.id">{{props.rowData.substitute.name}}</a>
                        <span v-if="!props.rowData.substitute" class="text-muted">Aucun</span>
                    </template>

                    <template slot="bookinglink" slot-scope="props">
                        <a :href="'/bookings/' + props.rowData.id">{{formatDate(props.rowData.start)}}</a>
                    </template>

                    <template slot="bookingShowlink" slot-scope="props">
                        <a :href="'/bookings/' + props.rowData.id">Voir</a>
                    </template>

                    <template slot="ownerlink" slot-scope="props">
                        <a v-if="props.rowData.owner" :href="'/users/' + props.rowData.owner.id">{{props.rowData.owner.name}}</a>
                        <span v-if="!props.rowData.owner" class="text-muted">Aucun</span>
                    </template>

                    <template slot="networkslinkrelation" slot-scope="props">
                        <ul class="list-inline m-0" v-if="props.rowData.networks">
                            <li class="list-inline-item" v-for="network in props.rowData.networks">
                                <a :href="'/networks/' + network.id">
                                    <span class="badge text-white" :style="'background-color: ' + network.color + ';'">{{network.name}}</span>
                                </a>
                            </li>
                        </ul>
                        <span v-if="!props.rowData.networks" class="text-muted">Aucun</span>
                    </template>

                    <template slot="networksrelation" slot-scope="props">
                        <ul class="list-inline m-0" v-if="props.rowData.networks">
                            <li class="list-inline-item" v-for="network in props.rowData.networks">
                                <span class="badge text-white" :style="'background-color: ' + network.color + ';'">{{network.name}}</span>
                            </li>
                        </ul>
                        <span v-if="!props.rowData.networks" class="text-muted">Aucun</span>
                    </template>

                    <template slot="networklinkrelation" slot-scope="props">
                        <a :href="'/networks/' + props.rowData.network.slug" v-if="props.rowData.network">
                        <span class="badge text-white" :style="'background-color: ' + props.rowData.network.color + ';'">
                            {{props.rowData.network.name}}</span>
                        </a>
                        <span v-if="!props.rowData.network" class="text-muted">Aucun</span>
                    </template>

                    <template slot="networkrelation" slot-scope="props">
                        <span class="badge text-white" :style="'background-color: ' + props.rowData.network.color + ';'">
                            {{props.rowData.network.name}}</span>
                        <span v-if="!props.rowData.network" class="text-muted">Aucun</span>
                    </template>

                    <template slot="nurserylinkrelation" slot-scope="props">
                        <a v-if="props.rowData.nursery" :href="'/nurseries/' + props.rowData.nursery.slug">{{props.rowData.nursery.name}}</a>
                        <span v-if="!props.rowData.nursery" class="text-muted">Aucun</span>
                    </template>

                </vuetable>
            </div>
            <div class="vuetable-pagination">
                <vuetable-pagination-info
                        ref="paginationInfo"
                        info-class="pagination-info"
                        info-template="Résultats de {from} à {to} sur un total de {total}."
                no-data-template="Aucune donnée disponible"></vuetable-pagination-info>
                <vuetable-pagination-bootstrap ref="pagination" :css="css.pagination" @vuetable-pagination:change-page="onChangePage" :on-each-side="1"></vuetable-pagination-bootstrap>
            </div>
        </div>
    </div>
</template>

<script>
    import accounting from 'accounting'
    import moment from 'moment'
    import Vuetable from 'vuetable-2/src/components/Vuetable'
    import VuetablePaginationBootstrap from './VuetablePaginationBootstrap'
    import VuetablePaginationInfo from 'vuetable-2/src/components/VuetablePaginationInfo'
    import VueTableFilterBar from './VueTableFilterBar'
    import CustomActions from './CustomActions'

    //6DIow7lpTECXfIJfrZbBpDLc5gGJGcj6WbHlLtro5bhyJGD6FYRJC6OXpKjk

    let vm;

    export default {
        props: {
            fields: Array,
            apiUrl: String,
            title: String,
            statuses: Object,
            cardBgClass: String,
            user: Number,
            currentTeam: Object
        },
        components: {
            Vuetable,
            VuetablePaginationBootstrap,
            VuetablePaginationInfo,
            'custom-actions': CustomActions,
            'filter-bar': VueTableFilterBar
        },
        mounted() {
            vm = this;
        },
        data () {
            return {
                css: {
                    table: {
                        tableClass: 'table table-borderless table-striped',
                        ascendingIcon: 'fa fa-chevron-up',
                        descendingIcon: 'fa fa-chevron-down'
                    },
                    pagination: {
                        wrapperClass: 'pagination',
                        activeClass: 'active',
                        disabledClass: 'disabled',
                        pageClass: 'btn btn-sm btn-light',
                        linkClass: 'btn btn-sm btn-light',
                        icons: {
                            first: 'fa fa-step-backward',
                            prev: 'fa fa-chevron-left',
                            next: 'fa fa-chevron-right',
                            last: 'fa fa-step-forward',
                        },
                    },
                    icons: {
                        first: 'glyphicon glyphicon-step-backward',
                        prev: 'fa fa-chevron-left',
                        next: 'fa fa-chevron-right',
                        last: 'glyphicon glyphicon-step-forward',
                    },
                },
                moreParams: {
                    uid: this.user
                },
                queryParams: {
                    uid: 'uid'
                }
            }
        },
        methods: {
            allcap (value) {
                return value.toUpperCase()
            },
            setFilter (text) {
                this.moreParams.filter = text;
                Vue.nextTick( () => this.$refs.vuetable.refresh() );
            },
            statusLabel (value) {
                switch (value) {
                    case this.statuses.pending:
                        return '<span class="badge badge-info">En attente</span>';
                    case this.statuses.approved:
                        return '<span class="badge badge-success">Validé</span>';
                    case this.statuses['denied']:
                        return '<span class="badge badge-danger">Refusé</span>';
                    case this.statuses['archived']:
                        return '<span class="badge badge-dark">Archivé</span>';
                }

            },
            formatNumber (value) {
                return accounting.formatNumber(value, 2);
            },
            formatDate (value, fmt = 'DD.MM.YYYY') {
                return (value == null)
                    ? ''
                    : moment(value, 'YYYY-MM-DD').format(fmt)
            },
            formatTime (value, fmt = 'HH:mm') {
                return (value == null)
                    ? ''
                    : moment(value, 'YYYY-MM-DD HH:mm:ss').format(fmt)
            },
            onPaginationData (paginationData) {
                this.$refs.pagination.setPaginationData(paginationData);
                this.$refs.paginationInfo.setPaginationData(paginationData);
            },
            onChangePage (page) {
                this.$refs.vuetable.changePage(page);
            },
        }
    }
</script>

<style lang="scss">
    .vuetable-pagination-info {
        margin-bottom: 20px;
    }
    .user-profile-picture {
        display: inline-block;
        background-position: 50%;
        background-size: cover;
        vertical-align: middle;
        height: 20px;
        width: 20px;
        border-radius: 50%;
    }
</style>
