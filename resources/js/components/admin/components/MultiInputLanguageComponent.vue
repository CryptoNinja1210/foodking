<template>
    <div class="form-col-12 md:form-col-12" v-if="typeof props.text === 'object'" v-for="prop in props.text">
        <MultiInputLanguageComponent :props="{text : prop, formPost : props.formPost}"/>
    </div>
    <div v-else class="form-col-12 md:form-col-12">
        <label for="name" class="db-field-title required">
            {{ props.text }}
        </label>
        <input type="text" class="db-field-control" v-model="props.formPost[props.text.replaceAll(' ', '_')]">
    </div>
</template>

<script>

export default {
    name: "MultiInputLanguageComponent",
    props: ['props']
}
</script>
