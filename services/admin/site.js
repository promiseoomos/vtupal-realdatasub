const headers = {
    "Cache-control" : "no-cache"
}

export default {

    async loginAdmin(body,baseURL ){

        const data = await $fetch("/admin/login", {
            baseURL,
            headers,
            method : "POST",
            body : body
        })

        return JSON.parse(data)
    },
    async getSiteDetails(){

        const data = await $fetch("/admin/sitedetails", {
            baseURL : baseURL(),
            headers,
            method : "GET",
        })

        return JSON.parse(data)
    },

    async getProfitAnalysis(){

        const data = await $fetch("/admin/profitanalysis", {
            baseURL : baseURL(),
            headers,
            method : "GET",
        })

        return JSON.parse(data)
    },
    async getTransactionAnalysis(){

        const data = await $fetch("/admin/transactions", {
            baseURL : baseURL(),
            headers,
            method : "GET",
        })

        return JSON.parse(data)
    },
    async createVirtualAcct(payload){

        const data = await $fetch("/admin/createsiteaccount", {
            baseURL : baseURL(),
            headers,
            method : "POST",
            body : payload
        })

        return JSON.parse(data)
    },
    async updateSiteBank(payload){

        const data = await $fetch("/admin/updatesitebank", {
            baseURL : baseURL(),
            headers,
            method : "POST",
            body : payload
        })

        return JSON.parse(data)
    },
    async updateSiteDetails(payload){

        const data = await $fetch("/admin/updatesitedetails", {
            baseURL : baseURL(),
            headers,
            method : "POST",
            body : payload
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
    async updateService(payload){

        const data = await $fetch("/admin/updateservice", {
            baseURL : baseURL(),
            headers,
            method : "POST",
            body : payload
        })

        return JSON.parse(data)
    },
    
    


}