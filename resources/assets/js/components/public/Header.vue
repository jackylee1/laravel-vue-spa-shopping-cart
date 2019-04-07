<template>
    <div>
        <header class="header" id="header">
            <div class="container">
                <div class="row">
                    <div class="col-sm-3 logo">
                        <router-link :to="{name: 'index'}">
                            <a class="navbar-brand logotype" href="/">
                                <img class="navbar_logo" src="/assets/public/images/logo.png" alt="FitClothing">
                            </a>
                        </router-link>
                        <p class="slogan">брендовая спортивная одежда</p>
                    </div>
                    <div class="col-sm-7 searching">
                        <form class="form-inline ">
                            <input class="form-control form-control-sm mr-3" type="text" placeholder="Введите поисковое слово в этом поле..." aria-label="Search">
                            <i class="fas fa-search" aria-hidden="true"></i>
                        </form>
                    </div>
                    <div class="col-sm-1">
                        <a href="#">
                            <img class="heart" src="/assets/public/images/cart/heart_white.png" alt="Heart">
                        </a>
                    </div>
                    <div class="col-sm-1">
                        <div class="dropdown">
                            <a class="btn" href="#" data-toggle="modal" data-target="#exampleModal">
                                <img class="cart" src="/assets/public/images/cart/cart_white.png" alt="">
                                <span class="badge">10</span>
                            </a>
                            <!-- Modal -->
                            <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-12 centered">
                                                    <h3 class="modal_title">Товар добавлен в корзину</h3>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-3">
                                                    <img src="/assets/public/images/items/t-shirt.png" alt="">
                                                </div>
                                                <div class="col-md-6">
                                                    <h3 class="item_title">Рубашка поло Nike Grey XXL</h3>
                                                    <p>Артикул: <strong>547586</strong></p>
                                                    <p>Размер: <strong>XXL</strong></p>
                                                    <p>Кол-во: <strong>1 шт.</strong></p>
                                                </div>
                                                <div class="col-3">
                                                    <h4 class="modal_price">256 грн</h4>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-3">
                                                    <img src="/assets/public/images/items/t-shirt.png" alt="">
                                                </div>
                                                <div class="col-md-6">
                                                    <h3 class="item_title">Рубашка поло Nike Grey XXL</h3>
                                                    <p>Артикул: <strong>547586</strong></p>
                                                    <p>Размер: <strong>XXL</strong></p>
                                                    <p>Кол-во: <strong>1 шт.</strong></p>
                                                </div>
                                                <div class="col-3">
                                                    <h4 class="modal_price">256 грн</h4>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-3">
                                                    <img src="/assets/public/images/items/t-shirt.png" alt="">
                                                </div>
                                                <div class="col-6">
                                                    <h3 class="item_title">Рубашка поло Nike Grey XXL</h3>
                                                    <p>Артикул: <strong>547586</strong></p>
                                                    <p>Размер: <strong>XXL</strong></p>
                                                    <p>Кол-во: <strong>1 шт.</strong></p>
                                                </div>
                                                <div class="col-3">
                                                    <h4 class="modal_price">256 грн</h4>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12 righted">
                                                    <h5 class="item_sum">
                                                        <span>Сумма заказа:</span> 1024 грн
                                                    </h5>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-dark" data-dismiss="modal">Продолжить покупки</button>
                                            <button type="button" class="btn btn-danger" onclick="window.location='cart.html'">Оформить заказ</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>
    </div>
</template>

<script>
    export default {
        name: 'Header'
    }
</script>