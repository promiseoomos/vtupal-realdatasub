<template>
    <div>
        <WelcomeBase />
        <div class="rounded-md bg-gray-100 p-2">
            <p class="text-xl border-b-4 border-black mx-auto w-fit">Broadcast Message</p>
            <form @submit.prevent="cancelTransaction">
                <div class="rounded-md bg-white p-2 mt-3">
                    <div class="mt-3">
                        <label for="subject" class="block mb-3 font-medium text-lg pl-1">Subject:</label>
                        <input type="text" v-model="msgObj.subject" id="subject" class="p-3 rounded-md w-full ring-0 border border-gray-200" />
                    </div>
                    <div class="mt-3">
                        <label for="sender" class="block mb-3 font-medium text-lg pl-1">Sender ID:</label>
                        <input type="text" v-model="msgObj.senderId" id="sender" class="p-3 rounded-md w-full ring-0 border border-gray-200" placeholder="Topup BossNg" />
                    </div>
                    <div class="mt-3">
                        <label for="recipients" class="block mb-1 font-medium text-lg pl-1">Recipients:</label>
                        <p class="text-sm text-gray-400 mb-2 pl-1">Seperate multiple recipients with comma</p>
                        <textarea rows="4" v-model="msgObj.recipients" id="recipients" class="p-3 rounded-md w-full ring-0 border border-gray-200 focus:border-white"></textarea>
                        <p class="pl-1">{{ msgObj.recipients.length > 0 ? msgObj.recipients.split(",").length : 0 }} Recipient{{ msgObj.recipients.split(",").length > 1 ? 's' : ''  }}</p>
                    </div>
                    
                    <div class="mt-3">
                        <label for="message" class="block mb-3 font-medium text-lg pl-1">Message:</label>
                        <textarea rows="4" v-model="msgObj.message" id="message" class="p-3 rounded-md w-full ring-0 border border-gray-200 focus:border-white"></textarea>
                        <p>{{ wordCount }} of 63 Words ({{ pageCount }} Page)</p>
                    </div>
                    <div class="mt-3">
                        <label for="sender" class="block mb-3 font-medium text-lg pl-1">Routing Config</label>

                        <div class="">
                            <input type="radio" v-model="msgObj.routing" id="sender" value="2" class="p-3  ring-0 border border-gray-200" placeholder="Topup BossNg" />
                            Send all messages via the Basic Route. DND phone numbers are not charged    
                        </div>
                        <div class="">
                            <input type="radio" v-model="msgObj.routing" value="3" id="sender" class="p-3 ring-0 border border-gray-200" placeholder="Topup BossNg" />
                            Send message via the Basic route but send to those on DND via the Corporate Route.
                        </div>
                        
                    </div>


                    <div v-if="apiResponse" class="rounded-md p-3 mt-3" :class="{ 'text-red-600 bg-red-600/[.2]' : apiResponse.status == 'error' || apiError, 'text-green-600 bg-green-600/[.2]' : apiResponse.status == 'success' }">
                        <p v-if="apiResponse.status == 'error'">{{ apiResponse.message }}</p>
                        <p v-else-if="apiResponse.status == 'success'">{{ apiResponse.message }}</p>
                        <p v-else>{{ apiResponse }}</p>
                    </div>

                   <div class="text-center p-2 mt-3">
                        <button :disabled="checkfields" :class="{ 'opacity-50' : checkfields }" type="submit" class="p-3 px-10 bg-[#0f8B8D] w-full lg:w-1/2 text-white rounded-md font-bold text-md hover:bg-[#0a5556]"><span v-if="api_calling" class="animate-spin h-7 w-7 -mb-2 mr-3 pt-2 border-r-2 border-b-2 border-l-2 border-whtie rounded-full inline-block"></span> Send</button>
                    </div>
                   
                    <div v-if="api_calling" class="text-center p-2 mt-3">
                        <button :disabled="chkCancelTime" @click="cancelProceed" :class="{ 'opacity-50' : chkCancelTime }" class="p-2 bg-black w-full lg:w-1/2 text-white rounded-md font-bold text-lg">
                            Cancel ({{ timer }})
                        </button>
                    </div>
                

                    
                </div>            
            </form>    

            

        </div>
    </div>
</template>

<script type="text/javascript" setup>

import { userStore } from "@/store/user.js"
import { vendStore } from "~~/store/operations/vend";

const config = useRuntimeConfig();
getBaseUrl(config)

const userstore = userStore()
const vendstore = vendStore()

const msgObj = ref({
    subject : "",
    senderId : "",
    recipients : "",
    recipient : "",
    message : "",
    routing : "2",
    type : "0",
    amountPerPage : 0,
    recipientsCount : "",
    amount : 0,
    face_amount : 0,
    face_price : 0,
    famount : "",
    userId : userstore.details.track_id,
    transactionType : "bulksms-payment",
    paymentChannel : "Smartsmssolultion",
    charge : 0.5,
    currency : userstore.details.currency
})

let api_calling = ref(false)
let apiResponse = ref("")
let apiError = ref(false)
let percentageIncrease = ref(0)


watchEffect(() => {


    if(process.client){
        if(vendstore.services2.length > 0){
            let product = vendstore.services2.find(x => x.standard_name == 'bulk_sms')

            let pricingType = product.pricing

            if(pricingType == 'value'){
                msgObj.value.amountPerPage = Number(product[userstore.details.type + '_price'])

            }else if(pricingType == 'percentage'){
                msgObj.value.amountPerPage = Math.round(Number(product.face_price) + (Number(product.face_price) * Number(product[userstore.details.type + '_percentage']/100) ))
            }

            msgObj.value.face_price = product.face_price
        }
    }
})



const wordCount = computed(() => {
    return msgObj.value.message.length > 0 ? msgObj.value.message.split(" ").length : 0
})

const pageCount = computed(() => {
    return Math.ceil(wordCount.value / 63)
})

const checkfields = computed(() => {
    return api_calling.value || msgObj.value.subject.length == 0 || msgObj.value.recipients.length == 0 || msgObj.value.senderId.length == 0 || msgObj.value.message.length == 0 ? true : false
})

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
        sendSMS()
    }
})

const addOneSecond = () => {
    timer.value--
}

const sendSMS = async () => {

    msgObj.value.recipientsCount = msgObj.value.recipients.split(",").length

    msgObj.value.amount = msgObj.value.recipientsCount * msgObj.value.amountPerPage;
    msgObj.value.face_amount = msgObj.value.recipientsCount * msgObj.value.face_price;

    msgObj.value.recipient = msgObj.value.recipients

    let formattedNum = useFormatDigits(msgObj.value.amount)
    msgObj.value.famount = userstore.details.currency == 'NGN' ? '₦' + `${formattedNum}` : '$' + `${formattedNum}` 

    api_calling.value = true;

    try {
        const response = await vendstore.sendSMS(msgObj.value)
        api_calling.value = false;
        userstore.getUserDetails(userstore.details.track_id)

        if(response.status == 'success'){
            msgObj.value.subject = ""
            msgObj.value.senderId = ""
            msgObj.value.recipients = ""
            msgObj.value.message = ""
        }



        apiResponse.value = response

    } catch (error) {
        api_calling.value = false;
        apiResponse.value = error
        apiError.value = true

    }finally{
        timer.value = 5
        chkCancelTime.value = false
    }
}

</script>

<style lang="scss" scoped>

</style>