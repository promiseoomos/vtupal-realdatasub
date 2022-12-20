<template>
    <div>
        <WelcomeBaseAdmin />

        <div class="rounded-md bg-white pb-10">
            
            <div class="pt-10">
                <button @click="showModal = true" class="p-3 rounded-md block border w-full lg:w-1/2 border-[#0f8B8D] text-[#0f8B8D] font-bold text-md hover:text-[#50b8ba] hover:border-[#50b8ba] lg:text-lg mx-auto">Withdraw Profit</button>
            </div>
            

            <Teleport to="body">
                <ModalBase v-if="showModal" @hide-modal="hideModal">
                    <template #title>Withdraw your Earnings</template>
                    <template #body>
                        <p class="text-lg font-semibold text-center mt-3">Confirm Bank Details</p>

                        <div v-if="adminstore.details.recipient_code.length > 0" class="p-3 bg-slate-300/[.35] rounded-md">
                            <p class="font-bold">Amount will be sent to this Account.</p>
                            <div class="flex flex-nowrap justify-between mt-3">
                                <p class="w-1/2 text-lg font-medium">Account Number:</p>
                                <p class="w-1/2 font-bold text-left">{{ adminstore.details.account_number }}</p>
                            </div>
                            <div class="flex flex-nowrap justify-between">
                                <p class="w-1/2 text-lg font-medium">Account Bank :</p>
                                <p class="w-1/2 font-bold text-left">{{ adminstore.details.account_bank }}</p>
                            </div>
                            <div class="flex flex-nowrap justify-between">
                                <p class="w-1/2 text-lg font-medium">Account Name:</p>
                                <p class="w-1/2 font-bold text-left">{{ adminstore.details.account_name }}</p>
                            </div>
                        </div>
                        <div v-else class="mt-3">
                            <p class="text-lg">Account Details is Incomplete. Please go to <nuxt-link class="text-blue-600 text-bold" :to="{ name : 'admin-settings-bankdetails'}">your Profile</nuxt-link> to update your account details before Withdrawal.</p>
                        </div>
                        
                        <p class="font-semibold text-lg mt-5">Amount to Withdrawal :</p>
                        <input type="number" v-model="fundObj.amount" min="0" :max="adminstore.analysis.withdrawableBalance" class="p-3 mt-2 rounded-lg border border-gray-400 w-full" placeholder="3000">
                        
                        <div v-if="finalapiresponse" class="rounded-md p-3 mt-3" :class="{ 'text-red-600 bg-red-600/[.2]' : finalapiresponse.status == false, 'text-green-600 bg-green-600/[.2]' : finalapiresponse.status == true }">
                            <p v-if="finalapiresponse.status == false">{{ finalapiresponse.message }}.</p>
                            <p v-if="finalapiresponse.status == true">{{ finalapiresponse.message }}</p>
                        </div>

                        <button :disabled="fundObj.amount <= 0 || adminstore.details.recipient_code.length <= 0 || !withdrawable" :class="{ 'bg-[#0f8b8d89] hover:bg-[#0f8b8d89]' : fundObj.amount <= 0 || adminstore.details.recipient_code.length <= 0 || !withdrawable  }" @click="withdrawFund" class="block w-full p-3 rounded-sm mt-3 font-semibold text-lg bg-[#0f8B8D] text-white hover:bg-[#13696a]"><span v-if="api_calling" class="animate-spin h-7 w-7 -mb-2 mr-3 pt-2 border-r border-b border-l-2 border-white rounded-full inline-block"></span> Withdraw Profit</button>
                    </template>
                </ModalBase>
            </Teleport>

            <div class="overflow-x-auto bg-slate-300/[.4] mt-10">
                
                <div class="flex flex-nowrap gap-4 justify-start lg:justify-around w-[800px] lg:w-full bg-slate-600/[.4] p-3 rounded-sm mb-4">
                    <p class="text-sm w-[5%]">S/N</p>
                    <p class="text-sm w-[30%] mr-2 font-semibold">User</p>
                    <p class="text-sm w-[30%] mr-2 font-semibold">Recipient</p>
                    <p class="text-sm w-[30%] mr-2 font-semibold">Transaction type</p>
                    <p class="text-sm w-[30%] mr-2 font-semibold">Transaction Ref</p>
                    <p class="text-sm w-[30%] mr-2 font-semibold">Amount</p>
                    <p class="text-sm w-[30%] mr-2 font-semibold">Profit</p>
                    <p class="text-sm w-[30%] mr-2 font-semibold">Date</p>
                    <p class="w-[30%] font-semibold">Status</p>
                </div>
                
            
                <div v-if="transactions.length == 0" class="flex flex-nowrap justify-start lg:justify-around w-[800px] lg:w-full bg-slate-100 gap-4 mt-3 rounded-sm p-3">
                    <p>No Transactions</p>
                </div>
                <div v-else>
                
                    <div v-for="(transaction,index) in transactions" :key="transaction.id" class="flex flex-nowrap justify-start lg:justify-around w-[800px] lg:w-full bg-slate-100 gap-4 mt-3 rounded-sm p-3">
                        
                        <p class="text-sm w-[5%] font-semibold">{{ index + 1 }}</p>
                        <p class="text-sm w-[30%] mr-2 overflow-x-auto font-semibold">{{ transaction.userDetails[0].last_name }} {{ transaction.userDetails[0].first_name }}</p>
                        <p class="text-sm w-[30%] mr-2 overflow-x-auto font-semibold">{{ transaction.recipient }}</p>
                        <p class="text-sm w-[30%] mr-2 break-words font-semibold">{{ capitalize(transaction.transaction_type) }}</p>
                        <p class="text-sm w-[30%] mr-2  font-semibold">{{ transaction.reference }}</p>
                        <p class="text-sm w-[30%] mr-2 font-semibold" :class="{ 'text-red-600' : transaction.category == 'debit', 'text-green-600' : transaction.category == 'credit'}"><span>{{ transaction.category == "debit" ? '-' : '+'}}</span>{{ transaction.currency == 'NGN' ? '₦' : '$' }}{{ transaction.amount }}</p>
                        <p class="text-sm w-[30%] mr-2 font-semibold text-green-600" ><span>+</span>{{ transaction.currency == 'NGN' ? '₦' : '$' }}{{ transaction.profit }}</p>
                        <p class="text-sm w-[30%] mr-2  font-semibold">{{ transaction.trnx_date }}</p>
                        <p class="w-[30%]"><span class="rounded-full text-center h-fit p-1 px-3 text-sm" :class="{'bg-yellow-500/[.2] text-yellow-500' : transaction.status == 'pending' || transaction.status == 'Pending', 'bg-red-600/[.2] text-red-600' : transaction.status == 'error', 'bg-green-600/[.2] text-green-600' : transaction.status == 'completed' || transaction.status == 'Completed' || transaction.status == 'success'  }">{{ capitalize(transaction.status) }}</span></p>                    
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</template>

