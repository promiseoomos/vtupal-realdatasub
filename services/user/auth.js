const headers = {
    "Cache-control" : "no-cache"
}

export default {

    async registerUser(dataObj, baseURL){
        const data  = await $fetch("auth/signup/", {
            baseURL,
            headers,
            method : "POST",
            body : dataObj
        })
        
        return JSON.parse(data)
    },
    async loginUser(dataObj, baseURL){
        const data = await $fetch("auth/login/", {
            baseURL,
            headers,
            method : "POST",
            body : dataObj
        })

        return JSON.parse(data)

    },
    
    async confirmEmail(cid,baseURL){
        const data = await $fetch(`auth/confirm/?cid=${cid}`, {
            baseURL,
            headers,
            method : "GET",
        })

        return JSON.parse(data)

    },
    async resetToken(email,baseURL){
        const data = await $fetch(`auth/resettoken/?email=${email}`, {
            baseURL,
            headers,
            method : "GET",
        })

        return JSON.parse(data)

    },
    async resetPassword(resetObj,baseURL){
        const data = await $fetch(`auth/resetpassword`, {
            baseURL,
            headers,
            method : "POST",
            body : resetObj
        })

        return JSON.parse(data)

    },
}