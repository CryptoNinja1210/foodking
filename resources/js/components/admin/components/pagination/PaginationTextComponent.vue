<template>
    <div>
        <p class="text-sm text-gray-700">
            {{
                $t("message.pagination_label", {
                    from: props.page.from, to: props.page.to,
                    total: props.page.total
                })
            }}
        </p>
    </div>
</template>

<script>
export default {
    name: "PaginationTextComponent",
    props: ['props'],
}
</script>
