<template>
    <div>
        <NuxtLink :to="!dropdown ? { name : name } : ''" @click="!dropdown ? $emit('routeChanged') : ''; showdd = !showdd" class="cursor-pointer text-white w-full h-full flex flex-nowrap justify-between p-3 hover:bg-gray-700">
            <!-- <img src="~/assets/logo.jpg" alt="Feed" class="w-8 h-7 mr-5"> -->
            <div class="font-bold pt-2 text-md">{{ text }}</div>
            <div v-if="dropdown">
                <img src="~/assets/caret-down.png" v-if="showdd == false" class="h-10 w-10">
                <img src="~/assets/caret-up.png" v-if="showdd == true" class="h-10 w-10 cursor-pointer">
            </div>
        </NuxtLink>
        <div class="bg-[#2c2721] text-white" v-if="showdd == true">
            <div v-for="(child, index) in children" :key="index" class="pl-7 py-2 hover:bg-gray-700 cursor-pointer text-md">
                <NuxtLink :to="{ name : child.name , params :  child.params, query : child.query }" @click="$emit('routeChanged')" class="w-full inline-block text-md">{{ child.text }}</NuxtLink>
            </div>
            
        </div>
    </div>
</template>

<script type="text/javascript" setup>
let showdd = ref(false)
const props = defineProps({
    name : String,
    path : String,
    text : String,
    dropdown : Boolean,
    children : [Object, Array]
})
</script>

<style lang="scss" scoped>

</style>