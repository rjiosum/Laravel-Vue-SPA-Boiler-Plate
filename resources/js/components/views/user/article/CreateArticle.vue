<template>
        <v-layout row wrap justify-center>
            <v-flex xs12 sm12 md10 lg6 xl6>

                <v-list-item one-line class="mt-5 text-center">
                    <v-list-item-content>
                        <v-list-item-title class="font-weight-bold headline">Write / Share an article!</v-list-item-title>
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
                        <v-form @submit.prevent="submitData" ref="vform">
                            <v-text-field
                                    filled
                                    counter="150"
                                    label="Title"
                                    v-model.trim="form.title"
                                    type="text"
                                    background-color="#f3f5f9"
                                    append-icon="mdi-alert-circle"
                                    :error-messages="titleErrors"
                                    @input="$v.form.title.$touch()"
                                    @blur="$v.form.title.$touch()"
                            ></v-text-field>
                            <!--<v-textarea
                                    filled auto-grow
                                    v-model="form.description"
                                    name="description"
                                    label="Description"
                                    background-color="#f5f8fa"
                                    :error-messages="descriptionErrors"
                                    @input="$v.form.description.$touch()"
                                    @blur="$v.form.description.$touch()"
                            ></v-textarea>-->

                            <ckeditor
                                    :editor="editor"
                                    v-model.trim="form.description"
                                    :config="editorConfig"
                                    @input="$v.form.description.$touch()"
                            ></ckeditor>

                            <div v-if="!$v.form.description.required || errors.description" class="v-text-field__details mt-2">
                                <div class="v-messages theme--light error--text">
                                    <div class="v-messages__wrapper">
                                        <div class="v-messages__message">Description is required</div>
                                    </div>
                                </div>
                            </div>

                            <v-checkbox v-model="form.status" label="Is Active?"></v-checkbox>

                            <v-alert tile dismissible outlined border="left" v-model="submit.status" :type="submit.type">
                                {{submit.message}}
                            </v-alert>

                            <v-btn depressed rounded class="blue white--text mt-3 mr-5" type="submit"
                                   :loading="submitted"
                                   :disabled="submitted">Create
                            </v-btn>
                            <v-btn depressed rounded class="red white--text mt-3" @click="back">
                                Back
                            </v-btn>
                        </v-form>
                    </v-card-text>
                </v-card>
            </v-flex>
        </v-layout>
</template>

<script>
    import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
    import CKEditor from '@ckeditor/ckeditor5-vue';

    import {validationMixin} from 'vuelidate';
    import {required, maxLength} from 'vuelidate/lib/validators';
    import {mapGetters} from 'vuex';
    import {RepositoryFactory} from '@/repositories/RepositoryFactory';

    const ArticleRepository = RepositoryFactory.get('article');

    export default {
        name: "CreateArticle",
        mixins: [validationMixin],
        components: {
            ckeditor: CKEditor.component
        },
        validations: {
            form: {
                title: {required, maxLength: maxLength(150)},
                description: {required}
            }
        },
        data() {
            return {
                form: {
                    title: '',
                    description: '',
                    status: false
                },
                isDescriptionEmpty: false,
                editor: ClassicEditor,
                editorConfig: {
                    toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote']
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
            async submitData() {
                this.$v.$touch();
                if (this.$v.$invalid) {
                    return
                }
                try {
                    const {data} = await ArticleRepository.create(this.form);

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

                } catch (e) {
                    this.submitted = false;
                }
            },
            back() {
                this.$router.back();
            }
        },
        computed: {
            ...mapGetters({
                errors: 'errors/errors',
                user: 'auth/user'
            }),
            titleErrors() {
                const errors = [];
                if (!this.$v.form.title.$dirty) return errors;
                !this.$v.form.title.required && errors.push('Title is required');
                !this.$v.form.title.maxLength && errors.push('Title cannot be more than 150 characters');
                this.errors.title && errors.push(this.errors.title[0]);
                return errors;
            }
        }
    }
</script>

<style scoped>

</style>