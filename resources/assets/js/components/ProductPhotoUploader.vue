<template>
<div class="image-upload">
    <label for="front-file-input">
        <img src="http://goo.gl/pB9rpQ" width="60px"/>
    </label>
    <input type="file" name="front-file-input"/>
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
</style>
