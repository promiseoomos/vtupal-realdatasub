<template>
    <div class="h-screen p-3">
        <p class="text-3xl text-center mt-3">Welcome to {{ appConfig.siteName }}</p>
        <div :style="{ 'background-color' : appConfig.siteTheme.authCardColor, 'color' : appConfig.siteTheme.authTextColor  }" class="mt-16 p-5 mx-auto w-full lg:w-1/3 rounded-lg">
            <div class="text-center">
                <p class="text-xl border-b-4 border-black mx-auto w-fit">Secured Login (Admin)</p>
            </div>

            <div class="text-left p-3">
                <form @submit.prevent="loginAdmin">
                    <div class="mt-3">
                        <label class="block mb-3 font-medium text-lg" for="email">Email:</label>
                        <input v-model="adminObj.email" class="p-3 rounded-md w-full ring-0" id="email" name="email" type="email" placeholder="example@gmail.com" />    
                    </div>

                    <div class="mt-3">
                        <label class="block mb-3" for="password">Password:</label>
                        <input v-model="adminObj.password" class="p-3 rounded-md w-full ring-3 ring-red-300 outline-0" id="password" name="password" type="password" placeholder="************" />        
                    </div>

                    <div class="text-center p-2 mt-3">
                        <button :style="{ 'background-color' : appConfig.siteTheme.buttonBgColor }" class="p-2 w-1/2 text-white rounded-md font-bold text-lg hover:bg-[#0a5556]"><span v-if="api_calling" class="animate-spin h-7 w-7 -mb-2 mr-3 pt-2 border-r-2 border-b-2 border-l-2 border-whtie rounded-full inline-block"></span> Login</button>
                        
                        <div v-if="responsereturned" :class="{ 'bg-green-300/[.6] text-green-800' : api_response.status, 'bg-red-400/[.40] text-red-900' : !api_response.status }" class="p-3 mt-3 text-left rounded-md">
                            <p>{{ api_response.message }}</p>
                        </div>


                        <p class="mt-3">Not yet registered? <NuxtLink :to="{ name : 'signup' }" class="text-[#4B4237] hover:text-[#978269]">Signup</NuxtLink></p>
                        <p class="mt-3"><NuxtLink :to="{ name : 'signup' }" class="text-[#4B4237] hover:text-[#978269]">Forgot Password</NuxtLink></p>

                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script type="text/javascript" setup>
import { adminStore } from "@/store/admin"

const config = useRuntimeConfig();
getBaseUrl(config)



const adminstore = adminStore()
const adminObj = reactive({
    email : "",
    password : ""
})

let api_response = ref({})
let responsereturned = ref(false)
let api_calling = ref(false)

const appConfig = useAppConfig();

const loginAdmin = async () => {
    responsereturned.value = false
    api_calling.value = true

    try {
        adminstore.loginAdmin(adminObj).then((response) => {
            api_calling.value = false
            api_response.value = response
            responsereturned.value = true

            if(adminstore.loggedin){
                navigateTo({
                    name : "admin"
                })
            }
        })    
    } catch (error) {
        api_calling.value = false
        api_response.value = error
        responsereturned.value = true
    }
    
}

</script>

<style lang="scss" scoped>

</style>