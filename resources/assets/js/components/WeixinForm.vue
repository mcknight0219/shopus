<template>
    <div class="smaller">
        <form @submit.prevent="onSubmit" class="pure-form pure-form-stacked">
            <label for="weixin">Weixin ID</label>
            <input type="text" v-model="weixin"></input>

            <button type="submit" class="pure-button pure-button-primary margintop1">Save</button>
        </form>
    </div>
</template>

<script>
    export default {
        props: ['onSubmit'],

        data () {
            return {
                weixin: ''
            };
        }
    }
</script>

<style>
    .smaller {
        font-size: 0.75em;
    }
</style>