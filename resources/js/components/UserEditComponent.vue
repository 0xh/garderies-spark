<script>
    import {mask} from 'vue-the-mask';
    import flatPickr from 'vue-flatpickr-component';
    import 'flatPickr/dist/flatpickr.css';
    import {French} from 'flatPickr/dist/l10n/fr';

    let data = {
        flatPickrConfig: {
            wrap: true,
            dateFormat: 'd.m.Y',
            enableTime: false,
            locale: French,
        },
        date: 0,
        date_end: 0
    };

    export default {
        data() {
            return data;
        },
        mounted() {},
        methods: {},
        directives: {mask},
        components: {flatPickr}
    }
</script>
