<template>
    <div class="row">
        <div class="col-12">
            <div class="col users border scrollbar" id="messages-canvas">
            </div>
        </div>

        <div class="col">
            <input @keyup.enter="enterClicked()" class="form-control mt-3" id="input-message" type="text"
                   placeholder="Введите сообщение" autofocus>
        </div>
    </div>

</template>

<script>
    conn.onopen = function(e) {
        console.log("Connection established!");
    };

    conn.onmessage = function(e) {
        console.log(e.data);
    };

    conn.onclose = function (e) {
        console.log(e);
    };

    export default {
        data: function() {
            return  {
                message: 'Hello'
            }
        },
        methods: {
            enterClicked() {
                let chat_msg = 'Hello';
                conn.send(
                    JSON.stringify({
                        'type': 'chat',
                        'user_id': '{{auth()->id()}}',
                        'user_name': '{{auth()->user()->name}}',
                        'chat_msg': chat_msg
                    })
                )
            }
        }
    }
</script>
