<template>
    <v-card flat outlined background="#ffffff" class="mb-10">
        <v-card-title class="mb-4">
            <v-layout row wrap>
                <v-flex xs12 sm12 md10>
                    <div>
                        <div class="headline article-title">{{article.title}}</div>
                        <span class="grey--text subtitle-2">Submitted {{article.created}}</span>
                    </div>
                </v-flex>
                <v-flex xs12 sm12 md2>
                    <div class="text-md-right">
                        <template v-if="authorized(article.user.id)">
                            <v-tooltip top color="#000000">
                                <template v-slot:activator="{ on }">
                                    <v-btn v-on="on" fab dark depressed small
                                           color="teal" route :to="{name: 'user.view.article', params:{id: article.slug}}">
                                        <v-icon dark>mdi-view-split-vertical</v-icon>
                                    </v-btn>
                                </template>
                                <span>View Details</span>
                            </v-tooltip>

                            <v-tooltip top color="#000000">
                                <template v-slot:activator="{ on }">
                                    <v-btn v-on="on" fab dark depressed small color="cyan"
                                           route :to="{name: 'user.edit.article', params:{id: article.uuid}}">
                                        <v-icon dark>mdi-content-save-edit</v-icon>
                                    </v-btn>
                                </template>
                                <span>Edit / Update</span>
                            </v-tooltip>

                            <v-tooltip top color="#000000">
                                <template v-slot:activator="{ on }">
                                    <v-btn :id="article.uuid" v-on="on" fab dark depressed small color="red"
                                           @click="deleteArticle(article.uuid)">
                                        <v-icon dark>mdi-delete</v-icon>
                                    </v-btn>
                                </template>
                                <span>Delete</span>
                            </v-tooltip>
                        </template>
                    </div>
                </v-flex>
            </v-layout>
        </v-card-title>

        <v-card-text>
            <span class="body-2" v-html="description(article.description)"></span>
            <router-link :to="{name: routeName, params:{id: article.slug}}">
                Read More
            </router-link>
        </v-card-text>

        <v-card-title>
            <v-avatar>
                <v-img :src="article.user.avatar" class="elevation-1"></v-img>
            </v-avatar>
            <v-spacer></v-spacer>
            <v-chip class="ma-2" color="primary" outlined pill>
                {{article.user.name}}
                <v-icon right>mdi-account-outline</v-icon>
            </v-chip>
        </v-card-title>

    </v-card>
</template>

<script>
    import {mapGetters} from "vuex";

    export default {
        name: "AppArticlesInner",
        props: ['article', 'routeName'],
        methods: {
            description(text) {
                let body = this.stripTags(text);
                return body.length > 300 ? body.substring(0, 300) + '...' : body;
            },
            stripTags(text) {
                return text.replace(/(<([^>]+)>)/ig, '');
            },
            deleteArticle(uuid) {
                this.$emit('deleteArticle', uuid);
            },
            authorized(articleUserId) {
                const userId = (this.user) ? this.user.id : -1;
                return (userId === articleUserId);
            }
        },
        computed: {
            ...mapGetters({
                user: 'auth/user'
            })
        }
    }
</script>

<style scoped>

</style>