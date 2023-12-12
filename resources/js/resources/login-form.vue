<script>


export default {
    name: "login-form",

    data() {
        return {
            formData: {
                email:"",
                password:"",
            },
        };
    },
    created() {
        this.csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    },
    methods: {
        async submitForm() {
            try {
                const response = await fetch('http://127.0.0.1:8000/api/login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': this.csrfToken,
                    },
                    body: JSON.stringify(this.formData)
                });
                if(response.ok) {
                    const data = await response.json();
                    console.log(data);
                    window.location.href = data.redirect_url;
                } else {
                    console.error('Echec de la requête');
                }
            } catch (error) {
                console.error(error);
            }
        },
    }
}
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
