<template>
    <div>
        <div :style="{ 'background-color' : appConfig.siteTheme.sidebarBgColor, 'color' : appConfig.siteTheme.sidebarTextColor  }" class=" h-full pt-10">
            <NuxtLink :to="{ name : sidebar.base}" active-class="bg-gray-500/[0.1]" @click="$emit('routeChanged')" class="text-white w-full flex p-3 h-14 hover:bg-gray-700">
                <!-- <img src="~/assets/logo.jpg" alt="Feed" class="w-8 h-7 mr-5"> -->
                <span class="font-black text-md">Dashboard</span>
            </NuxtLink>
            <SidebarLinkBase v-for="(sidelink, index) in sidebar.bars" :key="index" :text="sidelink.text" :name="sidelink.name" @route-changed="$emit('routeChanged')" :base="sidebar.base" :dropdown="sidelink.dropdown" :children="sidelink.children" />
            <NuxtLink :to="''"  @click="logOut" class="text-white w-full flex p-3 hover:bg-gray-700 cursor-pointer">
                <!-- <img src="~/assets/logo.jpg" alt="Feed" class="w-8 h-7 mr-5"> -->
                <span class="font-black">Logout</span>
            </NuxtLink>
        </div>
    </div>
</template>

<script type="text/javascript" setup>
import { userStore } from "@/store/user.js"

const userstore = userStore()

const props = defineProps({
    sidebar : Object
})

const appConfig = useAppConfig();

function logOut(){
    userstore.$reset()
    navigateTo("/login")
}
</script>

<style lang="scss" scoped>

</style>