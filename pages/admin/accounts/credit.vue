<template>
    <div>
        <WelcomeBaseAdmin />
        <p class="text-center text-2xl font-bold">Credit Account</p>
        <div class="rounded-md bg-gray-100 p-2">
            
            <div class="rounded-md bg-white p-2 mt-3">
                <form @submit.prevent="creditAccount">

                    <div class="mt-3">
                        <label for="subject" class="block mb-3 font-medium text-lg pl-1">Email of Account</label>
                        <input type="email" @keyup="validate" @blur="validateUserEmail" v-model="creditObj.recipient" id="recipient" placeholder="Recipient's Email" class="p-3 rounded-md w-full ring-0 border border-gray-200" />
                    </div>
                    <p v-if="validateapiresponse.status == true" class="mt-1 text-green-600">{{ validateapiresponse.message }}</p>
                    <p v-if="validateapicalling" class="animate-pulse font-semibold text-yellow-600">Validating...</p>
                    <p v-if="validateapiresponse.status == false" class="mt-1 text-red-600">{{ validateapiresponse.message }}</p>
                    <p v-if="validateapiresponse.status == true" class="mt-1 text-green-600">Account Name : {{ validateapiresponse.userName }}</p>
                    <p v-if="apiError" class="mt-1 text-red-600">{{ validateapiresponse }}</p>
                    <div class="mt-3">
                        <label for="amount" class="block mb-3 font-medium text-lg pl-1">Amount</label>
                        <input type="number" min="100" v-model="creditObj.amount" id="amount" class="p-3 rounded-md w-full ring-0 border border-gray-200" placeholder="3000" />
                    </div>

                    <div class="mt-3">
                        <label for="description" class="block mb-3 font-medium text-lg pl-1">Description</label>
                        <textarea v-model="creditObj.description" rows="3" id="description" class="p-3 rounded-md w-full ring-0 border border-gray-200" placeholder="Enter description"></textarea>
                    </div>
                    
                    <div v-if="finalapiresponse" class="rounded-md p-3 mt-3" :class="{ 'text-red-600 bg-red-600/[.2]' : finalapiresponse.status == false, 'text-green-600 bg-green-600/[.2]' : finalapiresponse.status == true }">
                        <p v-if="finalapiresponse.status == false">{{ finalapiresponse.message }}.</p>
                        <p v-if="finalapiresponse.status == true">{{ finalapiresponse.message }}</p>
                    </div>
                
                    <div class="text-center p-2 mt-3">
                        <button :disabled="chkfields" :class="{ 'opacity-50' : chkfields }" type="submit" class="p-2 bg-[#0f8B8D] w-full lg:w-1/2 text-white rounded-md font-bold text-lg hover:bg-[#0a5556]"><span v-if="api_calling" class="animate-spin h-7 w-7 -mb-2 mr-3 pt-2 border-r-2 border-b-2 border-l-2 border-whtie rounded-full inline-block"></span> Credit Account </button>
                    </div>
                </form>

                
            </div>
            

        </div>


    </div>
</template>

<script type="text/javascript" setup>

import { adminStore } from "@/store/admin.js"
import { validateDigitChars, validateDigitLength } from "~~/composables/user/useValidation.js";

const adminstore = adminStore();
let validaterecipientchars = ref({})
let validaterecipientlength = ref({})
let validateamountchars = ref({})
let validateamountlength = ref({})


const creditObj = ref({
    recipient : "",
    amount : "",
    userId : adminstore.details.track_id,
    transactionType : "credit-account",
    paymentChannel : "Website",
    description : "",
})

let validateapiresponse = ref({})
let validateapicalling = ref(false)
let finalapiresponse = ref({})
let apiError = ref(false)

async function validateUserEmail(){
    validateapicalling.value = true
    validateapiresponse.value = {}
    apiError.value = false
    try {
        await adminstore.validateUserEmail(creditObj.value.recipient).then((response) => {
            validateapiresponse.value = response
            validateapicalling.value = false
        })    
    } catch (error) {
        validateapiresponse.value = error
        validateapicalling.value = false
        apiError.value = true
    }
    
}

let api_calling = ref(false)
async function creditAccount(){
    api_calling.value = true
    

    try{
        await adminstore.creditUserAccount(creditObj.value).then((response) => {
            api_calling.value = false
            finalapiresponse.value = response

            creditObj.value.amount = 100
            creditObj.value.recipient = ""
            creditObj.value.description = ""
        })
         
    }catch(error){
        api_calling.value = false
        finalapiresponse.value = error
    }
}

const chkfields = computed(() => {
    return api_calling.value == true || validateapicalling.value == true || validateapiresponse.value.status == false || creditObj.value.recipient.length == 0 || creditObj.value.amount <= 0 ? true : false
})

</script>

<style lang="scss" scoped>

</style>