const headers = {
    "Cache-control" : "no-cache"
}

export default {

    async getBillersCat(){
        const data = await $fetch("vend/billers/", {
            baseURL : baseURL(),
            headers,
            method : "GET",
        })

        return JSON.parse(data)
    },
    async createBill(dataObj){
        const data = await $fetch("vend/createbill/", {
            baseURL : baseURL(),
            headers,
            method : "POST",
            body : dataObj
        })

        return JSON.parse(data)
    },
    async validateBill(dataObj){
        const data = await $fetch("vend/validaterecipient/", {
            baseURL : baseURL(),
            headers,
            method : "POST",
            body : dataObj
        })

        return JSON.parse(data)
    },
    async buySME(dataObj){
        const data = await $fetch(`vend/sme/${dataObj.network}`, {
            baseURL : baseURL(),
            headers,
            method : "POST",
            body : dataObj
        })

        return JSON.parse(data)
    },
    async buyAirtime(dataObj){
        const data = await $fetch(`vend/airtime/${dataObj.network}`, {
            baseURL : baseURL(),
            headers,
            method : "POST",
            body : dataObj
        })

        return JSON.parse(data)
    },
    async getSMEPrices(level){
        const data = await $fetch(`vend/smeprices/${level}`, {
            baseURL : baseURL(),
            headers,
            method : "GET",
        })

        return JSON.parse(data)
    },

    async sendSMS(dataObj){
        const data = await $fetch("vend/bulksms", {
            baseURL : baseURL(),
            headers,
            method : "POST",
            body : dataObj
        })

        return JSON.parse(data) 
    },
    async getServices(){

        const data = await $fetch("/operations/all/services", {
            baseURL : baseURL(),
            headers,
            method : "GET"
        })

        return JSON.parse(data)
    },
}