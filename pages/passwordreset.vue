<template>
    <div>
        <div class="h-screen p-3 pb-3">
            <p class="text-3xl text-center mt-3">Welcome to Akfemtopup</p>
            <div class="mt-16 p-5 bg-[#F7DBA7]/[.5] mx-auto w-full lg:w-1/3 rounded-lg mb-7">
                <div class="text-center">
                    <p class="text-xl border-b-4 border-black mx-auto w-fit">Reset your Password</p>
                </div>

                <div class="text-left p-3">
                    
                        <div v-if="route.query.token">
                        
                            <form @submit.prevent="resetPassword">
                                <div class="mt-3">
                                    <label class="block mb-3 pl-2" for="password">Password:</label>
                                    <input v-model="resetObj.password" class="p-3 rounded-md w-full ring-3 ring-red-300 outline-0" id="password" name="password" type="password" required placeholder="************" />        
                                </div>

                                <div class="mt-3">
                                    <label class="block mb-3 pl-2" for="cpassword">Confirm Password:</label>
                                    <input v-model="resetObj.confirm_password" @keyup="confirmpassword()" class="p-3 rounded-md w-full ring-3 ring-red-300 outline-0" id="cpassword" name="cpassword" type="password" required placeholder="************" />        
                                    <div v-if="validator.unmatchpassword" class="">
                                        <p v-html="validator.unmatchpasswordmsg"></p>
                                    </div>
                                </div>

                                <div v-if="responsereturned" :class="{ 'bg-green-300/[.6] text-green-800' : api_response.status, 'bg-red-400/[.40] text-red-900' : !api_response.status }" class="p-3 mt-3 text-left rounded-md">
                                    <p>{{ api_response.message }} <span class="mt-3 font-bold"><NuxtLink :to="{ name : 'login' }" class="text-[#4B4237] hover:text-[#978269]">Login</NuxtLink></span></p>
                                </div>

                                <div class="text-center p-2 mt-3">
                                    <button  v-if="route.query.token" :disabled="chkfields" type="submit" :class="{ 'opacity-50' : chkfields }" class="p-2 bg-[#0f8B8D] w-1/2 text-white rounded-md font-bold text-lg hover:bg-[#0a5556]"><span v-if="api_calling" class="animate-spin h-7 w-7 -mb-2 mr-3 pt-2 border-r-2 border-b-2 border-l-2 border-whtie rounded-full inline-block"></span>  Reset Password </button>
                                </div>

                                
                            </form>

                        </div>
                        <div v-else>
                            <div class="mt-3" v-if="!route.query.token">
                                <label class="block mb-3 pl-2 font-medium text-lg" for="email">Account Email:</label>
                                <input v-model="resetObj.email" class="p-3 rounded-md w-full ring-0" id="email" name="email" type="email" required placeholder="example@gmail.com" />    
                            </div>
                            <div v-if="responsereturned" :class="{ 'bg-green-300/[.6] text-green-800' : api_response.status, 'bg-red-400/[.40] text-red-900' : !api_response.status }" class="p-3 mt-3 text-left rounded-md">
                                <p>{{ api_response.message }}</p>
                            </div>

                            <div class="text-center p-2 mt-3">
                                <button v-if="!route.query.token" @click="sendToken()" :disabled="api_calling.value || resetObj.email.length <= 0" :class="{ 'opacity-50' : api_calling.value || resetObj.email.length <= 0 }" class="p-2 bg-[#0f8B8D] w-1/2 text-white rounded-md font-bold text-lg hover:bg-[#0a5556]"><span v-if="api_calling" class="animate-spin h-7 w-7 -mb-2 mr-3 pt-2 border-r-2 border-b-2 border-l-2 border-whtie rounded-full inline-block"></span> Send Token Mail </button>
                            </div>
                        </div>

                        

                </div>
            </div>
        </div>
    </div>
</template>

<script type="text/javascript" setup>
import { userStore } from "@/store/user.js"

const userstore = userStore()
const resetObj = ref({
    email : "",
    password : "",
    confirm_password : "",
    token : ""
})

let api_response = ref({})
let responsereturned = ref(false)
let route = useRoute()

const config = useRuntimeConfig();
getBaseUrl(config)

const validator = reactive({
    userphoneerror : false,
    userphoneerrormsg : "",
    unmatchpassword : false,
    unmatchpasswordmsg : "",
})

function confirmpassword(){
    let pword1 = resetObj.value.password
    let pword2 = resetObj.value.confirm_password
    
    if(pword1.length < 8){
        validator.unmatchpassword = true
        validator.unmatchpasswordmsg = "<b class='text-red-500'>Passwords must be 8 Characters or more</b>"
    }else if(pword1 != pword2){
        validator.unmatchpassword = true
        validator.unmatchpasswordmsg = "<b class='text-red-500'>Passwords do not match</b>"
    }else{
        validator.unmatchpassword = false
        validator.unmatchpasswordmsg = ""
    }
}


const chkfields = computed(() => {
    return api_calling.value || resetObj.value.password.length <= 0 || validator.unmatchpassword == true ? true : false
})
let api_calling = ref(false)

const sendToken = async () => {

    responsereturned.value = false
    api_calling.value = true

    try {

        return await userstore.resetToken(resetObj.value.email).then((response) => {
            api_calling.value = false
            api_response.value = response
            responsereturned.value = true

            resetObj.value.email = ""
            resetObj.value.password = ""
            resetObj.value.confirm_password = ""
            resetObj.value.token = ""
         
        })

    } catch (error) {
        api_calling.value = false
        api_response.value = error
        responsereturned.value = true
    
    }
}
async function resetPassword() {
    responsereturned.value = false
    api_calling.value = true
    resetObj.value.email = route.query.email
    resetObj.value.token = route.query.token

    try {

        return await userstore.resetPassword(resetObj.value).then((response) => {
            api_calling.value = false
            api_response.value = response
            responsereturned.value = true

            resetObj.value.email = ""
            resetObj.value.password = ""
            resetObj.value.confirm_password = ""
            resetObj.value.token = ""
         
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