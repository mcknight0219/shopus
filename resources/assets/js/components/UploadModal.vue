<template>
    <div class="modal-mask" v-show="show" transition="modal">
        <div class="modal-wrapper">
            <div class="modal-container">
                <h4>Upload your photo customer will see</h4>
                <form @submit.prevent="upload" class="pure-form">
                    <input v-el:avatar type="file" class="file-selector" @change="onFileChange">
                    <img class="pure-img margindown" :src="image" alt="" width="200" height="200"></img>

                    <button class="pure-button pure-button-primary">Save</button>
                    <button class="pure-button" @click="show = false">Cancel</button>
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
                file: null,
                image: 'photo/profile', // for preview
                error: ''
            };
        },

        methods: {
            upload: function () {
                if (! this.file) { 
                    this.error = 'Please choose your best photo !';
                    return;
                }

                var formData = new FormData();
                formData.append('photo', this.file);
                this.$http.post('profile/edit', formData).then(function (response) { 
                    this.error = '';
                    this.show = false;
                }, function (response) { 
                    this.error = "Oops ! something is wrong with our stupid server"
                });
            },

            onFileChange: function (e) { 
                e.preventDefault();
                var files = this.$els.avatar.files;
                this.file = files[0];

                var reader = new FileReader();
                var vm = this;
                reader.onload = function (e) { 
                    vm.image = e.target.result;
                };
                reader.readAsDataURL(files[0]);
            }
        }
    }
</script>

<style>
    h4 {
        margin-bottom: 5px;
    }

    .file-selector { 
        margin-bottom: 25px;
    }

    .margindown { 
        margin-bottom: 15px;
    }

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

    .modal-wrapper { 
        display: table-cell;
        vertical-align: middle;
    }

    .modal-container { 
        font-size: 0.75em;
        width: 300px;
        margin: 0 auto;
        padding: 20px 30px;
        background-color: #fff;
        border-radius: 2px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, .33);
        transition: all .3s ease;
    }

    .modal-enter, .modal-leave { 
        opacity: 0;
    }

    .modal-enter .modal-container,
    .modal-leave .modal-container { 
        -webkit-transform: scale(1.1);
        transform: scale(1.1);
    }
</style>
