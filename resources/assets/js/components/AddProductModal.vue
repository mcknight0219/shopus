<template>
    <div class="modal-mask" v-show="show" transition="modal">
        <div class="modal-wrapper">
            <div class="modal-container">
                    <form @submit.prevent="upload" class="pure-form pure-form-stacked">
                        <h2><b>Add product</b></h2>                                
                        <div class="pure-g">
                            <div class="pure-u-2-3">
                                <div class="pure-u-2-5">
                                    <label for="name">Name</label>
                                    <input type="text" class="pure-u-23-24">
                                </div>
                                <div class="pure-u-2-5">
                                    <label for="brand">Brand</label>
                                    <input type="text" class="pure-u-23-24">
                                </div>
                                <div class="pure-u-1-5">
                                    <label for="price">Price</label>
                                    <input type="text" class="pure-u-23-24">
                                </div>

                                <div class="pure-u-1">
                                    <label for="description">Description</label>
                                    <textarea id="product-description" name="description" cols="30" rows="5"></textarea>
                                </div>

                                <div class="pure-u-1">
                                    <label for="publish-checkbox" class="pure-checkbox">
                                        <input type="checkbox"> Publish right now !
                                    </label>
                                </div>
                            </div>
                            
                            <div class="pure-u-1-3">
                                <div class="divider"></div>
                                <product-photo name="front"></product-photo>
                            </div>

                        </div>
                        
                        <button class="pure-button pure-button-primary" type="submit">Save</button>
                        <button class="pure-button" @click="show=false">Cancel</button>
                    </form>
            </div>
        </div>
    </div>
</template>

<script>
    export default { 
        props: { 
            show: { 
                type: Boolean, 
                required: true,
                twoWay: true
            }
        },

        data () { 
            return { 
                brand:  '',
                name:   '',
                descripton: '',
                price: '',
                publish: false
            };
        },

        methods: { 
            upload: function () { 
            }
        },

        components: { 
            'product-photo': require('./ProductPhotoUploader.vue') 
        }
    }
</script>

<style>
    .modal-mask {
        position: fixed;
        z-index: 9998;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, .5);
        display: table;
        transition: opacity .3s ease;
    }

    .moda-wrapper {
        display: table-cell;
        vertical-align: middle;
    }

    .modal-container { 
        width: 650px;
        margin: 0 auto;
        padding: 20px 30px;
        background-color: #fff;
        border-radius: 2px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, .33);
        transition: all .3s ease;
        position: relative;
    }

    .moda-enter, .modal-leave { 
        opacity: 0;
    }

    .modal-enter .modal-container,
    .modal-leave .modal-container { 
        -webkit-transform: scale(1.1);
        transform: scale(1.1);
    }

    .divider {
        border-left:1px solid silver; 
        border-right:1px solid silver; 
        height:275px;
        position:absolute;
        right:218px;
        top:70px; 
    }
</style>
