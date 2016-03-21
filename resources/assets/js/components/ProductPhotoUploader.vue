<template>
<div class="image-upload">
    <div class="title">{{ displayName }}</div>
    <label for="{{ name }}-file-input">
        <img :src="image" width="128px" height="128px"/>
    </label>
    <input v-el:selector @change="onFileChange" type="file" id="{{ name }}-file-input" style="display: none"/>
</div>
</template>

<script>
    export default { 
        props: { 
            name: {
                type: String,
                required: true,
                twoWay: false
            },

            file: { 
                default: null,
                required: true,
                twoWay: true
            }
        },

        data () { 
            return { 
                image: 'http://lcsinfo.com/wp-content/uploads/2012/06/product-placeholder.png',
            }
        },

        watch: { 
            'file': function(val, oldVal) { 
                if (val === null && oldVal !== null) { 
                    this.image = 'http://lcsinfo.com/wp-content/uploads/2012/06/product-placeholder.png';
                }
            }
        },

        computed: { 
            displayName: function() { 
                return { 
                    'front'     : '正面',
                    'back'      : '背面',
                    'custom1'   : '任意一',
                    'custom2'   : '任意二'
                }[this.name];
            }
        },

        methods: { 
            onFileChange: function(e) { 
                e.preventDefault();
                this.file = this.$els.selector.files[0];
                if (this.file === undefined) { 
                    return;
                }

                var reader = new FileReader();
                var vm = this;
                reader.onload = function (e) { 
                    vm.image = e.target.result;
                }
                reader.readAsDataURL(vm.file);
            }
        }
    }
</script>

<style>
    .image-upload img { 
        cursor: pointer;
        border-radius: 9px;
    }

    .image-upload { 
        display: inline-block;
        float: left;
        margin-left: 15px;
    }

    .image-upload .title { 
        margin-bottom: 3px;
        text-align: center;
        font-size: 1.1em;
    }
</style>
