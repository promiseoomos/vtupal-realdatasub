import{_ as f}from"./WelcomeBase.d566a554.js";import{q as w,r as l,f as c,a as r,h as v,b as t,w as g,i,v as n,u as a,k as B,j as h,l as k,o as m}from"./entry.9a5b8fbf.js";import"./ModalBase.2f1919b6.js";import"./useStrings.2b5df556.js";const y={class:"rounded-md bg-white pb-10"},_=t("p",{class:"font-bold text-center mt-7"},"Change Password",-1),j={class:"rounded-md bg-white p-2 mt-3"},P={class:"mt-3"},V=t("label",{for:"subject",class:"block mb-3 font-medium text-lg pl-1"},"New Password",-1),C={class:"mt-3"},N=t("label",{for:"subject",class:"block mb-3 font-medium text-lg pl-1"},"Confirm Password",-1),D={class:"text-center p-2 mt-3"},K=["disabled"],M={key:0,class:"animate-spin h-7 w-7 -mb-2 mr-3 pt-2 border-r-2 border-b-2 border-l-2 border-whtie rounded-full inline-block"},z={__name:"index",setup(O){const u=w();l(!1);let p=l(!1);const d=l({password:"",confirmPassword:""});return c(()=>u.details.wallet_balance-50>upgradeObj.value.amount),(o,e)=>{const b=f;return m(),r("div",null,[v(b),t("div",y,[_,t("div",j,[t("form",{onSubmit:e[6]||(e[6]=g((...s)=>o.createBill&&o.createBill(...s),["prevent"]))},[t("div",P,[V,i(t("input",{type:"password",onKeyup:e[0]||(e[0]=(...s)=>o.validate&&o.validate(...s)),onBlur:e[1]||(e[1]=(...s)=>o.validateBill&&o.validateBill(...s)),"onUpdate:modelValue":e[2]||(e[2]=s=>a(d).password=s),id:"subject",class:"p-3 rounded-md w-full ring-0 border border-gray-200"},null,544),[[n,a(d).password]])]),t("div",C,[N,i(t("input",{type:"password",onKeyup:e[3]||(e[3]=(...s)=>o.validate&&o.validate(...s)),onBlur:e[4]||(e[4]=(...s)=>o.validateBill&&o.validateBill(...s)),"onUpdate:modelValue":e[5]||(e[5]=s=>a(d).confirmPassword=s),id:"subject",class:"p-3 rounded-md w-full ring-0 border border-gray-200"},null,544),[[n,a(d).confirmPassword]])]),t("div",D,[t("button",{disabled:o.chkfields,class:B([{"opacity-50":o.chkfields},"p-3 px-10 bg-[#0f8B8D] text-white rounded-md font-bold text-md hover:bg-[#0a5556]"]),type:"submit"},[a(p)?(m(),r("span",M)):h("",!0),k(" Change Password")],10,K)])],32)])])])}}};export{z as default};
