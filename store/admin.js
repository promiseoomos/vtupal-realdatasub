import { defineStore } from "pinia";
import accounts from "~~/services/admin/accounts";
import admin from "~~/services/admin/site";

export const adminStore = defineStore("admin", {
    state : () => {
        return {
            loggedin : false,
            details : {},
            baseURL : "",
            accounts : [],
            services : [],
            analysis : []
        }
        
    },
    actions : {
        async loginAdmin(dataObj){

            const data = await admin.loginAdmin(dataObj, this.baseURL)
            
            if(data.status){
                this.details = data.details
                this.loggedin = data.status

                sessionStorage.setItem("logdetails", JSON.stringify(this.details))
                sessionStorage.setItem("baseURL", JSON.stringify(this.baseURL))
            }

            return data
        },

        async getUserAccounts(){
            
            const response = await accounts.getUserAccounts()

            this.accounts = response.data
            return response.data
        },

        async getSiteDetails(){
            const response = await admin.getSiteDetails()

            this.details = response.data

            sessionStorage.setItem("logdetails", JSON.stringify(this.details))
            
            return response.data
        },
        async validateUserEmail(email){
            const response = await accounts.validateUserEmail(email)

            return response
        },

        async creditUserAccount(payload){
            const response = await accounts.creditUserAccount(payload)
            console.log(response)
            return response
        },

        async getProiftAnalysis(){
            const response = await admin.getProfitAnalysis()

            this.analysis = response.data
            return response
        },

        async getTransactionAnalysis(){
            const response = await admin.getTransactionAnalysis()

            return response
        },
        async createVirtualAcct(payload){
            const response = await admin.createVirtualAcct(payload)

            return response
        },
        async updateSiteBank(payload){
            const response = await admin.updateSiteBank(payload)

            return response
        },
        async updateSiteDetails(payload){
            const response = await admin.updateSiteDetails(payload)

            return response
        },
        async getServices(){
            const response = await admin.getServices()

            this.services = response.data
            return response
        },
        async updateService(payload){
            const response = await admin.updateService(payload)

            return response
        }
    
    
    },
    getters : {
        
    }
})