<script type="text/javascript" setup>
import { userStore } from "~~/store/user.js";
import { getterStore } from "~~/store/operations/getters";
import { adminStore } from "~~/store/admin";


const userstore = userStore();
const adminstore = adminStore();
const getterstore = getterStore()

const showModal = ref(false)
let api_calling = ref(false)
let finalapiresponse = ref({})
let apiError = ref(false)

adminstore.getProiftAnalysis()

let transactions = ref({})


let activeTab = ref("sms")
let activeStyle = ref("bg-white rounded-lg px-10")

try {
    const response = await adminstore.getTransactionAnalysis()
    
    transactions.value = response.data
    
} catch (error) {
    console.log(error)
}

const fundObj = ref({
    amount : 0,
    details : {
        userId : adminstore.details.track_id,
        recipient_code : adminstore.details.recipient_code
    }
})

function hideModal(){
    adminstore.$patch({
        modalshowing : false
    })
    showModal.value = false
}

const withdrawable = computed(() => {
    return (adminstore.analysis.withdrawableBalance) > fundObj.value.amount ? true : false 
})

const withdrawFund = async () => {
    api_calling.value = true

    try{
        await userstore.withdrawFunds(fundObj.value).then((response) => {
            api_calling.value = false
            finalapiresponse.value = response

            fundObj.value.amount = 0
            
            adminstore.getTransactionAnalysis()
            
            setTimeout(() => {
                adminstore.getSiteDetails()    
            }, 3000);
        })   
    }catch(error){
        api_calling.value = false
        finalapiresponse.value = error
    }
}
</script>

<style lang="scss" scoped>

</style>