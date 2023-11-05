<template>
    <div v-if="page.total > 10" class="db-field-down-arrow">
        <select @change="limitChange" v-model="search.per_page" class="db-card-filter-select">
            <option value="10">{{ $t('number.10') }}</option>
            <option value="25">{{ $t('number.25') }}</option>
            <option value="50">{{ $t('number.50') }}</option>
            <option value="100">{{ $t('number.100') }}</option>
            <option value="500">{{ $t('number.500') }}</option>
            <option value="1000">{{ $t('number.1000') }}</option>
        </select>
    </div>
</template>


<script>

export default {
    name: "TableLimitComponent",
    props: {
        page: {type: Object},
        search: {type: Object},
        method: {type: Function}
    },
    methods: {
        limitChange: function () {
            this.method();
        }
    }
}
</script>
