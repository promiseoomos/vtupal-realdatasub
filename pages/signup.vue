<template>
    <div>
        <div class="h-screen p-3 pb-3">
            <p class="text-3xl text-center mt-3">Welcome to {{ appConfig.siteName }}</p>
            <div :style="{ 'background-color' : appConfig.siteTheme.authCardColor, 'color' : appConfig.siteTheme.authTextColor  }" class="mt-16 p-5 mx-auto w-full lg:w-1/3 rounded-lg mb-7">
                <div class="text-center">
                    <p class="text-xl border-b-4 border-black mx-auto w-fit">Sign up</p>
                </div>

                <div class="text-left p-3">
                    <form @submit.prevent="registerUser">
                        <div class="mt-3">
                            <label class="block mb-3 pl-2 font-medium text-lg" for="first_name">First Name:</label>
                            <input v-model="userObj.first_name" class="p-3 rounded-md w-full ring-0" id="first_name" name="first_name" type="text" required placeholder="" />    
                        </div>

                        <div class="mt-3">
                            <label class="block mb-3 pl-2 font-medium text-lg" for="last_name">Last Name:</label>
                            <input v-model="userObj.last_name" class="p-3 rounded-md w-full ring-0" id="last_name" name="last_name" type="text" required placeholder="" />    
                        </div>

                        <div class="mt-3">
                            <label class="block mb-3 pl-2 font-medium text-lg" for="email">Email:</label>
                            <input v-model="userObj.email" class="p-3 rounded-md w-full ring-0" id="email" name="email" type="email" required placeholder="example@gmail.com" />    
                        </div>
                        
                        <div class="mt-3">
                            <label class="block mb-3 pl-2 font-medium text-lg" for="phone">Phone:</label>
                            <input v-model="userObj.phone" @keyup="validatephone()" class="p-3 rounded-md w-full ring-0" id="phone" name="phone" type="text" required placeholder="08020000300" />
                            <div v-if="validator.userphoneerror" class="">
                                <p v-html="validator.userphoneerrormsg"></p>
                            </div> 
                        </div>

                        <div class="mt-3">
                            <label class="block mb-3 pl-2" for="password">Password:</label>
                            <input v-model="userObj.password" class="p-3 rounded-md w-full ring-3 ring-red-300 outline-0" id="password" name="password" type="password" required placeholder="************" />        
                        </div>

                        <div class="mt-3">
                            <label class="block mb-3 pl-2" for="cpassword">Confirm Password:</label>
                            <input v-model="userObj.confirm_password" @keyup="confirmpassword()" class="p-3 rounded-md w-full ring-3 ring-red-300 outline-0" id="cpassword" name="cpassword" type="password" required placeholder="************" />        
                            <div v-if="validator.unmatchpassword" class="">
                                <p v-html="validator.unmatchpasswordmsg"></p>
                            </div>
                        </div>
                        

                        <div class="mt-3">
                            <label class="block mb-3 pl-2 font-medium text-lg" for="last_name">Referral Code:</label>
                            <input v-model="userObj.ref_code" class="p-3 rounded-md w-full ring-0" id="ref_code" name="ref_code" type="text"  placeholder="" />    
                        </div>

                        <div v-if="responsereturned" :class="{ 'bg-green-300/[.6] text-green-800' : api_response.status, 'bg-red-400/[.40] text-red-900' : !api_response.status }" class="p-3 mt-3 text-left rounded-md">
                            <p>{{ api_response.message }}</p>
                        </div>

                        <div class="text-center p-2 mt-3">
                            <button :style="{ 'background-color' : appConfig.siteTheme.buttonBgColor }" type="submit" :disabled="chkfields" :class="[{ 'opacity-50' : chkfields }]" class="p-2 w-1/2 text-white rounded-md font-bold text-lg hover:bg-[#0a5556]"><span v-if="api_calling" class="animate-spin h-7 w-7 -mb-2 mr-3 pt-2 border-r-2 border-b-2 border-l-2 border-whtie rounded-full inline-block"></span> Sign up</button>

                            <p class="mt-3">Not yet registered? <NuxtLink :to="{ name : 'login' }" class="text-[#4B4237] hover:text-[#978269]">Login</NuxtLink></p>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script type="text/javascript" setup>
import { userStore } from "@/store/user.js"

const userstore = userStore()
const userObj = reactive({
    first_name : "",
    last_name : "",
    phone : "",
    email : "",
    password : "",
    confirm_password : "",
    ref_code : ""
})

let api_response = ref({})
let responsereturned = ref(false)

const config = useRuntimeConfig();
getBaseUrl(config)

const appConfig = useAppConfig();


const validator = reactive({
    userphoneerror : false,
    userphoneerrormsg : "",
    unmatchpassword : false,
    unmatchpasswordmsg : "",
})

function confirmpassword(){
    let pword1 = userObj.password
    let pword2 = userObj.confirm_password
    
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

function validatephone(){
    let phone = userObj.phone
    var patt = /[a-z]+/i

    if((patt.test(phone) || phone.length != 11)){
        validator.userphoneerror = true
        validator.userphoneerrormsg = "<ul class='text-red-500'><li>Only numeric characters required</li><li>Number must be 11 digits</li></ul>"
    }else{
        validator.userphoneerror = false
        validator.userphoneerrormsg = ""
    }
}

const chkfields = computed(() => {
    return api_calling.value || userObj.first_name.length <= 0 || userObj.last_name.length <= 0 || userObj.email.length <= 0 ||userObj.phone.length <= 0 || userObj.password.length <= 0 || validator.userphoneerror == true || validator.unmatchpassword == true ? true : false
})
let api_calling = ref(false)

async function registerUser() {
    responsereturned.value = false
    api_calling.value = true

    try {

        return await userstore.registerUser(userObj).then((response) => {
            api_calling.value = false
            api_response.value = response
            responsereturned.value = true
            
            userObj.first_name = ""
            userObj.last_name = ""
            userObj.email = ""
            userObj.phone = ""
            userObj.password = ""
            userObj.confirm_password = ""
            userObj.ref_code = ""
         
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