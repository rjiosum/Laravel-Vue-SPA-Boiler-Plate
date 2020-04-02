<template>
    <v-layout row wrap justify-center>
        <v-flex xs12 class="mt-10">
            <v-card flat outlined class="mb-10">
                <v-card-text>
                    <v-layout row wrap>
                        <v-flex sx9>
                            <v-pagination
                                    v-model="pagination.page"
                                    :length="pagination.total"
                                    :total-visible="7"
                                    @input="next"
                                    circle
                                    color="black"
                                    v-if="articlesCount > 0"
                            ></v-pagination>
                        </v-flex>
                        <v-flex sx3>
                            <div class="text-right mt-1">
                                <v-btn class="blue white--text" depressed rounded route
                                       :to="{name: 'user.create.article'}">
                                    <v-icon dark left>mdi-fountain-pen</v-icon>
                                    Create Article
                                </v-btn>
                            </div>
                        </v-flex>
                    </v-layout>
                </v-card-text>
            </v-card>

            <template v-if="articlesCount > 0">
                <div class="article-lists">
                    <AppArticlesInner
                            v-for="(article) in articles"
                            :key="article.uuid"
                            :article="article"
                            :routeName="routeName"
                            @deleteArticle="deleteArticle"
                    ></AppArticlesInner>
                </div>
            </template>

            <div v-cloak v-if="articlesCount === 0" class="display-1 font-weight-bold text-center">No articles found!!
            </div>

            <v-card flat outlined class="mt-10" v-if="articlesCount > 0">
                <v-card-text>
                    <v-pagination
                            v-model="pagination.page"
                            :length="pagination.total"
                            :total-visible="7"
                            @input="next"
                            circle
                            color="black"
                    ></v-pagination>
                </v-card-text>
            </v-card>
        </v-flex>
        <AppConfirm ref="confirm"></AppConfirm>
    </v-layout>
</template>

<script>
    import AppArticlesInner from './AppArticlesInner';
    import AppConfirm from "../AppConfirm";

    import {RepositoryFactory} from '@/repositories/RepositoryFactory';

    const HomeRepository = RepositoryFactory.get('home');
    const ArticleRepository = RepositoryFactory.get('article');

    export default {
        name: "AppArticles",
        components: {
            AppArticlesInner,
            AppConfirm
        },
        props: {
            methodName: {
                type: String,
                default: ''
            },
            routeName: {
                type: String,
                default: ''
            }
        },
        data() {
            return {
                articles: null,
                articlesCount: -1,
                pagination: {
                    page: parseInt(this.$route.query.page) || 1,
                    total: 0
                }
            }
        },
        methods: {
            async fetch() {
                const fn = eval(this.methodName);
                if (typeof fn === "function") {
                    await fn(this.pagination.page)
                        .then((response) => {
                            this.articles = response.data.data;
                            this.pagination.page = response.data.meta.current_page;
                            this.pagination.total = response.data.meta.last_page;
                            this.articlesCount = response.data.meta.total;
                        })
                        .catch(error => {
                        });
                }
            },
            next() {
                this.fetch();
                this.$router.push({query: {page: this.pagination.page}});
            },
            deleteArticle(uuid) {
                this.$refs.confirm.open('Delete', 'Are you sure?', {color: 'red'}).then((confirm) => {
                    if (confirm) {
                        ArticleRepository.delete(uuid)
                            .then((response) => {
                                this.$store.dispatch('notify/setNotice', {
                                    msg: response.data.message,
                                    color: "success",
                                    icon: "mdi-check-bold"
                                });
                                this.fetch();
                            })
                            .catch(error => {
                            });
                    }
                })
            }
        },
        created() {
            this.fetch();
        }
    }
</script>

<style scoped>

</style>