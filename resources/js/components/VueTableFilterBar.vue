<template>

    <div class="float-sm-right">
        <div class="input-group form-group mb-0  mt-3 mt-sm-0">
            <input type="search" v-model="filterText" class="form-control" aria-describedby="basic-addon2"
                   @keyup.enter="doFilter">
            <div class="input-group-append">
                <button class="btn btn-outline-secondary" type="button" @click.prevent="doFilter"><i
                        class="fa fa-search text-light"></i></button>
            </div>
        </div>
    </div>
</template>

<script>

    export default {
        data: function() { return {filterText: ''} },
        methods: {
            doFilter() {
                this.$emit('filter', this.filterText);
                //this.$events.fire('filter-set', this.filterText);
            }
        }
    }
</script>
<style>
    .filter-bar {
        padding: 10px;
    }
</style>
