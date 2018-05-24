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
                <span class="success">
                    <svg xmlns="http://www.w3.org/2000/svg" width="512" height="512" viewBox="0 0 442.533 442.533">
                        <path d="M434.539 98.499l-38.828-38.828c-5.324-5.328-11.799-7.993-19.41-7.993-7.618 0-14.093 2.665-19.417 7.993L169.59 247.248l-83.939-84.225c-5.33-5.33-11.801-7.992-19.412-7.992-7.616 0-14.087 2.662-19.417 7.992L7.994 201.852C2.664 207.181 0 213.654 0 221.269c0 7.609 2.664 14.088 7.994 19.416l103.351 103.349 38.831 38.828c5.327 5.332 11.8 7.994 19.414 7.994 7.611 0 14.084-2.669 19.414-7.994l38.83-38.828L434.539 137.33c5.325-5.33 7.994-11.802 7.994-19.417.004-7.611-2.669-14.084-7.994-19.414z" />
                    </svg>
                </span>
                <span class="loader">
                    <span class="circle"></span>
                </span>
                <span>Subscribe</span>
            </button>
        </form>
        <p class="error" v-if="error">An error occurred</p>
        <p class="success" v-if="successful">Thank you for subscribing!</p>
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
