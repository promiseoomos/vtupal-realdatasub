<template>
    <div>
        <WelcomeBase />
        <div class="rounded-md bg-gray-100 p-2">
            <div class="flex flex-wrap justify-between mt-4 px-2">
                <ActiveBoardBase class="w-1/5 h-32" icon="mtn.jpg" text="MTN" link="dashboard-smedata"  :query="{ network : 'MTN'}"/>
            </div>
            
            <div class="rounded-md bg-white p-2 mt-3">

                <form @submit.prevent="createBill">
                    <div class="mt-3">
                        <label for="subject" class="block mb-3 font-medium text-lg pl-1">Choose the {{ network }} Plan</label>
                        <select class="p-3 rounded-md w-full ring-0 border border-gray-200" v-model="payObj.product" @change="setObj">
                            <option value="null">--Select product Plan--</option>
                            <option v-for="(product, index) in productplans" :key="index" :value="product.standard_name">{{ product.display_name }} {{ product.pricing == 'percentage' ? getAmount(Number(product['face_price']), Number(product[userstore.details.type + '_percentage'])) : getAmount(Number(product[userstore.details.type + '_price']), 0) }}</option>
                        </select>
                    </div>

                    <div class="mt-3">
                        <label for="subject" class="block mb-3 font-medium text-lg pl-1">Mobile Number</label>
                        <input type="text" :disabled="network.length == 0" @keyup="validate" v-model="payObj.recipient" id="subject" class="p-3 rounded-md w-full ring-0 border border-gray-200" />
                    </div>
                    <p v-if="!validaterecipientchars.status" class="mt-1 text-red-600">{{ validaterecipientchars.message }}</p>
                    <p v-if="!validaterecipientlength.status" class="mt-1 text-red-600">{{ validaterecipientlength.message }}</p>
                    
                    <div class="mt-3">
                        <label for="sender" class="block mb-3 font-medium text-lg pl-1">Amount </label>
                        <input type="text" :disabled="true" v-model="payObj.famount" id="sender" class="p-3 rounded-md w-full ring-0 border border-gray-200" placeholder="3000" />
                    </div>
                    <div v-if="!validateamountchars.status" class="mt-1 text-red-600">{{ validateamountchars.message }}</div>
                
                    <div v-if="finalapiresponse" class="rounded-md p-3 mt-3" :class="{ 'text-red-600 bg-red-600/[.2]' : finalapiresponse.status == 'error', 'text-green-600 bg-green-600/[.2]' : finalapiresponse.status == 'success' }">
                        <p v-if="finalapiresponse.status == 'error'">{{ finalapiresponse.message }}</p>
                        <p v-if="finalapiresponse.status == 'success'">{{ finalapiresponse.message }}</p>
                    </div>
                
                    <div class="text-center p-2 mt-3">
                        <button :disabled="chkfields" :class="{ 'opacity-50' : chkfields }" @click="cancelTransaction" class="p-3 px-10 bg-[#0f8B8D] w-full lg:w-1/2 text-white rounded-md font-bold text-md hover:bg-[#0a5556]"><span v-if="api_calling" class="animate-spin h-7 w-7 -mb-2 mr-3 pt-2 border-r-2 border-b-2 border-l-2 border-whtie rounded-full inline-block"></span> Make {{ $route.query.type }} Payment</button>
                    </div>
                   
                    <div v-if="api_calling" class="text-center p-2 mt-3">
                        <button :disabled="chkCancelTime" @click="cancelProceed" :class="{ 'opacity-50' : chkCancelTime }" class="p-2 bg-black w-full lg:w-1/2 text-white rounded-md font-bold text-lg">
                            Cancel ({{ timer }})
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</template>

<script type="text/javascript" setup>

import { userStore } from "@/store/user.js"
import { validateDigitChars, validateDigitLength } from "~~/composables/user/useValidation.js";
import formatDigits from "~~/composables/useFormatDigits.js"
import { vendStore } from "~~/store/operations/vend";

