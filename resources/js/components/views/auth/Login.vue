<template>
    <v-container fluid grid-list-md>
        <v-layout row wrap justify-center>
            <v-flex xs12 sm12 md5 lg4 xl4>

                <v-list-item two-line class="mt-10 text-center">
                    <v-list-item-content>
                        <v-list-item-title class="font-weight-bold headline">Hello</v-list-item-title>
                        <v-list-item-subtitle class="subtitle-1">
                            Great to see you back. Now login and get started.
                        </v-list-item-subtitle>
                    </v-list-item-content>
                </v-list-item>

                <v-card flat class="card--flex-toolbar">
                    <v-card-text>
                        <v-form @submit.prevent="login">
                            <v-text-field
                                    filled
                                    label="E-mail"
                                    v-model.trim="form.email"
                                    type="email"
                                    background-color="#f3f5f9"
                                    append-icon="mdi-email-outline"
                                    :error-messages="emailErrors"
                                    @input="$v.form.email.$touch()"
                                    @blur="$v.form.email.$touch()"
                            ></v-text-field>
                            <v-text-field
                                    filled
                                    label="Password"
                                    v-model.trim="form.password"
                                    type="password"
                                    background-color="#f3f5f9"
                                    append-icon="mdi-lock-open"
                                    :error-messages="passwordErrors"
                                    @input="$v.form.password.$touch()"
                                    @blur="$v.form.password.$touch()"
                            ></v-text-field>

                            <v-switch v-model="form.remember" :label="`Stay signed in`"></v-switch>

                            <template v-if="verifyEmail">
                                <v-divider></v-divider>
                                <VerifyPrompt :email="form.email"></VerifyPrompt>
                            </template>
                            <v-divider></v-divider>
                            <v-btn depressed tile class="mt-5 blue white--text" type="submit"
                                   :loading="submitted"
                                   :disabled="submitted">Login</v-btn>

                        </v-form>
                    </v-card-text>
                </v-card>

                <v-list-item one-line class="text-center">
                    <v-list-item-content>
                        <div>
                            New?
                            <router-link :to="{ name: 'auth.register' }">Sign Up</router-link>
                            |
                            <router-link :to="{ name: 'password.reset.email' }">Forgot Your Password?</router-link>
                        </div>
                    </v-list-item-content>
                </v-list-item>
            </v-flex>
        </v-layout>
    </v-container>
</template>

<script>
    import {validationMixin} from 'vuelidate'
    import {required, email} from 'vuelidate/lib/validators'
    import {mapGetters} from "vuex";
    import VerifyPrompt from '@/components/partials/VerifyPrompt';


    export default {
        name: "AppLogin",
        components: {VerifyPrompt},
        mixins: [validationMixin],
        validations: {
            form: {
                email: {required, email},
                password: {required}
            }
        },
        data() {
            return {
                form: {
                    email: null,
                    password: null,
                    remember: false
                },
                submitted: false,
                verifyEmail: false
            }
        },
        methods: {

            async login() {
                this.$v.$touch();
                if (this.$v.$invalid) {
                    return;
                }
                this.submitted = false;
                await this.$store.dispatch('auth/login', this.form)
                    .then((response) => {
                        if(response.data.verify){
                            this.verifyEmail = true;
                            this.submitted = false;
                        } else {
                            this.$store.dispatch('notify/setNotice', {
                                msg: "You are successfully logged in!!",
                                color: "success",
                                icon: "mdi-check-bold"
                            });
                            if (this.$route.query.redirect) {
                                this.$router.replace(this.$route.query.redirect);
                                return;
                            }
                            this.$router.replace({
                                name: 'user.dashboard'
                            });
                        }

                    }).catch((error) => {
                        this.submitted = false;
                    });
            }
        },
        computed: {
            ...mapGetters({errors: "errors/errors"}),
            emailErrors() {
                const errors = [];
                if (!this.$v.form.email.$dirty) return errors;
                !this.$v.form.email.email && errors.push('Must be valid e-mail');
                !this.$v.form.email.required && errors.push('E-mail is required');
                this.errors.email && errors.push(this.errors.email[0]);
                return errors;
            },
            passwordErrors() {
                const errors = [];
                if (!this.$v.form.password.$dirty) return errors;
                !this.$v.form.password.required && errors.push('Password is required');
                this.errors.password && errors.push(this.errors.password[0]);
                return errors;
            }
        }
    }
</script>

<style scoped>

</style>
