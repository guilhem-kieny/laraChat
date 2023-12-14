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
                const response = await fetch(`/api/conversations`, {
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

            const response = fetch(`/api/conversations/${id}`, {
                method: 'GET',
            }).then((response) => response.json()).then((data) =>{

                this.conversation = data.conversation;
                this.messages = data.messages;
            }).then(() => {

                this.scrollToTop();

            }).catch((err) => console.log(err))
        },
        deleteConversation(id) {

            fetch(`/api/conversations/${id}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken,
                }
            })
        },
        async storeMessage(id) {
            try {
                const response = await fetch(`/api/conversations/${id}/messages`, {
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
            <v-list class="list-conversation-container" v-for="conversation in conversations" :key="conversation.id">
                <v-list-item class="list-item-conversation">
                    <a class="link-conv" v-on:click.prevent="showConversation(conversation.id)" href="#">
                        <v-list-item-title class="list-item">{{ conversation.name }}</v-list-item-title>
                    </a>
                    <div class="list-delete-container" v-on:click="deleteConversation(conversation.id)">
                        <v-icon class="delete-conv-icon">mdi-close-circle</v-icon>
                    </div>
                </v-list-item>

            </v-list>
        </v-card-text>
        </div>

        <div class="conversation-main-container" v-else-if="state === 'show' && conversation">
            <v-card-text class="conversation-title-container">
                <v-card-title><h2>{{ conversation.name }}</h2></v-card-title>
                <a v-on:click.prevent="state = 'index'" href="#">
                    <v-btn class="btn-transparent">
                        <v-icon>mdi-chevron-left</v-icon>
                    </v-btn>
                </a>
            </v-card-text>
            <v-divider></v-divider>

            <v-card-text id="conversation-container">
                <v-list  v-for="message in messages" :key="message.id">
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
    .list-conversation-container {
        padding: 5px 20px;
    }
    .list-item-conversation {
        display: flex;
        border: 1px solid #e6e6e6;
        border-radius: 5px;
        padding: 0;
        text-align: center;
        transition: .3s;
        &:hover {
            background-color: $mainColor;
            border: 1px solid $mainColor;
            .delete-conv-icon {
                visibility: visible;
            }
            .link-conv {
                color: white;
            }
        }
        .link-conv {
            width: 100%;
            padding: 20px 0 20px 58px;
        }
        .list-delete-container {
            display: flex;
            padding: 17px;
            cursor: pointer;
        }
        .delete-conv-icon {
            visibility: hidden;
            color: white;
        }
    }
    .conversation-title-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 0;
        padding-bottom: 0;
    }
    .list-item {

        &:hover {

        }
    }
    .conversation-main-container {
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-around;
    }
    #conversation-container {
        height: 68vh;
        overflow: scroll;
    }
    .message-field {
        display: flex;
    }
    .message-writer {
        padding: 15px;
    }
    .message-content {
        border-radius: 20px;
        max-width: 70%;
        padding: 15px;
        font-size: 16px;
    }
    .otherMessage {
        background-color: #73b72b;
        color: white !important;
    }
    .userMessage {
        background-color: #e4e4e4;
        margin-left: auto;
    }
    .send-btn {
        height: auto !important;
        border-radius: 0;
        box-shadow: none;
        background: none !important;
        .v-icon:hover {
            color: $mainColor;
        }
        &:before {
            background-color: transparent;
        }
    }
    .add-btn {
        color: $mainColor;
    }
    @media only screen and (max-width: 576px) {
        .message-content {
            max-width: 85%;
            padding: 10px;
            font-size: 14px;
        }
    }
</style>
