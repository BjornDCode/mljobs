<template>
    <select :value="value" @change="handleChangeEvent" >
        <option selected disabled value="" class="default">Hours</option>
        <option value="Full Time">Full Time</option>
        <option value="Part Time">Part Time</option>
        <option value="Internship">Internship</option>
        <option value="Freelance">Freelance</option>
        <option value="Temporary">Temporary</option>
    </select>
</template>

<script>
    export default {
        props: ['value'],
        methods: {
            handleChangeEvent(e) {
                this.styleSelect(e.target);
                this.$emit('input', e.target.value)
            },
            styleSelect(element) {
                element.classList.add('selected')
            },
        }
    }
</script>
