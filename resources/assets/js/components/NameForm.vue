<template>
    <div class="smaller">
        <form @submit.prevent="update" class="pure-form pure-form-stacked">
            <fieldset>
            <label class="control-label">Name</label>
                <div class="pure-g">
                    <div class="pure-u-sm-1-2">
                        <input type="text" class="pure-u-23-24" placeholder="First name" v-model="firstName"></input>
                    </div>
                    <div class="pure-u-sm-1-2">
                        <input type="text" class="pure-u-23-24" placeholder="Last name" v-model="lastName"></input>
                    </div>
                </div>

                <button type="submit" class="pure-button pure-button-primary margintop1">Save</button>
            </fieldset> 
        </form>
        
    </div>
</template>

<script>
    export default {
        data ()  {
            return {
                lastName: '',
                firstName: ''
            };
        },

        methods: {
            update: function(event) {
                
            }
        }
    };
</script>

<style>
    .smaller {
        font-size: 0.75em;
    }
</style>