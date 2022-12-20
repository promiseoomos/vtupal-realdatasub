<template>
    <div>
        <WelcomeBase />
        <div class="flex flex-wrap justify-between">
            <div class="rounded-lg w-full lg:w-3/5 p-2 mt-5">
                <div class="flex flex-wrap justify-evenly py-3 gap-2 lg:justify-start bg-white rounded-lg p-2">
                    <BoardcardBase class="" icon="envelope.png" text="Bulk SMS" link="dashboard-bulksms" />
                    <BoardcardBase class="" icon="screen.png" text="TV" link="dashboard-tvpayment" />
                    <BoardcardBase class="" icon="phone-call.png" text="VTU Airtime" link="dashboard-vtuairtime"/>
                    <BoardcardBase class="" icon="signal-alt.png" text="Normal Data" link="dashboard-giftingdata"/>
                    <BoardcardBase class="" icon="wifi-alt.png" text="SME Data" link="dashboard-smedata" />
                    <BoardcardBase class="" icon="bulb.png" text="Electricity" link="dashboard-electricity"/>
                    <BoardcardBase class="" icon="sack-dollar.png" text="Earnings" link="dashboard-earnings"/>
                    <BoardcardBase class="" icon="grin-alt.png" text="Upgrade" link="dashboard-reseller-setup"/>
                </div>
                
            </div>

            <div class="p-2 mt-5 w-full lg:w-2/5">
                <div class="p-3 h-full rounded-lg bg-white">
                    <p class="text-xl text-center font-semibold">Your Referrals</p>
                        <div v-for="(referral,index) in getterstore.referrals.data" :key="referral.id" class="flex flex-nowrap justify-between mt-3 w-full bg-slate-100 rounded-sm p-3">
                            <p class="text-xs lg:text-sm w-2/12 font-semibold">{{ index + 1 }}</p>
                            <p class="text-xs lg:text-sm w-8/12 font-semibold">{{ referral.name }}</p>
                            <p class="text-xs lg:text-sm w-8/12 font-semibold">{{ referral.dateReferred }}</p>
                            <p class="w-5/12"><span class="rounded-full text-center h-fit p-1 px-2 lg:px-3 text-xs lg:text-sm" :class="{'bg-yellow-500/[.2] text-yellow-500' : referral.status == 'unredeemed', 'bg-red-600/[.2] text-red-600' : referral.status == 'error', 'bg-green-600/[.2] text-green-600' : referral.status == 'redeemed'  }">{{ capitalize(referral.status) }}</span></p>
                        </div>
                </div>
                
            </div>
        </div>
        

        <div class="p-2">
            <p class="text-lg lg:text-3xl mb-6 font-bold text-[#0f8B8D] border-b-4 border-black ">Recent Payments</p>
            <div class="p-6 overflow-x-auto bg-white min-h-56 w-full rounded-lg">

                <div class="flex flex-nowrap gap-2  box-border justify-start lg:justify-around p-3 text-xs bg-slate-600/[.4] w-[800px] lg:w-full rounded-sm mb-4">
                    <p class="text-xs w-[5%]">S/N</p>
                    <p class="text-xs w-[30%] mr-2 box-border font-semibold">Reference</p>
                    <p class="text-sm w-[30%] mr-2 font-semibold">Recipient</p>
                    <p class="text-xs w-[30%] mr-2 box-border font-semibold">Transaction type</p>
                    <p class="text-xs w-[30%] mr-2 font-semibold">Amount</p>
                    <p class="text-xs w-[30%] mr-2 font-semibold">Date</p>
                    <p class="w-[30%] mr-2 font-semibold">Status</p>
                </div>
                <div v-for="(payment,index) in getterstore.payments.data" :key="payment.id" class="flex flex-nowrap box-border justify-start lg:justify-around  gap-4 w-[800px] lg:w-full mt-3 bg-slate-100 rounded-sm p-3">
                    <p class="text-xs w-[5%] font-semibold">{{ index + 1 }}</p>
                    <p class="text-xs w-[30%] mr-2 font-semibold">{{ payment.reference }}</p>
                    <p class="text-sm w-[30%] mr-2 font-semibold overflow-x-auto">{{ payment.recipient }}</p>
                    <p class="text-xs w-[30%] mr-2 font-semibold">{{ capitalize(payment.transactionType) }}</p>
                    <p class="text-sm w-[30%] mr-2 font-semibold" :class="{ 'text-red-600' : payment.category == 'debit', 'text-green-600' : payment.category == 'credit'}"><span>{{ payment.category == "debit" ? '-' : '+'}}</span>{{ payment.currency == 'NGN' ? '₦' : '$' }}{{ payment.amount }}</p>
                    <p class="text-xs w-[30%] mr-2 font-semibold">{{ payment.trnxDate }}</p>
                    <p class="w-[30%] mr-2"><span class="rounded-full text-center h-fit p-1 px-3 text-sm" :class="{'bg-yellow-500/[.2] text-yellow-500' : payment.status == 'pending' || payment.status == 'Pending', 'bg-red-600/[.2] text-red-600' : payment.status == 'error', 'bg-green-600/[.2] text-green-600' : payment.firstPayment == 'completed' || payment.status == 'success'  }">{{ capitalize(payment.status) }}</span></p>
                </div>
            </div>
            
        </div>
    </div>
</template>

<script type="text/javascript" setup>
import { getterStore } from "~~/store/operations/getters";

const getterstore = getterStore()

</script>

<style lang="scss" scoped>

</style>