import { adminStore } from "~~/store/admin";
import { userStore } from "~~/store/user.js";

export function userId(){
    return userStore().details.track_id
}

export function baseURL(){
    let userLoggedin = userStore().loggedin
    let adminLoggedin = adminStore().loggedin

    if(userLoggedin){
        return userStore().baseURL    
    }else if(adminLoggedin){
        return adminStore().baseURL
    }
    
}