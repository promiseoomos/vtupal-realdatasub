<template>
    <div>
        <WelcomeBase />
        <div class="p-2">
            <p class="text-lg lg:text-3xl mb-6 font-bold text-[#0f8B8D] text-center border-b-4 border-black">All Wallet Deposits</p>

            <div class="p-6 bg-white min-h-56 w-full rounded-lg overflow-x-auto">

                <div class="flex flex-nowrap gap-4  justify-start lg:justify-around w-[800px] lg:w-full p-3 bg-slate-600/[.4] rounded-sm mb-4">
                    <p class="text-sm w-[5%]">S/N</p>
                    <p class="text-sm w-[20%] font-semibold">Reference</p>
                    <p class="text-sm w-[20%] font-semibold">Transaction type</p>
                    <p class="text-sm w-[20%] font-semibold">Amount</p>
                    <p class="text-sm w-[20%] font-semibold">Date</p>
                    <p class="w-[20%] font-semibold">Status</p>
                </div>
                <div v-for="(deposit,index) in getterstore.deposits.data" :key="deposit.id" class="flex flex-nowrap justify-start lg:justify-around w-[800px] lg:w-full gap-4 mt-3 bg-slate-100 rounded-sm p-3">
                    <p class="text-sm w-[5%] font-semibold">{{ index + 1 }}</p>
                    <p class="text-sm w-[20%] font-semibold">{{ deposit.reference }}</p>
                    <p class="text-sm w-[20%] font-semibold">{{ capitalize(deposit.transactionType) }}</p>
                    <p class="text-sm w-[20%] mr-2 font-semibold" :class="{ 'text-red-600' : deposit.category == 'debit', 'text-green-600' : deposit.category == 'credit'}"><span>{{ deposit.category == "debit" ? '-' : '+'}}</span>{{ deposit.currency == 'NGN' ? '₦' : '$' }}{{ deposit.amount }}</p>
                    <p class="text-sm w-[20%] font-semibold">{{ deposit.trnxDate }}</p>
                    <p class="w-[20%]"><span class="rounded-full text-center h-fit p-1 px-3 text-sm" :class="{'bg-yellow-500/[.2] text-yellow-500' : deposit.status == 'pending' || deposit.status == 'Pending', 'bg-red-600/[.2] text-red-600' : deposit.status == 'error', 'bg-green-600/[.2] text-green-600' : deposit.status == 'completed' || deposit.status == 'Completed' || deposit.status == 'success'   }">{{ capitalize(deposit.status) }}</span></p>
                </div>
                
            </div>
            
        </div>
    </div>
</template>

<script type="text/javascript" setup>
import { userStore } from "~~/store/user.js";
import { capitalize } from "~~/composables/user/useStrings.js"
import { getterStore } from "~~/store/operations/getters";

const getterstore = getterStore()

getterstore.getDeposits()
</script>

<style lang="scss" scoped>

</style>