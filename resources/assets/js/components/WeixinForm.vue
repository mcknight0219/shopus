<template>
    <div class="smaller">
        <form @submit.prevent="update" class="pure-form pure-form-stacked">
            <div class="error">{{ error }}</div>

            <label for="weixin">Weixin ID</label>
            <input type="text" v-model="weixin"></input>

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
                weixin: '',
                error: ''
            };
        },

        computed: { 
            data: function () { 
                return { 
                    weixin: this.weixin
                };
            }
        },

        methods: { 
            update: function () { 
                if (this.weixin.length === 0) { 
                    this.error = "Please enter a value";
                    return;
                }

                this.$http.post('profile/edit', this.data).then(function (response) { 
                    this.error = '';
                    this.profile.weixin = this.weixin;
                    $('#weixincell').next().webuiPopover('hide');                    
                }, function (error) { 
                    this.error = "Hmm, please try again later, will ya ?"; 
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
