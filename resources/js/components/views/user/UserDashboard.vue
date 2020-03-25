<template>
    <v-layout row wrap justify-space-between>
        <v-flex sm12>
            <v-card flat class="mb-10">
                <v-card-text>From your My Account Dashboard you have the ability to view a snapshot of your
                    recent account activity and update your account information. Select a link below to view
                    or edit information.
                </v-card-text>
            </v-card>
        </v-flex>

        <v-flex xs12 sm12 md4>
            <v-card flat height="100%">
                <v-card-title primary class="title justify-center mb-6">
                    <v-icon left>mdi-comment-question</v-icon>
                    <span>Share</span>
                </v-card-title>
                <v-card-text class="text-center mb-4">Share some article, thoughts or quotes. Here you
                    can create, update or delete old articles, thoughts or quotes.
                </v-card-text>
                <v-card-actions class="justify-center pb-5">
                    <v-btn depressed small dark rounded color="info" route :to="{name: 'user.articles'}">
                        Browse
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-flex>

        <v-flex xs12 sm12 md4>
            <v-card flat height="100%">
                <v-card-title primary class="title justify-center mb-6">
                    <v-icon left>mdi-account-box</v-icon>
                    <span>Profile</span></v-card-title>
                <v-card-text class="text-center mb-4">Manage your profile. Here, you can update your account
                    information.
                </v-card-text>
                <v-card-actions class="justify-center pb-5">
                    <v-btn depressed small dark rounded color="info" route :to="{name:'user.profile'}">
                        Browse
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-flex>

        <v-flex xs12 sm12 md4>
            <v-card flat height="100%">
                <v-card-title primary class="title justify-center mb-6">
                    <v-icon left>mdi-contact-mail</v-icon>
                    <span>Password</span></v-card-title>
                <v-card-text class="text-center mb-4">Change your password. Here, you can change your password.
                </v-card-text>
                <v-card-actions class="justify-center pb-5">
                    <v-btn depressed small dark rounded color="info" route :to="{name:'user.password'}">
                        Browse
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-flex>

        <v-flex xs12 sm12 md4>
            <v-card flat height="100%">
                <v-card-title primary class="title justify-center mb-6">
                    <v-icon left>mdi-contact-mail</v-icon>
                    <span>Avatar</span></v-card-title>
                <v-card-text class="text-center mb-4">Change your avatar. Here, you can change your avatar.
                </v-card-text>
                <v-card-actions class="justify-center pb-5">
                    <v-btn depressed small dark rounded color="info" route :to="{name:'user.avatar'}">
                        Browse
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-flex>

        <v-flex xs12 sm12 md4>
            <v-card flat height="100%">
                <v-card-title primary class="title justify-center mb-6">
                    <v-icon left>mdi-contact-mail</v-icon>
                    <span>Address Book</span></v-card-title>
                <v-card-text class="text-center mb-4">Manage your address. Here, you can add new address or
                    update an existing ones.
                </v-card-text>
                <v-card-actions class="justify-center pb-5">
                    <v-btn depressed small dark rounded color="error">
                        Coming Soon...
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-flex>
    </v-layout>
</template>

<script>
    export default {
        name: "UserDashboard"
    }
</script>



