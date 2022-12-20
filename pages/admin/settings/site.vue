<template>
    <div>
        <WelcomeBaseAdmin />
        <div class="text-left p-3 bg-white rounded-md">
            <form @submit.prevent="updateSitedetails">
                <div class="mt-3">
                    <label class="block mb-3 pl-2 font-medium text-lg" for="first_name">First Name:</label>
                    <input v-model="userObj.first_name" class="p-3 caret-green-300 rounded-md w-full ring-0 bg-gray-200" id="first_name" name="first_name" type="text" required placeholder="" />    
                </div>

                <div class="mt-3">
                    <label class="block mb-3 pl-2 font-medium text-lg" for="last_name">Last Name:</label>
                    <input v-model="userObj.last_name" class="p-3 rounded-md w-full ring-0 bg-gray-200" id="last_name" name="last_name" type="text" required placeholder="" />    
                </div>

                <div class="mt-3">
                    <label class="block mb-3 pl-2 font-medium text-lg" for="email">Email:</label>
                    <input disabled v-model="userObj.email" class="p-3 rounded-md w-full ring-0 bg-gray-200" id="email" name="email" type="email" required placeholder="example@gmail.com" />    
                </div>

                <div class="mt-3">
                    <label class="block mb-3 pl-2 font-medium text-lg" for="site_name">Site Name:</label>
                    <input v-model="userObj.site_name" class="p-3 caret-green-300 rounded-md w-full ring-0 bg-gray-200" id="site_name" name="site_name" type="text" required placeholder="" />    
                </div>
                
                <div class="mt-3">
                    <label class="block mb-3 pl-2 font-medium text-lg" for="phone">Phone:</label>
                    <input v-model="userObj.phone" @keyup="validatephone()" class="p-3 rounded-md w-full ring-0 bg-gray-200" id="phone" name="phone" type="text" required placeholder="08020000300" />
                    <div v-if="validator.userphoneerror" class="">
                        <p v-html="validator.userphoneerrormsg"></p>
                    </div> 
                </div>

                <div v-if="responsereturned || apiError" :class="{ 'bg-green-300/[.6] text-green-800' : responsemsg.status, 'bg-red-400/[.40] text-red-900' : !responsemsg.status || apiError }" class="p-3 mt-3 text-left rounded-md">
                    <p>{{ responsemsg.message }}</p>
                    <p v-if="apiError">{{ responsemsg }}</p>
                </div>

                <div class="text-center p-2 mt-3">
                    <button type="submit" :disabled="chkfields" :class="{ 'opacity-50' : chkfields }" class="p-2 bg-[#0f8B8D] w-1/2 text-white rounded-md font-bold text-lg hover:bg-[#0a5556]">
                        <span v-if="api_calling" class="animate-spin h-7 w-7 -mb-2 mr-3 pt-2 border-r-2 border-b-2 border-l-2 border-whtie rounded-full inline-block"></span>
                        Update Profile
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script type="text/javascript" setup>
import { userStore } from "@/store/user.js"
import { adminStore } from "~~/store/admin"

const userstore = userStore()
const adminstore = adminStore();


const userObj = ref({
    first_name : "",
    last_name : "",
    phone : "",
    email : "",
    site_name : "",
    track_id : ""
})

userObj.value.first_name = adminstore.details.first_name
userObj.value.last_name = adminstore.details.last_name
userObj.value.email = adminstore.details.email
userObj.value.phone = adminstore.details.phone
userObj.value.site_name = adminstore.details.site_name

let responsemsg = ref({})
let responsereturned = ref(false)
let filteredBanks = ref([])
let toggleBanks = ref(false)
let bank_exists = ref(false)
let checkbank = ref(true)
let checkbankmsg = ref("")
let apiError = ref(false)


const validator = reactive({
    userphoneerror : false,
    userphoneerrormsg : "",
    useraccterror : false,
    useraccterrormsg : "",
})



function validatephone(){
    let phone = userObj.value.phone
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
    return api_calling.value == true || userObj.value.first_name.length <= 0 || userObj.value.last_name.length <= 0 || userObj.value.email.length <= 0 ||userObj.value.phone.length <= 0 
    || validator.userphoneerror == true ? true : false
})

let api_calling = ref(false)

async function updateSitedetails() {

    responsereturned.value = false
    api_calling.value = true

    try{
        return await adminstore.updateSiteDetails(userObj.value).then((response) => {
            api_calling.value = false

            responsemsg.value = response
            responsereturned.value = true
            apiError.value = false

            adminstore.getSiteDetails()
        })    
    }catch(error){
        api_calling.value = false
        apiError.value = true
        responsemsg.value = error
    }
    
}
</script>

<style lang="scss" scoped>

</style>