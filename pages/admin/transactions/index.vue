<template>
    <div>
        <WelcomeBaseAdmin />
        <p class="text-center text-2xl font-bold">See all Transactions</p>

        <div class="rounded-md bg-gray-100 mt-4 p-2">

            <div class="flex flex-wrap gap-4 justify-start text-md font-semibold cursor-pointer">
                <p @click="activeTab = 'bulksms'" :class="[ activeTab == 'bulksms' ? activeStyle : '' ]" class="p-3">SMS</p>
                <p @click="activeTab = 'giftingdata-payment'" :class="[ activeTab == 'giftingdata-payment' ? activeStyle : '' ]" class="p-3">Gifting Data</p>
                <p @click="activeTab = 'smedata-payment'" :class="[ activeTab == 'smedata-payment' ? activeStyle : '' ]" class="p-3">SME Data</p>
                <p @click="activeTab = 'cable'" :class="[ activeTab == 'cable' ? activeStyle : '' ]" class="p-3">Cable</p>
                <p @click="activeTab = 'electricity-payment'" :class="[ activeTab == 'electricity-payment' ? activeStyle : '' ]" class="p-3">Disco</p>
            </div>

            <div class="overflow-x-auto bg-slate-300/[.4] mt-5">
                
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

import { adminStore } from "@/store/admin.js"

const adminstore = adminStore();

let transactions = ref({})


let activeTab = ref("sms")
let activeStyle = ref("bg-white rounded-lg px-10")

try {
    const response = await adminstore.getTransactionAnalysis()
    
    transactions.value = response.data
    
} catch (error) {
    console.log(error)
}

</script>

<style lang="scss" scoped>

</style>