<template>
    <div>
        <v-app-bar app class="app-header" color="#f5f8fa">
            <v-app-bar-nav-icon @click="drawer = !drawer"></v-app-bar-nav-icon>
            <v-toolbar-title app-data class="text-uppercase">
                <router-link :to="{name: 'home'}">
                    <v-icon>mdi-comment-question-outline</v-icon>
                    <span class="font-weight-light grey--text">spa</span><span class="blue--text">boilerplate</span>
                </router-link>
            </v-toolbar-title>
            <v-spacer></v-spacer>

            <v-toolbar-items>

                <template v-if="!authenticated">
                    <v-btn text color="grey" router :to="{ name: 'auth.login'}">Login
                        <v-icon right>mdi-lock-open</v-icon>
                    </v-btn>
                    <v-btn text color="grey" router :to="{ name: 'auth.signup'}">SignUp
                        <v-icon right>mdi-account</v-icon>
                    </v-btn>
                </template>
                <template v-else>
                    <v-menu min-width="150" open-on-hover offset-y>
                        <template v-slot:activator="{ on }">
                            <v-btn text color="grey" v-on="on">
                                <span><v-avatar><img :src="user.avatar"></v-avatar> {{user.name}}</span>
                                <v-icon right>mdi-chevron-down</v-icon>
                            </v-btn>
                        </template>

                        <v-list>
                            <v-list-item route :to="{name: 'user.dashboard'}">
                                <v-list-item-content>
                                    <v-list-item-title><v-icon left>mdi-home-city</v-icon>Dashboard</v-list-item-title>
                                </v-list-item-content>
                            </v-list-item>
                            <v-divider></v-divider>
                            <v-list-item route :to="{name: 'auth.logout'}">
                                <v-list-item-content>
                                    <v-list-item-title><v-icon left>mdi-lock-open</v-icon>Logout</v-list-item-title>
                                </v-list-item-content>
                            </v-list-item>
                        </v-list>
                    </v-menu>
                </template>
            </v-toolbar-items>
        </v-app-bar>

        <v-navigation-drawer
                app
                v-model="drawer"
                src="/images/side-bar.png"
                dark>
            <v-list>
                <v-list-item two-line>
                    <v-list-item-avatar>
                        <img src="/images/male-user.png">
                    </v-list-item-avatar>
                    <v-list-item-content>
                        <v-list-item-title>Boiler Plate</v-list-item-title>
                        <v-list-item-subtitle>SPA</v-list-item-subtitle>
                    </v-list-item-content>
                </v-list-item>
            </v-list>
            <v-divider></v-divider>
            <template v-if="!authenticated">
                <v-list>
                    <v-list-item router :to="{ name: 'auth.login'}">
                        <v-list-item-action>
                            <v-icon class="white--text">mdi-lock-open</v-icon>
                        </v-list-item-action>
                        <v-list-item-content>
                            <v-list-item-title class="white--text">Login</v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>

                    <v-list-item router :to="{ name: 'auth.signup'}">
                        <v-list-item-action>
                            <v-icon class="white--text">mdi-account</v-icon>
                        </v-list-item-action>
                        <v-list-item-content>
                            <v-list-item-title class="white--text">SignUp</v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>
                </v-list>
            </template>
            <template v-else>
                <v-list>
                    <v-list-item route :to="{name: 'user.dashboard'}">
                        <v-list-item-action>
                            <v-icon class="white--text">mdi-home-city</v-icon>
                        </v-list-item-action>
                        <v-list-item-content>
                            <v-list-item-title class="white--text">Dashboard</v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>
                    <v-divider></v-divider>
                    <v-list-item route :to="{name: 'auth.logout'}">
                        <v-list-item-action>
                            <v-icon class="white--text">mdi-lock-open</v-icon>
                        </v-list-item-action>
                        <v-list-item-content>
                            <v-list-item-title class="white--text">Logout</v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>
                </v-list>
            </template>
        </v-navigation-drawer>


    </div>
</template>

<script>
    import {mapGetters} from "vuex";

    export default {
        name: "AppToolbar",
        data() {
            return {
                drawer: false
            }
        },
        computed: {
            ...mapGetters({
                authenticated: 'auth/authenticated',
                user: 'auth/user'
            })
        },
        created() {
            EventBus.$on('kick', () => {
                this.$store.dispatch('auth/logout')
                    .then((response) => {
                        this.$store.dispatch('notify/setNotice', {
                            msg:"You are successfully logged out!!",
                            color: "info",
                            icon: "mdi-check-bold"
                        });
                        this.$router.back();
                    })
                    .catch((error) => {
                        window.location = '/login';
                    });
            });
        }
    }
</script>
<style scoped>
    .app-header {
        -webkit-box-shadow: none;
        box-shadow: none;
        background-color: rgb(255, 255, 255) !important;
        border-color: rgb(255, 255, 255) !important;
    }
    .app-header .v-toolbar__title{font-family: Roboto,sans-serif;}
</style>