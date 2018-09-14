<template>
    <ul class="pagination justify-content-center">
        <li :class="['page-item', isOnFirstPage ? 'disabled' : '']">
            <a class="page-link" href="" @click.prevent="loadPage(1)">
                <i v-if="css.icons.first != ''" :class="[css.icons.first]"></i>
                <span v-else>&laquo;</span>
            </a>
        </li>
        <li :class="['page-item', isOnFirstPage ? 'disabled' : '']">
            <a class="page-link" href="" @click.prevent="loadPage('prev')">
                <i v-if="css.icons.next != ''" :class="[css.icons.prev]"></i>
                <span v-else>&nbsp;&lsaquo;</span>
            </a>
        </li>
        <template v-if="notEnoughPages">
            <li v-for="n in totalPage" :class="['page-item', isCurrentPage(n) ? 'active' : '']">
                <a class="page-link" @click.prevent="loadPage(n)" v-html="n"></a>
            </li>
        </template>
        <template v-else>
            <li v-for="n in windowSize" :class="['page-item', isCurrentPage(windowStart+n-1) ? 'active' : '']">
                <a class="page-link" @click.prevent="loadPage(windowStart+n-1)" v-html="windowStart+n-1"></a>
            </li>
        </template>

        <li :class="['page-item', isOnLastPage ? 'disabled' : '']">
            <a class="page-link" href="" @click.prevent="loadPage('next')">
                <i v-if="css.icons.next != ''" :class="[css.icons.next]"></i>
                <span v-else>&rsaquo;&nbsp;</span>
            </a>
        </li>
        <li :class="['page-item', isOnLastPage ? 'disabled' : '']">
            <a class="page-link" href="" @click.prevent="loadPage(totalPage)">
                <i v-if="css.icons.last != ''" :class="[css.icons.last]"></i>
                <span v-else>&raquo;</span>
            </a>
        </li>
    </ul>
</template>

<script>
    import VuetablePaginationMixin from 'vuetable-2/src/components/VuetablePaginationMixin'
    export default {
        mixins: [VuetablePaginationMixin]
    }
</script>