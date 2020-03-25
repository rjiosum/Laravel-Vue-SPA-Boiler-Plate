<template>
    <v-container fluid grid-list-md>
        <v-layout row wrap justify-center>
            <v-flex xs12 sm12 md5 lg4 xl4>

                <v-alert
                        v-model="alert"
                        border="left"
                        type="success"
                        dismissible
                >
                    {{message}}
                </v-alert>

                <template v-if="!alert">
                    <v-list-item two-line class="mt-10 text-center">
                        <v-list-item-content>
                            <v-list-item-title class="font-weight-bold headline">Recover</v-list-item-title>
                            <v-list-item-subtitle class="subtitle-1">
                                It happens. Enter your email address and we'll send you a password reset link.
                            </v-list-item-subtitle>
                        </v-list-item-content>
                    </v-list-item>

                    <v-card flat class="card--flex-toolbar">
                        <v-card-text>
                            <v-form @submit.prevent="send">
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

                                <v-btn depressed tile class="blue white--text" type="submit"
                                       :loading="submitted"
                                       :disabled="submitted">Continue
                                </v-btn>
                            </v-form>
                        </v-card-text>
                    </v-card>

                    <v-list-item one-line class="text-center">
                        <v-list-item-content>
                            <div>Remember it?
                                <router-link :to="{ name: 'auth.login' }">
                                    Sign in
                                </router-link>
                            </div>
                        </v-list-item-content>
                    </v-list-item>
                </template>
            </v-flex>
        </v-layout>
    </v-container>
</template>

<script>
    import {validationMixin} from 'vuelidate'
    import {required, email} from 'vuelidate/lib/validators'
    import {mapGetters} from "vuex";

    import {RepositoryFactory} from '@/repositories/RepositoryFactory';

    const AuthRepository = RepositoryFactory.get('auth');

    export default {
        name: "PasswordResetEmail",
        mixins: [validationMixin],
        validations: {
            form: {
                email: {required, email}
            }
        },
        data() {
            return {
                form: {
                    email: null,
                },
                submitted: false,
                alert: false,
                message: null
            }
        },
        methods: {
            async send() {
                this.$v.$touch();
                if (this.$v.$invalid) {
                    return;
                }
                this.submitted = true;
                try {
                    const {data} = await AuthRepository.sendResetPasswordLinkEmail(this.form);
                    this.alert = data.status;
                    this.message = data.message;
                    this.form.email = '';
                    this.submitted = false;
                } catch (e) {
                    this.submitted = false;
                }
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
            }
        }
    }
</script>

<style scoped>

</style>