<script>
    import flatPickr from 'vue-flatpickr-component';
    import 'flatPickr/dist/flatpickr.css';
    import {French} from 'flatPickr/dist/l10n/fr';
    import moment from 'moment';

    let data = {
        search: {
            date_start: null,
            date_end: null
        },
        flatPickrConfig: {
            wrap: false,
            dateFormat: 'd.m.Y',
            enableTime: false,
            noCalendar: false,
            locale: French
        },
        bookings: []
    };

    export default {
        data() {
            return data;
        },
        props: {
            nursery: Number,
            firstDay: String,
            lastDay: String
        },
        mounted() {
            data.search.date_start = this.firstDay;
            data.search.date_end = this.lastDay;
            this.applyFilter()
        },
        methods: {
            dateChange: function (data) {
                //
            },
            applyFilter: function () {
                axios.get('/api/nurseries/planning', {
                    params: {
                        nursery: this.nursery,
                        start: data.search.date_start,
                        end: data.search.date_end
                    }
                    })
                    .then(function (response) {
                        data.bookings = response.data;
                    });
            }
        },
        components: {
            flatPickr
        }
    }
</script>
