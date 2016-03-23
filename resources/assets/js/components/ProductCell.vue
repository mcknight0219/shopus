<template>
    <div class="productcell">
        <img :src="src" class="productphoto">
        <div class="productcontent">
            <div class="titlestack">
                <div class="title singleline">{{ product.name }}</div>
                <div class="caption singleline nowrap">{{ product.description }}</div>
            </div>
        </div>
    </div>    
</template>

<script>
    export default { 
        props: { 
            product : {
                type: Object,
                required: true,
                twoWay: true
            }
        },

        computed: {
            src: function () {
                return "photo/product/" + this.product.id + '/front';
            }
        },

        data () {
            return {

            };
        }
    }
</script>

<style>
    .nowrap {
        white-space: nowrap;
    }
</style>