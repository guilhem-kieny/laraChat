<script>
export default {
    name: "register-form",

    data() {
        return {
            formData: {
                name:"",
                email:"",
                password:"",
            }
        };
    },
    created() {
        this.csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    },
    methods: {
        async registerUser() {
            try {
                const response = await fetch('/api/register', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': this.csrfToken,
                    },
                    body: JSON.stringify({

                        name: this.formData.name,
                        email: this.formData.email,
                        password: this.formData.password,
                    }),
                });
                console.log(response);
                if (!response.ok) {
                    throw new Error('Inscription échouée');
                }

                const data = await response.json();
                console.log(data);
                window.location.href = data.redirect_url;

            } catch (error) {
                console.error(error);

            }
        },
    }
}
</script>

<template>
    <v-card class="fill-width">
        <v-card-title>Créer un compte</v-card-title>
        <v-divider></v-divider>
        <form @submit.prevent="registerUser">
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
