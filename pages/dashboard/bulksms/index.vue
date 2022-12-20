<template>
    <div>
        <WelcomeBase />
        <div class="flex flex-wrap gap-2 justify-start lg:justify-around mt-5">
            <BoardcardBase class="w-full lg:w-[30%] h-56" icon="envelope.png" text="Send SMS" link="dashboard-bulksms-send" />
            <BoardcardBase class="w-full lg:w-[30%] h-56" icon="screen.png" text="Message History" link="dashboard-bulksms-send" />
            <BoardcardBase class="w-full lg:w-[30%] h-56" icon="sack-dollar.png" text="All Transactions" link="dashboard-transactions"/>
        </div>        
    </div>
</template>

<script type="text/javascript" setup>

await navigateTo({
    name : "dashboard-bulksms-send"
})
</script>

<style lang="scss" scoped>

</style>