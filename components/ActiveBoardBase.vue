<template>
    <div>
        <NuxtLink :to="{ name : link, query : query, params : params }">
            <div class="p-2 rounded-md bg-[#F7DBA7] text-center hover:shadow-xl hover:shadow-[#4B4237]/[0.3] hover:scale-105 hover:border-2 hover:border-[#5f420e]" :class="{ 'bg-[#dea843] scale-110 shadow-xl shadow-[#4B4237]/[0.3] border border-[#5f420e]' : $route.query.type == text || $route.query.network == text || $route.query.disco == text }">
                <div class="text-center">
                    <img  :src="'../img/' + icon" class="inline-block h-12 w-24" />
                </div>
                <div class="mt-2 font-medium text-xs lg:text-lg">
                    {{ text }}
                </div>
            </div>
        </NuxtLink>
        
    </div>
</template>

<script type="text/javascript" setup>
const props = defineProps({
    icon : String,
    text : String,
    link : String,
    params : Object,
    query : Object
})

</script>

<style lang="scss" scoped>

</style>