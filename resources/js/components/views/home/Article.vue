<template>
    <div>
        <v-container fluid grid-list-md>
            <v-layout row wrap justify-center>
                <v-flex xs12 sm12 md10 lg10 xl9>
                    <AppArticle :methodName="methodName"></AppArticle>
                </v-flex>
            </v-layout>
        </v-container>
    </div>
</template>

<script>
    import AppArticle from '@/components/partials/articles/AppArticle';

    export default {
        name: "AppHome",
        components: {
            AppArticle
        },
        data() {
            return {
                methodName: 'HomeRepository.show'
            }
        }
    }
</script>

<style scoped>

</style>