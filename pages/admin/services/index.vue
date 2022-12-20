<template>
    <div>
        <WelcomeBaseAdmin />
        <p class="text-center text-2xl font-bold">All Services</p>
        
        <div class="rounded-lg overflow-x-auto bg-slate-300/[.4] mt-5 p-2">
            
            <div class="flex flex-nowrap gap-4 justify-start lg:justify-start w-[400%] lg:w-[200%] bg-slate-600/[.5] p-3 rounded-sm mb-4">
            
                <p class="text-sm w-[30%]  font-semibold">Module</p>
                <p class="text-sm w-[30%]  font-semibold">Sub-module</p>

                <p class="text-sm w-[30%]  font-semibold">Standard Name</p>
                <p class="text-sm w-[30%]  font-semibold">Display Name</p>
            
                <p class="text-sm w-[30%]  font-semibold">Module Prefix</p>
                <p class="text-sm w-[30%]  font-semibold">Module Suffix</p>
                <p class="text-sm w-[30%]  font-semibold">Pricing</p>
                <p class="text-sm w-[30%]  font-semibold">Face Price</p>
                <p class="text-sm w-[30%]  font-semibold">Face Percentage</p>
                <p class="text-sm w-[30%]  font-semibold">Normal Price</p>
                <p class="text-sm w-[30%]  font-semibold">Reseller Price</p>
                <p class="text-sm w-[30%]  font-semibold">Normal %</p>
                <p class="text-sm w-[30%]  font-semibold">Reseller %</p>
                <p class="text-sm w-[30%]  font-semibold">Status</p>
                <p class="w-[30%] font-semibold"></p>
                
            </div>
            
        
            <div v-if="adminstore.services.length == 0" class="flex flex-nowrap justify-start lg:justify-around  bg-slate-100 gap-4 mt-3 rounded-sm p-3">
                <p>No services</p>
            </div>
            <div v-else class="">
                <ServiceFormBase class="w-[200%]" v-for="(service,index) in adminstore.services" :key="index" :sn="index" :service="service" />
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

adminstore.getServices()

console.log(adminstore.services )
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
    return api_calling.value == true || validateapicalling.value == true || validateapiresponse.value.status == false ? true : false
})

</script>

<style lang="scss" scoped>

</style>