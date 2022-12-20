<template>
    <div >
        <WelcomeBaseAdmin />

        
        <div class="bg-white flex flex-wrap justify-around mt-5">
            
            <AnalysisBoard class="w-1/2 lg:w-[30%] h-56" text="Alltime Profit" :amount="analysis.totalProfits" />
            <AnalysisBoard class="w-1/2 lg:w-[30%] h-56" text="Users Wallet" :amount="analysis.userWallets" />
            <AnalysisBoard class="w-1/2 lg:w-[30%] h-56" text="Site Wallet" :amount="analysis.siteBalance" />
            <AnalysisBoard class="w-1/2 lg:w-[30%] h-56" text="Withdrawable" :amount="analysis.withdrawableBalance" />
            <AnalysisBoard v-for="analyze in analysis.totals" :key="analyze.name" class="w-1/2 lg:w-[30%] h-56" :text="analyze.name" :amount="analyze.total" />

        </div> 

        <div class="p-3">
            <p class="text-4xl font-bold text-[#0f8B8D]">Your Stats</p>
        </div>
        
    </div>
</template>

<script type="text/javascript" setup>
import { adminStore } from "@/store/admin"

const adminstore = adminStore()
let analysis = ref({})
adminstore.getUserAccounts()

try {
    const response = await adminstore.getProiftAnalysis()
    
    analysis.value = response.data
    
    
} catch (error) {
    console.log(error)
}

</script>

<style lang="scss" scoped>

</style>