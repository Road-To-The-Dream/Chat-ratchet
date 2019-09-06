<template>
    <div class="row">
        <div class="col-12">
            <div><label>Members online:</label></div>
        </div>

        <div class="col">
            <div v-for="user in usersOnline" class="shadow-sm pl-3 pr-3 pb-2 mb-3 bg-white rounded">
                <div class="row mb-3">
                    <div class="col-2 p-0">
                        <img :src="user.gravatar_img" alt="" class="rounded-circle">
                    </div>
                    <div class="col-7 align-self-end">{{ user.name }}</div>
                    <div class="col-3 text-right align-self-end">{{ user.role }}</div>
                </div>
                <div v-if="isRole(user)" class="row user-status">
                    <btn-ban-component :currentUser="user"></btn-ban-component>
                    <btn-mute-component :currentUser="user"></btn-mute-component>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
    export default {
        props: ['usersOnline', 'currentUser'],

        data() {
            return {
                btnBanValue: 'Ban',
                btnMuteValue: 'Mute'
            }
        },

        methods: {
            isRole(user) {
                return (this.isAdmin() && this.currentUser.role !== user.role);
            },

            isAdmin() {
                return this.currentUser.role === 'admin';
            },
        }
    };
</script>
