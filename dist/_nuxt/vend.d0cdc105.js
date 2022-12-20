import{I as t,J as r}from"./entry.9a5b8fbf.js";const s={"Cache-control":"no-cache"},i={async getBillersCat(){const e=await $fetch("vend/billers/",{baseURL:t(),headers:s,method:"GET"});return JSON.parse(e)},async createBill(e){const a=await $fetch("vend/createbill/",{baseURL:t(),headers:s,method:"POST",body:e});return JSON.parse(a)},async validateBill(e){const a=await $fetch("vend/validaterecipient/",{baseURL:t(),headers:s,method:"POST",body:e});return JSON.parse(a)},async buySME(e){const a=await $fetch(`vend/sme/${e.network}`,{baseURL:t(),headers:s,method:"POST",body:e});return JSON.parse(a)},async buyAirtime(e){const a=await $fetch(`vend/airtime/${e.network}`,{baseURL:t(),headers:s,method:"POST",body:e});return JSON.parse(a)},async getSMEPrices(e){const a=await $fetch(`vend/smeprices/${e}`,{baseURL:t(),headers:s,method:"GET"});return JSON.parse(a)},async sendSMS(e){const a=await $fetch("vend/bulksms",{baseURL:t(),headers:s,method:"POST",body:e});return JSON.parse(a)},async getServices(){const e=await $fetch("/operations/all/services",{baseURL:t(),headers:s,method:"GET"});return JSON.parse(e)}},c=r("vender",{state:()=>({dataServices:[],cableServices:[],services:{providers:"vtupal",last_updated:"",data:[{service_name:"cable",service_suffix:"PLAN",service_prefix:"",service_provider:"arise",percentage:[{user_type:"normal",value:5},{user_type:"reseller",value:3},{user_type:"retailer",value:1.8}],services:[{name:"GOTV",id:1,status:"active"},{name:"DSTV",id:2,status:"active"},{name:"STARTIME",id:3,status:"active"}]},{service_name:"data",service_prefix:"",service_suffix:"PLAN",providers:"arise",percentage:[{user_type:"normal",value:5},{user_type:"reseller",value:3},{user_type:"retailer",value:1.8}],services:[{name:"9MOBILE",id:1,status:"active",plans:[]},{name:"AIRTEL",id:2,status:"active",plans:[]},{name:"GLO",id:3,status:"active",plans:[]},{name:"MTN",id:4,status:"active",plans:[]}]},{service_name:"electricity",service_prefix:"",service_suffix:"Electric",service_provider:"arise",service_charge:100,percentage:[{user_type:"normal",value:0},{user_type:"reseller",value:5},{user_type:"retailer",value:20}],services:[{id:1,name:"Ikeja Electric",types:[{name:"Prepaid",id:1},{name:"Postpaid",id:2}],status:"active"},{id:2,name:"Eko Electric",types:[{name:"Prepaid",id:1},{name:"Postpaid",id:2}],status:"active"},{id:3,name:"Abuja Electric",types:[{name:"Prepaid",id:1},{name:"Postpaid",id:2}],status:"active"},{id:4,name:"Kanu Electric",types:[{name:"Prepaid",id:1},{name:"Postpaid",id:2}],status:"active"},{id:5,name:"Enugu Electric",types:[{name:"Prepaid",id:1},{name:"Postpaid",id:2}],status:"active"},{id:6,name:"Port Harcourt Electric",types:[{name:"Prepaid",id:1},{name:"Postpaid",id:2}],status:"active"},{id:7,name:"Ibadan Electric",types:[{name:"Prepaid",id:1},{name:"Postpaid",id:2}],status:"active"},{id:8,name:"Kaduna Electric",types:[{name:"Prepaid",id:1},{name:"Postpaid",id:2}],status:"active"},{id:9,name:"Jos Electric",types:[{name:"Prepaid",id:1},{name:"Postpaid",id:2}],status:"active"},{id:10,name:"Benin Electric",types:[{name:"Prepaid",id:1},{name:"Postpaid",id:2}],status:"active"},{id:11,name:"Yola Electric",types:[{name:"Prepaid",id:1},{name:"Postpaid",id:2}],status:"active"}]},{service_name:"sme",service_prefix:"",service_suffix:"",service_provider:"simhosting",service_charge:100,percentage:[{user_type:"normal",value:15},{user_type:"reseller",value:8},{user_type:"retailer",value:5}],services:[{id:1,name:"MTN",products:[{id:1,name:"500MB",value:"500MB",amount:109.45},{id:2,name:"1GB",value:"1000MB",amount:218.9},{id:3,name:"2GB",value:"2000MB",amount:437.8},{id:4,name:"3GB",value:"3000MB",amount:656.7},{id:4,name:"5GB",value:"5000MB",amount:1094.5},{id:5,name:"10GB",value:"10000MB",amount:2189}]}]},{service_name:"bulksms",service_prefix:"",service_suffix:"",service_provider:"smartsms",service_charge:2.4,percentage:[{user_type:"normal",value:20},{user_type:"reseller",value:15},{user_type:"retailer",value:10}],services:[]}]},services2:[]}),actions:{async getBillerCat(){const e=await i.getBillersCat();return this.dataServices=e.data.dataPlans,this.cableServices=e.data.cablePlans,e.data},async createBill(e){return await i.createBill(e)},async validateBill(e){return await i.validateBill(e)},async buySME(e){return await i.buySME(e)},async buyAirtime(e){return await i.buyAirtime(e)},async getAirtimePrices(){return await i.getSMEPrices(this.details.level)},async sendSMS(e){return await i.sendSMS(e)},async getServices(){const e=await i.getServices();return this.services2=e.data,e}},getters:{}});export{c as v};
