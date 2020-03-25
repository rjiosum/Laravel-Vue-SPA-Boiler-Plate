<template>
    <v-layout row wrap justify-center>
        <v-flex xs12 sm12 md10 lg10 xl9>
            <AppArticle :methodName="methodName"></AppArticle>
        </v-flex>
    </v-layout>
</template>

<script>
    import AppArticle from '@/components/partials/articles/AppArticle';

    export default {
        name: "ViewArticle",

        components: {
            AppArticle
        },

        data() {
            return {
                methodName: 'ArticleRepository.show'
            }
        }
    }
</script>

<style scoped>

</style>