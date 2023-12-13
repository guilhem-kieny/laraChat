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
}
</script>

<template>
    <div>
        <div v-if="state === 'close'" v-on:click="state = 'open'" class="open-chat-container">
            <v-icon class="robot-icon">mdi-robot</v-icon>
        </div>
        <div v-if="state === 'open'" class="chat-container">
            <list-conversations :conversations="{ conversations }" :user="{ user }"></list-conversations>
        </div>
    </div>
</template>

<style lang="scss">
    .open-chat-container {
        background-color: white;
        padding: 10px;
        position: absolute;
        bottom: 0;
        left: -100px;
    }
    .chat-container {
        position: absolute;
        bottom: 0;
        left: -100px;
        .list-conversation-container {
            height: 500px;
            background-color: white;
            box-shadow: 0 2px 4px 1px #828282;
        }
    }
</style>
