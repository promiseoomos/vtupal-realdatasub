<template>
    <div>
        <WelcomeBase />
        <div class="rounded-md bg-gray-100 p-2">
            
            <div class="flex flex-wrap justify-between mt-4 px-2">
                <ActiveBoardBase class="w-1/5 h-32" icon="mtn.jpg" text="MTN" link="dashboard-vtuairtime" prefix="newtork" :query="{ network : 'MTN'}"/>
                <ActiveBoardBase class="w-1/5 h-32" icon="glo.jpg" text="GLO" link="dashboard-vtuairtime" prefix="newtork" :query="{ network : 'GLO'}"/>
                <ActiveBoardBase class="w-1/5 h-32" icon="airtel.jpg" text="AIRTEL" link="dashboard-vtuairtime" prefix="newtork" :query="{ network : 'AIRTEL'}"/>
                <ActiveBoardBase class="w-1/5 h-32" icon="9mobile.jpg" text="9MOBILE" link="dashboard-vtuairtime" prefix="newtork" :query="{ network : '9MOBILE'}"/>
            </div>
            <div class="rounded-md bg-white p-2 mt-3">
                <form @submit.prevent="createBill">
                    <div class="mt-3">
                        <label for="subject" class="block mb-3 font-medium text-lg pl-1">Mobile Number</label>
                        <input type="text" @keyup="validate" v-model="payObj.recipient" id="subject" class="p-3 rounded-md w-full ring-0 border border-gray-200" />
                    </div>
                    <p v-if="!validaterecipientchars.status" class="mt-1 text-red-600">{{ validaterecipientchars.message }}</p>
                    <p v-if="!validaterecipientlength.status" class="mt-1 text-red-600">{{ validaterecipientlength.message }}</p>

                    <div class="mt-3">
                        <label for="sender" class="block mb-3 font-medium text-lg pl-1">Amount</label>
                        <input type="number" min="0" @keyup="setObj" v-model="payObj.amount" id="sender" class="p-3 rounded-md w-full ring-0 border border-gray-200" placeholder="3000" />
                    </div>
                    <p class="mt-2 text-green-600">You will be charged {{ payObj.famount }} for this Airtime Purchase</p>
                    <div v-if="!validateamountchars.status" class="mt-1 text-red-600">{{ validateamountchars.message }}</div>
                
                    <div v-if="finalapiresponse" class="rounded-md p-3 mt-3" :class="{ 'text-red-600 bg-red-600/[.2]' : finalapiresponse.status == 'error', 'text-green-600 bg-green-600/[.2]' : finalapiresponse.status == 'success' }">
                        <p v-if="finalapiresponse.status == 'error'">{{ finalapiresponse.message }}</p>
                        <p v-if="finalapiresponse.status == 'success'">{{ finalapiresponse.message }}</p>
                    </div>

                    <div class="text-center p-2 mt-3">
                        <button :disabled="chkfields" :class="{ 'opacity-50' : chkfields }" @click="cancelTransaction" class="p-3 px-10 bg-[#0f8B8D] w-full lg:w-1/2 text-white rounded-md font-bold text-md hover:bg-[#0a5556]"><span v-if="api_calling" class="animate-spin h-7 w-7 -mb-2 mr-3 pt-2 border-r-2 border-b-2 border-l-2 border-whtie rounded-full inline-block"></span> Purchase {{ $route.query.network }} Airtime</button>
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
import { vendStore } from "~~/store/operations/vend";

const userstore = userStore();
const vendstore = vendStore();

let validaterecipientchars = ref({})
let validaterecipientlength = ref({})
let validateamountchars = ref({})
let validateamountlength = ref({})


const payObj = ref({
    recipient : "",
    bouquet : null,
    biller_code : "",
    phone : "",
    type : "Airtime",
    network : "",
    country : "NG",
    amount : null,
    charge_amount : null,
    face_amount : 0,
    famount : "₦0",
    userId : userstore.details.track_id,
    transactionType : "airtime-payment",
    paymentChannel : "Simhosting",
    charge : 0.5,
    currency : userstore.details.currency
})

let bouquetplans = ref([])
const route = useRoute()
let network = ref("")
let amount = ref(0)

let productplans = ref([])
let percentageIncrease = ref(0)
let amountStr = ref("")

let validateapiresponse = ref({})
let validateapicalling = ref(false)
let finalapiresponse = ref({})

async function validateBill(){
    validateapicalling.value = true
    validateapiresponse.value = {}

    try{
        await vendstore.validateBill(payObj.value).then((response) => {
            validateapiresponse.value = response
            validateapicalling.value = false
        })
    }catch(error){
        validateapiresponse.value = error
        validateapicalling.value = false
    }
}

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

const setObj = () => {

if(productplans.value.length > 0){

    let product = productplans.value.find(x => x.standard_name == network.value.toLowerCase() + '_airtime')

    let pricingType = product.pricing

    if(pricingType == 'value'){
        payObj.value.amount = Number(product[userstore.details.type + '_price'])

    }else if(pricingType == 'percentage'){
        amount.value = Math.round(payObj.value.amount - (payObj.value.amount * Number(product[userstore.details.type + '_percentage']/100)))
    }
    

    payObj.value.face_amount = payObj.value.amount
    

    let formattedNum = useFormatDigits(amount.value)
    payObj.value.famount = userstore.details.currency == 'NGN' ? '₦' + `${formattedNum}` : '$' + `${formattedNum}` 
    
    api_calling.value = false;    
}   
}

async function createBill(){
    api_calling.value = true
    payObj.value.charge_amount = amount.value
    payObj.value.network = network.value

    try{
        await vendstore.buyAirtime(payObj.value).then((response) => {
            api_calling.value = false
            finalapiresponse.value = response
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
    return api_calling.value == true || validateapicalling.value == true || validateapiresponse.value.status == false || payObj.value.recipient.length == 0 || payObj.value.amount <= 0 ? true : false
})

watch(() => route.query.network, (newval) => {
    payObj.value.recipient = "",
    network.value = null,
    payObj.value.biller_code = "",
    payObj.value.phone = "",
    payObj.value.type = "",
    payObj.value.country = "",
    payObj.value.amount = "",
    payObj.value.famount = "₦0"
    validateapiresponse.value = {}
    finalapiresponse.value = {}
    payObj.value.bouquet = null
})

function validate(){
    validaterecipientchars.value = validateDigitChars(payObj.value.recipient)
    validaterecipientlength.value = validateDigitLength(payObj.value.recipient, 11)
    validateamountchars.value = validateDigitChars(payObj.value.amount)                           
    validateamountlength.value = validateDigitLength(payObj.value.amount, 11)
}

watchEffect(() =>{
    
    if(route.query.network){
        if(route.query.network.length > 0){
            network.value = route.query.network;    
        }

        if(process.client){

            let billerObj = vendstore.services2.filter(x => x.module == 'Airtime' && x.submodule == network.value)

            if(billerObj.length > 0){
                productplans.value = billerObj
            }
        }
    }
    
})


</script>

<style lang="scss" scoped>

</style>