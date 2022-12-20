<template>
    <div>
        <WelcomeBase />

        <div class="rounded-md bg-white pb-10">

            <p class="font-bold text-center mt-7">Change Password</p>
            
             <div class="rounded-md bg-white p-2 mt-3">
                <form @submit.prevent="createBill">

                    <div class="mt-3">
                        <label for="subject" class="block mb-3 font-medium text-lg pl-1">New Password</label>
                        <input type="password" @keyup="validate" @blur="validateBill" v-model="changeObj.password" id="subject" class="p-3 rounded-md w-full ring-0 border border-gray-200" />
                    </div>

                    <div class="mt-3">
                        <label for="subject" class="block mb-3 font-medium text-lg pl-1">Confirm Password</label>
                        <input type="password" @keyup="validate" @blur="validateBill" v-model="changeObj.confirmPassword" id="subject" class="p-3 rounded-md w-full ring-0 border border-gray-200" />
                    </div>


                    <!-- <div v-if="finalapiresponse" class="rounded-md p-3 mt-3" :class="{ 'text-red-600 bg-red-600/[.2]' : finalapiresponse.status == 'error', 'text-green-600 bg-green-600/[.2]' : finalapiresponse.status == 'success' }">
                        <p v-if="finalapiresponse.status == 'error'">{{ finalapiresponse.message }}</p>
                        <p v-if="finalapiresponse.status == 'success'">{{ finalapiresponse.message }}</p>
                    </div> -->

                    <div class="text-center p-2 mt-3">
                        <button :disabled="chkfields" :class="{ 'opacity-50' : chkfields }" type="submit" class="p-3 px-10 bg-[#0f8B8D] text-white rounded-md font-bold text-md hover:bg-[#0a5556]"><span v-if="api_calling" class="animate-spin h-7 w-7 -mb-2 mr-3 pt-2 border-r-2 border-b-2 border-l-2 border-whtie rounded-full inline-block"></span> Change Password</button>
                    </div>
                </form>

                
            </div>
        </div>
    </div>
</template>

<script type="text/javascript" setup>
import { userStore } from "~~/store/user.js";
import { capitalize } from "~~/composables/user/useStrings.js"

const userstore = userStore();

const showModal = ref(false)
let api_calling = ref(false)

const changeObj = ref({
    password : "",
    confirmPassword : ""
})

function hideModal(){
    userstore.$patch({
        modalshowing : false
    })
    showModal.value = false
}

const withdrawable = computed(() => {
    return (userstore.details.wallet_balance - 50) > upgradeObj.value.amount ? true : false 
})

const upgradeReseller = async () => {
    api_calling.value = true

    try{
        await userstore.upgradeReseller(upgradeObj.value).then((response) => {
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