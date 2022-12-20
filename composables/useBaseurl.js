import { adminStore } from "~~/store/admin";
import { userStore } from "~~/store/user";

export const getBaseUrl = async (config) => {
    let userstore = userStore()
    let adminstore = adminStore()

    onMounted(() => {
        if(process.client){
            if(process.env.NODE_ENV == 'development'){

                userstore.$patch({
                    baseURL : config.public.apiLocalBaseUrl
                })

                adminstore.$patch({
                    baseURL : config.public.apiLocalBaseUrl
                })
            }else{
                userstore.$patch({
                    baseURL : config.public.apiProductionBaseUrl
                })

                adminstore.$patch({
                    baseURL : config.public.apiProductionBaseUrl
                })
            }
        }
        
    })

}