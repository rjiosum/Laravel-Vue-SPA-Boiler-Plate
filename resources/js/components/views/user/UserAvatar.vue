<template>
    <v-row align="center" justify="center">
        <v-col cols="12" xs="12" sm="12" md="10" lg="6" xl="6">
            <v-list-item one-line class="mt-5 text-center">
                <v-list-item-content>
                    <v-list-item-title class="font-weight-bold headline">Update your avatar</v-list-item-title>
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
                        <v-file-input
                                label="Avatar"
                                show-size
                                filled
                                prepend-icon=""
                                append-icon="mdi-camera"
                                placeholder="Pick an avatar"
                                background-color="#f3f5f9"
                                accept="image/*"
                                v-model="avatar"
                                :error-messages="avatarErrors"
                                @input="$v.avatar.$touch()"
                                @blur="$v.avatar.$touch()"
                        ></v-file-input>

                        <v-alert tile dismissible outlined border="left" v-model="submit.status" :type="submit.type">
                            {{submit.message}}
                        </v-alert>

                        <v-btn depressed rounded class="blue white--text" type="submit"
                               :loading="submitted"
                               :disabled="submitted">Update</v-btn>
                    </v-form>
                </v-card-text>
            </v-card>
        </v-col>
    </v-row>
</template>

<script>
    import {validationMixin} from 'vuelidate';
    import {required} from 'vuelidate/lib/validators';
    import {mapGetters} from 'vuex';
    import {RepositoryFactory} from '@/repositories/RepositoryFactory';
    const UserRepository = RepositoryFactory.get('user');


    export default {
        name: "UserAvatar",
        mixins: [validationMixin],
        validations: {
            avatar: {required}
        },
        data() {
            return {
                avatar: null,
                submitted: false,
                submit: {
                    status: false,
                    type: 'success',
                    message: ''
                },
            }
        },
        methods: {
            async update () {
                this.$v.$touch();
                if (this.$v.$invalid) {
                    return;
                }
                this.submitted = true;

                let formData = new FormData();
                formData.append('avatar', this.avatar);

                try {
                    const {data} = await UserRepository.updateAvatar(formData);
                    this.submit.message = data.message;
                    this.submit.status = true;
                    if (data.status) {
                        this.submit.type = "success";
                        await this.$store.dispatch('auth/getUser');
                    } else {
                        this.submit.type = "error";
                    }
                    this.submitted = false;
                    this.avatar = null;
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

            avatarErrors() {
                const errors = [];
                if (!this.$v.avatar.$dirty) return errors;
                !this.$v.avatar.required && errors.push('Image is required');
                this.errors.avatar && errors.push(this.errors.avatar[0]);
                return errors;
            },

        }
    }
</script>

<style scoped>

</style>