<template>
    <div>
        <!-- <img :src="'/' + logo" alt="No logo"> -->
        <logo-file-input :existing-logo="logo" @change="handleFileChange"></logo-file-input>
        <input type="text" name="company_logo" v-model="imagePath">
    </div>
</template>

<script>
    export default {
        props: ['logo'],

        data() {
            return {
                imageFile: undefined,
                imagePath: this.logo
            }
        },
        methods: {
            handleFileChange(image) {
                this.imageFile = image
                this.uploadImage()
                // this.uploadImage();
            },
            uploadImage() {
                let formData = new FormData();
                formData.append('image', this.imageFile)
                axios.post('/images/upload', formData)
                    .then(response => {
                        this.imagePath = response.data.path
                    })
                    .catch(errpr => {
                        this.loading = false
                    })
            },
        }
    }
</script>
