import{_ as R}from"./WelcomeBase.8ace50e6.js";import{Q as T,q as U,r as i,R as $,f as y,S as E,a as c,h as z,b as s,w as O,i as g,v as b,u as t,t as d,z as B,l as x,k as w,j as k,o as p}from"./entry.a5fcd4f4.js";import{g as W}from"./useBaseurl.e4380f80.js";import{f as q}from"./useFormatDigits.0d52ca80.js";import{v as G}from"./vend.e5739c9e.js";import"./ModalBase.57ba2d8e.js";import"./useStrings.2b5df556.js";const Q={class:"rounded-md bg-gray-100 p-2"},A=s("p",{class:"text-xl border-b-4 border-black mx-auto w-fit"},"Broadcast Message",-1),F=["onSubmit"],H={class:"rounded-md bg-white p-2 mt-3"},J={class:"mt-3"},K=s("label",{for:"subject",class:"block mb-3 font-medium text-lg pl-1"},"Subject:",-1),L={class:"mt-3"},X=s("label",{for:"sender",class:"block mb-3 font-medium text-lg pl-1"},"Sender ID:",-1),Y={class:"mt-3"},Z=s("label",{for:"recipients",class:"block mb-1 font-medium text-lg pl-1"},"Recipients:",-1),ee=s("p",{class:"text-sm text-gray-400 mb-2 pl-1"},"Seperate multiple recipients with comma",-1),te={class:"pl-1"},se={class:"mt-3"},ae=s("label",{for:"message",class:"block mb-3 font-medium text-lg pl-1"},"Message:",-1),le={class:"mt-3"},oe=s("label",{for:"sender",class:"block mb-3 font-medium text-lg pl-1"},"Routing Config",-1),re={class:""},ne={class:""},ie={key:0},de={key:1},ue={key:2},ce={class:"text-center p-2 mt-3"},pe=["disabled"],me={key:0,class:"animate-spin h-7 w-7 -mb-2 mr-3 pt-2 border-r-2 border-b-2 border-l-2 border-whtie rounded-full inline-block"},ge={key:1,class:"text-center p-2 mt-3"},ve=["disabled"],Se={__name:"send",setup(be){const I=T();W(I);const u=U(),f=G(),e=i({subject:"",senderId:"",recipients:"",recipient:"",message:"",routing:"2",type:"0",amountPerPage:0,recipientsCount:"",amount:0,face_amount:0,face_price:0,famount:"",userId:u.details.track_id,transactionType:"bulksms-payment",paymentChannel:"Smartsmssolultion",charge:.5,currency:u.details.currency});let n=i(!1),o=i(""),S=i(!1);i(0),$(()=>{if(f.services2.length>0){let r=f.services2.find(h=>h.standard_name=="bulk_sms"),a=r.pricing;a=="value"?e.value.amountPerPage=Number(r[u.details.type+"_price"]):a=="percentage"&&(e.value.amountPerPage=Math.round(Number(r.face_price)+Number(r.face_price)*Number(r[u.details.type+"_percentage"]/100))),e.value.face_price=r.face_price}});const N=y(()=>e.value.message.length>0?e.value.message.split(" ").length:0),j=y(()=>Math.ceil(N.value/63)),C=y(()=>!!(n.value||e.value.subject.length==0||e.value.recipients.length==0||e.value.senderId.length==0||e.value.message.length==0));let m=i(5),v=i(!1),_=i();const D=()=>{n.value=!0,_.value=setInterval(V,1e3)},P=()=>{clearInterval(_.value),n.value=!1,m.value=5,v.value=!1};E(m,()=>{m.value<=0&&(clearInterval(_.value),v.value=!0,M())});const V=()=>{m.value--},M=async()=>{e.value.recipientsCount=e.value.recipients.split(",").length,e.value.amount=e.value.recipientsCount*e.value.amountPerPage,e.value.face_amount=e.value.recipientsCount*e.value.face_price,e.value.recipient=e.value.recipients;let r=q(e.value.amount);e.value.famount=u.details.currency=="NGN"?`\u20A6${r}`:`$${r}`,n.value=!0;try{const a=await f.sendSMS(e.value);n.value=!1,u.getUserDetails(u.details.track_id),a.status=="success"&&(e.value.subject="",e.value.senderId="",e.value.recipients="",e.value.message=""),o.value=a}catch(a){n.value=!1,o.value=a,S.value=!0}finally{m.value=5,v.value=!1}};return(r,a)=>{const h=R;return p(),c("div",null,[z(h),s("div",Q,[A,s("form",{onSubmit:O(D,["prevent"])},[s("div",H,[s("div",J,[K,g(s("input",{type:"text","onUpdate:modelValue":a[0]||(a[0]=l=>t(e).subject=l),id:"subject",class:"p-3 rounded-md w-full ring-0 border border-gray-200"},null,512),[[b,t(e).subject]])]),s("div",L,[X,g(s("input",{type:"text","onUpdate:modelValue":a[1]||(a[1]=l=>t(e).senderId=l),id:"sender",class:"p-3 rounded-md w-full ring-0 border border-gray-200",placeholder:"Topup BossNg"},null,512),[[b,t(e).senderId]])]),s("div",Y,[Z,ee,g(s("textarea",{rows:"4","onUpdate:modelValue":a[2]||(a[2]=l=>t(e).recipients=l),id:"recipients",class:"p-3 rounded-md w-full ring-0 border border-gray-200 focus:border-white"},null,512),[[b,t(e).recipients]]),s("p",te,d(t(e).recipients.length>0?t(e).recipients.split(",").length:0)+" Recipient"+d(t(e).recipients.split(",").length>1?"s":""),1)]),s("div",se,[ae,g(s("textarea",{rows:"4","onUpdate:modelValue":a[3]||(a[3]=l=>t(e).message=l),id:"message",class:"p-3 rounded-md w-full ring-0 border border-gray-200 focus:border-white"},null,512),[[b,t(e).message]]),s("p",null,d(t(N))+" of 63 Words ("+d(t(j))+" Page)",1)]),s("div",le,[oe,s("div",re,[g(s("input",{type:"radio","onUpdate:modelValue":a[4]||(a[4]=l=>t(e).routing=l),id:"sender",value:"2",class:"p-3 ring-0 border border-gray-200",placeholder:"Topup BossNg"},null,512),[[B,t(e).routing]]),x(" Send all messages via the Basic Route. DND phone numbers are not charged ")]),s("div",ne,[g(s("input",{type:"radio","onUpdate:modelValue":a[5]||(a[5]=l=>t(e).routing=l),value:"3",id:"sender",class:"p-3 ring-0 border border-gray-200",placeholder:"Topup BossNg"},null,512),[[B,t(e).routing]]),x(" Send message via the Basic route but send to those on DND via the Corporate Route. ")])]),t(o)?(p(),c("div",{key:0,class:w(["rounded-md p-3 mt-3",{"text-red-600 bg-red-600/[.2]":t(o).status=="error"||t(S),"text-green-600 bg-green-600/[.2]":t(o).status=="success"}])},[t(o).status=="error"?(p(),c("p",ie,d(t(o).message),1)):t(o).status=="success"?(p(),c("p",de,d(t(o).message),1)):(p(),c("p",ue,d(t(o)),1))],2)):k("",!0),s("div",ce,[s("button",{disabled:t(C),class:w([{"opacity-50":t(C)},"p-3 px-10 bg-[#0f8B8D] w-full lg:w-1/2 text-white rounded-md font-bold text-md hover:bg-[#0a5556]"]),type:"submit"},[t(n)?(p(),c("span",me)):k("",!0),x(" Send")],10,pe)]),t(n)?(p(),c("div",ge,[s("button",{disabled:t(v),onClick:P,class:w([{"opacity-50":t(v)},"p-2 bg-black w-full lg:w-1/2 text-white rounded-md font-bold text-lg"])}," Cancel ("+d(t(m))+") ",11,ve)])):k("",!0)])],40,F)])])}}};export{Se as default};