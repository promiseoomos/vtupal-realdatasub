import { userStore } from "~~/store/user";

export default function (faceAmount, percentageIncrease){
    let userstore = userStore()

    let amount = Math.round(faceAmount + (faceAmount * (percentageIncrease/100)))
    let formattedNum = useFormatDigits(amount)

    return userstore.details.currency == 'NGN' ? '₦' + `${formattedNum}` : '$' + `${formattedNum}` 
}