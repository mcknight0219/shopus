<template>
    <div class="modal-mask" v-show="show" transition="modal">
        <div class="modal-wrapper">
            <div class="modal-container">
                <div class="pure-g">
                    <form @submit.prevent="upload" class="pure-form pure-form-stacked">
                        <fieldset>
                            <legend>Add a product</legend>
                            
                        </fieldset>
                    </form>
                </div>
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
    
    }

    .moda-enter, .modal-leave { 
        opacity: 0;
    }

    .modal-enter .modal-container,
    .modal-leave .modal-container { 
        -webkit-transform: scale(1.1);
        transform: scale(1.1);
    }
</style>
