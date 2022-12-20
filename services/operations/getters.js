

const headers = {
    "Cache-control" : "no-cache"
}

export default {

    async getReferrals(){
        const data = await $fetch(`operations/all/referrals/${userId()}`, {
            baseURL : baseURL(),
            headers,
            method : "GET"
        })

        return JSON.parse(data)
    },
    async getPayments(){
        const data = await $fetch(`operations/all/payments/${userId()}`, {
            baseURL : baseURL(),
            headers,
            method : "GET"
        })

        return JSON.parse(data)
    },
    async getDeposits(){
        const data = await $fetch(`operations/all/deposits/${userId()}`, {
            baseURL : baseURL(),
            headers,
            method : "GET"
        })

        return JSON.parse(data)
    },
    async getTransactions(){
        const data = await $fetch(`operations/all/transactions/${userId()}`, {
            baseURL : baseURL(),
            headers,
            method : "GET"
        })

        return JSON.parse(data)
    },
    async getSMSMessages(){
        const data = await $fetch(`operations/all/sms/${userId()}`, {
            baseURL : baseURL(),
            headers,
            method : "GET"
        })

        return JSON.parse(data)
    },

    async getBanks(){
        const data = await $fetch(`operations/all/banks`, {
            baseURL : baseURL(),
            headers,
            method : "GET"
        })

        return JSON.parse(data)
    },

    async getServices(){
        const data = await $fetch(`operations/all/services`, {
            baseURL : baseURL(),
            headers,
            method : "GET"
        })

        return JSON.parse(data)
    },
    
}