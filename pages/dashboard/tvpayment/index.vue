<template>
    <div>
        <WelcomeBase />
        <div class="rounded-md bg-gray-100 p-2">
            
            <div class="flex flex-wrap justify-between mt-4 px-2">
                <ActiveBoardBase class="w-1/4 h-32" icon="gotv.jpg" text="GOTV" link="dashboard-tvpayment" :query="{ type : 'GOTV'}"/>
                <ActiveBoardBase class="w-1/4 h-32" icon="dstv.jpg" text="DSTV" link="dashboard-tvpayment" :query="{ type : 'DSTV'}"/>
                <ActiveBoardBase class="w-1/4 h-32" icon="startimes.jpg" text="STARTIME" link="dashboard-tvpayment" :query="{ type : 'STARTIME'}"/>
            </div>

            <div class="rounded-md bg-white p-2 mt-3">
                <form @submit.prevent="createBill">
                    <div class="mt-3">
                        <label for="subject" class="block mb-3 font-medium text-lg pl-1">Choose Bouquet Plan</label>
                        <select class="p-3 rounded-md w-full ring-0 border border-gray-200" v-model="payObj.planId" @change="setObj">
                            <option value="null">--Select Bouquet Plan--</option>
                            <option v-for="(bouquet, index) in bouquetplans" :key="index" :value="bouquet.cableplan_id">{{ bouquet.package }} {{ getAmount(Number(bouquet.plan_amount), percentageIncrease) }}</option>
                        </select>
                    </div>
                    <div class="mt-3" v-if="$route.query.type == 'GOTV'">
                        <label for="subject" class="block mb-3 font-medium text-lg pl-1">IUC Number</label>
                        <input type="text" @keyup="validate" v-model="payObj.recipient" id="subject" class="p-3 rounded-md w-full ring-0 border border-gray-200" />
                    </div>

                    <div class="mt-3" v-if="$route.query.type != 'GOTV'"> 
                        <label for="subject" class="block mb-3 font-medium text-lg pl-1">Smartcard Number</label>
                        <input type="text" @keyup="validateBill" v-model="payObj.recipient" id="subject" class="p-3 rounded-md w-full ring-0 border border-gray-200" />
                    </div>
                    <p v-if="validateapicalling" class="animate-pulse font-semibold text-yellow-600">Validating...</p>
                    <p v-if="validateapiresponse.status == 'error'" class="mt-1 text-red-600">{{ validateapiresponse.message }}</p>
                    <p v-if="!validaterecipientchars.status" class="mt-1 text-red-600">{{ validaterecipientchars.message }}</p>
                    <p v-if="!validaterecipientlength.status" class="mt-1 text-red-600">{{ validaterecipientlength.message }}</p>
                    <p v-if="validateapiresponse.status == 'success'" class="mt-1 text-green-600">{{ validateapiresponse.message }}</p>
                    <p v-if="validateapiresponse.status == 'success'" class="mt-1 text-green-600">Customer name :{{ validateapiresponse.data.name }}</p>
                    <p v-if="validateapiresponse.status == 'error'" class="mt-1 text-red-600">Could not validate customer. <span @click="proceedanyways = true" class="underline cursor-pointer text-blue-400">Proceed anyways.</span></p>
                    
                    <div class="mt-3">
                        <label for="sender" class="block mb-3 font-medium text-lg pl-1">Amount </label>
                        <input type="text" :disabled="true" v-model="amountStr" id="sender" class="p-3 rounded-md w-full ring-0 border border-gray-200" placeholder="3000" />
                    </div>
                     
                    <div class="mt-3">
                        <label for="sender" class="block mb-3 font-medium text-lg pl-1">Phone (optional)</label>
                        <input type="text" v-model="payObj.phone" @keyup="validate" id="sender" class="p-3 rounded-md w-full ring-0 border border-gray-200" placeholder="09020801526" />
                    </div>
                    <div v-if="!validatephonechars.status" class="mt-1 text-red-600">{{ validatephonechars.message }}</div>
                    <div v-if="!validatephonelength.status" class="mt-1 text-red-600">{{ validatephonelength.message }}</div>
                    
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
import { vendStore } from "@/store/operations/vend"
import { validateDigitChars, validateDigitLength } from "~~/composables/user/useValidation.js";


