<script>
export default {
    name: "modal-bot",

    data() {
        return {
            state: "close",
        }
    },
    props: [
        "conversations",
        "user"
    ],
    methods: {
        toggleState() {
            this.state = this.state === 'open' ? 'close' : 'open';
        }
    }
}
</script>

<template>
    <div>
        <div v-on:click="toggleState" class="open-chat-container">
            <v-icon class="robot-icon">mdi-robot</v-icon>
        </div>
        <div v-if="state === 'open'" class="chat-container">
            <div class="header-bot">
                <v-btn class="btn-transparent"><v-icon>mdi-menu</v-icon></v-btn>
                <v-icon class="robot-icon">mdi-robot</v-icon>
                <v-btn class="btn-transparent" v-on:click="toggleState"><v-icon class="robot-icon">mdi-close</v-icon></v-btn>
            </div>
            <list-conversations :conversations="conversations" :user="user"></list-conversations>
        </div>
    </div>
</template>

<style lang="scss">
    $mainColor: #73b72b;
    .open-chat-container {
        z-index: 1;
        background-color: white;
        padding: 10px;
        position: absolute;
        bottom: 0;
        left: -100px;
        cursor: pointer;
    }
    .chat-container {
        position: absolute;
        bottom: 0;
        left: -100px;
        .list-conversation-container {
            height: 500px;
            width: 350px;
            background-color: white;
            box-shadow: 0 2px 4px 1px #828282;

            h2 {
                font-size: 25px;
            }
            #conversation-container {
                height: 70%
            }
        }
    }
    .header-bot {
        background-color: $mainColor;
        display: flex;
        justify-content: space-between;
        padding:15px;
        .v-btn {
            padding: 0 !important;
            min-width: fit-content !important;
        }
        .v-icon {
            color: white !important;
        }
    }
</style>
