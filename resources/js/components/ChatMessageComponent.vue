<template>
    <div>
        <div class="row">
            <div class="col-8">
                <div v-chat-scroll class="users border scrollbar pb-2" id="messages-canvas">
                    <div v-for="message in messages">
                        <div class="row ml-2 mr-2">
                            <div class="col-1 pl-0">
                                <img :src="message.gravatar_img" alt="" class="rounded-circle">
                            </div>
                            <div class="col align-self-end" v-bind:style="{ color: message.user_color }">{{ message.user_name }} : {{ message.text }}</div>
                        </div>
                    </div>
                </div>

                <div v-if="!mutedUser">
                    <input v-model="messageInput" ref="messageInput" @keyup.enter="enterClicked()" class="form-control mt-3" id="input-message" type="text"
                           placeholder="Введите сообщение" autofocus>

                    <div v-if="error">
                        <p class="mt-2 text-danger message-mute">{{ error }}</p>
                    </div>
                </div>
            </div>

            <div class="col-4">
                <online-user-component :usersOnline="usersOnline" :currentUser="currentUser"></online-user-component>
            </div>
        </div>
    </div>

</template>

<script>
    export default {
        props: ['usertoken', 'allmessages', 'user'],

        data() {
            return  {
                messageInput: null,
                messages: [],
                usersOnline: [],
                currentUser: {},
                error: null,
                mutedUser: false
            }
        },

        created() {
            window.conn = new WebSocket('ws://localhost:8078?' + this.usertoken);

            this.messages = JSON.parse(this.allmessages);
            this.currentUser = JSON.parse(this.user);

            conn.onopen = ((event) => {
            });

            conn.onmessage = ((event) => {
                // console.log('json', JSON.parse(event.data));
                const data = JSON.parse(event.data);
                // console.log('user.name', aaa.user.name);

                // let data = eval("(" + event.data + ")");

                switch (data.type) {
                    case 'newMessage':
                        let user = data.user;
                            this.messages.push({
                                gravatar_img: user.gravatar_img,
                                user_name: user.name,
                                user_color: user.color.name,
                                text: data.message
                            });
                        break;

                    case 'disconnect':
                    case 'newUser':
                        this.usersOnline = data.onlineUsers;
                        break;

                    case 'ban':
                        if (data.message === this.currentUser.id) {
                            location.reload();
                        }
                        break;

                    case 'mute':
                        if (data.message === this.currentUser.id) {
                            this.mutedUser = !this.mutedUser;
                        }
                        break;

                    case 'error':
                        if (data.user.id === this.currentUser.id) {
                            this.error = data.message;
                        }
                        break;
                }
            });

            conn.onclose = (event) => {
                location.reload();
            };
        },

        methods: {
            enterClicked() {
                if (this.validateInput()) {
                    this.messageInput = '';

                    conn.send(
                        JSON.stringify({
                            'type': 'newMessage',
                            'message': this.$refs.messageInput.value,
                            'userId': this.currentUser.id
                        })
                    );
                }
            },

            validateInput() {
                if (this.messageInput) {
                    this.error = null;

                    return true;
                }

                this.error = null;

                if (!this.messageInput) {
                    this.error = 'Введите сообщение !';
                }
            }
        }
    }
</script>
