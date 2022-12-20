const headers = {
    "Cache-Control" : "no-cache"
}

export default {

    async getUserDetails(userId, baseURL){
        const data = await $fetch("classes/user.class.php", {
            baseURL,
            headers : {
                "Cache-control" : "no-cache"
            },
            method : "POST",
            body : {
                action : "get-user-details",
                userId
            }
        })

        return JSON.parse(data)

    },

    async fundWallet(payload, baseURL){
    
        const data = await $fetch("transaction/fund", {
            baseURL,
            headers,
            method : "POST",
            body : payload
        })

        return JSON.parse(data)
    },

    async updateUserdetails(dataObj, baseURL){
        const data = await $fetch("classes/user.class.php", {
            baseURL,
            headers : {
                "Cache-Control" : "no-cache"
            },
            method : "POST",
            body : {
                action : "update-user",
                dataObj
            }
        })

        return JSON.parse(data)
    },

    async verifyAccount(dataObj){
        const data = await $fetch("classes/user.class.php", {
            baseURL : baseURL(),
            headers : {
                "Cache-Control" : "no-cache"
            },
            method : "POST",
            body : {
                action : "verify-account",
                dataObj
            }
        })

        return JSON.parse(data)
    },

    

    async makePayment(dataObj, baseURL){
        const data = await $fetch("classes/user.class.php", {
            baseURL,
            headers : {
                "Cache-Control" : "no-cache"
            },
            method : "POST",
            body : {
                action : "make-payment",
                dataObj
            }
        })

        return JSON.parse(data)
    },
    
   
    async verifyPayment(reference, baseURL){
        const data = await $fetch(`/transaction/verify/${reference}`, {
            baseURL,
            method : "GET"
        })

        return JSON.parse(data)
    },
    async withdrawFunds(body){
        const data = await $fetch("/transaction/withdraw", {
            baseURL : baseURL(),
            headers,
            method : "POST",
            body
        })

        return JSON.parse(data)
    }


}