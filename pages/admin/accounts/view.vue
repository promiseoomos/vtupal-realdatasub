<template>
    <div>
        <WelcomeBaseAdmin />
        <div class="p-2">
            <p class="text-lg lg:text-3xl mb-6 font-bold text-[#0f8B8D] text-center border-b-4 border-black">All User Accounts</p>

            <div class="p-6 overflow-x-auto bg-slate-300/[.4] mt-5 min-h-56 w-full rounded-lg">

                <div class="flex flex-nowrap gap-4 justify-start lg:justify-around p-3 rounded-sm w-[800px] lg:w-full bg-slate-600/[.4] mb-4">
                    <p class="text-sm w-[5%]">S/N</p>
                    <p class="text-sm w-[30%] font-semibold">Account ID</p>
                    <p class="text-sm w-[30%] font-semibold">Name</p>
                    <p class="text-sm w-[30%] font-semibold">Email</p>
                    <p class="text-sm w-[30%] font-semibold">Phone</p>
                    <p class="text-sm w-[30%] font-semibold">Virtual Acct</p>
                    <p class="text-sm w-[30%] font-semibold">Acct Type</p>
                    <p class="text-sm w-[30%] font-semibold">Balance</p>
                    <p class="w-[30%] font-semibold">Status</p>
                </div>
                <div v-for="(accounts,index) in adminstore.accounts" :key="accounts.id" class="flex flex-nowrap justify-start lg:justify-around w-[800px] lg:w-full gap-4 mt-3  rounded-sm p-3">
                    <p class="text-sm w-[5%] font-semibold">{{ index + 1 }}</p>
                    <p class="text-sm w-[30%] overflow-x-auto font-semibold">{{ accounts.userId }}</p>
                    <p class="text-sm w-[30%] overflow-x-auto font-semibold">{{ accounts.name }}</p>
                    <p class="text-sm w-[30%] overflow-x-auto font-semibold ">{{accounts.email }}</p>
                    <p class="text-sm w-[30%] overflow-x-auto font-semibold">{{ accounts.phone }}</p>
                    <p class="text-sm w-[30%] overflow-x-auto font-semibold">{{ accounts.virtualAcct }}</p>
                    <p class="text-sm w-[30%] font-semibold">{{accounts.type }}</p>
                    <p class="text-sm w-[30%] font-semibold">₦{{ accounts.walletBalance }}</p>
                    <p class="w-[30%]"><span class="rounded-full text-center h-fit p-1 px-3 text-sm" :class="{'bg-yellow-500/[.2] text-yellow-500' : accounts.status == 'suspended' || accounts.status == 'Suspended', 'bg-red-600/[.2] text-red-600' : accounts.status == 'deleted', 'bg-green-600/[.2] text-green-600' : accounts.status == 'active' || accounts.status == 'Active' || accounts.status == 'success'  }">{{ capitalize(accounts.status) }}</span></p>
                </div>
            </div>
            
        </div>
    </div>
</template>

<script type="text/javascript" setup>
import { adminStore } from "@/store/admin"
import { capitalize } from "~~/composables/user/useStrings.js"

const adminstore = adminStore()

adminstore.getUserAccounts()
</script>

<style lang="scss" scoped>

</style>