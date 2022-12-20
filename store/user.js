import { defineStore } from "pinia"
import upgrade from "~~/services/operations/upgrade"
import auth from "~~/services/user/auth"
import userservice from "~~/services/userservice.js"

export const userStore = defineStore("user", {
    state : () => {
        return {
            details : {},
            loggedin : false,
            modalshowing : false,
            banks : [],
            billercats : [],
            smsmessages : {},
            baseURL : ""
        }
    },
    actions: {
        async registerUser(userObj){
            const data = await auth.registerUser(userObj, this.baseURL)
            
            return data
        },
        async loginUser(userObj){
            const data = await auth.loginUser(userObj, this.baseURL)
            
            if(data.status){
                this.details = data.details
                this.loggedin = data.status

                sessionStorage.setItem("logdetails", JSON.stringify(this.details))
                sessionStorage.setItem("baseURL", JSON.stringify(this.baseURL))
            }

            return data
        },
        async confirmEmail(cid){
            const data = await auth.confirmEmail(cid, this.baseURL)

            return data
        },
        async getUserDetails(userId){
            const data = await userservice.getUserDetails(userId, this.baseURL)

            this.details = data.details
        },

        async fundWallet(fundObj){
            const data = await userservice.fundWallet(fundObj, this.baseURL)

            return data
        },

        async updateUserdetails(userObj){
            const data = await userservice.updateUserdetails(userObj, this.baseURL)

            return data
        },
        async upgradeReseller(userObj){
            const data = await upgrade.upgradeReseller(userObj, this.baseURL)

            console.log(data)

            return data
        },
        
        async verifyAccount(dataObj){
            const data = await userservice.verifyAccount(dataObj)

            return data
        },
        
        async makePayment(dataObj){
            const data = await userservice.makePayment(dataObj, this.baseURL)

            return data
        },
        
        
        
        async verifyPayment(reference){
            const data = await userservice.verifyPayment(reference, this.baseURL)

            return data
        },
        async withdrawFunds(dataObj){
            const data = await userservice.withdrawFunds(dataObj)

            return data
        },
        async resetToken(email){
            const data = await auth.resetToken(email, this.baseURL)

            return data
        },
        async resetPassword(resetObj){
            const data = await auth.resetPassword(resetObj, this.baseURL)

            return data
        },
    },

    getters : {
        getBillerbyName: (state) => {
            return (name) => state.billercats.filter( x => x.name == "all").map((x) => { x.data })//.data.filter(x => x.short_name == name)
        },
    }
})