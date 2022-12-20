<template>
    <div>
        <div class="p-3 text-center rounded-md w-11/12 bg-[#004E64]/[0.3]">
            <img src="../assets/add.png" class="h-10 w-10 mt-3 mx-auto">
            <p class="text-xl lg:text-4xl font-bold mt-5 h-16">{{ symbol }} {{ value }}</p>
            <p class="font-semibold text-md lg:text-xl mt-3 ">{{ title }}</p>
        </div>
    </div>
</template>

<script type="text/javascript" setup>

const props = defineProps({
    title : String,
    value : Number,
    icon : String,
    symbol : String
})


</script>

<style lang="scss" scoped>

</style>