const userstore = userStore();
const vendstore = vendStore();
let validaterecipientchars = ref({})
let validaterecipientlength = ref({})
let validateamountchars = ref({})
let validateamountlength = ref({})

let productplans = ref([])
const route = useRoute()
let network = ref("")
let percentageIncrease = ref(0)
let amountStr = ref("")




const payObj = ref({
    recipient : "",
    product : null,
    biller_code : "",
    phone : "",
    type : "SME",
    country : "NG",
    amount : "",
    face_amount : 0,
    network : "",
    famount : "",
    userId : userstore.details.track_id,
    transactionType : "smedata-payment",
    paymentChannel : "SIMHost_API",
    charge : 0.5,
    currency : userstore.details.currency
})

watchEffect(() =>{
    
    if(route.query.network){
        if(route.query.network.length > 0){
            network.value = route.query.network;    
        }

        if(process.client){

            let billerObj = vendstore.services2.filter(x => x.module == 'SME' && x.submodule == network.value)

            if(billerObj.length > 0){
                productplans.value = billerObj
            }
        }
    }
})

const setObj = () => {

    if(productplans.value.length > 0){

        let product = productplans.value.find(x => x.standard_name == payObj.value.product)
        
        let pricingType = product.pricing
        
        if(pricingType == 'value'){
            payObj.value.amount = Math.round(Number(product[userstore.details.type + '_price']))

        }else if(pricingType == 'percentage'){
            payObj.value.amount = Math.round(Number(product.face_price) + (Number(product.face_price) * Number(product[userstore.details.type + '_percentage']/100) ))
        }
        

        payObj.value.face_amount = product.face_price
        

        let formattedNum = useFormatDigits(payObj.value.amount)
        payObj.value.famount = userstore.details.currency == 'NGN' ? '₦' + `${formattedNum}` : '$' + `${formattedNum}` 
        
        api_calling.value = false;    
    }   
}

let finalapiresponse = ref({})


let api_calling = ref(false)

let timer = ref(5)
let chkCancelTime = ref(false)
let cancelInterval = ref()

const cancelTransaction = () => {

    api_calling.value = true
    cancelInterval.value = setInterval(addOneSecond, 1000)
    
}

const cancelProceed = () => {
    clearInterval(cancelInterval.value)
    api_calling.value = false
    timer.value = 5
    chkCancelTime.value = false
}

watch(timer, () => {
        
    if(timer.value <= 0){
        clearInterval(cancelInterval.value)
        chkCancelTime.value = true
        createBill()
        
    }
})

const addOneSecond = () => {
    timer.value--
}

async function createBill(){
    api_calling.value = true
    payObj.value.network = network.value
    
    try{
        await vendstore.buySME(payObj.value).then((response) => {
            api_calling.value = false
            finalapiresponse.value = response

            payObj.value.recipient = ""
            network.value = null
            payObj.value.biller_code = ""
            payObj.value.phone = ""
            payObj.value.type = ""
            payObj.value.amount = ""
        })    
    }catch(error){
        
        finalapiresponse.value = error
        api_calling.value = false
    }finally{
        timer.value = 5
        chkCancelTime.value = false
    }
}

const chkfields = computed(() => {
    return api_calling.value == true || payObj.value.recipient.length == 0 || payObj.value.amount <= 0 ? true : false
})

watch(() => route.query.network, (newval) => {
    payObj.value.recipient = "",
    network.value = null,
    payObj.value.biller_code = "",
    payObj.value.phone = "",
    payObj.value.type = "",
    payObj.value.country = "",
    payObj.value.amount = "",
    finalapiresponse.value = {}
    payObj.value.product = null
    api_calling.value = false;    
})

function validate(){
    validaterecipientchars.value = validateDigitChars(payObj.value.recipient)
    validaterecipientlength.value = validateDigitLength(payObj.value.recipient, 11)
    validateamountchars.value = validateDigitChars(payObj.value.amount)                           
    validateamountlength.value = validateDigitLength(payObj.value.amount, 11)
}


</script>

<style lang="scss" scoped>

</style>