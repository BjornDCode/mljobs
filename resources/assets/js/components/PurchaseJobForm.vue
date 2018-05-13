<template>
    <form>
        <div class="form-group">
            <span class="form-group__label hide-mobile">Job</span>
            <input type="text" placeholder="Title" v-model="form.title">
            <textarea placeholder="Description" v-model="form.description"></textarea>
        </div>
        <div class="form-group">
            <span class="form-group__label hide-mobile">Company</span>
            <input type="text" placeholder="Company Name" v-model="form.company">
            <label class="file-input" 
                :class="{'has-preview': this.logoPreview}"
                :style="this.logoPreview ? `background-image: url('${this.logoPreview}');` : ''"
            >
                <span>Company Logo</span>
                <input type="file" @change="updateLogoPreview">
            </label>
        </div>
        <div class="form-group inline-group">
            <span class="form-group__label hide-mobile">Details</span>
            <input type="text" placeholder="Location" v-model="form.location">
            <input type="text" placeholder="Salary" v-model="form.salary">
            <select @change="styleSelect" v-model="form.type">
                <option selected disabled value="" class="default">Hours</option>
                <option value="Full Time">Full Time</option>
                <option value="Part Time">Part Time</option>
                <option value="Internship">Internship</option>
                <option value="Freelance">Freelance</option>
                <option value="Temporary">Temporary</option>
            </select>
            <input type="url" placeholder="URL" v-model="form.apply_url">
        </div>
        <div class="form-group">
            Billing
        </div>
        <button class="button">Purchase Job Listing</button>
    </form>
</template>

<script>
    export default {
        data() {
            return {
                logoPreview: null,
                form: {
                    token: '',
                    title: '',
                    description: '',
                    company: '',
                    company_logo: '',
                    location: '',
                    salary: '',
                    type: '',
                    apply_url: '',
                    featured: 1
                }
            }
        },

        methods: {
            styleSelect(e) {
                e.target.classList.add('selected');
            },

            updateLogoPreview(e) {
                const files = e.target.files;
                const reader = new FileReader();

                // Image selected and then removed
                if (files.length === 0) {
                    console.log('cancelled')
                }

                reader.onload = (e) => {
                    this.logoPreview = e.target.result
                    console.log('preview', this.logoPreview)
                    console.log('target', e.target.result)
                }
                
                reader.readAsDataURL(files[0]);
                // console.log(files)
            }
        }
    }
</script>
