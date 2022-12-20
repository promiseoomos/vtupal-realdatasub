<template>
    <div>
        <div class="grid grid-cols-1 lg:grid-cols-8" :class="{ 'opacity-30 pointer-events-none' : userstore.modalshowing }">
            <SidebarBase class="col-span-2 lg:hidden w-4/5" :sidebar="sidebar" @route-changed="showsidebar = false" v-if="showsidebar"/>
            <SidebarBase class="col-span-2 hidden lg:block" :sidebar="sidebar"/>
    
            <NuxtPage class="col-span-6 p-3 bg-[#b6b6d051]" v-if="!showsidebar" />
        </div>
    </div>
</template>

<script type="text/javascript" setup>
import { userStore } from "@/store/user.js"
import { getterStore } from "~~/store/operations/getters";
import { vendStore } from "~~/store/operations/vend";

const userstore = userStore();


vendStore().getBillerCat()
vendStore().getServices()
getterStore().getPayments()
getterStore().getReferrals()
getterStore().getTransactions()
getterStore().getDeposits()
getterStore().getSMSMessages()
getterStore().getBanks()

onMounted(() => {
    if(!userstore.loggedin){
        navigateTo({
            name : "login"
        })
    }    
})





const showsidebar = inject("showsidebar")
const sidebar = reactive({
    base  : "dashboard",
    bars : [
        {
            name : "dashboard-bulksms",
            text : "Bulk SMS",
            dropdown : true,
            children : [
                {name : 'dashboard-bulksms-send', text : 'Send SMS' , params : { }}, 
                {name : 'dashboard-bulksms-history', text : 'Messaging History' , params : { }}
            ]
        },
        {
            name : "dashboard-tvpayment",
            text : "TV Payment",
            dropdown : true,
            children : [
                { name : "dashboard-tvpayment", text : "GOTV Payment", params : { type : "GOTV"}, query : { type : "GOTV"}},
                { name : "dashboard-tvpayment", text : "DSTV Payment", params : { type : "DSTV"}, query : { type : "DSTV"}},
                { name : "dashboard-tvpayment", text : "STARTIMES Payment", params : { type : "STARTIME"}, query : { type : "STARTIME"}},
            ]
        },
        {
            name : "dashboard-vtuairtime",
            text : "VTU Airtime",
            dropdown : true,
            children : [
                { name : "dashboard-vtuairtime", text : "MTN Airtime", params : { network : "MTN"}, query : { network : "MTN"}},
                { name : "dashboard-vtuairtime", text : "GLO Airtime", params : { network : "GLO"}, query : { network : "GLO"}},
                { name : "dashboard-vtuairtime", text : "AIRTIME Airtime", params : { network : "AIRTIME"}, query : { network : "AIRTEL"}},
                { name : "dashboard-vtuairtime", text : "9MOBILE Airtime", params : { network : "9MOBILE"}, query : { network : "9MOBILE"}},

            ]
        },
        {
            name : "dashboard-giftingdata",
            text : "Gifting Data",
            dropdown : true,
            children : [
                { name : "dashboard-giftingdata", text : "MTN Gifting Data", params : { network : "MTN"}, query : { network : "MTN"}},
                { name : "dashboard-giftingdata", text : "GLO Gifting Data", params : { network : "GLO"}, query : { network : "GLO"}},
                { name : "dashboard-giftingdata", text : "AIRTEL Gifting Data", params : { network : "AIRTIME"}, query : { network : "AIRTEL"}},
                { name : "dashboard-giftingdata", text : "9MOBILE Gifting Data", params : { network : "9MOBILE"}, query : { network : "9MOBILE"}},

            ]
        },
        {
            name : "dashboard-smedata",
            text : "SME Data",
            dropdown : true,
            children : [
                { name : "dashboard-smedata", text : "MTN SME Data", params : { network : "MTN"}, query : { network : "MTN"}},
            ]
        },
        
        {
            name : "dashboard-electricity",
            text : "Electricity Payment",
            dropdown : true,
            children : [
                { name : "dashboard-electricity", text : "Ikeja Electric", params : { disco : "Ikeja"}, query : { disco : "Ikeja"}},
                { name : "dashboard-electricity", text : "Portharcourt Electric", params : { disco : "Port Harcourt"}, query : { disco : "Port Harcourt"}},
                { name : "dashboard-electricity", text : "Kano Electric", params : { disco : "Kano"}, query : { disco : "Kano"}},
                { name : "dashboard-electricity", text : "Enugu Electric", params : { disco : "Enugu"}, query : { disco : "Enugu"}},
                { name : "dashboard-electricity", text : "Abuja Electric", params : { disco : "Abuja"}, query : { disco : "Abuja"}},
                { name : "dashboard-electricity", text : "Jos Electric", params : { disco : "Jos"}, query : { disco : "Jos"}},
                { name : "dashboard-electricity", text : "Eko Electric", params : { disco : "Eko"}, query : { disco : "Eko"}},
            ]
        },
        {
            name : "dashboard-payment",
            text : "Wallets & Payments",
            dropdown : true,
            children : [
                { name : "dashboard-payments-cwallet", text : "Credit Wallet"},
                { name : "dashboard-payments-history", text : "Payment History"}
            ]
        },
        {
            name : "dashboard-transactions",
            text : "All Transactions",
            dropdown : false,
            children : []
        },
        {
            name : "dashboard-reseller",
            text : "Reseller",
            dropdown : true,
            children : [
                { name : "dashboard-reseller-setup", text : "Upgrade to Reseller"},
            ]
        },
        {
            name : "dashboard-earnings",
            text : "Earnings and Referrals",
            dropdown : false,
            children : []
        },
        {
            name : "dashboard-settings",
            text : "Settings",
            dropdown : true,
            children : [
                { name : "passwordreset", text : "Change Password"},
                { name : "dashboard-settings-myprofile", text : "My Profile"}
            ]
        }

    ]
})

const hideSmile = {
    name : "dashboard-smile",
    text : "SMILE Plans",
    dropdown : true,
    children : [
        { name : "dashboard-smile", text : "SMILE Voice", params : { plan : "Voice"}, query : { plan : "Voice"}},
        { name : "dashboard-smile", text : "SMILE Data", params : { plan : "Data"}, query : { plan : "Data"}},
    ]
}
</script>

<style lang="scss" scoped>

</style>