<template>

</template>

<script>
    export default {
        name: "AppLogout",
        created() {
            EventBus.$emit('kick')
        }

    }

</script>


