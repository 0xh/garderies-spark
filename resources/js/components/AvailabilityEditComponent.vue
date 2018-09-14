
<script>
    import flatPickr from 'vue-flatpickr-component';
    import 'flatPickr/dist/flatpickr.css';
    import {French} from 'flatPickr/dist/l10n/fr';

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
        data() {
            return data;
        },
        mounted() {
            console.log('Availability edit component mounted.')
        },
        components: {
            flatPickr
        }
    }
</script>
