<template>
    <div :style="{ 'background-color' : appConfig.siteTheme.primaryColor }" class="h-20 py-3 flex flex-nowrap justify-between">
        <div class="flex flex-wrap px-2">
            <button class="mr-3 lg:hidden" v-if="route.name != 'login' && route.name != 'adminlogin' && route.name != 'signup'"  @click="$emit('show-sidebar')">
                <p v-if="!showsidebar" class="text-3xl text-black w-8 -mt-3"><img src="~/assets/align-justify.png"/></p>
                <p v-if="showsidebar" class="text-3xl text-black w-8 -mt-3"><img src="~/assets/cross-small.png"/></p>
            </button>
            
            <NuxtLink :to="{ name : 'dashboard' }" @click="showdropdown = false" class="text-md lg:text-3xl lg:ml-3 mt-2 text-white font-bold"><img class="w-[50px] h-[50px] lg:w-[70px]" src="../public/img/site_logo.png" /></NuxtLink>
            <p class="text-md lg:text-3xl mt-2 text-white font-bold"></p>
        </div>

        <div class="mr-1 mt-2" v-if="userstore.loggedin && route.name != 'login' && route.name != 'signup'">
            <div class="flex flex-wrap gap-4">
                <img src="~/assets/profile.png" class="rounded-full h-10 w-10 bg-emerald-400">
                <p class="text-sm lg:text-lg mt-2 lg:mt-1 flex text-white cursor-pointer" @click="showdropdown = !showdropdown"><span class="inline-block mt-1">{{ userstore.details.first_name }} {{ userstore.details.last_name.slice(0,1) }}</span> <img src="../public/img/caret-down.png" class="h-7 w-7 ml-1"></p>
            </div>
            <div v-if="showdropdown" class="cursor-pointer absolute right-1 rounded-lg bg-white w-40 pt-2 pb-2">
                <NuxtLink :to="{ name : 'dashboard-settings-myprofile' }" @click="showdropdown = false" class="hover:bg-gray-200 p-2 block w-full">My Profile</NuxtLink>
                <NuxtLink @click="logOut(); showdropdown = false" class="hover:bg-gray-200 p-2 block w-full">Logout</NuxtLink>
            </div>
        </div>
        <div class="mr-1 mt-2" v-if="adminstore.loggedin && route.name != 'adminlogin' && route.name != 'signup'">
            <div class="flex flex-wrap gap-4">
                <img src="~/assets/profile.png" class="rounded-full h-10 w-10 bg-emerald-400">
                <p class="text-sm lg:text-lg mt-2 lg:mt-1 text-white flex cursor-pointer" @click="showdropdown = !showdropdown"><span class="inline-block mt-1">{{ adminstore.details.first_name }} {{ adminstore.details.last_name.slice(0,1) }}</span> <img src="../public/img/caret-down.png" class="h-7 w-7 ml-1"></p>
            </div>
            <div v-if="showdropdown" class="cursor-pointer absolute right-1 rounded-lg bg-white w-40 pt-2 pb-2">
                <NuxtLink :to="{ name : 'admin-settings-site' }" @click="showdropdown = false" class="hover:bg-gray-200 p-2 block w-full">My Profile</NuxtLink>
                <NuxtLink @click="logOut(); showdropdown = false" class="hover:bg-gray-200 p-2 block w-full">Logout</NuxtLink>
            </div>
        </div>
    </div>
</template>

<script type="text/javascript" setup>
import { adminStore } from "~~/store/admin"
import { userStore } from "~~/store/user.js"

const route = useRoute()

const showsidebar = inject("showsidebar")

const userstore = userStore()
const adminstore = adminStore()

function logOut(){
    userstore.$reset()
    adminstore.$reset()
    sessionStorage.clear()

    navigateTo("/login")
}


const showdropdown = ref(false)

const appConfig = useAppConfig();
</script>

<style lang="scss" scoped>

</style>