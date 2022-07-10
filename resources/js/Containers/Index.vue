<template>
        <router-view></router-view>
</template>

<script>

export default {
    name: 'Index',
    created () {
        // set axios defaults after component is created

        axios.defaults.baseURL = 'http://localhost:8000/api/'
        // axios.defaults.headers.common['Authorization'] = `Bearer ${authService.getToken()}`;
        axios.defaults.headers.post['Content-Type'] = 'application/json'
    },
    // computed: {
    //     layout () {
    //         return (this.$route.meta.layout || defaultLayout)
    //     }
    // }
}
</script>

<style scoped>

</style>
