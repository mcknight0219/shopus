<template>
    <div class="smaller">
        <form @submit.prevent="update" class="pure-form pure-form-stacked">
            <fieldset>
                <label class="control-label">Name</label>
                <div class="pure-g">
                    <div class="error pure-u-1" v-show="error">{{ error }}</div>

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
        props: { 
            profile: { 
                type: Object,
                required: true,
                twoWay: true
            }
        },

        data ()  {
            return {
                lastName: '',
                firstName: '',
                error: ''
            };
        },

        computed: { 
            data: function () { 
                return { 
                    lastName: this.lastName,
                    firstName: this.firstName
                };
            }
        },
        
        methods: { 
            update: function () { 
                // reset everything
                if (this.lastName.length === 0 || this.firstName.length === 0) { 
                    this.error = 'Please enter a value';
                    return;
                }
                
                this.$http.post('profile/edit', this.data).then(function (response) { 
                    this.error = '';
                    $('#namecell').next().webuiPopover('hide');                    
                    this.profile.name = this.lastName + ' ' + this.firstName
                }, function (error) { 
                    this.error = 'Hmm, try again later, will ya ?';
                }); 
            }
        }
    };
</script>

<style>
    .smaller {
        font-size: 0.75em;
    };

</style>
