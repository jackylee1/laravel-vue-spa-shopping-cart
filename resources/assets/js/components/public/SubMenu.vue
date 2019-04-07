<template>
    <div>
        <section class="submenu">
            <div class="container">
                <div class="row">
                    <div class="col-6 phones my-auto">
                        <ul>
                            <li>(073) 069-6097</li>
                            <li>(068) 225-7235</li>
                        </ul>
                    </div>
                    <div class="col-2 heart my-auto">
                        <a href="#">
                            <img src="/assets/public/assets/public/images/cart/heart.png" alt="">
                        </a>
                    </div>
                    <div class="col-2 carte my-auto">
                        <a href="checkout.html">
                            <img src="/assets/public/assets/public/images/cart/cart.png" alt="">
                        </a>
                    </div>
                    <div class="col-2 search my-auto">
                        <a data-toggle="collapse" href="#collapseSubmenu" role="button" aria-expanded="false" aria-controls="collapseSubmenu">
                            <img src="/assets/public/assets/public/images/cart/search.png" alt="">
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>

<script>
    export default {
        name: 'SubMenu'
    }
</script>