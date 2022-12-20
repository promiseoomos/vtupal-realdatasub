import { defineStore } from "pinia";
import getters from "~~/services/operations/getters";
import { userStore } from "../user";
import { vendStore } from "./vend";

export const getterStore = defineStore("getters", {
    state : () => {
        return {
            dataServices : [],
            cableServices : [],
            referrals : {},
            payments : {},
            transactions : {},
            deposits : {},
            banks : [],
            smsmessages : {},
        }
        
    },
    actions : {
        async getPayments(){
            const data = await getters.getPayments()

            this.payments = data
            return data
        },
        async getReferrals(){
            const data = await getters.getReferrals()

            this.referrals = data
            return data
        },
        async getDeposits(){
            const data = await getters.getDeposits()

            this.deposits = data
            return data
        },
        async getTransactions(){
            const data = await getters.getTransactions()

            this.transactions = data
            return data
        },
        async getSMSMessages(){
            const data = await getters.getSMSMessages()

            this.smsmessages = data
            return data
        },
        async getBanks(){
            const data = await getters.getBanks()

            this.banks = data.banks

            return data
        },

        async getServices(){
            const data = await getters.getServices()

            vendStore().services2 = data.data

            return data
        },
    },
    getters : {
        
    }
})