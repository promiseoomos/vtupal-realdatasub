<template>
  <div class="">
    <NavbarBase @show-sidebar="showSidebar" />
    <NuxtPage  />
  </div>
</template>

<script type="text/javascript" setup>
import { userStore } from "@/store/user.js"

const userstore = userStore();

var showsidebar = ref(false)

function showSidebar(){
  // console.log(show)
  showsidebar.value = !showsidebar.value
  // console.log(showsidebar.value)
}

const route = useRoute()

watch(route, () =>  {
  showsidebar.value = false
})

provide("showsidebar", showsidebar)


</script>
