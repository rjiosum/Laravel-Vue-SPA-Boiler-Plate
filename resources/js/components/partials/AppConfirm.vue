<template>
    <v-dialog v-model="dialog" :max-width="options.width" :style="{ zIndex: options.zIndex }" @keydown.esc="cancel">
        <v-card>
            <v-toolbar :color="options.color" dark flat dense>
                <v-toolbar-title>{{ title }}</v-toolbar-title>
            </v-toolbar>

            <v-card-text class="pa-2" v-show="!!message">{{ message }}</v-card-text>

            <v-divider></v-divider>

            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn depressed outlined small rounded color="success" class="mr-3" @click.native="agree"><v-icon left>mdi-check</v-icon>Yes</v-btn>
                <v-btn depressed outlined small rounded color="error" @click.native="cancel"><v-icon left>mdi-close</v-icon>Cancel</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>

    export default {
        data() {
            return {
                dialog: false,
                resolve: null,
                reject: null,
                message: null,
                title: null,
                options: {
                    color: 'primary',
                    width: 280,
                    zIndex: 200
                }
            }
        },
        methods: {
            open(title, message, options) {
                this.dialog = true;
                this.title = title;
                this.message = message;
                this.options = Object.assign(this.options, options);
                return new Promise((resolve, reject) => {
                    this.resolve = resolve;
                    this.reject = reject;
                })
            },
            agree() {
                this.resolve(true);
                this.dialog = false;
            },
            cancel() {
                this.resolve(false);
                this.dialog = false;
            }
        }
    }
</script>