<template>
    <div>
        <Header/>

        <router-view></router-view>

        <Footer/>

        <notifications/>
    </div>
</template>

<script>
    import { Header, Footer } from '.';

    export default {
        name: 'Layout',
        components: {
            Header,
            Footer
        }
    }
</script>