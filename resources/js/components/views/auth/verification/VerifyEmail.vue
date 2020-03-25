<template>
    <v-container fluid grid-list-md>
        <v-layout row wrap justify-center>
            <v-flex xs12 sm12 md5 lg4 xl4>
                <template v-if="verify.success">
                    <v-card flat>
                        <v-card-text>
                            <v-alert v-model="verify.success"
                                     color="#2A3B4D"
                                     dark
                                     icon="mdi-vuetify"
                                     border="left"
                                     prominent
                            >
                                {{verify.message}}
                            </v-alert>
                            <div class="text-right">
                                <v-btn depressed rounded class="mt-1 blue white--text" router :to="{ name: 'auth.login'}">
                                    Proceed with Login
                                </v-btn>
                            </div>
                        </v-card-text>
                    </v-card>
                </template>
                <template v-else-if="verify.error">
                    <v-card flat>
                        <v-card-text>
                            <v-alert v-model="verify.error"
                                     color="#C51162"
                                     dark
                                     icon="mdi-vuetify"
                                     border="left"
                                     prominent
                            >
                                {{verify.message}}
                            </v-alert>
                            <div class="text-right">
                                <v-btn depressed rounded class="mt-1 blue white--text" router :to="{ name: 'verification.resend'}">
                                    Resend Verification Link
                                </v-btn>
                            </div>
                        </v-card-text>
                    </v-card>
                </template>
            </v-flex>
        </v-layout>
    </v-container>
</template>

<script>
    import {RepositoryFactory} from '@/repositories/RepositoryFactory';
    const AuthRepository = RepositoryFactory.get('auth');

    const queryString = (params) => Object.keys(params).map(key => key + '=' + params[key]).join('&');

    export default {
        name: "VerifyEmail",

        data() {
            return {
                verify: {
                    message: '',
                    success: false,
                    error: false
                }
            }
        },

        async beforeRouteEnter(to, from, next) {
           try {
                const {data} = await AuthRepository.verifyEmail(to.params.id, to.params.hash, queryString(to.query));

                if(data.status){
                    next(vm => {
                        vm.verify.success = true;
                        vm.verify.message = data.message;
                    });
                } else {
                    next(vm => {
                        vm.verify.error = true;
                        vm.verify.message = data.message;
                    });
                }

            } catch (e) {
               next(vm => {
                   vm.verify.error = true;
                   vm.verify.message = e.response.data.message;
               })
            }
        },

    }
</script>

<style scoped>

</style>