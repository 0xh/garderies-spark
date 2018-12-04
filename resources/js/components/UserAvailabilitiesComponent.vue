<template>
    <div class="wrapper">
        <div class="modal modal-event" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Disponibilité</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="form-group col">
                                <label for="date_start">Heure de début</label>
                                <flat-pickr
                                        v-model="editEvent.hour_start"
                                        :config="flatPickrConfig"
                                        class="form-control"
                                        placeholder="Select a date"
                                        name="date_start">
                                </flat-pickr>
                            </div>
                            <div class="form-group col">
                                <label for="date_end">Heure de fin</label>
                                <flat-pickr
                                        v-model="editEvent.hour_end"
                                        :config="flatPickrConfig"
                                        class="form-control"
                                        placeholder="Select a date"
                                        name="date_end">
                                </flat-pickr>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-outline-danger btn-sm" v-on:click="userDeleteEvent">Supprimer cet élément</button>
                        <button type="button" class="btn btn-primary" v-on:click="userUpdateEvent">Sauvegarder</button>
                    </div>
                </div>
            </div>
        </div>

        <div id="calendar"></div>
    </div>
</template>

<script>
    import flatPickr from 'vue-flatpickr-component';
    import 'flatPickr/dist/flatpickr.css';
    import {French} from 'flatPickr/dist/l10n/fr';
    import moment from 'moment';

    import swal from 'sweetalert2';
    import 'sweetalert2/dist/sweetalert2.min.css';

    import 'fullcalendar/dist/fullcalendar.min.css';
    import 'fullcalendar';

    let vm;
    let data = {
        editEvent: {
            event: null,
            day_start: null,
            hour_start: null,
            hour_end: null
        },
        flatPickrConfig: {
            wrap: false,
            dateFormat: 'H:i',
            enableTime: true,
            noCalendar: true,
            time_24hr: true,
            minuteIncrement: 30,
            locale: French
        },
    };
    let calendar;

    export default {
        data() { return data; },
        props: ['user', 'openingTime', 'closingTime', 'defaultDate'],
        mounted() {
            vm = this;

            // Instantiate the calendar
            calendar = $('#calendar').fullCalendar({
                schedulerLicenseKey: 'CC-Attribution-NonCommercial-NoDerivatives',
                themeSystem: 'bootstrap4',
                contentHeight: 'auto',
                defaultView: 'agendaWeek',
                defaultDate: vm.defaultDate,
                locale: 'fr-ch',
                header: {
                    left: 'title',
                    center: '',
                    right: 'today prev,next'
                },
                buttonText: {
                    today: "Aujourd'hui",
                    month: "Vue mois",
                    week: "Vue semaine",
                    list: "Vue liste"
                },
                weekends: false,
                eventOverlap: false,
                allDaySlot: false,
                columnHeaderFormat: 'ddd DD.MM',
                timeFormat: 'HH:mm',
                slotLabelFormat: 'HH:mm',
                minTime: '06:00:00', // May change depending on Nursery
                maxTime: '19:00:00', // May change depending on Nursery
                businessHours: {
                    dow: [1, 2, 3, 4, 5],
                    start: vm.openingTime,
                    end: vm.closingTime
                },
                editable: true,
                eventSources: [
                    {
                        url: '/api/availabilities/user/' + this.user,
                        textColor: 'white'
                    },
                    {
                        url: '/api/bookings/user/' + this.user,
                        color: '#c62828',
                        textColor: 'white',
                        editable: false
                    }
                ],
                dayClick: function(date, event, view) {
                    let start   = date;
                    let end     = date.clone().add(2, 'hour');

                    // New event object
                    let newEvent = {
                        title: 'Nouvelle disponibilité',
                        description: '',
                        start: start.format(),
                        end: end.format(),
                        color: '#ffa000',
                        status: 0,
                        type: 'availability'
                    };

                    // Save the new event
                    axios.post('/api/availabilities', {
                        params: {
                            'event': newEvent,
                            'userID': vm.user
                        }
                    })
                    .then(function(response){
                        if (!response.data.isOverlapping) {
                            // Assign the created ID to the event object
                            newEvent.id = response.data.id;

                            newEvent.start = response.data.event.start;
                            newEvent.end = response.data.event.end;

                            // Render the event on the calendar
                            calendar.fullCalendar('renderEvent', newEvent);
                        } else {
                            swal({
                                type: 'error',
                                position: 'top-end',
                                toast: true,
                                title: 'Disponibilités se chevauchent',
                                showConfirmButton: false,
                                timer: 3000
                            });
                        }
                    });
                },
                eventResize: function( event, delta, revertFunc, jsEvent, ui, view ) {
                    // Update the event
                    axios.put('/api/availabilities/' + event.id, {
                        params: {
                            'start': event.start,
                            'end': event.end,
                        }
                    })
                    .then(function(response){
                    });
                },
                eventDrop: function (event, delta, revertFunc, jsEvent, ui, view) {
                    // Update the event
                    axios.put('/api/availabilities/' + event.id, {
                        params: {
                            'start': event.start,
                            'end': event.end,
                        }
                    })
                    .then(function(response){
                    });
                },
                eventClick: function(event, jsEvent, view) {

                    // Limit the event to events that are availabilities
                    if (event.type !== 'availability') { return; }

                    // Display the modal
                    $('.modal-event').modal({show: true, focus: false}); // focus on the modal messes up with flatpickr

                    // Fill the event object for edit
                    data.editEvent = {
                        event: event,
                        date_start: event.start.format('DD.MM.YYYY'),
                        hour_start: event.start.format('HH:mm'),
                        hour_end: event.end.format('HH:mm')
                    };
                },
                eventRender: function (event, element, view) {

                    // Inject a link element to the events
                    if (event.type === 'availability' && event.status === 0) {
                        //element.append('<a href="/availabilities/' + event.id + '" class="edit-link">Editer</a>');
                    }
                }
            });
        },
        methods: {
            userUpdateEvent: function () {
                if (data.editEvent.event) {
                    // Format dates
                    let start   = data.editEvent.date_start + ' ' + data.editEvent.hour_start;
                    let end     = data.editEvent.date_start + ' ' + data.editEvent.hour_end;

                    // Update the event
                    axios.put('/api/availabilities/' + data.editEvent.event.id, {
                        params: {
                            'start': start,
                            'end': end,
                        }
                    })
                    .then(function(response){
                        $('.modal-event').modal('hide');
                        calendar.fullCalendar('refetchEvents');
                        data.editEvent.event = null;
                    });
                } else {
                    $('.modal-event').modal('hide');
                }
            },
            userDeleteEvent: function() {
                // Delete the event
                let eventID = data.editEvent.event.id;

                if (eventID) {
                    axios.delete('/api/availabilities/' + eventID, {
                        params: {}
                    })
                    .then(function(response){
                        $('.modal-event').modal('hide');
                        calendar.fullCalendar('refetchEvents');
                        data.editEvent.event = null;

                        swal({
                            type: 'success',
                            position: 'top-end',
                            toast: true,
                            title: 'Disponibilité supprimée',
                            showConfirmButton: false,
                            timer: 3000
                        });

                    });
                }
            }
        },
        components: {
            flatPickr
        }
    }
</script>

<style lang="scss">
    .edit-link {
        position:absolute;
        z-index: 10;
        bottom: 0;
        color: #fff;
    }
</style>