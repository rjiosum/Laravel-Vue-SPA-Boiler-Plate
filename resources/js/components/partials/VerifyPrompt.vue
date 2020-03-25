<template>
    <div>
        <v-list-item one-line class="mt-10 text-center">
            <v-list-item-content>
                <v-list-item-title class="font-weight-bold headline red--text">Please, verify your email address.</v-list-item-title>
            </v-list-item-content>
        </v-list-item>

        <v-card flat>
            <v-card-text>
                Before proceeding, please check your email for a verification link. If you did not receive the email, click
                <router-link :to="url">here</router-link> to request another.
            </v-card-text>
        </v-card>
    </div>
</template>

<script>
    export default {
        name: "VerifyPrompt",

        props: {
            email:{
                type: String,
                default: ''
            }
        },

        computed: {
            url(){
                return `/email/resend?email=${ (this.email == null) ? '' : encodeURIComponent(this.email) }`
            }
        }
    }
</script>

<style scoped>

</style>