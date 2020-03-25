<template>
    <v-app>
        <AppNotify />

        <AppToolbar />

        <v-content>
            <transition name="fade" mode="out-in">
                <router-view></router-view>
            </transition>
        </v-content>

        <v-btn v-scroll="onScroll" v-show="fab" fab dark fixed bottom right color="primary" @click="toTop">
            <v-icon>mdi-chevron-up</v-icon>
        </v-btn>

        <AppFooter />

    </v-app>
</template>

<script>
    import AppToolbar from '../partials/AppToolbar';
    import AppFooter from '../partials/AppFooter';
    import AppNotify from '../partials/AppNotify';
    export default {
        name: "app-default",
        components:{
            AppNotify,
            AppToolbar,
            AppFooter
        },
        data: () => ({
            fab: false
        }),
        methods: {
            onScroll (e) {
                if (typeof window === 'undefined') return;
                const top = window.pageYOffset ||   e.target.scrollTop || 0;
                this.fab = top > 20;
            },
            toTop () {
                this.$vuetify.goTo(0);
            }
        }
    }
</script>