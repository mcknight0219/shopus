<template>
    <div class="smaller">
        <form @submit.prevent="onSubmit" class="pure-form pure-form-stacked">

            <label for="country">Country</label>
            <input type="text" v-model="country"></input>

            <label for="city">City</label>
            <input type="text" v-model="city"></input>

            <button type="submit" class="pure-button pure-button-primary margintop1">Save</button>
            
        
        </form>
    </div>
</template>

<script>
    export default {
        props: ['onSubmit'],

        data () {
            return {
                city: '',
                country: ''
            };
        }
    }
</script>

<style>
    .smaller {
        font-size: 0.75em;
    }
</style>