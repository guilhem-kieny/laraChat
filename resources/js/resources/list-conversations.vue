<script>

export default {
    name: "list-conversations",
    data() {
        return {
            state: "index",
            conversation: this.conversation,
            messages:null,
            newMessage:"",
        }
    },

    props: [
        "conversations",
        "user"
    ],
    created() {
        this.csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    },

    methods: {
        scrollToTop() {
            let container = document.getElementById('conversation-container');
            container.scrollTop = container.scrollHeight;
        },
        async storeConversation() {
            this.state = 'show';

            try {
                const response = await fetch(`http://127.0.0.1:8000/api/conversation`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': this.csrfToken,
                    },
                });
                if (!response.ok) {
                    throw new Error('Échec de création de la conversation');
                }

                const data = await response.json();
                this.conversation = data.conversation;

            } catch (error) {
                console.error(error);
                throw error;
            }
        },
        showConversation(id) {
            this.state = 'show';

            const response = fetch(`http://127.0.0.1:8000/api/conversation/${id}`, {
                method: 'GET',
            }).then((response) => response.json()).then((data) =>{

                this.conversation = data.conversation;
                this.messages = data.messages;
            }).then(() => {

                this.scrollToTop();

            }).catch((err) => console.log(err))
        },
        async storeMessage(id) {
            try {
                const response = await fetch(`http://127.0.0.1:8000/api/conversation/${id}/message`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': this.csrfToken,
                    },
                    body: JSON.stringify({ content: this.newMessage }),
                });
                if (!response.ok) {
                    throw new Error('Échec de l\'envoi du message');
                }

                this.newMessage = '';

                this.showConversation(id);
            } catch (error) {
                console.error(error);
                throw error;
            }
        },
    }
}
</script>

<template>
    <div class="list-conversation-container">
        <div v-if="state === 'index'">
            <v-card-text class="conversation-title-container">
                <v-card-title><h2>Discussions</h2></v-card-title>
                <v-btn class="add-btn" v-on:click="storeConversation"><v-icon>mdi-plus</v-icon></v-btn>
            </v-card-text>
            <v-divider></v-divider>

            <v-card-text>
            <v-list v-for="conversation in conversations" :key="conversation.id">
                <a v-on:click.prevent="showConversation(conversation.id)" href="#"><v-list-item-title class="list-item"> {{ conversation.name }}</v-list-item-title></a>
            </v-list>
        </v-card-text>
        </div>

        <div class="conversation-main-container" v-else-if="state === 'show' && conversation">
            <v-card-text class="conversation-title-container">
                <v-card-title><h2>{{ conversation.name }}</h2></v-card-title>
                <a v-on:click.prevent="state = 'index'" href="#"><v-btn class="btn-transparent"><v-icon>mdi-chevron-left</v-icon></v-btn></a>
            </v-card-text>
            <v-divider></v-divider>

            <v-card-text id="conversation-container">
                <v-list v-for="message in messages" :key="message.id">
                    <v-list-item class="message-content" :class="{ userMessage: message.user_id === user.id, otherMessage: message.user_id !== user.id }">{{ message.content }}</v-list-item>
                </v-list>
            </v-card-text>
            <v-divider></v-divider>

            <div class="message-field">
                <v-text-field v-model="newMessage" class="message-writer"></v-text-field>
                <v-btn class="send-btn" v-on:click="storeMessage(conversation.id)"><v-icon>mdi-send</v-icon></v-btn>
            </div>
        </div>
    </div>

</template>

<style lang="scss" scoped>
    $mainColor: #73b72b;
    a {
        color: black;
        text-decoration: none;
    }
    .conversation-title-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 0;
        padding-bottom: 0;
    }
    .list-item {
        background-color: #e4e4e4;
        border-radius: 5px;
        padding: 15px;
    }
    .conversation-main-container {
        height: 100%;
    }
    #conversation-container {
        height: 68vh;
        overflow: scroll;
    }

    .message-writer {
        margin-top: 40px;
        width: calc(100% - 80px);
    }
    .message-content {
        border-radius: 5px;
        max-width: 70%;
        padding: 20px;
        font-size: 16px;
    }
    .otherMessage {
        background-color: #73b72b;
        color: white !important;
    }
    .userMessage {
        background-color: #e4e4e4;
        margin-left: 30%;
    }
    .send-btn {
        height: 88px !important;
        border-radius: 0;
        width: 88px;
        position: absolute;
        right: 0;
        bottom: 0;
    }
    .add-btn {
        color: $mainColor;
    }
</style>
