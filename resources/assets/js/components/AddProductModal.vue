<template>
    <div class="modal-mask" v-show="show" transition="modal">
        <div class="modal-wrapper">
            <div class="product-modal-container">
                <form @submit.prevent="upload" class="pure-form pure-form-stacked">
                        <legend>
                            <div class="add-product-title"><strong>添加商品详情</strong></div>                                
                        </legend>
                        <div class="pure-g">
                            <div class="pure-u-1-2">
                                <div class="pure-u-2-5">
                                    <label for="name">名称</label>
                                    <input v-model="name" type="text" class="pure-u-23-24">
                                </div>
                                <div class="pure-u-2-5">
                                    <label for="brand">品牌</label>
                                    <input v-model="brand" type="text" class="pure-u-23-24">
                                </div>
                                <div class="pure-u-1-5">
                                    <label for="price">价格</label>
                                    <input v-model="price" type="text" class="pure-u-23-24">
                                </div>

                                <div class="pure-u-1">
                                    <label for="description">商品详情</label>
                                    <textarea v-model="description" id="product-description" name="description" cols="23" rows="5"></textarea>
                                </div>

                                <div class="pure-u-1">
                                    <label for="publish-checkbox" class="pure-checkbox">
                                        <input type="checkbox" v-model="publish"> 即时上架 !
                                    </label>
                                </div>
                            </div>
                            
                            <div class="pure-u-1-2">
                                <div class="add-product-subtitle"><p>上传商品靓照</p></div>
                                <product-photo name="front" :file.sync="files.front"></product-photo>
                                <product-photo name="back" :file.sync="files.back"></product-photo>
                                <product-photo name="custom1" :file.sync="files.custom1"></product-photo>
                                <product-photo name="custom2" :file.sync="files.custom2"></product-photo>
                            </div>
                        </div>
                       
                        <div class="singleline">
                            <error>{{ error }}</error>
                        </div>

                        <button class="pure-button pure-button-primary large-button margintop1" type="submit">保存</button>
                        <button class="pure-button button-gap margintop1" @click="close">取消</button>
                    </form>

                    <div class="progress-overlay" v-if="isUploading">
                        <spinner style="margin-top:30%"></spinner>
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
                description: '',
                price: '',
                publish: false,
                currency: '',
                files: { 
                    front: null,
                    back: null,
                    custom1: null,
                    custom2: null
                },

                isUploading: false,
                error: ''
            };
        },

        methods: { 
            upload: function () {
                var vm = this;
                this.isUploading = true;
                var errMsg = this.validateInput();
                if (errMsg.length > 0) {
                    setTimeout(function() { 
                        vm.isUploading = false;
                        vm.error = errMsg;
                    }, 2000);
                    return;
                }

                var formData = new FormData();
                formData.append('name',     this.name);
                formData.append('brand',    this.brand);
                formData.append('price',    this.price);
                formData.append('currency', this.currency);
                formData.append('description', this.description);
                formData.append('publish',  this.publish);

                
                ['front', 'back', 'custom1', 'custom2'].forEach(function (typeName) { 
                    if (vm.files[typeName] instanceof File) { 
                        formData.append(typeName, vm.files[typeName]);
                    }
                });
                
                
                this.$http.post('product/add', formData).then(function (response) { 
                    if (response.ok) { 
                        vm.close();    
                    } 
                }, function (error) { 
                    // TODO put up a flash message on page top 
                    // is probably a much better idea.
                    console.log(JSON.parse(error.data));
                    vm.close();
                });
            },

            validateInput: function () {
                var vm = this;
                var props = ['name', 'brand', 'price', 'description'];
                for (var i = 0; i <= props.length - 1; i++) {
                    if(vm[props[i]].length === 0) {
                        return '请输入商品的信息';
                    }
                }
                
                if (vm.files.front === null) {
                    return '一张正脸都不给看嘛';
                }

                return '';
            },
            
            /**
             * It's product creation page. So after close, we need to restore every
             * state to its default values including the photo uploader
             */
            close: function () { 
                this.restore();
                this.error = '';
                this.show = false;
            },

            restore: function () { 
                this.files.front = null; 
                this.files.back = null; 
                this.files.custom1 = null; 
                this.files.custom2 = null; 
                this.brand = '';
                this.name = '';
                this.description = '';
                this.price = '';
                this.currency = '';
                this.publish = false;
                this.isUploading = false;
            }
        },

        components: { 
            'product-photo': require('./ProductPhotoUploader.vue'),
            'spinner': require('./Spinner.vue'),
            'error': require('./Error.vue')
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
        background-color: rgba(0, 0, 0, .2);
        display: table;
        transition: opacity .3s ease;
    }

    .moda-wrapper {
        display: table-cell;
        vertical-align: middle;
    }

    .product-modal-container { 
        font-size: .8em;
        width: 650px;
        margin: 0 auto;
        padding: 20px 30px;
        background-color: #fff;
        border-radius: 2px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, .33);
        transition: all .3s ease;
        position: relative;
    }

    .progress-overlay { 
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(0, 0, 0, .57);
        z-index: 9999;
    }

    .moda-enter, .modal-leave { 
        opacity: 0;
    }

    .modal-enter .modal-container,
    .modal-leave .modal-container { 
        -webkit-transform: scale(1.1);
        transform: scale(1.1);
    }

    .button-gap { 
        margin-left: 10px;
    }

    .large-button { 
        width: 120px;
    }

    .add-product-title { 
        font-size: 1.5em;
        margin-bottom: 16px;
    }

    .add-product-subtitle { 
        margin-top: -0.5rem;
        text-align: center;
    }
</style>
