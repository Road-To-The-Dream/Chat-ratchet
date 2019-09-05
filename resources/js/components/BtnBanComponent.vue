<template>
    <div class="col-6">
        <button @click="ban()" id="isBan" type="button" class="block btn btn-danger btn-sm mb-1">
            {{ btnBanValue }}
        </button>
    </div>
</template>

<script>
    export default {
        props: ['currentUser'],

        data() {
            return {
                btnBanValue: 'Ban',
                btnMuteValue: 'Mute'
            }
        },

        created() {
            this.isBan();
        },

        methods: {
            ban() {
                this.currentUser.isBan = !this.currentUser.isBan;

                this.isBan();

                conn.send(
                    JSON.stringify({
                        'type': 'ban',
                        'userToken': this.currentUser.token,
                        'value': this.currentUser.isBan
                    })
                );
            },

            isBan() {
                if (this.currentUser.isBan) {
                    this.btnBanValue = 'UnBan';
                } else {
                    this.btnBanValue = 'Ban';
                }
            },
        }
    };
</script>
