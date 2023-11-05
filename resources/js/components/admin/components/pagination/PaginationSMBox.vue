<template>
    <div class="flex flex-1 justify-between sm:hidden">
        <TailwindPagination
            :data="pagination"
            @pagination-change-page="page"
            :active-classes="activeClass"
            :limit="-1"
        />

<!--        <button type="button" @click="page(pagination.meta.form)"-->
<!--                class="relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">-->
<!--            Previous-->
<!--        </button>-->
<!--        <button type="button" @click="page(pagination.meta.to)"-->
<!--                class="relative ml-3 inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">-->
<!--            Next-->
<!--        </button>-->
    </div>
</template>
<script>

import {TailwindPagination} from 'laravel-vue-pagination';

export default {
    name: "PaginationSMBox",
    components: {TailwindPagination},
    props: {
        pagination: {type: Object},
        method: {type: Function}
    },
    data() {
        return {
            activeClass: [
                "bg-blue-50",
                "border-blue-500",
                "text-primary"
            ]
        }
    },
    methods: {
        page: function (page = 1) {
            this.method(page);
        }
    }
}
</script>
