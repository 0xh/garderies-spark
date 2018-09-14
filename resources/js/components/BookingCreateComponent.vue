
<script>
    import flatPickr from 'vue-flatpickr-component';
    import 'flatPickr/dist/flatpickr.css';
    import {French} from 'flatPickr/dist/l10n/fr';

    import selectpicker  from 'bootstrap-select';
    import 'bootstrap-select/dist/css/bootstrap-select.min.css';

    let data = {
        flatPickrConfig: {
            wrap: true,
            dateFormat: 'd.m.Y H:i',
            enableTime: true,
            time_24hr: true,
            minuteIncrement: 30,
            locale: French
        },
        date_start: 0,
        date_end: 0
    };

    export default {
        props: [],
        data() {
            return data;
        },
        mounted() {
            console.log('Booking create component mounted.');
        },
        components: {
            flatPickr
        }
    }
</script>
