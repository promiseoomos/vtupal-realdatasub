<template>
    <div class="bg-white">
        <div class="grid grid-cols-1 lg:grid-cols-8 gap-3">
            <SidebarBase class="col-span-2 lg:hidden w-full " :sidebar="sidebar" v-if="showsidebar"/>
            <SidebarBase class="col-span-2 hidden lg:block min-h-screen" :sidebar="sidebar"/>
            <NuxtPage class="col-span-6 p-3" v-if="!showsidebar" />
        </div>
    </div>
</template>

<script type="text/javascript" setup>
import { adminStore } from "~~/store/admin"

const showsidebar = inject("showsidebar")

const adminstore = adminStore()

adminstore.getUserAccounts()


onMounted(() => {
    if(!adminstore.loggedin){
        navigateTo({
            name : "adminlogin"
        })
    }    
})


const sidebar = reactive({
    base : "admin",
    bars :[
        {
            name : "admin-accounts",
            text : "Accounts",
            dropdown : true,
            children : [
                // {name : 'admin-accounts-create', text : 'Create new Account' , params : { }}, 
                {name : 'admin-accounts-view', text : 'View Accounts' , params : { }},
                {name : 'admin-accounts-credit', text : 'Credit Account' , params : { }},
            ]
        },
        {
            name : "admin-services",
            text : "Services",
            dropdown : true,
            children : [
                {name : 'admin-services', text : 'All Services' , params : { }},
            ]
        },
        
        {
            name : "admin-transactions",
            text : "Transactions",
            dropdown : false,
            children : [
                
            ]
        },
        {
            name : "admin-earnings",
            text : "Earnings",
            dropdown : false,
            children : []
            
        },
        {
            name : "admin-settings",
            text : "Settings",
            dropdown : true,
            children : [
                
                {name : 'admin-settings-site', text : 'Site Settings' , params : { }},
                // {name : 'admin-settings-payment', text : 'Payment Settings' , params : { }},
                {name : 'admin-settings-bankdetails', text : 'Add Bank Details' , params : { }},
                // {name : 'admin-settings-appearance', text : 'Site Appearance Settings' , params : { }}
            ]
        }
    ]
})
</script>

<style lang="scss" scoped>

</style>