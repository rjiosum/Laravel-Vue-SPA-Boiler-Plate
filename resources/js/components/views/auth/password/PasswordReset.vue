<template>
    <v-container fluid grid-list-md>
        <v-layout row wrap justify-center>
            <v-flex xs12 sm12 md5 lg5 xl4>

                <v-list-item one-line class="mt-10 text-center">
                    <v-list-item-content>
                        <v-list-item-title class="font-weight-bold headline">Reset your password.</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>

                <v-card flat>
                    <v-card-text>
                        <v-form @submit.prevent="reset">
                            <v-text-field
                                    filled
                                    label="E-mail"
                                    v-model.trim="form.email"
                                    type="email"
                                    name="email"
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
                                    name="password"
                                    background-color="#f3f5f9"
                                    append-icon="mdi-lock-open"
                                    :error-messages="passwordErrors"
                                    @input="$v.form.password.$touch()"
                                    @blur="$v.form.password.$touch()"
                            ></v-text-field>
                            <v-text-field
                                    filled
                                    label="Confirm Password"
                                    v-model.trim="form.password_confirmation"
                                    type="password"
                                    name="password_confirmation"
                                    background-color="#f3f5f9"
                                    append-icon="mdi-lock-open"
                                    :error-messages="confirmPasswordErrors"
                                    @input="$v.form.password_confirmation.$touch()"
                                    @blur="$v.form.password_confirmation.$touch()"
                            ></v-text-field>

                            <v-alert v-model="alert" border="left" type="success" dismissible>
                                {{message}}
                            </v-alert>

                            <v-btn depressed tile class="blue white--text" type="submit"
                                   :loading="submitted"
                                   :disabled="submitted">Reset
                            </v-btn>
                        </v-form>
                    </v-card-text>
                </v-card>
            </v-flex>
        </v-layout>
    </v-container>
</template>

<script>
    import {validationMixin} from 'vuelidate';
    import {required, minLength, email, sameAs} from 'vuelidate/lib/validators';
    import {mapGetters} from 'vuex';
    import {RepositoryFactory} from '@/repositories/RepositoryFactory';

    const AuthRepository = RepositoryFactory.get('auth');

    export default {
        name: "PasswordReset",
        mixins: [validationMixin],
        validations: {
            form: {
                email: {required, email},
                password: {required, minLength: minLength(6)},
                password_confirmation: {required, sameAsPassword: sameAs('password')}
            }
        },
        data() {
            return {
                form: {
                    token: '',
                    email: '',
                    password: '',
                    password_confirmation: ''
                },
                submitted: false,
                alert: false,
                message: null
            }
        },
        created() {
            this.form.email = this.$route.query.email;
            this.form.token = this.$route.params.token;
        },
        methods: {
            async reset() {
                this.$v.$touch();
                if (this.$v.$invalid) {
                    return;
                }
                this.submitted = true;
                try {
                    const {data} = await AuthRepository.resetPassword(this.form);
                    this.alert = data.status;
                    this.message = data.message;
                    this.submitted = false;
                } catch (e) {
                    this.submitted = false;
                }
            }
        },
        computed: {
            ...mapGetters({
                errors: 'errors/errors'
            }),
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
                !this.$v.form.password.minLength && errors.push('Password should be more than 6 characters');
                this.errors.password && errors.push(this.errors.password[0]);
                return errors;
            },
            confirmPasswordErrors() {
                const errors = [];
                if (!this.$v.form.password_confirmation.$dirty) return errors;
                !this.$v.form.password_confirmation.required && errors.push('Please confirm your password');
                !this.$v.form.password_confirmation.sameAsPassword && errors.push('Password and confirm password do not match');
                this.errors.password_confirmation && errors.push(this.errors.password_confirmation[0]);
                return errors;
            },
        }
    }
</script>

<style scoped>

</style>