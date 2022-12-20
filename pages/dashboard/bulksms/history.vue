<template>
    <div>
        <WelcomeBase />
        <div class="p-2">
            <p class="text-lg lg:text-3xl mb-6 font-bold text-[#0f8B8D] text-center border-b-4 border-black">All Messages</p>

            <div class="p-6 bg-white min-h-56 w-full rounded-lg overflow-x-auto">

                <div class="flex flex-nowrap gap-4 justify-start lg:justify-around w-[800px] lg:w-full p-3 bg-slate-600/[.4] rounded-sm mb-4">
                    <p class="text-sm w-[5%]">S/N</p>
                    <p class="text-sm w-[20%] font-semibold">Subject</p>
                    <p class="text-sm w-[20%] font-semibold">Message</p>
                    <p class="text-sm w-[20%] font-semibold">Sender Id</p>
                    <p class="text-sm w-[20%] font-semibold">Recipients</p>
                    <p class="text-sm w-[20%] font-semibold">Amount Spent</p>
                    <p class="text-sm w-[20%] font-semibold">Date</p>
                    <p class="w-[20%] font-semibold">Status</p>
                </div>
                <div v-for="(smsmessage,index) in getterstore.smsmessages.data" :key="smsmessage.id" class="flex flex-nowrap gap-4 justify-start lg:justify-around w-[800px] lg:w-full  mt-3 bg-slate-100 rounded-sm p-3">
                    <p class="text-sm w-[5%] font-semibold">{{ index + 1 }}</p>
                    <p class="text-sm w-[20%] font-semibold">{{ smsmessage.subject }}</p>
                    <p class="text-sm w-[20%] font-semibold">{{ smsmessage.message }}</p>
                    <p class="text-sm w-[20%] font-semibold">{{ smsmessage.senderId }}</p>
                    <p class="text-sm w-[20%] font-semibold overflow-x-auto">{{ smsmessage.recipients }}</p>
                    <p class="text-sm w-[20%] font-semibold text-red-600">-{{ userstore.details.currency == 'NGN' ? '₦' : '$' }}{{ smsmessage.amount }}</p>
                    <p class="text-sm w-[20%] font-semibold">{{ smsmessage.date_sent }} <br> {{ smsmessage.time_sent }} </p>
                    <p class="w-[20%]"><span class="rounded-full text-center h-fit p-1 px-3 text-sm" :class="{'bg-yellow-500/[.2] text-yellow-500' : smsmessage.status == 'sending', 'bg-red-600/[.2] text-red-600' : smsmessage.status == 'error', 'bg-green-600/[.2] text-green-600' : smsmessage.status == 'success' }">{{ capitalize(smsmessage.status) }}</span></p>
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

getterstore.getSMSMessages()
</script>

<style lang="scss" scoped>

</style>