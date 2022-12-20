import{q,M as w,r as h,Q as L,m as M,f as S,a as u,b as e,t as x,u as t,p as v,w as B,i as n,v as d,j as f,k as y,l as g,h as P,E as H,G as z,o as p}from"./entry.a5fcd4f4.js";import{g as E}from"./useBaseurl.e4380f80.js";const j={class:"h-screen p-3 pb-3"},D={class:"text-3xl text-center mt-3"},K=e("div",{class:"text-center"},[e("p",{class:"text-xl border-b-4 border-black mx-auto w-fit"},"Sign up")],-1),O={class:"text-left p-3"},R=["onSubmit"],$={class:"mt-3"},A=e("label",{class:"block mb-3 pl-2 font-medium text-lg",for:"first_name"},"First Name:",-1),F={class:"mt-3"},G=e("label",{class:"block mb-3 pl-2 font-medium text-lg",for:"last_name"},"Last Name:",-1),Q={class:"mt-3"},W=e("label",{class:"block mb-3 pl-2 font-medium text-lg",for:"email"},"Email:",-1),I={class:"mt-3"},J=e("label",{class:"block mb-3 pl-2 font-medium text-lg",for:"phone"},"Phone:",-1),X={key:0,class:""},Y=["innerHTML"],Z={class:"mt-3"},ee=e("label",{class:"block mb-3 pl-2",for:"password"},"Password:",-1),se={class:"mt-3"},te=e("label",{class:"block mb-3 pl-2",for:"cpassword"},"Confirm Password:",-1),oe={key:0,class:""},re=["innerHTML"],le={class:"mt-3"},ae=e("label",{class:"block mb-3 pl-2 font-medium text-lg",for:"last_name"},"Referral Code:",-1),ne={class:"text-center p-2 mt-3"},de=["disabled"],ie={key:0,class:"animate-spin h-7 w-7 -mb-2 mr-3 pt-2 border-r-2 border-b-2 border-l-2 border-whtie rounded-full inline-block"},me={class:"mt-3"},_e={__name:"signup",setup(ue){const k=q(),s=w({first_name:"",last_name:"",phone:"",email:"",password:"",confirm_password:"",ref_code:""});let i=h({}),c=h(!1);const C=L();E(C);const _=M(),r=w({userphoneerror:!1,userphoneerrormsg:"",unmatchpassword:!1,unmatchpasswordmsg:""});function V(){let a=s.password,o=s.confirm_password;a.length<8?(r.unmatchpassword=!0,r.unmatchpasswordmsg="<b class='text-red-500'>Passwords must be 8 Characters or more</b>"):a!=o?(r.unmatchpassword=!0,r.unmatchpasswordmsg="<b class='text-red-500'>Passwords do not match</b>"):(r.unmatchpassword=!1,r.unmatchpasswordmsg="")}function N(){let a=s.phone;var o=/[a-z]+/i;o.test(a)||a.length!=11?(r.userphoneerror=!0,r.userphoneerrormsg="<ul class='text-red-500'><li>Only numeric characters required</li><li>Number must be 11 digits</li></ul>"):(r.userphoneerror=!1,r.userphoneerrormsg="")}const b=S(()=>!!(m.value||s.first_name.length<=0||s.last_name.length<=0||s.email.length<=0||s.phone.length<=0||s.password.length<=0||r.userphoneerror==!0||r.unmatchpassword==!0));let m=h(!1);async function T(){c.value=!1,m.value=!0;try{return await k.registerUser(s).then(a=>{m.value=!1,i.value=a,c.value=!0,s.first_name="",s.last_name="",s.email="",s.phone="",s.password="",s.confirm_password="",s.ref_code=""})}catch(a){m.value=!1,i.value=a,c.value=!0}}return(a,o)=>{const U=z;return p(),u("div",null,[e("div",j,[e("p",D,"Welcome to "+x(t(_).siteName),1),e("div",{style:v({"background-color":t(_).siteTheme.authCardColor,color:t(_).siteTheme.authTextColor}),class:"mt-16 p-5 mx-auto w-full lg:w-1/3 rounded-lg mb-7"},[K,e("div",O,[e("form",{onSubmit:B(T,["prevent"])},[e("div",$,[A,n(e("input",{"onUpdate:modelValue":o[0]||(o[0]=l=>t(s).first_name=l),class:"p-3 rounded-md w-full ring-0",id:"first_name",name:"first_name",type:"text",required:"",placeholder:""},null,512),[[d,t(s).first_name]])]),e("div",F,[G,n(e("input",{"onUpdate:modelValue":o[1]||(o[1]=l=>t(s).last_name=l),class:"p-3 rounded-md w-full ring-0",id:"last_name",name:"last_name",type:"text",required:"",placeholder:""},null,512),[[d,t(s).last_name]])]),e("div",Q,[W,n(e("input",{"onUpdate:modelValue":o[2]||(o[2]=l=>t(s).email=l),class:"p-3 rounded-md w-full ring-0",id:"email",name:"email",type:"email",required:"",placeholder:"example@gmail.com"},null,512),[[d,t(s).email]])]),e("div",I,[J,n(e("input",{"onUpdate:modelValue":o[3]||(o[3]=l=>t(s).phone=l),onKeyup:o[4]||(o[4]=l=>N()),class:"p-3 rounded-md w-full ring-0",id:"phone",name:"phone",type:"text",required:"",placeholder:"08020000300"},null,544),[[d,t(s).phone]]),t(r).userphoneerror?(p(),u("div",X,[e("p",{innerHTML:t(r).userphoneerrormsg},null,8,Y)])):f("",!0)]),e("div",Z,[ee,n(e("input",{"onUpdate:modelValue":o[5]||(o[5]=l=>t(s).password=l),class:"p-3 rounded-md w-full ring-3 ring-red-300 outline-0",id:"password",name:"password",type:"password",required:"",placeholder:"************"},null,512),[[d,t(s).password]])]),e("div",se,[te,n(e("input",{"onUpdate:modelValue":o[6]||(o[6]=l=>t(s).confirm_password=l),onKeyup:o[7]||(o[7]=l=>V()),class:"p-3 rounded-md w-full ring-3 ring-red-300 outline-0",id:"cpassword",name:"cpassword",type:"password",required:"",placeholder:"************"},null,544),[[d,t(s).confirm_password]]),t(r).unmatchpassword?(p(),u("div",oe,[e("p",{innerHTML:t(r).unmatchpasswordmsg},null,8,re)])):f("",!0)]),e("div",le,[ae,n(e("input",{"onUpdate:modelValue":o[8]||(o[8]=l=>t(s).ref_code=l),class:"p-3 rounded-md w-full ring-0",id:"ref_code",name:"ref_code",type:"text",placeholder:""},null,512),[[d,t(s).ref_code]])]),t(c)?(p(),u("div",{key:0,class:y([{"bg-green-300/[.6] text-green-800":t(i).status,"bg-red-400/[.40] text-red-900":!t(i).status},"p-3 mt-3 text-left rounded-md"])},[e("p",null,x(t(i).message),1)],2)):f("",!0),e("div",ne,[e("button",{style:v({"background-color":t(_).siteTheme.buttonBgColor}),type:"submit",disabled:t(b),class:y([[{"opacity-50":t(b)}],"p-2 w-1/2 text-white rounded-md font-bold text-lg hover:bg-[#0a5556]"])},[t(m)?(p(),u("span",ie)):f("",!0),g(" Sign up")],14,de),e("p",me,[g("Not yet registered? "),P(U,{to:{name:"login"},class:"text-[#4B4237] hover:text-[#978269]"},{default:H(()=>[g("Login")]),_:1})])])],40,R)])],4)])])}}};export{_e as default};