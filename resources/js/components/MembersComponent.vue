<template>
    <div class="row">
        <div class="col">
            <label>All members:</label>

            <div v-for="member in allMembers" class="shadow-sm pl-3 pr-3 pb-2 mb-3 bg-white rounded">
                <div class="row mb-3">
                    <div class="col-2 p-0">
                        <img :src="member.gravatar_img" alt="" class="rounded-circle">
                    </div>
                    <div class="col-7 align-self-end">{{ member.name }}</div>
                    <div class="col-3 text-right align-self-end">{{ member.role }}</div>
                </div>
                <div class="row user-status">
                    <btn-ban-component :currentUser="member"></btn-ban-component>
                    <btn-mute-component :currentUser="member"></btn-mute-component>
                </div>
            </div>

        </div>
    </div>

</template>

<script>
    export default {
        props: ['members'],

        data() {
            return  {
                allMembers: {}
            }
        },

        created() {
            this.allMembers = JSON.parse(this.members);
        },
    };
</script>
