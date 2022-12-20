<template>
    <div>
        <WelcomeBase />
        <div class="rounded-md bg-gray-100 p-2">
            
            <div class="flex flex-wrap justify-around mt-4 px-2">
                <ActiveBoardBase class="w-1/4 h-32" icon="smilevoice.png" text="GOTV" link="dashboard-smile" :query="{ type : 'voice'}"/>
                <ActiveBoardBase class="w-1/4 h-32" icon="smiledata.png" text="DSTV" link="dashboard-smile" :query="{ type : 'data'}"/>
            </div>
            <div class="rounded-md bg-white p-2 mt-3">
                <form @submit.prevent="createBill">
                <!-- {{ userstore.billercats }} -->
                    <div class="mt-3">
                        <label for="subject" class="block mb-3 font-medium text-lg pl-1">Choose Bouquet Plan</label>
                        <select class="p-3 rounded-md w-full ring-0 border border-gray-200" v-model="payObj.bouquet" @change="setObj">
                            <option value="null">--Select Bouquet Plan--</option>
                            <option v-for="(bouquet, index) in bouquetplans" :key="index" :value="bouquet.item_code">{{ bouquet.biller_name }} {{ userstore.details.currency }}{{ bouquet.amount }}</option>
                        </select>
                    </div>
                    <div class="mt-3" v-if="$route.query.type == 'GOTV'">
                        <label for="subject" class="block mb-3 font-medium text-lg pl-1">IUC Number</label>
                        <input :disabled="payObj.bouquet == null" type="text" @keyup="validate" @blur="validateBill" v-model="payObj.recipient" id="subject" class="p-3 rounded-md w-full ring-0 border border-gray-200" />
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
                        <button :disabled="chkfields" :class="{ 'opacity-50' : chkfields }" type="submit" class="p-3 px-10 bg-[#0f8B8D] text-white rounded-md font-bold text-md hover:bg-[#0a5556]"><span v-if="api_calling" class="animate-spin h-7 w-7 -mb-2 mr-3 pt-2 border-r-2 border-b-2 border-l-2 border-whtie rounded-full inline-block"></span> Make {{ $route.query.type }} Payment</button>
                    </div>
                </form>

                
            </div>
            

        </div>


    </div>
</template>

<script type="text/javascript" setup>

import { userStore } from "@/store/user.js"
import { validateDigitChars, validateDigitLength } from "~~/composables/user/useValidation.js";


const userstore = userStore();
let validaterecipientchars = ref({})
let validaterecipientlength = ref({})
let validatephonechars = ref({})
let validatephonelength = ref({})
let proceedanyways = ref(false)
let api_calling = ref(false)


const payObj = ref({
    recipient : "",
    bouquet : null,
    biller_code : "",
    phone : "",
    type : "",
    country : "",
    amount : "",
    userId : userstore.details.track_id,
    transactionType : "cable-payment",
    paymentChannel : "Flutterwave_API",
    charge : 0.5,
    currency : userstore.details.currency
})

let bouquetplans = ref([])
const route = useRoute()

userstore.getBillerCat({ category : 'all'})

function setObj(){
    let biller_code = bouquetplans.value.filter(x => x.item_code = payObj.value.bouquet)[0].biller_code
    let biller_type = bouquetplans.value.filter(x => x.item_code = payObj.value.bouquet)[0].biller_name
    let amount = bouquetplans.value.filter(x => x.item_code = payObj.value.bouquet)[0].amount
    let country = bouquetplans.value.filter(x => x.item_code = payObj.value.bouquet)[0].country
    
    payObj.value.biller_code = biller_code
    payObj.value.amount = amount
    payObj.value.type = biller_type
    payObj.value.country = country 

    api_calling.value = false;
}

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

async function createBill(){
    api_calling.value = true
    try{
        await userstore.createBill(payObj.value).then((response) => {
            api_calling.value = false
            finalapiresponse.value = response
        })    
    }catch(error){
        finalapiresponse.value = error
        api_calling.value = false
    }
    
}

const chkfields = computed(() => {
    return api_calling.value || validateapicalling.value || (validateapiresponse.value.status == 'error' && !proceedanyways.value)  || payObj.value.bouquet == 'null'  ? true : false
})

watch(() => route.query.type, (newval) => {
    payObj.value.recipient = "",
    payObj.value.bouquet = null,
    payObj.value.biller_code = "",
    payObj.value.phone = "",
    payObj.value.type = "",
    payObj.value.country = "",
    payObj.value.amount = "",
    validateapiresponse.value = {}
    payObj.value.bouquet = null
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

function makePayment(){
    return userstore.makePayment(payObj.value).then((response) => {
        console.log(response)
    })
}

watchEffect(() =>{
    if(userstore.billercats.length > 0){ 
        let billerObj = userstore.billercats.filter( x => x.name == "all")
      
        if(billerObj.length > 0){
            bouquetplans.value = billerObj[0]["data"].filter(x => x.short_name == route.query.type)
        }
        
    }
})


</script>

<style lang="scss" scoped>

</style>