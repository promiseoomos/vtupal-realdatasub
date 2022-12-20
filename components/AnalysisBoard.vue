<template>
    <div class="w-5/12 p-2 lg:w-[28%] lg:m-3 h-32 lg:h-44">
        <div class="px-3 py-6 lg:py-8 rounded-md bg-[#F7DBA7]/[.7] text-center hover:shadow-xl hover:shadow-[#4B4237]/[0.3] hover:scale-105 hover:border-2 hover:border-[#5f420e]">
            <div class="text-center text-xl">
                ₦{{ useFormatDigits(amount) }}
            </div>
            <div class="mt-8 font-medium text-md lg:text-xl">
                {{ text }}
            </div>
        </div>
    </div>
</template>

<script type="text/javascript" setup>
const props = defineProps({
    icon : String,
    text : String,
    link : String,
    amount : String
})

// const icon = ref('gotv.jpg')

</script>

<style lang="scss" scoped>

</style>