<template>
    <label class="file-input" 
        :class="{'has-preview': this.logoPreview}"
        :style="this.logoPreview ? `background-image: url('${this.logoPreview}');` : ''"
    >
        <span>Logo</span>
        <input type="file" @change="updateLogoPreview" accept="image/*" :value="value">
    </label>
</template>

<script>
    export default {
        props: ['value'],

        data() {
            return {
                logoPreview: null
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
