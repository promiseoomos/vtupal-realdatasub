<template>
    <div>
        <WelcomeBase />
        <div class="rounded-md bg-gray-100 p-2">
            
            <div class="flex flex-wrap justify-between mt-4 px-2">
                <ActiveBoardBase class="w-1/5 h-32" icon="mtn.jpg" text="MTN" link="dashboard-giftingdata"  :query="{ network : 'MTN'}"/>
                <ActiveBoardBase class="w-1/5 h-32" icon="glo.jpg" text="GLO" link="dashboard-giftingdata"  :query="{ network : 'GLO'}"/>
                <ActiveBoardBase class="w-1/5 h-32" icon="airtel.jpg" text="AIRTEL" link="dashboard-giftingdata"  :query="{ network : 'AIRTEL'}"/>
                <ActiveBoardBase class="w-1/5 h-32" icon="9mobile.jpg" text="9MOBILE" link="dashboard-giftingdata"  :query="{ network : '9MOBILE'}"/>
            </div>

            <div class="rounded-md bg-white p-2 mt-3">
                <form @submit.prevent="createBill">
                    <div class="mt-3">
                        <label for="subject" class="block mb-3 font-medium text-lg pl-1">Choose the {{ network }} Plan</label>
                        <select class="p-3 rounded-md w-full ring-0 border border-gray-200" v-model="payObj.plan" @change="setObj">
                            <option value="null">--Select Bouquet Plan--</option>
                            <option v-for="(bouquet, index) in bouquetplans" :key="index" :value="bouquet.id">{{ bouquet.plan }} {{ bouquet.month_validate }} {{ getAmount(Number(bouquet.plan_amount), percentageIncrease) }} </option>
                        </select>
                    </div>

                    <div class="mt-3">
                        <label for="subject" class="block mb-3 font-medium text-lg pl-1">Mobile Number</label>
                        <input type="text" :disabled="network.length == 0" @keyup="validate" v-model="payObj.mobile_number" id="subject" class="p-3 rounded-md w-full ring-0 border border-gray-200" />
                    </div>
                    <p v-if="!validatemobile_numberchars.status" class="mt-1 text-red-600">{{ validatemobile_numberchars.message }}</p>
                    <p v-if="!validatemobile_numberlength.status" class="mt-1 text-red-600">{{ validatemobile_numberlength.message }}</p>

                    <div class="mt-3">
                        <label for="sender" class="block mb-3 font-medium text-lg pl-1">Amount</label>
                        <input type="text" :disabled="true" v-model="amountStr" @keyup="validate" id="sender" class="p-3 rounded-md w-full ring-0 border border-gray-200" placeholder="3000" />
                    </div>
                    <div v-if="!validateamountchars.status" class="mt-1 text-red-600">{{ validateamountchars.message }}</div>

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
import { vendStore } from "@/store/operations/vend"
import { validateDigitChars, validateDigitLength } from "~~/composables/user/useValidation.js";

const userstore = userStore();
const vendstore = vendStore();
let validatemobile_numberchars = ref({})
let validatemobile_numberlength = ref({})
let validateamountchars = ref({})
let validateamountlength = ref({})
let percentageIncrease = ref(0)
let amountStr = ref("")


vendstore.getBillerCat()


const payObj = ref({
    mobile_number : "",
    bouquet : null,
    recipient : "",
    network : "",
    phone : "",
    type : "giftdata",
    country : "NG",
    amount : 0,
    face_amount : 0,
    userId : userstore.details.track_id,
    transactionType : "giftingdata-payment",
    paymentChannel : "Arise_data",
    charge : 0.5,
    plan : null,
    plan_type : "",
    Ported_number : true,
    currency : userstore.details.currency
})

let bouquetplans = ref([])
const route = useRoute()
let network = ref("")


let serviceSuffix = vendstore.services.data.find( x => x.service_name == 'data').service_suffix


watch(() => route.query.network, (newval) => {
    payObj.value.mobile_number = "",
    network.value = null,
    payObj.value.phone = "",
    payObj.value.country = "",
    payObj.value.amount = "",
    finalapiresponse.value = {}
    payObj.value.bouquet = null
    
})

watchEffect(() => {

    if(route.query.network){
        if(route.query.network.length > 0){
            network.value = route.query.network;    
        }

        if(process.client){
            let percentValue = vendstore.services.data.find(x => x.service_name == 'data').percentage.find(x => x.user_type == userstore.details.type)
            percentageIncrease.value = percentValue == undefined ? 0 : percentValue.value
        
            if(Object.keys(vendstore.dataServices).length > 0){ 
                let finalValue = network.value + "_" + serviceSuffix
                
                let billerObj = vendstore.dataServices[finalValue]['ALL']

                if(billerObj.length > 0){
                    bouquetplans.value = billerObj.filter(x => x.plan_type != 'SME')
                }
            }
        }

        
    }
})


function setObj(){
    if(bouquetplans.value.length > 0){
        let network = bouquetplans.value.filter(x => x.id == payObj.value.plan)[0].network
        let amount = Number(bouquetplans.value.filter(x => x.id == payObj.value.plan)[0].plan_amount)
        let plan_type = bouquetplans.value.filter(x => x.id == payObj.value.plan)[0].plan + " " + bouquetplans.value.filter(x => x.id == payObj.value.plan)[0].month_validate + " " + bouquetplans.value.filter(x => x.id == payObj.value.plan)[0].plan_type
        
        payObj.value.face_amount = amount
        payObj.value.amount = Math.round(amount + (amount * percentageIncrease.value/100))
        let formattedNum = useFormatDigits(payObj.value.amount)
        amountStr.value = userstore.details.currency == 'NGN' ? '₦' + `${formattedNum}` : '$' + `${formattedNum}` 
        
        payObj.value.network = network
        payObj.value.plan_type = plan_type

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
    payObj.value.recipient = payObj.value.mobile_number

    try{
        await vendstore.createBill(payObj.value).then((response) => {

            api_calling.value = false
            finalapiresponse.value = response
            payObj.value.plan = null
            payObj.value.mobile_number = ""
            amountStr.value = ""
        })
         
    }catch(error){
        api_calling.value = false
        finalapiresponse.value = error

        payObj.value.plan = null
        payObj.value.mobile_number = ""
        amountStr.value = ""
    }finally{
        timer.value = 5
        chkCancelTime.value = false
    }
}

const chkfields = computed(() => {
    return api_calling.value == true || payObj.value.mobile_number.length == 0 || payObj.value.amount <= 0 ? true : false
})

function validate(){
    validatemobile_numberchars.value = validateDigitChars(payObj.value.mobile_number)
    validatemobile_numberlength.value = validateDigitLength(payObj.value.mobile_number, 11)
    validateamountchars.value = validateDigitChars(payObj.value.amount)                           
    validateamountlength.value = validateDigitLength(payObj.value.amount, 11)
}


</script>

<style lang="scss" scoped>

</style>