<script setup>
import { ref } from 'vue';

const formData = ref({
    email:"",
    password:"",
});

const csrfToken = ref(document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
const submitForm = async () => {
    try {
        const response = await fetch('/api/login', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken.value,
            },
            body: JSON.stringify(formData.value),
        });
        if (response.ok) {
            const data = await response.json();

            window.location.href = data.redirect_url;
        } else {
            throw new Error(`Erreur HTTP : ${response.status}`);
        }
    } catch (error) {
        console.log(error);
    }
};

</script>

<template>
    <v-card class="fill-width">
        <v-card-title>LaraChat</v-card-title>
        <v-divider></v-divider>
        <form @submit.prevent="submitForm">
            <v-card-text>
                <v-text-field v-model="formData.email" type="email" label="E-mail" autocomplete="mail" required></v-text-field>
                <v-text-field v-model="formData.password" type="password" label="Mot de passe" autocomplete="password" required></v-text-field>
            </v-card-text>

            <v-card-actions>
                <v-btn type="submit">Connexion</v-btn>
                <a href="http://127.0.0.1:8000/register">S'enregistrer</a>
            </v-card-actions>
        </form>
    </v-card>
</template>

<style>

</style>
