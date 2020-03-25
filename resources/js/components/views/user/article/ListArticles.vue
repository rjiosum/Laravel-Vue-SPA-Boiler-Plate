<template>
    <v-layout row wrap justify-center>
        <v-flex xs12 sm12 md10 lg10 xl9>
            <AppArticles
                    :methodName="methodName"
                    :routeName="routeName"
            ></AppArticles>
        </v-flex>
    </v-layout>
</template>

<script>

    import AppArticles from '@/components/partials/articles/AppArticles';

    export default {
        name: "ArticleList",

        components: {
            AppArticles
        },

        data() {
            return {
               methodName: 'ArticleRepository.index',
               routeName: 'user.view.article'
            }
        }
    }
</script>

<style scoped>

</style>