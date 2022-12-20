export default function(){

    onMounted(() => {
        const appConfig = useAppConfig()

        return appConfig.siteTheme
    })

    
}