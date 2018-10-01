<template>
    <div>
        <div class="row">
            <div class="col-md-3">
                <div class="card mb-4">
                    <div class="card-body">
                        <form action="#" method="post" v-on:submit.prevent="searchSubstitute">
                                <div class="form-group">
                                    <label for="day_start">Jour :</label>
                                    <flat-pickr
                                            v-model="search.day_start"
                                            :config="flatPickrConfigDays"
                                            class="form-control"
                                            placeholder="Sélectionner une date"
                                            name="day_start">
                                    </flat-pickr>
                                </div>
                                <div class="form-group">
                                    <label for="hour_start">Heure de début:</label>
                                    <flat-pickr
                                            v-model="search.hour_start"
                                            :config="flatPickrConfigHours"
                                            class="form-control"
                                            placeholder="Heure de départ"
                                            name="hour_start">
                                    </flat-pickr>
                                </div>
                                <div class="form-group">
                                    <label for="hour_end">Heure de fin :</label>
                                    <flat-pickr
                                            v-model="search.hour_end"
                                            :config="flatPickrConfigHours"
                                            class="form-control"
                                            placeholder="Heure de fin"
                                            name="hour_end">
                                    </flat-pickr>
                                </div>
                                <div class="mt-2 mb-4">
                                    <label for="" class="d-none">&nbsp;</label>
                                    <button class="btn btn-primary btn-block" type="submit">Rechercher</button>
                                </div>
                                <div class="form-group form-check m-0">
                                    <input type="checkbox" class="form-check-input" id="extended_search" name="extended_search"  v-model="search.extended">
                                    <label class="form-check-label" for="extended_search">Recherche étendue</label>
                                </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <div class="card card-default">
                    <div class="card-header bg-dark text-white">
                        Résultats de recherche
                        <div class="float-md-right" v-if="selectedAvailabilities.length">
                            <button class="btn btn-success btn-sm" v-on:click="contactPeopleValidation">Contacter les personnes sélectionnées</button>
                        </div>
                    </div>
                    <div class="card-body">

                        <div class="loading-overlay" v-show="!loaded">
                            <div class="sk-folding-cube">
                                <div class="sk-cube1 sk-cube"></div>
                                <div class="sk-cube2 sk-cube"></div>
                                <div class="sk-cube4 sk-cube"></div>
                                <div class="sk-cube3 sk-cube"></div>
                            </div>
                        </div>

                        <div v-show="availabilities.length">
                            <table class="table table-borderless table-striped table-smf table-responsive-sm">
                                <thead>
                                <tr>
                                    <th width="15"><input type="checkbox" v-on:click="selectAll" v-model="peopleSelected"></th>
                                    <th width="30">
                                        <a href="#" v-on:click.prevent="filterByFavorite" class="text-warning">
                                            <i :class="[favoriteOnly ? 'fas fa-star' : 'far fa-star']"></i>
                                        </a>
                                    </th>
                                    <th>Remplaçant</th>
                                    <th class="d-none d-lg-table-cell">Date</th>
                                    <th><span data-toggle="tooltip" title="Horaire libre">Disponibilité</span></th>
                                    <th class="d-none d-md-table-cell"><span data-toggle="tooltip" title="Réseau dans lequel le remplaçant est disponible">Réseaux</span></th>
                                    <th class="d-none d-md-table-cell text-right"><span data-toggle="tooltip" title="Indice de correspondance">IC<span>*</span></span></th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="item in filteredResults">
                                    <td><input type="checkbox" v-model="selectedAvailabilities" :value="item"></td>
                                    <td>
                                        <a href="#" v-on:click.prevent="addToFavorite(item)">
                                            <i class="far fa-star" style="color: #ccc;" v-show="!item.favorite"></i>
                                            <i class="fas fa-star text-warning" v-show="item.favorite"></i>
                                        </a>
                                    </td>
                                    <td>
                                        <span v-if="item.user">{{item.user.name}}</span>
                                        <span v-if="!item.user" class="text-muted">Aucun</span>
                                    </td>
                                    <td class="d-none d-lg-table-cell"><i class="far fa-calendar-alt d-none d-sm-inline mr-1"></i> {{item.start}}</td>
                                    <td class="text-truncate">{{item.start_hour}} <i class="fas fa-arrow-right"></i>
                                        {{item.end_hour}}
                                    </td>
                                    <td class="d-none d-md-table-cell">
                                        <ul class="list-inline m-0">
                                            <li v-for="network in item.networks" class="list-inline-item">
                                                <span class="badge text-white" :style="'background-color: ' + network.color + ';'" v-if="network.team_id == currentTeam.id">{{network.name}}</span>
                                            </li>
                                        </ul>
                                        <span v-if="!item.networks" class="text-muted">-</span>
                                    </td>
                                    <td class="d-none d-md-table-cell text-right">
                                        <span class="badge badge-secondary" v-if="item.matching=='none'">{{item.matching}}</span>
                                        <span class="badge badge-success" v-if="item.matching=='complete'">Complet</span>
                                        <span class="badge badge-warning" v-if="item.matching=='partial'">Partiel</span>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                            <p class="text-muted">* Indice de Correspondance (partiel ou complet)</p>
                        </div>
                        <div class="alert alert-info mb-0" v-if="!availabilities.length">Aucune disponibilité ne correspond à votre recherche.</div>
                    </div>
                    <div class="card-footer d-flex justify-content-end" v-if="selectedAvailabilities.length">
                        <button class="btn btn-success" v-on:click="contactPeopleValidation">Contacter les personnes sélectionnées</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="contact-modal modal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Confirmation de contact</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Les personnes sélectionnées seront contactées dans les plus brefs délais afin de confirmer le
                            remplacement de la plage horaire définie, merci de vérifier et compléter les informations ci-dessous :</p>

                        <div class="text-center">
                            <h2>Date : {{search.day_start}}</h2>
                            <p>De {{search.hour_start}} à {{search.hour_end}}</p>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-4">
                                <label for="nursery">Garderie <span class="text-danger">*</span></label>
                                <select name="nursery" class="form-control selectpicker" title="Sélectionner..." data-live-search="true" data-style="btn-link border text-secondary" v-model="nursery">
                                    <option v-for="nursery in nurseries" :value="nursery.id">{{nursery.name}}</option>
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="workgroup">Groupe de travail <span class="text-danger">*</span></label>
                                <select name="workgroup" class="form-control selectpicker" title="Sélectionner..." data-style="btn-link border text-secondary" v-model="workgroup">
                                    <option v-for="workgroup in workgroups" :value="workgroup.id">{{workgroup.name}}</option>
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="purpose">Raison <span class="text-danger">*</span></label>
                                <select name="purpose" class="form-control selectpicker" title="Sélectionner..." data-live-search="true" data-style="btn-link border text-secondary" v-model="purpose">
                                    <option v-for="purpose in purposes" :value="purpose.id">{{purpose.name}}</option>
                                </select>
                            </div>
                        </div>
                        <div class="row" v-if="role == 'director' || role == 'owner'">
                            <div class="form-group col">
                                <label for="employee">Employé</label>
                                <select name="employee" class="form-control selectpicker" title="Sélectionner..." data-live-search="true" data-style="btn-link border text-secondary" v-model="employee">
                                    <option v-for="employee in employees" :value="employee.id">{{employee.name}}</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="message">Message</label>
                            <p class="text-muted">Communiquez à votre remplaçant les informations essentielles.</p>
                            <textarea name="message" cols="30" rows="5" class="form-control" v-model="message"></textarea>
                        </div>

                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                        <button type="button" class="btn btn-primary" :disabled="nursery === 0 || workgroup === 0 || purpose === 0" v-on:click="contactPeople">Envoyer la demande
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import flatPickr from 'vue-flatpickr-component';
    import 'flatPickr/dist/flatpickr.css';
    import {French} from 'flatPickr/dist/l10n/fr';

    import swal from 'sweetalert2';
    import 'sweetalert2/dist/sweetalert2.min.css';

    import selectpicker from 'bootstrap-select';
    import 'bootstrap-select/dist/css/bootstrap-select.min.css';

    let vm;

    let today = new Date();
    today.setHours(6);
    today.setMinutes(0);

    let later = new Date();
    later.setHours(18);
    later.setMinutes(0);

    let data = {
        flatPickrConfigDays: {
            wrap: true,
            dateFormat: 'd.m.Y',
            locale: French,
            minDate: formattedDate(today)
        },
        flatPickrConfigHours: {
            wrap: true,
            dateFormat: 'H:i',
            enableTime: true,
            noCalendar: true,
            time_24hr: true,
            minuteIncrement: 30,
            locale: French
        },
        peopleSelected: false,
        selectedAvailabilities: [],
        availabilities: [],
        search: {
            day_start: formattedDate(today),
            hour_start: formattedHour(today),
            hour_end: formattedHour(later),
            extended: false
        },
        nurseries: [],
        nursery: 0,
        workgroups: [],
        workgroup: 0,
        purposes: [],
        purpose: 0,
        employees: [],
        employee: 0,
        message: null,
        loaded: true,
        favoriteOnly: false,
    };

    export default {
        props: ['user', 'currentTeam', 'role'],
        data() {
            return data;
        },
        mounted() {
            vm = this;

            this.$nextTick(function () {
                this.searchSubstitute();

                // init selectpicker
                $('.selectpicker').selectpicker({});
            });
        },
        computed: {
            filteredResults: function () {
                if (data.availabilities.length) {
                    return data.availabilities.filter(function (item) {
                        if (data.favoriteOnly) {
                            return item.favorite === true;
                        }

                        return true;
                    });
                }
            }
        },
        updated() {
            $(this.$el).find('.selectpicker').selectpicker('refresh');
        },
        destroyed() {
            $(this.$el).find('.selectpicker')
                .off()
                .selectpicker('destroy');
        },
        methods: {
            searchSubstitute: function () {
                data.loaded = false;
                data.availabilities = {};

                axios.get('/api/availabilities/search', {
                    params: {
                        'day_start': data.search.day_start,
                        'hour_start': data.search.hour_start,
                        'hour_end': data.search.hour_end,
                        'extended': data.search.extended,
                        'uid': this.spark.userId
                    }
                })
                .then(function (response) {
                    data.loaded = true;
                    data.availabilities = response.data.data;
                });
            },
            selectAll: function (event) {
                // Handles the selection of substitutes
                if (data.peopleSelected) {
                    data.selectedAvailabilities = [];
                } else {
                    data.selectedAvailabilities = [];
                    vm.filteredResults.forEach(function (item) {
                        data.selectedAvailabilities.push(item);
                    });
                }
            },
            contactPeopleValidation: function () {

                // retrieve data related to availabilities
                axios.all([getNurseries(), getWorkgroups(), getPurposes(), getEmployees()])
                    .then(axios.spread(function(nurseries, workgroups, purposes, employees) {
                       data.nurseries   = nurseries.data.data;
                       data.workgroups  = workgroups.data;
                       data.purposes    = purposes.data;
                       data.employees   = employees.data;
                    }));

                $('.contact-modal').modal('show'); // Show the modal
            },
            contactPeople: function () {
                $('.contact-modal').modal('hide'); // Hide the modal

                swal({
                    type: "info",
                    text: "Traitement de votre demande en cours, merci de patienter.",
                    onOpen: () => {
                        swal.showLoading();
                    }
                });

                axios.post('/api/booking-requests', {
                    // Pass the request data to the API
                    availabilities: data.selectedAvailabilities,
                    date_start: data.search.day_start + " " + data.search.hour_start,
                    date_end: data.search.day_start + " " + data.search.hour_end,
                    nursery: data.nursery,
                    workgroup: data.workgroup,
                    employee: data.employee,
                    message: data.message,
                    uid: this.spark.userId
                }).then(function (response) {

                    // No error, show an alert
                    swal({
                        title: "Demandes envoyées",
                        text: "Les demandes de remplacements sont en cours d'envoi.",
                        type: "success"
                    }).then((response) => {
                        data.peopleSelected = false;
                        data.selectedAvailabilities = [];
                        data.nursery = 0;
                        data.workgroup = 0;
                        data.purpose = 0;
                        data.employee = 0;
                        data.message = null;
                    });
                });
            },
            filterByFavorite: function () {
                data.favoriteOnly = !data.favoriteOnly;
            },
            addToFavorite: function (item) {
                axios.post('/api/users/favorites', {
                    params: {
                        userId: this.spark.userId,
                        substituteId: item.user.id
                    }
                })
                .then(function (response) {
                    let attached = response.data;
                    data.favorite = attached;

                    item.favorite = attached;

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
        },
        components: {
            flatPickr
        }
    }

    // Utils
    function zeroLeadingNumber(num) {
        if (num < 10) {
            return '0' + num;
        }
        return num;
    }

    function formattedHour(date) {
        return zeroLeadingNumber(date.getHours()) + ':' + zeroLeadingNumber(date.getMinutes());
    }

    function formattedDate(date) {
        return zeroLeadingNumber(date.getDate()) + '.' + zeroLeadingNumber(date.getMonth() + 1) + '.' + zeroLeadingNumber(date.getFullYear());
    }

    function getNurseries() { return axios.get('/api/nurseries', {params: {'uid': vm.spark.userId}}); }
    function getEmployees() { return axios.get('/api/users', {params: {'uid': vm.spark.userId, 'noPagination': true}}); }
    function getWorkgroups() { return axios.get('/api/workgroups'); }
    function getPurposes() { return axios.get('/api/purposes'); }

</script>

<style lang="scss">
    .loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        background: rgba(255, 255, 255, 0.75);
        z-index: 10;

        .sk-folding-cube {
            top: 46%;
        }
    }

    .fa-arrow-right {
        font-size: 0.7em;
    }
</style>