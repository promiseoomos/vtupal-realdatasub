
export function validateDigitChars(value){

    var patt = /[a-z]+/i

    if(value.length == 0){
        return {
            status : false,
            message : ""
        }    
    }else{
        if(patt.test(value)){
            return {
                status : false,
                message : "This field can only contain numeric characters"
            }
        }else{
            return {
                status : true,
                message : "Field contains numeric characters only."
            }
        }
    }
}

export function validateDigitLength(value, exactlength){
    // console.log(value.length)
    if(value.length > 0){
        if(value.length != exactlength){
            return {
                status : false,
                message : "This field must contain exactly " + exactlength + " characters"
            }
        }else{
            return {
                status : true,
                message : "Field passes the validation"
            }
        }    
    }else{
        return {
            status : false,
            message : ""
        }
    }
}

export function comfirmPassword(){

}