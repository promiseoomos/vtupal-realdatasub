import { userStore } from "~~/store/user.js"

export default defineNuxtRouteMiddleware(() => {
    const userstore = userStore()

    // if(process.client){
    //     let localUser = sessionStorage.getItem("userdetails")
    //     let localUserLoggedin = sessionStorage.getItem("userloggedin")
    //     let localBillerscat = sessionStorage.getItem("billerscat")

    //     if(localUserLoggedin){

    //         userstore.$patch({
    //             details : JSON.parse(localUser),
    //             loggedin : JSON.parse(localUserLoggedin),
    //             billerscat : JSON.parse(localBillerscat)
    //         })
    //     }
    // }
    
})