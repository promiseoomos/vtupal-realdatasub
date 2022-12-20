<template>
    <div>
        <WelcomeBase />
        <div class="rounded-md bg-gray-100 p-2">
            
            <div class="flex flex-wrap justify-evenly gap-2 mt-4 px-2">
                <ActiveBoardBase class="w-1/5" icon="ikeja-electric.jpg" text="Ikeja" link="dashboard-electricity"  :query="{ disco : 'Ikeja'}"/>
                <ActiveBoardBase class="w-1/5" icon="ibadan-electric.jpg" text="Ibadan" link="dashboard-electricity"  :query="{ disco : 'Ibadan'}"/>
                <ActiveBoardBase class="w-1/5" icon="jos-electric.jpg" text="Jos" link="dashboard-electricity"  :query="{ disco : 'Jos'}"/>
                <ActiveBoardBase class="w-1/5" icon="kaduna-electric.jpg" text="Kaduna" link="dashboard-electricity"  :query="{ disco : 'Kaduna'}"/>
                <ActiveBoardBase class="w-1/5" icon="enugu-electric.jpg" text="Enugu" link="dashboard-electricity"  :query="{ disco : 'Enugu'}"/>
                <ActiveBoardBase class="w-1/5" icon="eko-electric.jpg" text="Eko" link="dashboard-electricity"  :query="{ disco : 'Eko'}"/>
                <ActiveBoardBase class="w-1/5" icon="benin-electric.jpg" text="Benin" link="dashboard-electricity"  :query="{ disco : 'Benin'}"/>
                <ActiveBoardBase class="w-1/5" icon="portharcourt-electric.jpg" text="Port Harcourt" link="dashboard-electricity"  :query="{ disco : 'Port Harcourt'}"/>
            </div>
            <div class="rounded-md bg-white p-2 mt-3">
                <form @submit.prevent="createBill">
                    
                    <div class="mt-3">
                        <label for="subject" class="block mb-3 font-medium text-lg pl-1">Pay Electricity Bill for {{ disco }} Disco</label>
                    </div>

                    <div class="mt-3">
                        <label for="subject" class="block mb-3 font-medium text-lg pl-1">Meter Number</label>
                        <input type="text" :disabled="disco.length == 0" @focus="clear" @keyup="validate" @blur="validateBill" v-model="payObj.meter_number" id="subject" class="p-3 rounded-md w-full ring-0 border border-gray-200" />
                    </div>
                    <p v-if="!validaterecipientchars.status" class="mt-1 text-red-600">{{ validaterecipientchars.message }}</p>
                    <p v-if="validateapiresponse.status == 'success'" class="mt-1 text-green-600">{{ validateapiresponse.message }}</p>
                    <p v-if="validateapicalling" class="animate-pulse font-semibold text-yellow-600">Validating...</p>
                    <p v-if="validateapiresponse.status == 'error'" class="mt-1 text-red-600">{{ validateapiresponse.message }}</p>
                    <p v-if="validateapiresponse.status == 'success'" class="mt-1 text-green-600">Validated details : {{ validateapiresponse.data.name }} ({{ validateapiresponse.data.customer }})</p>

                    <div class="mt-3">
                        <label for="sender" class="block mb-3 font-medium text-lg pl-1">Amount</label>
                        <input type="number" min="0" v-model="payObj.amount" id="sender" class="p-3 rounded-md w-full ring-0 border border-gray-200" placeholder="3000" />
                    </div>
                    
                    <div v-if="finalapiresponse" class="rounded-md p-3 mt-3" :class="{ 'text-red-600 bg-red-600/[.2]' : finalapiresponse.status == 'error', 'text-green-600 bg-green-600/[.2]' : finalapiresponse.status == 'success' }">
                        <p v-if="finalapiresponse.status == 'error'">{{ finalapiresponse.message }}.</p>
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
import { vendStore } from "~~/store/operations/vend";

const userstore = userStore();
const vendstore = vendStore();

let validaterecipientchars = ref({})
let validaterecipientlength = ref({})
let validateamountchars = ref({})
let validateamountlength = ref({})


const payObj = ref({
    meter_number : "",
    MeterType : 1,
    biller_code : "",
    phone : "",
    type : "disco",
    country : "NG",
    amount : 0,
    face_amount : 0,
    recipient : "",
    disco_name : "",
    userId : userstore.details.track_id,
    transactionType : "electricity-payment",
    paymentChannel : "Flutterwave_API",
    charge : 0.5,
    currency : userstore.details.currency
})

let bouquetplans = ref([])
const route = useRoute()
let disco = ref("")
let amountStr = ref("")
let percentageIncrease = ref(0)
let serviceSuffix = ref("")

watchEffect(() =>{
    if(route.query.disco){
        if(route.query.disco.length > 0){
            disco.value = route.query.disco;    
        }    
    }

    serviceSuffix.value = vendstore.services.data.find( x => x.service_name == 'electricity').service_suffix
    let percentValue = vendstore.services.data.find(x => x.service_name == 'electricity').percentage.find(x => x.user_type == userstore.details.type)
    percentageIncrease.value = percentValue == undefined ? 0 : percentValue.value/100
})

let validateapiresponse = ref({})
let validateapicalling = ref(false)
let finalapiresponse = ref({})

async function validateBill(){
    validateapicalling.value = true
    validateapiresponse.value = {}

    try{
        await userstore.validateBill(payObj.value).then((response) => {
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

async function createBill(){
    api_calling.value = true
    payObj.value.recipient = payObj.value.meter_number
    payObj.value.disco_name = disco.value + " " + serviceSuffix.value
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

const clear = () => {
    validateapiresponse.value = {}
}

const chkfields = computed(() => {
    return api_calling.value == true || validateapicalling.value == true || validateapiresponse.value.status == false || payObj.value.meter_number.length == 0 || payObj.value.amount <= 0 ? true : false
})

watch(() => route.query.disco, (newval) => {
    payObj.value.meter_number = "",
    disco.value = "",
    payObj.value.biller_code = "",
    payObj.value.phone = "",
    payObj.value.type = "",
    payObj.value.country = "",
    payObj.value.amount = "",
    validateapiresponse.value = {}
    finalapiresponse.value = {}
    api_calling.value = false
})

function validate(){
    validaterecipientchars.value = validateDigitChars(payObj.value.meter_number)
}


</script>

<style lang="scss" scoped>

</style>