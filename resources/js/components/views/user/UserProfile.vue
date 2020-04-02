<template>
    <v-layout row wrap justify-center>
        <v-flex xs12 sm12 md10 lg6 xl6>
            <v-list-item one-line class="mt-5 text-center">
                <v-list-item-content>
                    <v-list-item-title class="font-weight-bold headline">Update your account details</v-list-item-title>
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
                                label="First Name"
                                v-model.trim="form.first_name"
                                type="text"
                                name="first_name"
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
                                name="last_name"
                                background-color="#f3f5f9"
                                append-icon="mdi-account"
                                :error-messages="lastNameErrors"
                                @input="$v.form.last_name.$touch()"
                                @blur="$v.form.last_name.$touch()"
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
    import {required, maxLength} from 'vuelidate/lib/validators';
    import {mapGetters} from 'vuex';
    import {RepositoryFactory} from '@/repositories/RepositoryFactory';

    const UserRepository = RepositoryFactory.get('user');

    export default {
        name: "UserProfile",
        mixins: [validationMixin],
        validations: {
            form: {
                first_name: {required, maxLength: maxLength(100)},
                last_name: {required, maxLength: maxLength(100)},
            }
        },
        data() {
            return {
                form: {
                    first_name: '',
                    last_name: ''
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
                    const {data} = await UserRepository.updateProfile(this.form);

                    this.submit.message = data.message;
                    this.submit.status = true;

                    if (data.status) {
                        this.submit.type = "success";
                        await this.$store.dispatch('auth/getUser');
                    } else {
                        this.submit.type = "error";
                    }
                    this.submitted = false;

                } catch (error) {
                    this.submitted = false;
                }
            }
        },
        created() {
            Object.keys(this.form).forEach((key) => {
                this.form[key] = this.user[key]
            });
        },
        computed: {
            ...mapGetters({
                errors: 'errors/errors',
                user: 'auth/user'
            }),
            firstNameErrors() {
                const errors = [];
                if (!this.$v.form.first_name.$dirty) return errors;
                !this.$v.form.first_name.required && errors.push('Name is required');
                !this.$v.form.first_name.maxLength && errors.push('Name cannot be more than 100 characters');
                this.errors.first_name && errors.push(this.errors.first_name[0]);
                return errors;
            },
            lastNameErrors() {
                const errors = [];
                if (!this.$v.form.last_name.$dirty) return errors;
                !this.$v.form.last_name.required && errors.push('Name is required');
                !this.$v.form.last_name.maxLength && errors.push('Name cannot be more than 100 characters');
                this.errors.last_name && errors.push(this.errors.last_name[0]);
                return errors;
            }
        }
    }
</script>

<style scoped>

</style>