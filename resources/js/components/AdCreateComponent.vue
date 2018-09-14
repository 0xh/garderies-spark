<script>
    import Editor from '@tinymce/tinymce-vue';

    let data = {};

    export default {
        props: [],
        data() {
            return data;
        },
        mounted() {
            console.log('Ad create component mounted.');

        },
        components: {
            'editor': Editor
        }
    }
</script>
