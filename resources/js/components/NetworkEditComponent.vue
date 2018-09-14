<script>
    import Swatches from 'vue-swatches';
    import 'vue-swatches/dist/vue-swatches.min.css';

    let data = {
        color: null
    };

    export default {
        props: ['currentColor', 'user', 'currentTeam'],
        data() {
            return data;
        },
        mounted() {
            console.log(Spark.currentTeam);
            data.color = this.currentColor;
        },
        components: {
            Swatches
        }
    }
</script>
