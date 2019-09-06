<template>
    <div class="col-6">
        <button @click="mute()" id="isMute" type="button" class="block btn btn-warning btn-sm">
            {{ btnMuteValue }}
        </button>
    </div>
</template>

<script>
    import {eventEmitter} from "../app";

    export default {
        props: ['currentUser'],

        data() {
            return {
                btnMuteValue: 'Mute'
            }
        },

        created() {
            eventEmitter.$on('MUTE', (data) => {
                if (this.currentUser.name !== data.name) {
                    return false;
                }

                this.btnMuteValue = 'UnMute';
                this.currentUser.isMute = !this.currentUser.isMute;
                this.isMute();
            });
        },

        methods: {
            mute() {
                eventEmitter.$emit('MUTE', this.currentUser);

                this.isMute();

                conn.send(
                    JSON.stringify({
                        'type': 'mute',
                        'userId': this.currentUser.id,
                        'value': this.currentUser.isMute
                    })
                );
            },

            isMute() {
                this.btnMuteValue = this.currentUser.isMute?'UnMute':'Mute';
            },
        }
    };
</script>
