import { defineStore } from "pinia";
import accounts from "~~/services/admin/accounts";
import admin from "~~/services/admin/site";

export const siteStore = defineStore("site", {
    state : () => {
        return {
            details : {},
            baseURL : "",
        }
        
    },
    actions : {
        async getSiteDetails(dataObj){

            const data = await admin.loginAdmin(dataObj, this.baseURL)
            
            if(data.status){
                this.details = data.details

                sessionStorage.setItem("logdetails", JSON.stringify(this.details))
                sessionStorage.setItem("baseURL", JSON.stringify(this.baseURL))
            }

            return data
        },
    },
    getters : {
        
    }
})