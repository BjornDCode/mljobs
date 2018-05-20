<template>
    <form @submit.prevent="handleFormSubmit" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <span class="form-group__label hide-mobile">Job</span>
            <input type="text" 
                    placeholder="Title*" 
                    v-model="form.job.title"
                    :class="{'has-error': hasError('job.title')}"
                >
            <textarea placeholder="Description*" 
                    v-model="form.job.description"
                    :class="{'has-error': hasError('job.description')}"
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
            >
        </div>
        <div class="form-group">
            <span class="form-group__label hide-mobile">Billing</span>
            <input 
                type="email" 
                placeholder="Email*"
                :class="{'has-error': hasError('email')}"
                v-model="form.email"
                >
            <div ref="card" class="credit-card-input"></div>
        </div>
        <button class="button" :class="{'loading': loading}">
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
                errors: {},
                job: undefined,
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
                        this.loading = false
                    })
                    .catch(error => {
                        this.errors = error.response.data.errors
                        this.loading = false
                    })
            }
        }
    }
</script>
