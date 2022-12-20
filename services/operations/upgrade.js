const headers = {
    "Cache-control" : "no-cache"
}

export default {

    async upgradeReseller(payload, baseURL){
        const data = await $fetch("operations/upgrade", {
            baseURL,
            headers,
            method : "POST",
            body : payload
        })

        return JSON.parse(data)
    },
}