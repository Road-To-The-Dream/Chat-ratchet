<template>
    <div class="col-6">
        <button @click="ban()" id="isBan" type="button" class="block btn btn-danger btn-sm mb-1">
            {{ btnBanValue }}
        </button>
    </div>
</template>

<script>
    import {eventEmitter} from "../app";

    export default {
        props: ['currentUser'],

        data() {
            return {
                btnBanValue: 'Ban'
            }
        },

        created() {
            eventEmitter.$on('BAN', (data) => {
                if (this.currentUser.name === data.name) {
                    this.btnBanValue = 'UnBan';
                    this.currentUser.isBan = !this.currentUser.isBan;
                }
            });

            this.isBan();
        },

        methods: {
            ban() {
                eventEmitter.$emit('BAN', this.currentUser);

                this.isBan();

                conn.send(
                    JSON.stringify({
                        'type': 'ban',
                        'userId': this.currentUser.id,
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
