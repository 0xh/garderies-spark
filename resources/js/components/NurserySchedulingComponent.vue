<script>
    import 'fullcalendar/dist/fullcalendar.min.css';
    import 'fullcalendar-scheduler/dist/scheduler.min.css';
    import 'fullcalendar';
    import 'fullcalendar-scheduler';

    let vm;
    let data = {};
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
                defaultView: 'timelineDay',
                locale: 'fr-ch',
                height: 'auto',
                weekends: false,
                eventOverlap: false,
                columnHeaderFormat: 'ddd DD.MM',
                timeFormat: 'HH:mm',
                slotLabelFormat: 'HH:mm',
                minTime: '06:00:00', // May change depending on Nursery
                maxTime: '19:00:00', // May change depending on Nursery
                resources: function (callback) {
                    axios.get('/api/nurseries/resources', {
                        params: {
                            nursery: vm.nursery
                        }
                    })
                    .then(function (response) {
                        callback(response.data);
                    });
                }
            });

        },
        methods: {},
        components: {}
    }
</script>
