<template>
    <div :style="{ 'background-color' : appConfig.siteTheme.bannerBgColor, 'color' : appConfig.siteTheme.bannerTextColor  }" class="p-1 rounded-md mb-5">
        <div class="p-3 flex flex-nowrap justify-between">
            <div class="w-11/12">
                <p class="text-2xl font-bold">Welcome, <span class="text-[#0f8B8D]">{{ adminstore.details.first_name }}</span></p>
                <p class="border-b border-gray-400 mt-2 lg:w-1/2"></p>
                <p class="font-bold text-xl"><span class="font-medium text-md"></span>{{ adminstore.details.virtual_account_number }}</p>
                <p class="font-medium text-sm lg:text-lg">{{ adminstore.details.virtual_account_name }}</p>
                <p class="font-medium text-md">{{ adminstore.details.virtual_account_bank }}</p>
                <button v-if="adminstore.details.customer_code.length == 0" :disabled="api_calling" @click="createVirtualAcct" class="bg-[#0f8B8D] text-white text-sm w-1/2 lg:w-fit mt-2 lg:text-lg font-bold p-2 rounded-lg hover:bg-[#0c5859]"><span v-if="api_calling" class="animate-spin h-7 w-7 -mb-2 mr-3 pt-2 border-r-2 border-b-2 border-l-2 border-whtie rounded-full inline-block"></span> Create Virtual Acct</button>
            </div>

            <div class="w-6/12 lg:w-1/2 text-right">
                <p class="text-md lg:text-lg font-bold">Wallet Balance: </p>
                <p class="text-lg lg:text-2xl font-bold text-green-600">{{ adminstore.details.currency == 'NGN' ? '₦' : '$' }} {{ adminstore.details.wallet_balance }}</p>
                <button @click="showModal" class="bg-[#0f8B8D] text-white text-sm w-full lg:w-fit lg:text-lg font-bold p-2 rounded-lg hover:bg-[#0c5859]">Fund Wallet</button>
            </div>

            <Teleport to="body">
                <ModalBase v-if="showmodal" @hide-modal="hideModal" />
            </Teleport>
            
            
        </div>
    </div>
</template>

<script type="text/javascript" setup>
import { adminStore } from "@/store/admin.js"

const adminstore = adminStore()

const showmodal = ref(false)
let api_calling = ref(false)

const appConfig = useAppConfig(); 

function showModal() {
    adminstore.$patch({
        modalshowing :  true
    })

    showmodal.value = true
}

function hideModal(){
    adminstore.$patch({
        modalshowing : false
    })
    showmodal.value = false
}
const fundingmethod = ref("paystack")

const createVirtualAcct = async () => {
    api_calling.value = true
    let payload = {
        first_name : adminstore.details.first_name,
        last_name : adminstore.details.last_name,
        email : adminstore.details.email,
        phone : adminstore.details.phone
    }

    try {
        await adminstore.createVirtualAcct(payload)
        
        api_calling.value = false
        adminstore.getSiteDetails()
        
        
    } catch (error) {
        api_calling.value = false
        console.log(error)
    }
}

</script>

<style lang="scss" scoped>

</style>