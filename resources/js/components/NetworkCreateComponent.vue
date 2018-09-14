<script>
    import Swatches from 'vue-swatches';
    import 'vue-swatches/dist/vue-swatches.min.css';

    let data = {
        color: null
    };

    export default {
        props: ['currentColor'],
        data() {
            return data;
        },
        mounted() {
            data.color = this.currentColor;
        },
        components: {
            Swatches
        }
    }
</script>

<style lang="scss">
    .vue-swatches__container,
    .vue-swatches__wrapper {
        padding: 0 !important;
    }
</style>
