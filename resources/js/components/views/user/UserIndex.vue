<template>
    <v-container fluid grid-list-md>
        <v-layout row wrap justify-center>
            <v-flex xs12 sm12 md10 lg10 xl9>
                <v-tabs background-color="white" color="#2196f3" right class="mb-5">
                    <v-tab router :to="{name: 'user.dashboard'}"><v-icon left>mdi-home-city</v-icon></v-tab>
                    <v-spacer></v-spacer>
                    <v-tab router :to="{name: 'user.profile'}"><v-icon left>mdi-account-edit</v-icon>Profile</v-tab>
                    <v-tab router :to="{name: 'user.password'}"><v-icon left>mdi-lock-open</v-icon>Password</v-tab>
                    <v-tab router :to="{name: 'user.avatar'}"><v-icon left>mdi-camera</v-icon>Avatar</v-tab>
                    <v-tab router :to="{name: 'user.articles'}"><v-icon left>mdi-fountain-pen</v-icon>Articles</v-tab>
                </v-tabs>

                <transition :name="transitionName">
                    <router-view class="child-view"></router-view>
                </transition>

            </v-flex>
        </v-layout>
    </v-container>
</template>

<script>
    export default {
        name: "User",
        data () {
            return {
                transitionName: 'slide-left'
            }
        },
        beforeRouteUpdate (to, from, next) {
            const toDepth = to.path.split('/').length;
            const fromDepth = from.path.split('/').length;
            this.transitionName = toDepth < fromDepth ? 'slide-right' : 'slide-left';
            next()
        }
    }
</script>

<style scoped>

</style>