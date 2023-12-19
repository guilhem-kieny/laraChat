<script setup>
import { ref } from "vue";

const formData = ref({
    name:"",
    email:"",
    password:"",
});

const csrfToken = ref(document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
const registerForm = async () => {
    try {
        const response = await fetch('/api/register', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken.value,
            },
            body: JSON.stringify({
                name: formData.value.name,
                email: formData.value.email,
                password: formData.value.password
            }),
        });
        if (response.ok) {
            const data = await response.json();

            window.location.href = data.redirect_url;
        } else {
            throw new Error(`Inscription échouée`);
        }
    } catch (error) {
        console.log(error);
    }
};
</script>

<template>
    <v-card class="fill-width">
        <v-card-title>Créer un compte</v-card-title>
        <v-divider></v-divider>
        <form @submit.prevent="registerForm">
            <v-card-text>
                <v-text-field v-model="formData.name" type="text" label="Nom" required></v-text-field>
                <v-text-field v-model="formData.email" type="email" label="E-mail" required></v-text-field>
                <v-text-field v-model="formData.password" type="password" label="Mot de passe" required></v-text-field>
            </v-card-text>

            <v-card-actions>
                <v-btn type="submit">Créer</v-btn>
            </v-card-actions>
        </form>
    </v-card>
</template>

<style scoped>

</style>
