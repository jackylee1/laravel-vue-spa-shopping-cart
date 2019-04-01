<template>
    <div>
        <Header/>

        <router-view></router-view>

        <Footer/>
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
