<script>
    import {mask} from 'vue-the-mask';

    let data = {};

    export default {
        data() {
            return data;
        },
        mounted() {
            console.log('Nursery edit component mounted.');
        },
        methods: {},
        directives: {mask}
    }
</script>
