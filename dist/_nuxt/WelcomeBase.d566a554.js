import{_ as m}from"./ModalBase.2f1919b6.js";import{q as u,r as c,m as f,o as a,a as x,b as e,l as r,t as o,u as t,k as g,c as d,j as h,T as b,p as y}from"./entry.9a5b8fbf.js";import{c as w}from"./useStrings.2b5df556.js";const k={class:"p-3 flex flex-nowrap justify-between"},v={class:"w-11/12"},B={class:"text-2xl font-bold"},C={class:"text-[#0f8B8D]"},T=e("p",{class:"border-b border-gray-400 mt-2 lg:w-1/2"},null,-1),N={class:"font-bold text-xl"},M=e("span",{class:"font-medium text-md"},null,-1),W={class:"font-medium text-sm lg:text-lg"},$={class:"font-medium text-md"},z={class:"w-6/12 lg:w-1/2 text-right"},D=e("p",{class:"text-md lg:text-lg font-bold"},"Wallet Balance: ",-1),S={class:"text-lg lg:text-2xl font-bold text-green-600"},G={__name:"WelcomeBase",setup(V){const s=u(),l=c(!1),n=f();function i(){s.$patch({modalshowing:!0}),l.value=!0}function _(){s.$patch({modalshowing:!1}),l.value=!1}return c("paystack"),(j,q)=>{const p=m;return a(),x("div",{style:y({"background-color":t(n).siteTheme.bannerBgColor,color:t(n).siteTheme.bannerTextColor}),class:"p-1 rounded-md mb-5"},[e("div",k,[e("div",v,[e("p",B,[r("Welcome, "),e("span",C,o(t(s).details.first_name),1),e("span",{class:g([{"bg-yellow-300/[.3] text-yellow-700":t(s).details.type=="normal","bg-sky-300/[.4] text-sky-700":t(s).details.type=="reseller","bg-purple-300/[.4] text-purple-700":t(s).details.type=="retailer"},"p-2 ml-2 text-xs rounded-md"])},o(t(w)(t(s).details.type)),3)]),T,e("p",N,[M,r(o(t(s).details.virtual_account_no),1)]),e("p",W,o(t(s).details.virtual_account_name),1),e("p",$,o(t(s).details.virtual_account_bank),1)]),e("div",z,[D,e("p",S,o(t(s).details.currency=="NGN"?"\u20A6":"$")+" "+o(t(s).details.wallet_balance),1),e("button",{onClick:i,class:"bg-[#0f8B8D] text-white text-sm w-full lg:w-fit lg:text-lg font-bold p-2 rounded-lg hover:bg-[#0c5859]"},"Fund Wallet")]),(a(),d(b,{to:"body"},[t(l)?(a(),d(p,{key:0,onHideModal:_})):h("",!0)]))])],4)}}};export{G as _};
