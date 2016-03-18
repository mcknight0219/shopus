<template>
<div class="image-upload">
    <label for="front-file-input">
        <img src="http://lcsinfo.com/wp-content/uploads/2012/06/product-placeholder.png" width="55px"/>
    </label>
    <input type="file" name="front-file-input" style="display: none"/>

    <div class="caption">{{ name }}</div>
</div>
</template>

<script>
    export default { 
        props: { 
            name: {
                type: String,
                required: true,
                twoWay: false
            }
        }
    }
</script>

<style>
    .input { 
        display: none;
    }

    .image-upload img { 
        cursor: pointer;
    }

    .caption { 
        margin-top: 3px;
    }
</style>
