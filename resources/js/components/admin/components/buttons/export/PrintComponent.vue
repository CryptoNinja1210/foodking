<template>
    <button v-print="props" class="db-card-filter-dropdown-menu">
        <i class="lab lab-printer-line lab-font-size-17"></i>
        {{ $t('button.print') }}
    </button>
</template>

<script>
import print from "vue3-print-nb";

export default {
    name: "PrintComponent",
    props: ["props"],
    directives: {
        print
    }
}
</script>
