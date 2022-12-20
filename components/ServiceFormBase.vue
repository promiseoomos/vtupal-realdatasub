<template>
    <form @submit.prevent="updateService">
        <div class="flex flex-nowrap justify-start lg:justify-start w-[200%] lg:w-fit bg-slate-100 gap-4 mt-3 rounded-sm p-3">
            
            <div class="text-sm w-[30%] overflow-x-auto font-semibold">
                <input type="text" disabled v-model="serviceObj.module" id="recipient" class="p-1 w-full rounded-md ring-0 border border-gray-200" />
            </div>

            <div class="text-sm w-[30%] overflow-x-auto font-semibold">
                <input type="text" disabled v-model="serviceObj.submodule" id="recipient" class="p-1 rounded-md w-full ring-0 border border-gray-200" />
            </div>

            <div class="text-sm w-[30%] overflow-x-auto font-semibold">
                <input type="text" disabled v-model="serviceObj.standard_name" id="recipient" class="overflow-x-auto p-1 rounded-md w-full ring-0 border border-gray-200" />
            </div>

            <div class="text-sm w-[30%] overflow-x-auto font-semibold">
                <input type="text" v-model="serviceObj.display_name" id="recipient" class="p-1 overflow-x-auto rounded-md w-full ring-0 border border-gray-200" />
            </div>

            <div class="text-sm w-[30%] overflow-x-auto font-semibold">
                <input type="text" disabled v-model="serviceObj.module_prefix" id="recipient" class="p-1 rounded-md w-full ring-0 border border-gray-200" />
            </div>

            <div class="text-sm w-[30%] overflow-x-auto font-semibold">
                <input type="text" disabled v-model="serviceObj.module_suffix" id="recipient" class="p-1 rounded-md w-full ring-0 border border-gray-200" />
            </div>

            <div class="text-sm w-[30%] overflow-x-auto font-semibold">
                <select v-model="serviceObj.pricing" class="p-1 rounded-md w-full ring-0 border border-gray-200">
                    <option value="value">Value</option>
                    <option value="percentage">Percentage</option>
                </select>
            </div>

            <div class="text-sm w-[30%] overflow-x-auto font-semibold">
                <input type="double" min="1" disabled v-model="serviceObj.face_price" id="amount" class="p-1 rounded-md w-full ring-0 border border-gray-200" placeholder="3000" />
            </div>

            <div class="text-sm w-[30%] overflow-x-auto font-semibold">
                <input type="double" min="1" disabled v-model="serviceObj.face_percentage" id="amount" class="p-1 rounded-md w-full ring-0 border border-gray-200" placeholder="5.0" />
            </div>

            <div class="text-sm w-[30%] overflow-x-auto font-semibold">
                <input type="double" min="1" v-model="serviceObj.normal_price" id="amount" class="p-1 rounded-md w-full ring-0 border border-gray-200" placeholder="3000" />
            </div>

            
            <div class="text-sm w-[30%] overflow-x-auto font-semibold">
                <input type="double" min="1" v-model="serviceObj.reseller_price" id="amount" class="p-1 rounded-md w-full ring-0 border border-gray-200" placeholder="3000" />
            </div>

             <div class="text-sm w-[30%] overflow-x-auto font-semibold">
                <input type="number" min="1" v-model="serviceObj.normal_percentage" id="amount" class="p-1 rounded-md w-full ring-0 border border-gray-200" placeholder="3000" />
            </div>

            
            <div class="text-sm w-[30%] overflow-x-auto font-semibold">
                <input type="number" min="1" v-model="serviceObj.reseller_percentage" id="amount" class="p-1 rounded-md w-full ring-0 border border-gray-200" placeholder="3000" />
            </div>

            <div class="text-sm w-[30%] overflow-x-auto font-semibold">
                <select v-model="serviceObj.status" class="p-1 rounded-md w-full ring-0 border border-gray-200">
                    <option value="active">Active</option>
                    <option value="disabled">Disabled</option>
                </select>
            </div>
        
            <div class="text-center p-2 text-sm w-[30%] font-semibold">
                <button :disabled="chkfields" :class="{ 'opacity-50' : chkfields }" type="submit" class="overflow-x-auto -mt-4 p-1 bg-[#0f8B8D] text-white rounded-md font-bold text-xs hover:bg-[#0a5556]"><span v-if="api_calling" class="animate-spin h-7 w-7 -mb-2 mr-3 pt-2 border-r-2 border-b-2 border-l-2 border-whtie rounded-full inline-block"></span> Update </button>
            </div> 
        </div>
    </form>
</template>

<script setup>
import { adminStore } from "@/store/admin.js"

const adminstore = adminStore();
let validaterecipientchars = ref({})
let validaterecipientlength = ref({})
let validateamountchars = ref({})
let validateamountlength = ref({})


const props = defineProps({
    service : Object,
    sn : Number
})


const serviceObj = ref({
    standard_name : "",
    display_name : "",
    normal_price : "",
    reseller_price : "",
    face_price : "",
    face_percentage : "",
    module : "",
    module_prefix : "",
    module_suffix : "",
    submodule : "",
    status : "",
    track_id : "",
    pricing : "",
    normal_percentage : "",
    reseller_percentage : ""
})

serviceObj.value.standard_name = props.service.standard_name
serviceObj.value.display_name = props.service.display_name
serviceObj.value.face_price = props.service.face_price
serviceObj.value.face_percentage = props.service.face_percentage
serviceObj.value.reseller_price = props.service.reseller_price
serviceObj.value.normal_price = props.service.normal_price
serviceObj.value.submodule = props.service.submodule
serviceObj.value.module = props.service.module
serviceObj.value.status = props.service.status
serviceObj.value.module_prefix = props.service.module_prefix
serviceObj.value.module_suffix = props.service.module_suffix
serviceObj.value.track_id = props.service.track_id
serviceObj.value.pricing = props.service.pricing
serviceObj.value.normal_percentage = props.service.normal_percentage
serviceObj.value.reseller_percentage = props.service.reseller_percentage


let validateapiresponse = ref({})
let validateapicalling = ref(false)
let finalapiresponse = ref({})
let apiError = ref(false)

let api_calling = ref(false)
const updateService = async () => {
    api_calling.value = true
    

    try{
        await adminstore.updateService(serviceObj.value).then((response) => {
            api_calling.value = false
            finalapiresponse.value = response
            adminstore.getServices()
        })
         
    }catch(error){
        api_calling.value = false
        finalapiresponse.value = error
    }
}





</script>

<style lang="scss" scoped>

</style>