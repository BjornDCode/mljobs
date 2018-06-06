<template>
    <div class="credit-card-input"></div>
</template>

<script>
    export default {
        mounted() {
            this.stripe = Stripe(window.AIJobs.stripe.publicKey)
            this.elements = this.stripe.elements()

            const cardOptions = {
                style: {
                    base: {
                        fontSize: '16px',
                        lineHeight: '2.5rem',
                        fontFamily: 'Ubuntu',
                        fontWeight: 'bold'
                    }
                }
            }
            this.card = this.elements.create('card', cardOptions)

            this.card.mount(this.$el)
        },
        methods: {
            createToken() {
                return this.stripe.createToken(this.card)
            }
        }
    }
</script>
