<template>
    <v-layout row wrap justify-center>
        <v-flex xs12 sm12 md10 lg6 xl6>
            <v-list-item one-line class="mt-5 text-center">
                <v-list-item-content>
                    <v-list-item-title class="font-weight-bold headline">Update your password.</v-list-item-title>
                </v-list-item-content>
            </v-list-item>

            <v-card flat>
                <v-card-text>
                    <v-list one-line>
                        <v-list-item>
                            <v-list-item-avatar>
                                <v-img :src="user.avatar"></v-img>
                            </v-list-item-avatar>
                            <v-list-item-content>
                                <v-list-item-title class="title">{{user.name}}</v-list-item-title>
                                <v-list-item-subtitle>{{user.email}}</v-list-item-subtitle>
                            </v-list-item-content>
                        </v-list-item>
                    </v-list>
                    <v-divider class="mb-10"></v-divider>

                    <v-form @submit.prevent="update">
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
                        <v-alert tile dismissible outlined border="left" v-model="submit.status" :type="submit.type">
                            {{submit.message}}
                        </v-alert>
                        <v-btn depressed rounded class="blue white--text mt-3" type="submit"
                               :loading="submitted"
                               :disabled="submitted">Update
                        </v-btn>
                    </v-form>
                </v-card-text>
            </v-card>
        </v-flex>
    </v-layout>
</template>

<script>
    import {validationMixin} from 'vuelidate';
    import {required, minLength, sameAs} from 'vuelidate/lib/validators';
    import {mapGetters} from 'vuex';
    import {RepositoryFactory} from '@/repositories/RepositoryFactory';
    const UserRepository = RepositoryFactory.get('user');

    export default {
        name: "UserPassword",
        mixins: [validationMixin],
        validations: {
            form: {
                password: {required, minLength: minLength(8)},
                password_confirmation: {required, sameAsPassword: sameAs('password')}
            }
        },
        data() {
            return {
                form: {
                    password: '',
                    password_confirmation: ''
                },
                submitted: false,
                submit: {
                    status: false,
                    type: 'success',
                    message: ''
                },
            }
        },
        methods: {
            async update() {
                this.$v.$touch();
                if (this.$v.$invalid) {
                    return;
                }
                this.submitted = true;

                try {
                    const {data} = await UserRepository.updatePassword(this.form);
                    this.submit.message = data.message;
                    this.submit.status = true;

                    if (data.status) {
                        this.submit.type = "success";
                    } else {
                        this.submit.type = "error";
                    }

                    this.submitted = false;
                    Object.keys(this.form).forEach((key) => {
                        this.form[key] = '';
                    });
                    this.$v.$reset();

                } catch (error) {
                    this.submitted = false;
                }
            }
        },
        computed: {
            ...mapGetters({
                errors: 'errors/errors',
                user: 'auth/user'
            }),
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