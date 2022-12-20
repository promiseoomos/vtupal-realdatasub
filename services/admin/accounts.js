const headers = {
    "Cache-control" : "no-cache"
}

export default {

    async getUserAccounts(){
        const data = await $fetch("accounts/", {
            baseURL : baseURL(),
            headers,
            method : "GET",
        })

        return JSON.parse(data)
    },

    async validateUserEmail(email){
        
        const data = await $fetch(`operations/validateaccount/${email}`, {
            baseURL : baseURL(),
            headers,
            method : "GET",
        })

        return JSON.parse(data)
    },

    async creditUserAccount(body){
        const data = await $fetch("accounts/", {
            baseURL : baseURL(),
            headers,
            method : "POST",
            body
        })

        return JSON.parse(data)
    }
}