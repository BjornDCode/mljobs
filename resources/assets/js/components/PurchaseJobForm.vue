<template>
    <form @submit.prevent="handleFormSubmit" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <span class="form-group__label hide-mobile">Job</span>
            <input type="text" 
                    placeholder="Title*" 
                    v-model="form.job.title"
                    :class="{'has-error': hasError('job.title')}"
                    required
                >
            <textarea placeholder="Description* (Supports Markdown)" 
                    v-model="form.job.description"
                    :class="{'has-error': hasError('job.description')}"
                    required
            ></textarea>
        </div>
        <div class="form-group">
            <span class="form-group__label hide-mobile">Company</span>
            <input type="text" placeholder="Company Name" v-model="form.job.company">
            <logo-file-input @change="imageFile = $event"></logo-file-input>
        </div>
        <div class="form-group inline-group">
            <span class="form-group__label hide-mobile">Details</span>
            <input type="text" placeholder="Location (e.g. London, UK)" v-model="form.job.location">
            <input type="text" placeholder="Salary (e.g. 100k)" v-model="form.job.salary">
            <type-select-input v-model="form.job.type"></type-select-input>
            <input type="url" 
                placeholder="URL*" 
                v-model="form.job.apply_url"
                :class="{'has-error': hasError('job.apply_url')}"
                required
            >
        </div>
        <div class="form-group">
            <span class="form-group__label hide-mobile">Billing</span>
            <input 
                type="email" 
                placeholder="Email*"
                :class="{'has-error': hasError('email')}"
                v-model="form.email"
                required
                >
            <div ref="card" class="credit-card-input"></div>
        </div>
        <button class="button" 
                :class="{'loading': loading, 'successful': successful}" 
                :disabled="loading || successful"
        >
            <span class="success">
                <svg xmlns="http://www.w3.org/2000/svg" width="512" height="512" viewBox="0 0 442.533 442.533">
                    <path d="M434.539 98.499l-38.828-38.828c-5.324-5.328-11.799-7.993-19.41-7.993-7.618 0-14.093 2.665-19.417 7.993L169.59 247.248l-83.939-84.225c-5.33-5.33-11.801-7.992-19.412-7.992-7.616 0-14.087 2.662-19.417 7.992L7.994 201.852C2.664 207.181 0 213.654 0 221.269c0 7.609 2.664 14.088 7.994 19.416l103.351 103.349 38.831 38.828c5.327 5.332 11.8 7.994 19.414 7.994 7.611 0 14.084-2.669 19.414-7.994l38.83-38.828L434.539 137.33c5.325-5.33 7.994-11.802 7.994-19.417.004-7.611-2.669-14.084-7.994-19.414z" />
                </svg>
            </span>
            <span class="loader">
                <span class="circle"></span>
            </span>
            <span>Purchase Job Listing ($49)</span>
        </button>
        <div class="form-success" v-show="job">
            <p>
                Thank you for purchasing a featured job. You can view it right <a :href="jobUrl">here</a>.
            </p>
        </div>
        <div class="form-errors" v-if="formHasErrors">
            <ul>
                <li v-for="error in errors">{{ error[0] }}</li>
            </ul>
        </div>
    </form>
</template>

<script>
    import { isEmpty } from 'lodash'

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
                errors: {},
                job: undefined,
                successful: false,
                loading: false,
                imageFile: undefined,
                form: {
                    token: '',
                    logo: null,
                    email: '',
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

        computed: {
            jobUrl() {
                if (!this.job) return;
                return `/job/${this.job.id}`;
            },

            formHasErrors() {
                return !isEmpty(this.errors)
            }
        },

        mounted() {
            card.mount(this.$refs.card)
        },

        methods: {
            hasError(field) {
                return this.errors.hasOwnProperty(field)
            },

            handleFormSubmit() {
                this.loading = true

                stripe.createToken(card).then(result => {
                    if (result.error) {
                        this.loading = false
                        return
                    }

                    this.form.token = result.token.id

                    if (this.imageFile) {
                        this.uploadImage()
                    } else {
                        this.createJob();
                    }

                })
            },

            uploadImage(callback) {
                let formData = new FormData();
                formData.append('image', this.imageFile)
                axios.post('/images/upload', formData)
                    .then(response => {
                        this.form.logo = response.data.path
                        this.createJob()
                    })
                    .catch(errpr => {
                        this.loading = false
                    })
            },

            createJob() {
                axios.post('/featured-job/store', this.form)
                    .then(response => {
                        this.job = response.data
                        this.errors = {}
                        this.loading = false
                        this.successful = true
                    })
                    .catch(error => {
                        this.errors = error.response.data.errors
                        this.loading = false
                        this.successful = false
                    })
            }
        }
    }
</script>
