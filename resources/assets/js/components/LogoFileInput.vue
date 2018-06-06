<template>
    <label class="file-input" 
        :class="{'has-preview': this.logoPreview || this.existingLogo}"
        :style="this.logoPreview || this.existingLogo ? `background-image: url('${this.logoPreviewUrl}');` : ''"
    >
        <span>Logo</span>
        <input type="file" @change="updateLogoPreview" accept="image/*" :value="value">
    </label>
</template>

<script>
    export default {
        props: ['value', 'existingLogo'],

        data() {
            return {
                logoPreview: null
            }
        },

        computed: {
            logoPreviewUrl() {
                return this.logoPreview ? this.logoPreview : '/' + this.existingLogo;
            }
        },

        methods: {
            updateLogoPreview(e) {
                const files = e.target.files;
                const reader = new FileReader();

                reader.onload = (e) => {
                    this.logoPreview = e.target.result
                }

                reader.readAsDataURL(files[0])
                this.$emit('change', files[0])
            }
        }
    }
</script>
