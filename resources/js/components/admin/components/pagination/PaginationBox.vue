<template>
    <div>
        <TailwindPagination
            :data="pagination"
            @pagination-change-page="page"
            :active-classes="activeClass"
            :limit="1"
        />
    </div>
</template>

<script>

import { TailwindPagination } from 'laravel-vue-pagination';

export default {
    name: "PaginationBox",
    components: {TailwindPagination},
    props: {
        pagination: {type: Object},
        method: {type: Function}
    },
    data() {
        return {
            activeClass : [
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
