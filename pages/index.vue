<template>
    <div class="">
        <NuxtPage class="col-span-6" />
    </div>
</template>

<script type="text/javascript" setup>
import { userStore } from "~~/store/user.js";


const userstore = userStore()

if(process.client){
    if(userstore.loggedin){
        userstore.$subscribe(() => {
            sessionStorage.setItem("userdetails", JSON.stringify(userstore.details))
            sessionStorage.setItem("userloggedin", JSON.stringify(userstore.loggedin))
            sessionStorage.setItem("billercats", JSON.stringify(userstore.billercats))
        }, { detached : true })
    }
}

if(userstore.loggedin){   
    navigateTo({
        path : "/dashboard"
    })
}else{
    navigateTo({
        path: "/login"
    })
}


</script>

<style lang="scss" scoped>

</style>