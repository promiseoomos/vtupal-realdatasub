<template>
    <div :style="{ 'background-color' : appConfig.siteTheme.bannerBgColor, 'color' : appConfig.siteTheme.bannerTextColor  }" class="p-1 rounded-md mb-5">
        <div class="p-3 flex flex-nowrap justify-between">
            <div class="w-11/12">
                <p class="text-2xl font-bold">Welcome, <span class="text-[#0f8B8D]">{{ userstore.details.first_name }}</span><span :class="{ 'bg-yellow-300/[.3] text-yellow-700' : userstore.details.type == 'normal', 'bg-sky-300/[.4] text-sky-700': userstore.details.type == 'reseller', 'bg-purple-300/[.4] text-purple-700' : userstore.details.type == 'retailer'}" class="p-2 ml-2 text-xs rounded-md ">{{ capitalize(userstore.details.type) }}</span></p>
                <p class="border-b border-gray-400 mt-2 lg:w-1/2"></p>
                <p class="font-bold text-xl"><span class="font-medium text-md"></span>{{ userstore.details.virtual_account_no }}</p>
                <p class="font-medium text-sm lg:text-lg">{{ userstore.details.virtual_account_name }}</p>
                <p class="font-medium text-md">{{ userstore.details.virtual_account_bank }}</p>
                <!-- <p>Select the Operation you want to Perform</p> -->
            </div>

            <div class="w-6/12 lg:w-1/2 text-right">
                <p class="text-md lg:text-lg font-bold">Wallet Balance: </p>
                <p class="text-lg lg:text-2xl font-bold text-green-600">{{ userstore.details.currency == 'NGN' ? '₦' : '$' }} {{ userstore.details.wallet_balance }}</p>
                <button @click="showModal" class="bg-[#0f8B8D] text-white text-sm w-full lg:w-fit lg:text-lg font-bold p-2 rounded-lg hover:bg-[#0c5859]">Fund Wallet</button>
            </div>

            <Teleport to="body">
                <ModalBase v-if="showmodal" @hide-modal="hideModal" />
            </Teleport>
            
            
        </div>
    </div>
</template>

<script type="text/javascript" setup>
import { userStore } from "@/store/user.js"

const userstore = userStore()

const showmodal = ref(false)
const appConfig = useAppConfig();

function showModal() {
    userstore.$patch({
        modalshowing :  true
    })

    showmodal.value = true
}

function hideModal(){
    userstore.$patch({
        modalshowing : false
    })
    showmodal.value = false
}
const fundingmethod = ref("paystack")

</script>

<style lang="scss" scoped>

</style>