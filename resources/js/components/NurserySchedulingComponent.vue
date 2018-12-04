<script>
    import 'fullcalendar/dist/fullcalendar.min.css';
    import 'fullcalendar-scheduler/dist/scheduler.min.css';
    import 'fullcalendar';
    import 'fullcalendar-scheduler';

    import flatPickr from 'vue-flatpickr-component';
    import 'flatPickr/dist/flatpickr.css';
    import {French} from 'flatPickr/dist/l10n/fr';
    import moment from 'moment';

    let vm;
    let data = {
        newEvent: {
            date: null,
            hour_start: null,
            hour_end: null,
            resource: ''
        },
        editEvent: {
            hour_start: null,
            hour_end: null,
            resource: ''
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
        flatPickrDateConfig: {
            wrap: false,
            dateFormat: 'd.m.Y',
            enableTime: false,
            locale: French
        },
    };
    let calendar;

    export default {
        data() {
            return data;
        },
        props: {
            nursery: Number
        },
        mounted() {
            vm = this;
            calendar = $('#calendar').fullCalendar({
                schedulerLicenseKey: 'CC-Attribution-NonCommercial-NoDerivatives',
                themeSystem: 'bootstrap4',
                defaultView: 'timelineDay',
                header: {center: 'timelineDay timelineWeek'},
                buttonText: {
                    timelineDay: "Aujourd'hui",
                    timelineWeek: 'Semaine en cours',
                    agendaDay: 'Agenda journalier',
                    agendaWeek: 'Agenda semaine',
                    month: 'Mois',
                    today: "Aujourd'hui"},
                views: {
                    timelineWeek: {
                        slotWidth: 60,
                        slotLabelFormat: ['DD.MM.YYYY', "H[h]"],
                    }
                },
                locale: 'fr-ch',
                height: 'auto',
                weekends: false,
                eventOverlap: false,
                columnHeaderFormat: 'ddd DD.MM',
                timeFormat: 'HH:mm',
                slotLabelFormat: ['DD.MM.YYYY', 'HH:mm'],
                minTime: '06:00:00', // May change depending on Nursery
                maxTime: '19:00:00', // May change depending on Nursery
                resourceAreaWidth: '200',
                resourceLabelText: 'Employés',
                duration: '00:30:00',
                allDaySlot: false,
                slotWidth: 25,
                displayEventTime: true,
                displayEventEnd: true,
                nowIndicator: true,
                resources: function (callback) {
                    axios.get('/api/nurseries/resources', {
                        params: {
                            nursery: vm.nursery
                        }
                    })
                    .then(function (response) {
                        callback(response.data);
                    });
                },
                eventSources: [
                    {
                        url: '/api/nurseries/schedules',
                        data: {
                            nursery: vm.nursery
                        },
                        editable: true
                    }
                ],
                eventClick: this.editEventModal,
                eventResize: this.evenResizeOrDrop,
                eventDrop: this.evenResizeOrDrop,
                dayClick: function (date, event, view, resource) {
                    let start       = date;
                    let end         = date.clone().add(2, 'hour');
                    let resourceId  = resource.id;

                    // New event object
                    let newEvent = {
                        resourceId: resourceId,
                        start: start.format(),
                        end: end.format(),
                        editable: true
                    };

                    axios.post('/api/schedules', {
                        params: {
                            event: {
                                hour_start: start,
                                hour_end: end,
                                resource: resourceId
                            }
                        }
                    })
                    .then(function (response) {
                        newEvent.id = response.data.id;
                        // Render the event on the calendar
                        calendar.fullCalendar('renderEvent', newEvent);
                    });

                },
            });
        },
        methods: {
            createEventModal: function() {
                // Display the modal
                $('.modal-event').modal({show: true, focus: false}); // focus on the modal messes up with flatpickr
            },
            editEventModal: function (event) {
                data.editEvent = {
                    id: event.id,
                    date: event.start,
                    hour_start: event.start.format("HH:mm"),
                    hour_end: event.end.format("HH:mm"),
                    resource: event.resourceId
                };

                // Display the modal
                $('.modal-edit-event').modal({show: true, focus: false}); // focus on the modal messes up with flatpickr
            },
            // save new event through create modal
            saveEvent: function () {
                axios.post('/api/schedules', {
                    params: {
                        hour_start: data.newEvent.hour_start,
                        hour_end: data.newEvent.hour_end,
                        resource: data.newEvent.resource
                    }
                })
                .then(function (response) {
                    // hide the modal
                    $('.modal-event').modal('hide');
                    calendar.fullCalendar('refetchEvents');
                });
            },
            // update event through edit modal
            updateEvent: function () {
                axios.put('/api/schedules/' + data.editEvent.id, {
                    params: {
                        date: data.editEvent.date,
                        hour_start: data.editEvent.hour_start,
                        hour_end: data.editEvent.hour_end,
                        resource: data.editEvent.resource
                    }
                })
                .then(function (response) {
                    // hide the modal
                    $('.modal-edit-event').modal('hide');
                    calendar.fullCalendar('refetchEvents');
                });
            },
            deleteEvent: function() {
                let eventID = data.editEvent.id;

                if (eventID) {
                    axios.delete('/api/schedules/' + eventID)
                        .then(function (response) {
                            // hide the modal
                            $('.modal-edit-event').modal('hide');
                            calendar.fullCalendar('refetchEvents');
                        })
                }
            },
            // update event when resized or moved
            evenResizeOrDrop: function (event) {
                axios.put('/api/schedules/' + event.id, {
                    params: {
                        date: event.start,
                        hour_start: event.start,
                        hour_end: event.end,
                        resource: event.resourceId
                    }
                })
                .then(function(response){
                });
            }
        },
        components: {
            flatPickr
        }
    }
</script>
