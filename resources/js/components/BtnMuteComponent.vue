<template>
    <div class="col-6">
        <button @click="mute()" id="isMute" type="button" class="block btn btn-warning btn-sm">
            {{ btnMuteValue }}
        </button>
    </div>
</template>

<script>
    export default {
        props: ['currentUser'],

        data() {
            return {
                btnMuteValue: 'Mute'
            }
        },

        created() {
            this.isMute();
        },

        methods: {
            mute() {
                this.currentUser.isMute = !this.currentUser.isMute;

                this.isMute();

                conn.send(
                    JSON.stringify({
                        'type': 'mute',
                        'userToken': this.currentUser.token,
                        'value': this.currentUser.isMute
                    })
                );
            },

            isMute() {
                if (this.currentUser.isMute) {
                    this.btnMuteValue = 'UnMute';
                } else {
                    this.btnMuteValue = 'Mute';
                }
            },
        }
    };
</script>
