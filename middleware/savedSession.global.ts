import { adminStore } from "~~/store/admin"
import { userStore } from "~~/store/user.js"

export default defineNuxtRouteMiddleware((to, from) => {
    const userstore = userStore()
    const adminstore = adminStore()
    
    if(process.client){
        const details = sessionStorage.getItem("logdetails")
        const baseURL = sessionStorage.getItem("baseURL")

        if(details){
            if(from.matched[0].path == '/admin' || to.matched[0].path == '/admin' || from.name == 'adminlogin'){

                adminstore.$patch({
                    details : JSON.parse(details),
                    loggedin : true,
                    baseURL : JSON.parse(baseURL)
                })

            }else{
                userstore.$patch({
                    details : JSON.parse(details),
                    loggedin : true,
                    baseURL : JSON.parse(baseURL)
                })
                
                userstore.getUserDetails(JSON.parse(details).track_id)
            }

        }
    }
    
})