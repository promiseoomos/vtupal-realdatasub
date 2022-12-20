<template>
    <div>
        <div class="absolute top-1/4 lg:left-1/3 left-3 pb-10 justify-center content-center justify-items-center align-middle w-11/12 lg:w-2/5 p-3 bg-white rounded-lg shadow-2xl shadow-gray-400 transition-transform duration-700 ease-in-out">
            <slot name="header">
                <p class="title text-center text-lg lg:text-xl font-bold"><slot name="title">Fund your wallet</slot></p>
                <img src="~/assets/cross-small.png" @click="hideModal" class="w-10 absolute right-4 cursor-pointer top-3"/>
            </slot>
            
            <slot name="body">
                <p class="text-xl px-3 mt-5">Choose your Funding Method</p>

                    <div class="p-3">
                        <p class="text-lg ">
                            <input type="radio" v-model="fundingmethod"  name="fundingmethod" value="card" class="w-5 h-5 mr-4"/>Pay with Card
                        </p>
                        <p class="text-lg">
                            <input type="radio" v-model="fundingmethod"  name="fundingmethod" value="transfer" class="w-5 h-5 mr-4">Pay by Bank Transfer
                        </p>
                    </div>

                    <div class="p-3" v-if="fundingmethod == 'card'">
                        <p class="font-semibold text-lg">Amount to Fund :</p>
                        <input type="number" v-model="fundObj.amount" class="p-3 rounded-lg border border-gray-400 w-full" placeholder="3000">

                        <div v-if="errorExists" class="rounded-md p-3 mt-3" :class="{ 'text-red-600 bg-red-600/[.2]' : !errorExists, 'text-green-600 bg-green-600/[.2]' : errorExists }">
                            <p v-if="!errorExists">{{ errorMessage }}. Contact the Administrators.</p>
                            <p v-if="errorExists">{{ errorMessage }}</p>
                        </div>

                        <button :disabled="fundObj.amount <= 0" :class="{ 'bg-[#0f8b8d9f] hover:bg-[#0f8b8d9f]' : fundObj.amount <= 0  }" @click="fundWallet" class="block w-full p-3 rounded-sm mt-3 font-semibold text-lg bg-[#0f8B8D] text-white hover:bg-[#13696a]"><span v-if="api_calling" class="animate-spin h-7 w-7 -mb-2 mr-3 pt-2 border-r border-b border-l-2 border-white rounded-full inline-block"></span> Pay with Card</button>
                    </div>

                    <div v-if="fundingmethod == 'transfer' && route.matched[0].path == '/dashboard'" class="p-3 bg-slate-300/[.35] rounded-md">{{ route.matched[0].path }}
                        <p class="font-bold">Transfer amount to this account, wallet will be credited Automatically</p>
                        <div class="flex flex-nowrap justify-between mt-3">
                            <p class="w-1/2 text-lg font-medium">Account Number:</p>
                            <p class="w-1/2 font-bold text-left">{{ userstore.details.virtual_account_no }}</p>
                        </div>
                        <div class="flex flex-nowrap justify-between">
                            <p class="w-1/2 text-lg font-medium">Account Bank :</p>
                            <p class="w-1/2 font-bold text-left">{{ userstore.details.virtual_account_bank}}</p>
                        </div>
                        <div class="flex flex-nowrap justify-between">
                            <p class="w-1/2 text-lg font-medium">Account Name:</p>
                            <p class="w-1/2 font-bold text-left">{{ userstore.details.virtual_account_name }}</p>
                        </div>
                    </div>

                    <div v-if="fundingmethod == 'transfer' && route.matched[0].path == '/admin'" class="p-3 bg-slate-300/[.35] rounded-md">
                        <p class="font-bold">Transfer amount to this account, wallet will be credited Automatically</p>
                        <div class="flex flex-nowrap justify-between mt-3">
                            <p class="w-1/2 text-lg font-medium">Account Number:</p>
                            <p class="w-1/2 font-bold text-left">{{ adminstore.details.virtual_account_number }}</p>
                        </div>
                        <div class="flex flex-nowrap justify-between">
                            <p class="w-1/2 text-lg font-medium">Account Bank :</p>
                            <p class="w-1/2 font-bold text-left">{{ adminstore.details.virtual_account_bank}}</p>
                        </div>
                        <div class="flex flex-nowrap justify-between">
                            <p class="w-1/2 text-lg font-medium">Account Name:</p>
                            <p class="w-1/2 font-bold text-left">{{ adminstore.details.virtual_account_name }}</p>
                        </div>
                    </div>
            </slot>
            
        </div>
    </div>
</template>

<script type="text/javascript" setup>
import { userStore } from "@/store/user.js"
import { adminStore } from "~~/store/admin"
const emit = defineEmits(['hide-modal'])

const userstore = userStore()
const adminstore = adminStore()

const route = useRoute()

function hideModal(){
    userstore.$patch({
        modalshowing : false
    })

    emit("hide-modal")
}

const fundingmethod = ref("card")
const amount = ref(0)
let api_calling = ref(false)
let errorExists = ref(false)
let errorMessage = ref(false)

const fundObj = ref({
    amount : 0,
    transactionType : "deposit",
    details : userstore.details
})

async function fundWallet(){
    api_calling.value = true

    try{
        await userstore.fundWallet(fundObj.value).then((response) => {
            api_calling.value = false

            window.location = response.rurl
        })   
    }catch(error){
        errorExists.value = true;
        errorMessage.value = error
        api_calling.value = false
    }
}

</script>

<style lang="scss" scoped>

</style>