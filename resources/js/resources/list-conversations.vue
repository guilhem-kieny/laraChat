<script>
import {DateTime} from 'luxon';

export default {
    name: "list-conversations",
    data() {
        return {
            conversationName: "",
            state: "index",
            dataConversations: this.conversations,
            dataConversation: "",
            messages: null,
            newMessage: "",
            nameState: "hide",
            successMessage: "",
            errorMessage: "",
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
        isLastItem(index) {
            return index === this.messages.length - 1;
        },
        formatCreatedAt(date) {
            return DateTime.fromISO(date).toFormat('HH:mm dd LLLL yyyy');
        },
        scrollToTop() {
            let container = document.getElementById('conversation-container');
            container.scrollTop = container.scrollHeight;
        },
        truncateTitle(title, maxLength) {
            if (title.length > maxLength) {
                title = title.slice(0, maxLength) + '...';
            }
            return title;
        },
        toggleConversationName() {
            this.nameState = this.nameState === 'show' ? 'hide' : 'show';
        },
        getDisplayNewName(conversation_id) {
            const oldConversation = this.dataConversations.find(conversation => conversation.id === conversation_id);
            oldConversation.name = this.conversationName;
        },
        closeAlert() {
            this.successMessage = "";
        },
        reloadConversations() {
            try {
                fetch(`/api/conversations`, {
                    method: 'GET',
                })
                    .then((response) => {
                        if (!response.ok) {
                            throw new Error(`La récupération des conversations a échoué. Statut : ${response.status}`);
                        }
                        return response.json();
                    })
                    .then((data) => {
                        this.dataConversations = data.conversations;
                    });
            } catch (error) {
                console.log(error);
            }
        },
        showConversation(conversation_id) {
            this.state = 'show';

            const response = fetch(`/api/conversations/${conversation_id}`, {
                method: 'GET',
            })
                .then((response) => response.json())
                .then((data) => {
                    this.dataConversation = data.conversation;
                    this.messages = data.messages;
                    this.conversationName = data.conversation.name;
                })
                .then(() => {
                    this.scrollToTop();
                }).catch((error) => console.log(error))
        },
        async storeConversation() {

            try {
                await fetch(`/api/conversations`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': this.csrfToken,
                    },
                })
                    .then((response) => response.json())
                    .then((data) => {
                        this.reloadConversations();
                        this.successMessage = "Conversation créé";
                        this.showConversation(data.conversation.id);
                    });

            } catch (error) {
                console.error(error);
                throw error;
            }
        },
        async deleteConversation(conversation_id) {
            try {
                const indexRemove = this.dataConversations.findIndex(conversation => conversation.id === conversation_id);
                this.dataConversations.splice(indexRemove, 1);

                await fetch(`/api/conversations/${conversation_id}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': this.csrfToken,
                    }
                })
                    .then((response) => {
                        if(response.ok) {
                            this.successMessage = "Conversation supprimée";
                        }
                    })
                    .then(() => {
                        this.reloadConversations();
                    });
            } catch (error) {
                console.error('Erreur lors de la suppression de la conversation', error);
            }
        },
        async storeMessage(conversation_id) {
            try {
                const response = await fetch(`/api/conversations/${conversation_id}/messages`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': this.csrfToken,
                    },
                    body: JSON.stringify({content: this.newMessage}),
                });
                if (!response.ok) {
                    throw new Error('Échec de l\'envoi du message');
                }

                this.newMessage = '';
                this.successMessage = '';
                this.showConversation(conversation_id);
            } catch (error) {
                console.error(error);
                throw error;
            }
        },
        async updateConversationName(conversation_id) {
            try {
                const response = await fetch(`/api/conversations/${conversation_id}/name`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': this.csrfToken,
                    },
                    body: JSON.stringify({content: this.conversationName}),
                });
                if (!response.ok) {
                    throw new Error('Échec de la modification du nom de la conversation');
                }
                this.getDisplayNewName(conversation_id);
                this.toggleConversationName();
            } catch (error) {
                console.error(error);
                throw error;
            }
        },
    }
}
</script>

<template>
    <div class="list-conversation-main-container">
        <div v-if="state === 'index'">
            <v-card-text class="conversation-title-container">
                <v-card-title><h2>Discussions</h2></v-card-title>
                <v-btn class="add-btn" v-on:click="storeConversation">
                    <v-icon>mdi-plus</v-icon>
                </v-btn>
            </v-card-text>
            <v-divider></v-divider>

            <v-card-text>

                <v-alert v-if="successMessage" v-on:click="closeAlert" type="success" class="success">
                    {{ successMessage }}
                </v-alert>

                <v-list class="list-conversation-container" v-for="conversation in dataConversations"
                        :key="conversation.id">
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

        <div class="conversation-main-container" v-else-if="state === 'show' && dataConversation">
            <v-card-text class="conversation-title-container">
                <v-card-title>
                    <h2 v-if="this.conversationName">{{ truncateTitle(this.conversationName, 20) }}</h2>
                </v-card-title>
                <div class="conversation-btn-container">
                    <v-btn v-on:click="toggleConversationName" class="btn-transparent">
                        <v-icon>mdi-pencil</v-icon>
                    </v-btn>
                    <a v-on:click.prevent="state = 'index'" href="#">
                        <v-btn class="btn-transparent">
                            <v-icon>mdi-chevron-left</v-icon>
                        </v-btn>
                    </a>
                </div>

                <div class="update-conversation-name-container" v-if="nameState === 'show'">
                    <v-text-field v-model="conversationName"
                                  placeholder="Modifiez le nom de la conversation ..."></v-text-field>
                    <v-btn class="send-btn" v-on:click="updateConversationName(dataConversation.id)">
                        <v-icon>mdi-send</v-icon>
                    </v-btn>
                </div>
            </v-card-text>
            <v-divider></v-divider>

            <v-card-text id="conversation-container">
                <v-alert v-if="successMessage" v-on:click="closeAlert" type="success" class="success">
                    {{ successMessage }}
                </v-alert>
                <v-list v-for="(message, index) in messages" :key="index">
                    <p v-if="isLastItem(index)" :class="{ dateUser: message.user_id === user.id }">
                        {{ formatCreatedAt(message.created_at) }}</p>
                    <v-list-item class="message-content"
                                 :class="{ userMessage: message.user_id === user.id, otherMessage: message.user_id !== user.id }">
                        {{ message.content }}
                    </v-list-item>
                </v-list>
            </v-card-text>
            <v-divider></v-divider>

            <div class="message-field">
                <v-text-field v-model="newMessage" placeholder="Écrivez votre message ..."
                              class="message-writer"></v-text-field>
                <v-btn class="send-btn" v-on:click="storeMessage(dataConversation.id)">
                    <v-icon>mdi-send</v-icon>
                </v-btn>
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
.success {
    background-color: #4caf51;
}

.succes-container {
    border-radius: 0 !important;
    display: flex;
    align-content: center;
    align-items: center;
    height: 50px;

    .success-title {
        color: white;
        text-align: center;
    }

}

.update-conversation-name-container {
    position: absolute;
    bottom: -90px;
    left: 0;
    display: flex;
    background-color: white;
    width: 100%;
    z-index: 1;
    padding: 10px;
    box-shadow: 0 0 2px 0 #a7a7a7;
}

.list-conversation-main-container {
    h2 {
        font-size: 20px;
    }
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
    position: relative;
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
    width: fit-content;
    min-height: 10px;
    padding: 15px;
    font-size: 16px;
}

.otherMessage {
    background-color: #73b72b;
    color: white !important;
}

.dateUser {
    text-align: right;
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