const userstore = userStore();
const vendstore = vendStore();
let validaterecipientchars = ref({})
let validaterecipientlength = ref({})
let validatephonechars = ref({})
let validatephonelength = ref({})
let proceedanyways = ref(false)
let api_calling = ref(false)
let type = ref("")
let amountStr = ref("")
let percentageIncrease = ref(1)
vendstore.getBillerCat()


const payObj = ref({
    mobile_number : "",
    planId : null,
    phone : "",
    type : "cable",
    country : "NG",
    amount : "",
    face_amount : 0,
    userId : userstore.details.track_id,
    transactionType : "tvcable-payment",
    paymentChannel : "arise_data",
    charge : 0.5,
    cable : null,
    cableId : "",
    planpackage : "",
    recipient : "",
    currency : userstore.details.currency
})

let bouquetplans = ref([])
const route = useRoute()


watchEffect(() =>{
    
    if(route.query.type){
        if(route.query.type.length > 0){
            type.value = route.query.type;    
        }

        if(process.client){
            let cablesuffix = vendstore.services.data.find( x => x.service_name == 'cable').service_suffix
            let percentValue = vendstore.services.data.find(x => x.service_name == 'cable').percentage.find(x => x.user_type == userstore.details.type)
            percentageIncrease.value = percentValue == undefined ? 0 : percentValue.value/100 

            if(Object.keys(vendstore.cableServices).length > 0){ 
                let finalType = type.value + "" + cablesuffix
                let billerObj = vendstore.cableServices[finalType]

                if(billerObj.length > 0){
                    bouquetplans.value = billerObj.filter(x => x.plan_type != 'SME')
                }
                
            }
        }
        
        
    }
})

function setObj(){
     if(bouquetplans.value.length > 0){
        let cable = bouquetplans.value.filter(x => x.id == payObj.value.planId)[0].cable
        let amount = Number(bouquetplans.value.filter(x => x.id == payObj.value.planId)[0].plan_amount)
        let planpackage = bouquetplans.value.filter(x => x.id == payObj.value.planId)[0].planpackage

        
        payObj.value.cableId = vendstore.services.data.find(x => x.service_name == 'cable').services.find(x => x.name == cable ).id
        payObj.value.cable = cable
        payObj.value.face_amount = amount
        payObj.value.amount = Math.round(amount + (amount * percentageIncrease.value))

        let formattedNum = useFormatDigits(payObj.value.amount)
        amountStr.value = userstore.details.currency == 'NGN' ? '₦' + `${formattedNum}` : '$' + `${formattedNum}` 
        payObj.value.planpackage = planpackage

        api_calling.value = false;    
    }
}

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
    try{
        await vendstore.createBill(payObj.value).then((response) => {
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
    return api_calling.value || validateapicalling.value || (validateapiresponse.value.status == 'error' && !proceedanyways.value)  || payObj.value.bouquet == 'null'  ? true : false
})

watch(() => route.query.type, (newval) => {
    payObj.value.recipient = "",
    payObj.value.phone = "",
    payObj.value.country = "",
    payObj.value.amount = "",
    payObj.value.planId = null,
    validateapiresponse.value = {}
    finalapiresponse.value = {}
    api_calling.value = false
    validateapicalling.value = false
})

function validate(){
    validaterecipientchars.value = validateDigitChars(payObj.value.recipient)
    validaterecipientlength.value = validateDigitLength(payObj.value.recipient, 10)
    validatephonechars.value = validateDigitChars(payObj.value.phone)                           
    validatephonelength.value = validateDigitLength(payObj.value.phone, 11)
}


</script>

<style lang="scss" scoped>

</style>