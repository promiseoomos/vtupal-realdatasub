<template>
    <div>
        <WelcomeBase />
        <div class="p-2">

            <p class="text-lg lg:text-3xl mb-6 font-bold text-[#0f8B8D] text-center border-b-4 border-black">All Transactions</p>
            <div class="p-6 overflow-x-auto bg-slate-300/[.4] min-h-56 w-full rounded-lg">

                <div class="flex flex-nowrap gap-4 justify-start lg:justify-around p-3 w-[800px] lg:w-full bg-slate-600/[.4] rounded-sm mb-4">
                    <p class="text-sm w-[5%]">S/N</p>
                    <p class="text-sm w-[30%] mr-2 font-semibold">Reference</p>
                    <p class="text-sm w-[40%] mr-2 font-semibold">Recipient</p>
                    <p class="text-sm w-[30%] mr-2 font-semibold">Transaction type</p>
                    <p class="text-sm w-[30%] mr-2 font-semibold">Amount</p>
                    <p class="text-sm w-[30%] mr-2 font-semibold">Date</p>
                    <p class="w-[30%] mr-2 font-semibold">Status</p>
                </div>
                <div v-for="(transaction,index) in getterstore.transactions.data" :key="transaction.id" class="flex flex-nowrap justify-start lg:justify-around w-[800px] lg:w-full gap-4 bg-slate-100 mt-3 rounded-sm p-3">
                    <p class="text-sm w-[5%] mr-2 font-semibold">{{ index + 1 }}</p>
                    <p class="text-sm w-[30%] mr-2 font-semibold overflow-x-auto">{{ transaction.reference }}</p>
                    <p class="text-sm w-[40%] mr-2 font-semibold overflow-x-auto">{{ transaction.recipient }}</p>
                    <p class="text-sm w-[30%] mr-2 font-semibold">{{ capitalize(transaction.transactionType) }}</p>
                    <p class="text-sm w-[30%] mr-2 font-semibold" :class="{ 'text-red-600' : transaction.category == 'debit', 'text-green-600' : transaction.category == 'credit'}"><span>{{ transaction.category == "debit" ? '-' : '+'}}</span>{{ transaction.currency == 'NGN' ? '₦' : '$' }}{{ transaction.amount }}</p>
                    <p class="text-sm w-[30%] mr-2 font-semibold">{{ transaction.trnxDate }}</p>
                    <p class="w-[30%] mr-2"><span class="rounded-full text-center h-fit p-1 px-3 text-sm" :class="{'bg-yellow-500/[.2] text-yellow-500' : transaction.status == 'pending' || transaction.status == 'Pending', 'bg-red-600/[.2] text-red-600' : transaction.status == 'error', 'bg-green-600/[.2] text-green-600' : transaction.status == 'completed' || transaction.status == 'Completed' || transaction.status == 'success'  }">{{ capitalize(transaction.status) }}</span></p>
                </div>
            </div>
            
        </div>
    </div>
</template>

<script type="text/javascript" setup>
import { getterStore } from "~~/store/operations/getters.js";

const getterstore = getterStore();

getterstore.getTransactions()
getterstore.getPayments()
getterstore.getDeposits()


</script>

<style lang="scss" scoped>

</style>