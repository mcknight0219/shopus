<template>
    <div class="smaller">
        <form @submit.prevent="update" class="pure-form pure-form-stacked">
            <div class="error" v-show="error">{{ error }}</div>

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
        props: { 
            profile: { 
                type: Object,
                required: true,
                twoWay: true
            }
        },

        data () {
            return {
                city: '',
                country: '',
                error: ''
            };
        },

        computed: { 
            data: function () {
                return {  
                    country: this.country,
                    city: this.city
                };
            }
        },

        methods: { 
            update: function () { 
                if (this.city.length === 0 || this.country.length === 0) {
                    this.error = 'Please enter a value';            
                    return;
                }

                this.$http.post('profile/edit', this.data).then(function (response) { 
                    this.error = '';
                    $('#addresscell').next().webuiPopover('hide');
                    this.profile.city = this.city;
                    this.profile.country = this.country;
                }, function (error) { 
                    this.error = 'Hmm, please try again later, will ya ?';
                });                 
            }
        }
    }
</script>

<style>
    .smaller {
        font-size: 0.75em;
    }
</style>
