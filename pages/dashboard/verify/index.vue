<template>
    <div>
        <WelcomeBase />
        <div class="rounded-md bg-gray-100 p-2 min-h-40">
            <div class="text-2xl text-center font-bold">
              <h3 v-if="apiCalling">Your Payment is being Verified, Please Wait...</h3>
              <h3 v-if="apiResponse.status == 'success'">Verification completed</h3>
              <h3 v-if="apiResponse.status == 'error'">Transaction Error</h3>
            </div>

            <div class="my-7">
                <p v-if="apiCalling" class="animate-pulse text-center text-xl lg:text-3xl font-semibold text-yellow-600">Verifying...</p>
                <p v-if="apiResponse.status == 'success'" class="mt-1 p-3 rounded-sm bg-green-600/[0.2] font-semibold text-xl my-5 text-center text-green-600">{{ apiResponse.message }}</p>
                <p v-if="apiResponse.status == 'error'" class="mt-1 text-red-600">{{ apiResponse.message }}</p>
                <p v-if="apiError" class="mt-1 text-red-600">{{ apiResponse }}</p>
                
            </div>
        </div>

    </div>
</template>

<script type="text/javascript" setup>

import { userStore } from "@/store/user.js"
import { validateDigitChars, validateDigitLength } from "~~/composables/user/useValidation.js";

const userstore = userStore();

let apiCalling = ref(false)
let apiError = ref(false)
let route = useRoute();
let apiResponse = ref({})
let reference = route.query.reference

const verifyPay = async () => {
    apiCalling.value = true
    
    try{
        await userstore.verifyPayment(reference).then((response) => {
            apiCalling.value = false

            userstore.getUserDetails(userstore.details.track_id)
            userstore.getTransactions()
            userstore.getDeposits()

            apiResponse.value = response
        })
    }catch(error){
        apiCalling.value = false
        apiResponse.value = error
        apiError.value = true
        console.log(error)
    }
    
}
if(route.query.reference){ 
    verifyPay()
    
}


</script>

<style lang="scss" scoped>

</style>