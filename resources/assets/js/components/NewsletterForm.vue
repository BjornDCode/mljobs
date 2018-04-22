<template>
    <div class="newsletter">
        <h2>Get the latest jobs in your inbox!</h2>
        <p>Receive a weekly email with the most interesting positions. We promise not to spam you!</p>
        <form @submit.prevent="submit">
            <input 
                class="input" 
                type="email" 
                placeholder="Email"
                :disabled="successful"
                v-model="email"
            >
            <button 
                class="button" 
                :class="{'loading': loading, 'successful': successful}" 
                :disabled="loading || successful" 
                type="submit"
            >
                Subscribe
            </button>
        </form>
        <p class="error" v-if="error">An error occurred</p>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                email: '',
                error: false,
                loading: false,
                successful: false
            }
        },

        methods: {
            submit() {
                this.loading = true;

                axios.post('/newsletter', { email: this.email })
                    .then(response => {
                        this.loading = false;
                        this.successful = true;
                        this.error = false;
                    })
                    .catch(error => {
                        this.loading = false;
                        this.successful = false;
                        this.error = true;
                    })
            }
        }
    }
</script>
