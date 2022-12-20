<template>
    <div>
        <WelcomeBaseAdmin />
        <div class="text-left p-3 bg-white rounded-md">
            <form @submit.prevent="updateUserdetails">

                <div class="mt-3">
                    <label class="block mb-3 pl-2 font-medium text-lg" for="bank_name">Bank Name:</label>
                    <input v-model="userObj.bank_name" @keyup="filterBanks" @change="verifyAccount" @focus="toggleBankslist = true; checkbank = true" @blur="checkBank()" class="p-3 rounded-md w-full ring-0 bg-gray-200" id="bank_name" name="bank_name" type="text" required placeholder="" />
                    <div class="rounded-md bg-gray-200 mt-1 overflow-y-auto max-h-56" v-if="toggleBankslist">
                        <p v-for="(bank) in filteredBanks" @click="setBank(bank.name);checkBank()" class="p-2 mt-1 cursor-pointer hover:bg-gray-100" :key="bank.code">{{ bank.name }}</p>
                    </div>    
                    <p v-if="!checkbank" class="mt-1 px-2 text-red-600 font-semibold">{{ checkbankmsg }}</p>
                </div>

                <div class="mt-3">
                    <label class="block mb-3 pl-2 font-medium text-lg" for="account_number">Account Number:</label>
                    <input v-model="userObj.account_number" @keyup="validateacctno()" class="p-3 rounded-md w-full ring-0 bg-gray-200" id="account_number" name="account_number" type="text" required placeholder="" />    
                    <div v-if="validator.useraccterror" class="">
                        <p v-html="validator.useraccterrormsg"></p>
                    </div> 
                </div>

                <div class="mt-3">
                    <label class="block mb-3 pl-2 font-medium text-lg" for="account_name">Account Name:</label>
                    <input v-model="userObj.account_name" class="p-3 rounded-md w-full ring-0 bg-gray-200" id="account_name" name="account_name" type="text" required placeholder="" />    
                    <p v-if="checkedacctnopending" class="animate-pulse font-semibold text-yellow-600">Verifying...</p>
                    <p v-if="checkedacctno" class="px-1 text-md font-semibold" :class="{ 'text-green-600' : account_number_status, 'text-red-600' : !account_number_status}" >{{ account_number_status ? "Account verified successfully" : "Account could not be verified. Check the Account details again. You can type the Account name and proceed if you are sure the details are correct." }}</p>
                </div>

                <div v-if="responsereturned || apiError" :class="{ 'bg-green-300/[.6] text-green-800' : responsemsg.status, 'bg-red-400/[.40] text-red-900' : !responsemsg.status || apiError }" class="p-3 mt-3 text-left rounded-md">
                    <p>{{ responsemsg.message }}</p>
                    <p v-if="apiError">{{ responsemsg }}</p>
                </div>
                
                <div class="text-center p-2 mt-3">
                    <button type="submit" :disabled="chkfields" :class="{ 'opacity-50' : chkfields }" class="p-2 bg-[#0f8B8D] w-1/2 text-white rounded-md font-bold text-lg hover:bg-[#0a5556]">
                        <span v-if="api_calling" class="animate-spin h-7 w-7 -mb-2 mr-3 pt-2 border-r-2 border-b-2 border-l-2 border-whtie rounded-full inline-block"></span>
                        Update Site
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script type="text/javascript" setup>
import { userStore } from "@/store/user.js"
import { getterStore } from "~~/store/operations/getters"
import { adminStore } from "@/store/admin.js"

const userstore = userStore()
const getterstore = getterStore()
const adminstore = adminStore()

getterstore.getBanks()
adminstore.getSiteDetails()


const userObj = ref({
    bank_code : "",
    bank_name : "",
    account_number : "",
    account_name : "",
})

userObj.value.bank_name = adminstore.details.account_bank
userObj.value.account_number = adminstore.details.account_number
userObj.value.account_name = adminstore.details.account_name
userObj.value.track_id = adminstore.details.track_id

let responsemsg = ref({})
let responsereturned = ref(false)
let filteredBanks = ref([])
let toggleBanks = ref(false)
let bank_exists = ref(false)
let checkbank = ref(true)
let checkbankmsg = ref("")
let apiError = ref(false)

if(process.client){

    onMounted(() => {
        if(adminstore.details.account_bank.length > 0){
            // checkBank()
        }    
    })
    
}



const validator = reactive({
    userphoneerror : false,
    userphoneerrormsg : "",
    useraccterror : false,
    useraccterrormsg : "",
})


let toggleBankslist = ref(false)

function setBank(bank){
  
    userObj.value.bank_name = bank
    toggleBankslist.value = false
}

function filterBanks(){
    toggleBankslist.value = true

    let query = userObj.value.bank_name
    let re = new RegExp(`${query}`, 'gi')
    let searchResult = getterstore.banks.filter( x => re.test(x.name) == true)
    filteredBanks.value = searchResult
}


function checkBank(){
    let checker = getterstore.banks.filter( x => x.name == userObj.value.bank_name)

    if(checker.length == 0){
        bank_exists.value = false
        checkbank.value = false
        checkbankmsg.value = "Bank does not exist. Choose from the Bank List."
    }else if(checker.length > 0){
        bank_exists.value = true
        checkbank.value = true
        userObj.value.bank_code = checker[0].code
    }

}

function validateacctno(){
    let phone = userObj.value.account_number
    var patt = /[a-z]+/i

    if((patt.test(phone) || phone.length != 10 && phone.length > 0)){
        checkedacctno.value = false
        validator.useraccterror = true
        validator.useraccterrormsg = "<ul class='text-red-500'><li>Only numeric characters required</li><li>Account Number must be 10 digits</li></ul>"
    }else{
        validator.useraccterror = false
        validator.useraccterrormsg = ""

        verifyAccount()
    }
}

let account_number_status = ref("")
let checkedacctno = ref(false)
let checkedacctnopending = ref(false)

function verifyAccount(){
    let checker = getterstore.banks.filter( x => x.name == userObj.value.bank_name)

    
    if(checker.length > 0 && userObj.value.account_number.length > 0){

        checkedacctno.value = false
        checkedacctnopending.value = true

        let bank_code = checker[0].code

        let dataObj = {
            "account_number" : userObj.value.account_number,
            bank_code
        }

        userstore.verifyAccount(dataObj).then((response) => {
            checkedacctnopending.value = false

            checkedacctno.value = true
            account_number_status.value = response.status
            userObj.value.account_name = response.account_name
            
        })
        
    }
}

const chkfields = computed(() => {
    return api_calling.value == true || !bank_exists.value || userObj.value.account_name.length <= 0 || userObj.value.account_number.length <= 0 
    || userObj.value.bank_name.length <= 0 || validator.useraccterror == true ? true : false
})

let api_calling = ref(false)

async function updateUserdetails() {

    responsereturned.value = false
    api_calling.value = true

    try{
        return await adminstore.updateSiteBank(userObj.value).then((response) => {
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