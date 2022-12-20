import{_ as k}from"./WelcomeBase.8ace50e6.js";import{q as j,r as d,a as o,h as A,b as t,i as h,z as x,u as e,A as w,l as b,v as B,k as g,t as m,j as i,o as a}from"./entry.a5fcd4f4.js";import"./ModalBase.57ba2d8e.js";import"./useStrings.2b5df556.js";const V={class:"top-2/4 lg:left-1/3 left-3 pb-10 justify-center content-center justify-items-center align-middle w-full p-3 bg-white rounded-lg shadow-2xl shadow-gray-400 transition-transform duration-700 ease-in-out"},N=t("p",{class:"title text-center text-2xl lg:text-3xl font-bold"},"Fund your wallet",-1),C=t("p",{class:"text-xl px-3 mt-5"},"Choose your Funding Method",-1),P={class:"p-3"},M={class:"text-lg"},T={class:"text-lg"},D={key:0,class:"p-3"},F=t("p",{class:"font-semibold text-lg"},"Amount to Fund :",-1),U={key:0},W={key:1},z=["disabled"],E={key:0,class:"animate-spin h-7 w-7 -mb-2 mr-3 pt-2 border-r border-b border-l-2 border-white rounded-full inline-block"},R={key:1,class:"p-3 bg-slate-300/[.35] rounded-md"},S=t("p",{class:"font-bold"},"Transfer amount to this account, wallet will be credited Automatically",-1),q={class:"flex flex-nowrap justify-between mt-3"},O=t("p",{class:"w-1/2 text-lg font-medium"},"Account Number:",-1),$={class:"w-1/2 font-bold text-left"},G={class:"flex flex-nowrap justify-between"},H=t("p",{class:"w-1/2 text-lg font-medium"},"Account Bank :",-1),I={class:"w-1/2 font-bold text-left"},J={class:"flex flex-nowrap justify-between"},K=t("p",{class:"w-1/2 text-lg font-medium"},"Account Name:",-1),L={class:"w-1/2 font-bold text-left"},st={__name:"index",emits:["hide-modal"],setup(Q,{emit:X}){const r=j(),s=d("paystack");d(0);let f=d(!1),l=d(!1),p=d(!1);const c=d({amount:0,details:r.details});async function y(){f.value=!0;try{await r.fundWallet(c.value).then(_=>{f.value=!1,window.location=_.rurl})}catch(_){l.value=!0,p.value=_}}return(_,n)=>{const v=k;return a(),o("div",null,[A(v),t("div",V,[N,C,t("div",P,[t("p",M,[h(t("input",{type:"radio","onUpdate:modelValue":n[0]||(n[0]=u=>w(s)?s.value=u:null),name:"fundingmethod",value:"paystack",class:"w-5 h-5 mr-4"},null,512),[[x,e(s)]]),b("Pay with Paystack ")]),t("p",T,[h(t("input",{type:"radio","onUpdate:modelValue":n[1]||(n[1]=u=>w(s)?s.value=u:null),name:"fundingmethod",value:"transfer",class:"w-5 h-5 mr-4"},null,512),[[x,e(s)]]),b("Pay by Bank Transfer ")])]),e(s)=="paystack"?(a(),o("div",D,[F,h(t("input",{type:"number","onUpdate:modelValue":n[2]||(n[2]=u=>e(c).amount=u),class:"p-3 rounded-lg border border-gray-400 w-full",placeholder:"3000"},null,512),[[B,e(c).amount]]),e(l)?(a(),o("div",{key:0,class:g(["rounded-md p-3 mt-3",{"text-red-600 bg-red-600/[.2]":!e(l),"text-green-600 bg-green-600/[.2]":e(l)}])},[e(l)?i("",!0):(a(),o("p",U,m(e(p))+". Contact the Administrators.",1)),e(l)?(a(),o("p",W,m(e(p)),1)):i("",!0)],2)):i("",!0),t("button",{disabled:e(c).amount<=0,class:g([{"bg-[#0f8b8d9f] hover:bg-[#0f8b8d9f]":e(c).amount<=0},"block w-full p-3 rounded-sm mt-3 font-semibold text-lg bg-[#0f8B8D] text-white hover:bg-[#13696a]"]),onClick:y},[e(f)?(a(),o("span",E)):i("",!0),b(" Pay with Paystack")],10,z)])):i("",!0),e(s)=="transfer"?(a(),o("div",R,[S,t("div",q,[O,t("p",$,m(e(r).details.virtual_account_no),1)]),t("div",G,[H,t("p",I,m(e(r).details.virtual_account_bank),1)]),t("div",J,[K,t("p",L,m(e(r).details.virtual_account_name),1)])])):i("",!0)])])}}};export{st as default};
