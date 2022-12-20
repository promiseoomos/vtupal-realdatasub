import{q as N,s as B,M as S,Q as T,m as V,r as m,L as U,a as p,b as e,t as h,u as t,w as E,i as b,v,j as x,l as i,k as L,h as w,E as y,p as M,G as q,o as _,N as D}from"./entry.a5fcd4f4.js";import{g as j}from"./useBaseurl.e4380f80.js";const z={class:"h-screen p-3"},P={class:"text-3xl text-center mt-3"},R=e("div",{class:"text-center"},[e("p",{class:"text-xl border-b-4 border-black mx-auto w-fit"},"Login")],-1),A={class:"text-left p-3"},F=["onSubmit"],G={class:"mt-3"},O=e("label",{class:"block mb-3 font-medium text-lg",for:"email"},"Email:",-1),Q={class:"mt-3"},W=e("label",{class:"block mb-3",for:"password"},"Password:",-1),H={class:"text-center p-2 mt-3"},I={class:"p-2 bg-[#0f8B8D] w-1/2 text-white rounded-md font-bold text-lg hover:bg-[#0a5556]"},J={key:0,class:"animate-spin h-7 w-7 -mb-2 mr-3 pt-2 border-r-2 border-b-2 border-l-2 border-whtie rounded-full inline-block"},K={class:"mt-3"},X={class:"mt-3"},ee={__name:"login",setup(Y){const d=N(),g=B(),l=S({email:"",password:""}),k=T();j(k);const u=V();let s=m({}),r=m(!1),o=m(!1);async function C(){r.value=!1,o.value=!0;try{return await d.loginUser(l).then(a=>{o.value=!1,s.value=a,r.value=!0,d.loggedin&&D({name:"dashboard"})})}catch(a){o.value=!1,s.value=a,r.value=!0}}return U(async()=>{g.query.cid&&(o.value=!0,await d.confirmEmail(g.query.cid).then(a=>{o.value=!1,s.value=a,r.value=!0}))}),(a,n)=>{const f=q;return _(),p("div",z,[e("p",P,"Welcome to "+h(t(u).siteName),1),e("div",{style:M({"background-color":t(u).siteTheme.authCardColor,color:t(u).siteTheme.authTextColor}),class:"mt-16 p-5 bg-[]/[.5] mx-auto w-full lg:w-1/3 rounded-lg"},[R,e("div",A,[e("form",{onSubmit:E(C,["prevent"])},[e("div",G,[O,b(e("input",{"onUpdate:modelValue":n[0]||(n[0]=c=>t(l).email=c),class:"p-3 rounded-md w-full ring-0",id:"email",name:"email",type:"email",placeholder:"example@gmail.com"},null,512),[[v,t(l).email]])]),e("div",Q,[W,b(e("input",{"onUpdate:modelValue":n[1]||(n[1]=c=>t(l).password=c),class:"p-3 rounded-md w-full ring-3 ring-red-300 outline-0",id:"password",name:"password",type:"password",placeholder:"************"},null,512),[[v,t(l).password]])]),e("div",H,[e("button",I,[t(o)?(_(),p("span",J)):x("",!0),i(" Login")]),t(r)?(_(),p("div",{key:0,class:L([{"bg-green-300/[.6] text-green-800":t(s).status,"bg-red-400/[.40] text-red-900":!t(s).status},"p-3 mt-3 text-left rounded-md"])},[e("p",null,h(t(s).message),1)],2)):x("",!0),e("p",K,[i("Not yet registered? "),w(f,{to:{name:"signup"},class:"text-[#4B4237] hover:text-[#978269]"},{default:y(()=>[i("Signup")]),_:1})]),e("p",X,[w(f,{to:{name:"passwordreset"},class:"text-[#4B4237] hover:text-[#978269]"},{default:y(()=>[i("Forgot Password")]),_:1})])])],40,F)])],4)])}}};export{ee as default};
