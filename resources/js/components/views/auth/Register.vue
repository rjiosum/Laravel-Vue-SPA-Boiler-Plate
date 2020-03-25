<template>
    <v-container fluid grid-list-md>
        <v-layout row wrap justify-center>
            <v-flex xs12 sm12 md5 lg5 xl4>
                <template v-if="verifyEmail">
                    <VerifyPrompt :email="form.email"></VerifyPrompt>
                </template>
                <template v-else>
                    <v-list-item one-line class="mt-10 text-center">
                        <v-list-item-content>
                            <v-list-item-title class="font-weight-bold headline">First, let's get you signed up.</v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>

                    <v-card flat>
                        <v-card-text>
                            <v-form @submit.prevent="signup">
                                <v-text-field
                                        filled
                                        label="First Name"
                                        v-model.trim="form.first_name"
                                        type="text"
                                        background-color="#f3f5f9"
                                        append-icon="mdi-account"
                                        :error-messages="firstNameErrors"
                                        @input="$v.form.first_name.$touch()"
                                        @blur="$v.form.first_name.$touch()"
                                ></v-text-field>
                                <v-text-field
                                        filled
                                        label="Last Name"
                                        v-model.trim="form.last_name"
                                        type="text"
                                        background-color="#f3f5f9"
                                        append-icon="mdi-account"
                                        :error-messages="lastNameErrors"
                                        @input="$v.form.last_name.$touch()"
                                        @blur="$v.form.last_name.$touch()"
                                ></v-text-field>
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
                                <v-text-field
                                        filled
                                        label="Confirm Password"
                                        v-model.trim="form.password_confirmation"
                                        type="password"
                                        background-color="#f3f5f9"
                                        append-icon="mdi-lock-open"
                                        :error-messages="confirmPasswordErrors"
                                        @input="$v.form.password_confirmation.$touch()"
                                        @blur="$v.form.password_confirmation.$touch()"
                                ></v-text-field>
                                <v-btn depressed tile class="blue white--text" type="submit"
                                       :loading="submitted"
                                       :disabled="submitted">Sign Up</v-btn>
                            </v-form>
                        </v-card-text>
                    </v-card>

                    <v-list-item one-line class="text-center">
                        <v-list-item-content>
                            <div>Already Have account? <router-link :to="{ name: 'auth.login' }">Sign in</router-link></div>
                        </v-list-item-content>
                    </v-list-item>
                </template>
            </v-flex>
        </v-layout>
    </v-container>
</template>

<script>
    import {validationMixin} from 'vuelidate';
    import {required, maxLength, minLength, email, sameAs} from 'vuelidate/lib/validators';
    import {mapGetters} from 'vuex';
    import VerifyPrompt from '@/components/partials/VerifyPrompt';

    export default {
        name: "AppSignUp",
        components: {VerifyPrompt},
        mixins: [validationMixin],
        validations: {
            form: {
                first_name: {required, maxLength: maxLength(100)},
                last_name: {required, maxLength: maxLength(100)},
                email: {required, email},
                password: {required, minLength: minLength(8)},
                password_confirmation: { required, sameAsPassword: sameAs('password') }
            }
        },
        data() {
            return {
                form: {
                    avatar: true,
                    first_name: '',
                    last_name: '',
                    email: '',
                    password: '',
                    password_confirmation: ''
                },
                submitted: false,
                verifyEmail: false
            }
        },
        methods: {
            async signup () {
                this.$v.$touch();
                if (this.$v.$invalid) {
                    return;
                }
                this.submitted = true;
                await this.$store.dispatch('auth/register', this.form)
                    .then((response) => {
                        if(response.data.verify){
                            this.verifyEmail = true;

                        } else {
                            this.$store.dispatch('notify/setNotice', {
                                msg:"You are successfully registered!!",
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
                            this.submitted = false;
                        }
                    }).catch((error) => {
                        this.submitted = false;
                    });
            }
        },
        computed: {
            ...mapGetters({
                errors: 'errors/errors'
            }),
            firstNameErrors() {
                const errors = [];
                if (!this.$v.form.first_name.$dirty) return errors;
                !this.$v.form.first_name.required && errors.push('First Name is required');
                !this.$v.form.first_name.maxLength && errors.push('First Name cannot be more than 100 characters');
                this.errors.first_name && errors.push(this.errors.first_name[0]);
                return errors;
            },
            lastNameErrors() {
                const errors = [];
                if (!this.$v.form.last_name.$dirty) return errors;
                !this.$v.form.last_name.required && errors.push('Last Name is required');
                !this.$v.form.last_name.maxLength && errors.push('Last Name cannot be more than 100 characters');
                this.errors.last_name && errors.push(this.errors.last_name[0]);
                return errors;
            },
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