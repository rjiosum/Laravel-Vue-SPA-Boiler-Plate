<template>
    <v-layout row wrap justify-center v-if="article">
        <v-flex xs12 class="mt-10">
            <v-row>
                <v-col cols="12" xs="12" sm="12" md="10" lg="10" xl="10">
                    <div class="font-weight-bold display-1" v-text="article.title"></div>
                    <div class="subtitle-2 font-italic" v-text="`Submitted ${article.created}`"></div>
                </v-col>

                <v-col cols="12" xs="12" sm="12" md="2" lg="2" xl="2" class="text-md-right" v-if="authorized(article.user.id)">
                    <v-tooltip top color="#000000">
                        <template v-slot:activator="{ on }">
                            <v-btn v-on="on" fab dark depressed small color="cyan" route
                                   :to="{name: 'user.edit.article', params:{id: article.uuid}}">
                                <v-icon dark>mdi-content-save-edit</v-icon>
                            </v-btn>
                        </template>
                        <span>Edit / Update</span>
                    </v-tooltip>
                </v-col>
            </v-row>
            <v-divider class="mt-3 mb-5"></v-divider>

            <div class="body-1" v-html="article.description"></div>

            <v-divider class="mt-3 mb-5"></v-divider>
            <v-row>
                <v-col cols="12" xs="12" sm="12" md="10" lg="10" xl="10">
                    <v-avatar>
                        <v-img :src="article.user.avatar" class="elevation-1"></v-img>
                    </v-avatar>
                    <v-chip class="ma-2" color="primary" outlined pill>
                        {{article.user.name}}
                        <v-icon right>mdi-account-outline</v-icon>
                    </v-chip>
                    <div class="subtitle-2 font-italic mt-3" v-text="`Created: ${article.createdAt}`"></div>
                    <div class="subtitle-2 font-italic" v-text="`Last Update: ${article.updatedAt}`"></div>
                </v-col>

                <v-col cols="12" xs="12" sm="12" md="2" lg="2" xl="2" class="text-md-right">
                    <v-btn depressed rounded class="red white--text mt-3" @click="back">
                        Back
                    </v-btn>
                </v-col>
            </v-row>

        </v-flex>
    </v-layout>
</template>

<script>
    import {RepositoryFactory} from '@/repositories/RepositoryFactory';
    import {mapGetters} from "vuex";
    const HomeRepository = RepositoryFactory.get('home');
    const ArticleRepository = RepositoryFactory.get('article');

    export default {
        name: "AppArticle",
        props:{
            methodName:{
                type: String,
                default: 'index'
            }
        },
        data() {
            return {
                article: null
            }
        },
        methods: {
            async fetch() {
                const fn = eval(this.methodName);
                if (typeof fn === "function") {
                    await fn(this.$route.params.id)
                        .then((response) => {
                            this.article = response.data;
                            const title = document.querySelector('head title');
                            title.textContent = response.data.title;
                        })
                        .catch(error => {});
                }
            },
            authorized(articleUserId) {
                const userId = (this.user) ? this.user.id : -1;
                return (userId === articleUserId);
            },
            back() {
                this.$router.back();
            }
        },
       created() {
            this.fetch();
        },
        computed: {
            ...mapGetters({
                user: 'auth/user'
            })
        }

    }
</script>

<style scoped>
    .body-1{line-height: 2rem}
</style>