<template>
    <v-snackbar
            v-model="show"
            top
            multi-line
            :color="color"
            :timeout="timeout"
    >
        <v-icon dark left>{{icon}}</v-icon> <div v-html="message"></div>
        <v-btn dark text @click.native="show = false">Close</v-btn>
    </v-snackbar>
</template>

<script>
    export default {
        name: "AppNotify",
        data () {
            return {
                show: false,
                message: '',
                color: 'success',
                icon: 'mdi-check-bold',
                timeout:0
            }
        },
        created() {
            this.$store.watch(state => state.notify.notice.msg, () => {
                const msg = this.$store.state.notify.notice.msg;
                if (msg !== '') {
                    this.show = true;
                    this.message = this.$store.state.notify.notice.msg;
                    this.color = this.$store.state.notify.notice.color;
                    this.icon = this.$store.state.notify.notice.icon;
                    this.timeout = this.$store.state.notify.notice.timeout;
                    this.$store.dispatch('notify/setNotice', {msg:'', color:'', icon:'', timeout: 0});
                }
            })
        }
    }
</script>

<style scoped>

</style>