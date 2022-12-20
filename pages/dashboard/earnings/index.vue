<template>
    <div>
        <WelcomeBase />

        <div class="rounded-md bg-white pb-10">
            <div class="flex flex-wrap justify-between hidden p-3 pt-5">
                <StatsBoardBase class="w-1/3" title="Total Earnings" :symbol="userstore.details.currency == 'NGN' ? '₦' : '$'" :value="2000" />
                <StatsBoardBase class="w-1/3" title="Total Referrals" :value="20" />
                <StatsBoardBase class="w-1/3" title="Total Withdrawal" :symbol="userstore.details.currency == 'NGN' ? '₦' : '$'" :value="1800" />
            </div>
            
            <div class="pt-10">
                <button @click="showModal = true" class="p-3 rounded-md block border w-full lg:w-1/2 border-[#0f8B8D] text-[#0f8B8D] font-bold text-md hover:text-[#50b8ba] hover:border-[#50b8ba] lg:text-lg mx-auto">Withdraw Funds</button>
            </div>
            

            <Teleport to="body">
                <ModalBase v-if="showModal" @hide-modal="hideModal">
                    <template #title>Withdraw your Earnings</template>
                    <template #body>
                        <p class="text-lg font-semibold text-center mt-3">Confirm Bank Details</p>

                        <div v-if="userstore.details.recipient_code.length > 0" class="p-3 bg-slate-300/[.35] rounded-md">{{  }}
                            <p class="font-bold">Amount will be sent to the Following Account. A flat charge of {{ userstore.details.currency}} 50 will be applied</p>
                            <div class="flex flex-nowrap justify-between mt-3">
                                <p class="w-1/2 text-lg font-medium">Account Number:</p>
                                <p class="w-1/2 font-bold text-left">{{ userstore.details.account_number }}</p>
                            </div>
                            <div class="flex flex-nowrap justify-between">
                                <p class="w-1/2 text-lg font-medium">Account Bank :</p>
                                <p class="w-1/2 font-bold text-left">{{ userstore.details.bank_name }}</p>
                            </div>
                            <div class="flex flex-nowrap justify-between">
                                <p class="w-1/2 text-lg font-medium">Account Name:</p>
                                <p class="w-1/2 font-bold text-left">{{ userstore.details.account_name }}</p>
                            </div>
                        </div>
                        <div v-else class="mt-3">
                            <p class="text-lg">Account Details is Incomplete. Please go to <nuxt-link class="text-blue-600 text-bold" :to="{ name : 'dashboard-settings-myprofile'}">your Profile</nuxt-link> to update your account details before Withdrawal.</p>
                        </div>

                        <p class="font-semibold text-lg mt-5">Amount to Withdrawal :</p>
                        <input type="number" v-model="fundObj.amount" min="0" :max="userstore.details.wallet_balance - 50" class="p-3 mt-2 rounded-lg border border-gray-400 w-full" placeholder="3000">
                        
                        <div v-if="finalapiresponse" class="rounded-md p-3 mt-3" :class="{ 'text-red-600 bg-red-600/[.2]' : finalapiresponse.status == false, 'text-green-600 bg-green-600/[.2]' : finalapiresponse.status == true }">
                            <p v-if="finalapiresponse.status == false">{{ finalapiresponse.message }}.</p>
                            <p v-if="finalapiresponse.status == true">{{ finalapiresponse.message }}</p>
                        </div>

                        <button :disabled="fundObj.amount <= 0 || userstore.details.recipient_code.length <= 0 || !withdrawable" :class="{ 'bg-[#0f8b8d89] hover:bg-[#0f8b8d89]' : fundObj.amount <= 0 || userstore.details.recipient_code.length <= 0 || !withdrawable  }" @click="withdrawFund" class="block w-full p-3 rounded-sm mt-3 font-semibold text-lg bg-[#0f8B8D] text-white hover:bg-[#13696a]"><span v-if="api_calling" class="animate-spin h-7 w-7 -mb-2 mr-3 pt-2 border-r border-b border-l-2 border-white rounded-full inline-block"></span> Withdraw </button>
                    </template>
                </ModalBase>
            </Teleport>

            <p class="text-center text-lg font-bold">Referrals Table</p>

            <div class="mt-10 overflow-x-auto">                
                <div class="flex flex-nowrap gap-4 justify-start lg:justify-around w-[800px] lg:w-full p-3 bg-slate-600/[.4] rounded-sm mb-4">
                    <p class="text-sm w-[20%]">S/N</p>
                    <p class="text-sm w-[20%] font-semibold">Referral Name</p>
                    <p class="text-sm w-[20%] font-semibold">Date referred</p>
                    <p class="text-sm w-[20%] font-semibold">Amount</p>
                    <p class="text-sm w-[20%] font-semibold">status</p>
                </div>
                <div v-for="(referral,index) in getterstore.referrals.data" :key="referral.id" class="flex flex-nowrap justify-start lg:justify-around w-[800px] lg:w-full gap-4 mt-3 bg-slate-100 rounded-sm p-3">
                    <p class="text-sm w-[20%] font-semibold">{{ index + 1 }}</p>
                    <p class="text-sm w-[20%] font-semibold">{{ referral.name }}</p>
                    <p class="text-sm w-[20%] font-semibold">{{ referral.dateReferred }}</p>
                    <p class="text-sm w-[20%] mr-2 font-semibold text-green-600">+{{ userstore.details.currency == 'NGN' ? '₦' : '$' }}{{ referral.amount }}</p>
                    <p class="w-[20%]"><span class="rounded-full text-center h-fit p-1 px-3 text-sm" :class="{'bg-yellow-500/[.2] text-yellow-500' : referral.status == 'unredeemed', 'bg-red-600/[.2] text-red-600' : referral.status == 'error', 'bg-green-600/[.2] text-green-600' : referral.status == 'redeemed'  }">{{ capitalize(referral.status) }}</span></p>
                </div>
            </div>
        </div>
    </div>
</template>

<script type="text/javascript" setup>
import { userStore } from "~~/store/user.js";
import { capitalize } from "~~/composables/user/useStrings.js"
import { getterStore } from "~~/store/operations/getters";

const userstore = userStore();
const getterstore = getterStore()

const showModal = ref(false)
let api_calling = ref(false)
let finalapiresponse = ref({})
let apiError = ref(false)

const fundObj = ref({
    amount : 0,
    details : {
        userId : userstore.details.track_id,
        recipient_code : userstore.details.recipient_code
    }
})

function hideModal(){
    userstore.$patch({
        modalshowing : false
    })
    showModal.value = false
}

const withdrawable = computed(() => {
    return (userstore.details.wallet_balance - 50) > fundObj.value.amount ? true : false 
})

const withdrawFund = async () => {
    api_calling.value = true

    try{
        await userstore.withdrawFunds(fundObj.value).then((response) => {
            api_calling.value = false
            finalapiresponse.value = response

            fundObj.value.amount = 0
            
            userstore.getTransactions()
            
            setTimeout(() => {
                userstore.getUserDetails(userstore.details.track_id)    
            }, 3000);


            console.log(response)
        })   
    }catch(error){
        api_calling.value = false
        finalapiresponse.value = error
    }
}
</script>

<style lang="scss" scoped>

</style>