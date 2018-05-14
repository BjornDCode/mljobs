<template>
    <form @submit.prevent="submit">
        <div class="form-group">
            <span class="form-group__label hide-mobile">Job</span>
            <input type="text" 
                    placeholder="Title" 
                    v-model="form.job.title"
                    :class="{'has-error': hasError('job.title')}"
                >
            <textarea placeholder="Description" 
                    v-model="form.job.description"
                    :class="{'has-error': hasError('job.description')}"
            ></textarea>
        </div>
        <div class="form-group">
            <span class="form-group__label hide-mobile">Company</span>
            <input type="text" placeholder="Company Name" v-model="form.job.company">
            <label class="file-input" 
                :class="{'has-preview': this.logoPreview}"
                :style="this.logoPreview ? `background-image: url('${this.logoPreview}');` : ''"
            >
                <span>Logo</span>
                <input type="file" @change="uploadLogo">
            </label>
        </div>
        <div class="form-group inline-group">
            <span class="form-group__label hide-mobile">Details</span>
            <input type="text" placeholder="Location" v-model="form.job.location">
            <input type="text" placeholder="Salary" v-model="form.job.salary">
            <select @change="styleSelect" v-model="form.job.type">
                <option selected disabled value="" class="default">Hours</option>
                <option value="Full Time">Full Time</option>
                <option value="Part Time">Part Time</option>
                <option value="Internship">Internship</option>
                <option value="Freelance">Freelance</option>
                <option value="Temporary">Temporary</option>
            </select>
            <input type="url" 
                placeholder="URL" 
                v-model="form.job.apply_url"
                :class="{'has-error': hasError('job.apply_url')}"
            >
        </div>
        <div class="form-group">
            <span class="form-group__label hide-mobile">Billing</span>
            <input 
                type="email" 
                placeholder="Email"
                :class="{'has-error': hasError('email')}"
                >
            <div ref="card" class="credit-card-input"></div>
        </div>
        <button class="button">Purchase Job Listing ($49)</button>
    </form>
</template>

<script>

    const stripe = Stripe(window.AIJobs.stripe.publicKey)
    const elements = stripe.elements()

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
    const card = elements.create('card', cardOptions)

    export default {
        data() {
            return {
                logoPreview: null,
                errors: {},
                form: {
                    token: '',
                    logo: null,
                    job: {
                        title: '',
                        description: '',
                        company: '',
                        location: '',
                        salary: '',
                        type: '',
                        apply_url: ''
                    }
                }
            }
        },

        mounted() {
            card.mount(this.$refs.card)
        },

        methods: {
            styleSelect(e) {
                e.target.classList.add('selected')
            },

            hasError(field) {
                return this.errors.hasOwnProperty(field)
            },

            uploadLogo(e) {
                const files = e.target.files;
                const reader = new FileReader();

                reader.onload = (e) => {
                    this.logoPreview = e.target.result
                }
                
                reader.readAsDataURL(files[0])
                this.form.logo = files[0]
            },

            submit() {
                stripe.createToken(card).then(result => {
                    if (result.error) return

                    this.form.token = result.token.id

                    axios.post('/featured-job/store', this.form)
                        .then(data => {
                            console.log(data)
                        })
                        .catch(error => {
                            this.errors = error.response.data.errors
                        })
                })
            }
        }
    }
</script>
