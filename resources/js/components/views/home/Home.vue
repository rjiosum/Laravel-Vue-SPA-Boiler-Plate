<template>
    <div>
        <v-parallax dark src="/images/background.jpg">
            <v-row align="center" justify="center">
                <v-col class="text-center" cols="12">
                    <h1 class="display-2 font-weight-bold mb-3">Boiler Plate For SPA</h1>
                    <h4 class="headline">Laravel 7.2, Laravel Passport, Vue, Vuetify</h4>
                </v-col>
            </v-row>
        </v-parallax>
        <v-container fluid grid-list-md>
            <v-layout row wrap justify-center>
                <v-flex xs12 sm12 md10 lg10 xl9>
                    <AppArticles
                            :methodName="methodName"
                            :routeName="routeName"
                    ></AppArticles>
                </v-flex>
            </v-layout>
        </v-container>
    </div>
</template>

<script>
    import AppArticles from '@/components/partials/articles/AppArticles';

    export default {
        name: "AppHome",
        components: {
            AppArticles
        },
        data() {
            return {
                methodName: 'HomeRepository.index',
                routeName: 'home.article'
            }
        }
    }
</script>

<style scoped>

</style>