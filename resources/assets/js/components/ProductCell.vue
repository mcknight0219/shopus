<template>
    <div class="productcell">
        <img :src="product.front" class="productphoto">
        <div class="productcontent">
            <div class="titlestack">
                <div class="title singleline">{{ product.name }}</div>
                <div class="caption singleline">{{ product.description }}</div>
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

        data () {
            return {

            };
        }
    }
</script>

<style>
    
</style>