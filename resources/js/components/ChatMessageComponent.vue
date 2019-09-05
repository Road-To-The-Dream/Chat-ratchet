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

                <div>
                    <input v-model="messageInput" ref="messageInput" @keyup.enter="enterClicked()" class="form-control mt-3" id="input-message" type="text"
                           placeholder="Введите сообщение" autofocus>

                    <div v-if="error">
                        <p class="mt-2 text-danger message-mute">{{ error }}</p>
                    </div>
                </div>
            </div>

            <div class="col-4">
                <online-user-component :usersOnline="usersOnline"></online-user-component>
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
                error: null
            }
        },

        created() {
            window.conn = new WebSocket('ws://localhost:8071?' + this.usertoken);

            this.messages = JSON.parse(this.allmessages);
            this.currentUser = JSON.parse(this.user);

            conn.onopen = ((event) => {
            });

            conn.onmessage = ((event) => {
                let data = eval("(" + event.data + ")");

                switch (data.type) {
                    case 'newMessage':
                        let user = JSON.parse(data.user);
                            this.messages.push({
                                gravatar_img: user.gravatar_img,
                                user_name: user.name,
                                user_color: user.color.name,
                                text: data.message
                            });
                        break;

                    case 'newUser':
                        if (data.user.token === this.usertoken) {
                            data.onlineUsers.forEach((user) => {
                                this.usersOnline.push(user);
                            });
                        } else {
                            this.usersOnline.push(data.user);
                        }
                        break;

                    case 'disconnect':
                        this.usersOnline.filter(user => {
                            this.usersOnline = data.onlineUsers.filter(item =>
                                user.id === item.id
                            )
                        });
                        break;

                    case 'error':
                        this.error = data.message;
                        break;
                }
            });

            conn.onclose = (event) => {
            };
        },

        methods: {
            enterClicked() {
                if (this.validateInput()) {
                    this.messageInput = '';

                    conn.send(
                        JSON.stringify({
                            'type': 'newMessage',
                            'message': this.$refs.messageInput.value
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
