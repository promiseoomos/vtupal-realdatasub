<template>
    <div>
        <WelcomeBase />

        <div class="rounded-md bg-white pb-10">
            
            <div class="flex flex-wrap justify-between p-3 pt-5">
                <StatsBoardBase class="w-1/3" title="Purchase More" symbol="" value="Reseller" />
                <StatsBoardBase class="w-1/3" title="Lesser Costs" value="More Profits" />
                <StatsBoardBase class="w-1/3" title="Cheaper Rates" symbol="" value="More Earnings" />
            </div>

            <p class="font-bold text-center mt-7">You'll be charged N1,000 for the upgrade.</p>
            <button :disabled="api_calling || userstore.details.type == 'reseller'" @click="upgradeReseller" class="p-3 rounded-md block border w-full lg:w-1/2 border-[#0f8B8D] text-[#0f8B8D] font-bold text-md hover:text-[#50b8ba] hover:border-[#50b8ba] lg:text-lg mx-auto mt-3"><span v-if="api_calling" class="animate-spin h-7 w-7 -mb-2 mr-3 pt-2 border-r-2 border-b-2 border-l-2 border-whtie rounded-full inline-block"></span> Upgrade to reseller</button>
            
            <div class="mt-10">
                <p class="text-center text-lg font-bold">Referrals Table</p>
                
                <div class="flex flex-nowrap gap-4 overflow-x-auto justify-start lg:justify-around  p-3 bg-slate-600/[.4] rounded-sm mb-4">
                    <p class="text-sm w-1/12">S/N</p>
                    <p class="text-sm w-4/12 font-semibold">Referral Name</p>
                    <p class="text-sm w-5/12 font-semibold">Date referred</p>
                    <p class="text-sm w-5/12 font-semibold">Amount</p>
                    <p class="text-sm w-3/12 font-semibold">status</p>
                </div>
                <div v-for="(referral,index) in getterstore.referrals.data" :key="referral.id" class="flex flex-nowrap justify-start lg:justify-around  gap-4 overflow-x-auto mt-3 bg-slate-100 rounded-sm p-3">
                    <p class="text-sm w-1/12 font-semibold">{{ index + 1 }}</p>
                    <p class="text-sm w-4/12 font-semibold">{{ referral.name }}</p>
                    <p class="text-sm w-5/12 font-semibold">{{ referral.dateReferred }}</p>
                    <p class="text-sm w-5/12 font-semibold">{{ referral.amount }} {{ userstore.details.currency }}</p>
                    <p class="w-3/12"><span class="rounded-full text-center h-fit p-1 px-3 text-sm" :class="{'bg-yellow-500/[.2] text-yellow-500' : referral.status == 'unredeemed', 'bg-red-600/[.2] text-red-600' : referral.status == 'error', 'bg-green-600/[.2] text-green-600' : referral.status == 'redeemed'  }">{{ capitalize(referral.status) }}</span></p>
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
const getterstore = getterStore();

const showModal = ref(false)
let api_calling = ref(false)
let errorMessage = ref("")

const upgradeObj = ref({
    amount : 1000,
    transactionType : "upgrade",
    details : userstore.details
})

function hideModal(){
    userstore.$patch({
        modalshowing : false
    })
    showModal.value = false
}

const withdrawable = computed(() => {
    return (userstore.details.wallet_balance - 50) > upgradeObj.value.amount ? true : false 
})

const upgradeReseller = async () => {
    api_calling.value = true

    try{
        await userstore.upgradeReseller(upgradeObj.value).then((response) => {
            api_calling.value = false

            window.location = response.rurl
        })   
    }catch(error){
        errorMessage.value = error
        api_calling.value = false
    }
}

</script>

<style lang="scss" scoped>

</style>