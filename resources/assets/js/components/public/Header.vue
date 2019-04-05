<template>
    <div>
        <section id="top_line" class="top-line" >
            <div class="container">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="telephone">
                    <span class="first">
                        <span class="code">(073) </span>
                        069-6097
                    </span>
                            <span class="second">
                        <span class="code">(068) </span>
                        225-7235
                    </span>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="authotisation">
                            <a class="acount" href="#">Мой аккаунт</a> <a class="login" href="#">Вход/Регистрация</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

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

        <div class="collapse searchcollapse" id="collapseSubmenu">
            <div class="card card-body">
                <form class="form-inline d-flex justify-content-center">
                    <input class="form-control" type="search" placeholder="Введите поисковое слово" aria-label="Search">
                    <button class="btn" type="submit">Поиск</button>
                </form>
            </div>
        </div>

        <header class="header" id="header">
            <div class="container">
                <div class="row">
                    <div class="col-sm-3 logo">
                        <router-link :to="{name: 'index'}">
                            <a class="navbar-brand logotype" href="">
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

        <section class="megamenu">
            <div class="container">
                <div class="row">
                    <div class="menu-container d-flex justify-content-center">
                        <div class="menu">
                            <ul>
                                <li class="menu_link"><a class="menu_tab" href="#">Новинки</a></li>
                                <li class="menu_link"><a class="menu_tab" href="#">Мужская</a>
                                    <ul>
                                        <li class="first_column">
                                            <ul>
                                                <li><p>Верх</p></li>
                                                <li><a href="#">Футболки</a></li>
                                                <li><a href="#">Майки</a></li>
                                                <li><a href="#">Толстовки</a></li>
                                                <li><a href="#">Куртки</a></li>
                                                <li><a href="#">Жилетки</a></li>
                                                <li><a href="#">Термокофты</a></li>
                                            </ul>
                                        </li>
                                        <li class="second_column">
                                            <ul>
                                                <li><p>Низ</p></li>
                                                <li><a href="#">Шорты</a></li>
                                                <li><a href="#">Штаны</a></li>
                                                <li><a href="#">Нижнее белье</a></li>
                                                <li><a href="#">Термоштаны</a></li>
                                            </ul>
                                        </li>
                                        <li class="third_column">
                                            <ul>
                                                <li><p>Компрессионная одежда</p></li>
                                                <li><a href="#">Футболки</a></li>
                                                <li><a href="#">Майки</a></li>
                                                <li><a href="#">Штаны</a></li>
                                                <li><a href="#">Шорты</a></li>
                                            </ul>
                                        </li>
                                        <li class="fourth_column">
                                            <ul>
                                                <li><p>Обувь</p></li>
                                                <li><a href="#">Кроссовки</a></li>
                                                <li><a href="#">Ботинки</a></li>
                                                <li><a href="#">Сандали и тапки</a></li>
                                            </ul>
                                        </li>
                                        <li class="fifth_column">
                                            <ul>
                                                <li><p>Аксессуары</p></li>
                                                <li><a href="#">Кепки</a></li>
                                                <li><a href="#">Шапки</a></li>
                                                <li><a href="#">Перчатки</a></li>
                                                <li><a href="#">Носки</a></li>
                                                <li><a href="#">Сумки</a></li>
                                                <li><a href="#">Рюкзаки</a></li>
                                                <li><a href="#">Очки</a></li>
                                                <li><a href="#">Ремни</a></li>
                                            </ul>
                                        </li>
                                        <li class="sixth_column">
                                            <ul>
                                                <li>
                                                    <a href="#">
                                                        <img class="menu_img" src="/assets/public/images/banners/men-1.jpg" alt="">
                                                    </a>
                                                </li>
                                            </ul>
                                        </li>
                                    </ul>
                                </li>
                                <li class="menu_link"><a class="menu_tab" href="#">Женская</a>
                                    <ul>
                                        <li class="first_column">
                                            <ul>
                                                <li><p>Верх</p></li>
                                                <li><a href="#">Футболки</a></li>
                                                <li><a href="#">Майки</a></li>
                                                <li><a href="#">Бра/Топы</a></li>
                                                <li><a href="#">Толстовки</a></li>
                                                <li><a href="#">Куртки</a></li>
                                                <li><a href="#">Термокофты</a></li>
                                            </ul>
                                        </li>
                                        <li class="second_column">
                                            <ul>
                                                <li><p>Низ</p></li>
                                                <li><a href="#">Лосины</a></li>
                                                <li><a href="#">Шорты</a></li>
                                                <li><a href="#">Штаны</a></li>
                                                <li><a href="#">Термоштаны</a></li>
                                            </ul>
                                        </li>
                                        <li class="third_column">
                                            <ul>
                                                <li><p>Обувь</p></li>
                                                <li><a href="#">Кроссовки</a></li>
                                                <li><a href="#">Ботинки</a></li>
                                                <li><a href="#">Сандали и тапки</a></li>
                                            </ul>
                                        </li>
                                        <li class="fourth_column">
                                            <ul>
                                                <li><p>Аксессуары</p></li>
                                                <li><a href="#">Кепки</a></li>
                                                <li><a href="#">Шапки</a></li>
                                                <li><a href="#">Перчатки</a></li>
                                                <li><a href="#">Носки</a></li>
                                                <li><a href="#">Сумки</a></li>
                                                <li><a href="#">Рюкзаки</a></li>
                                                <li><a href="#">Очки</a></li>
                                            </ul>
                                        </li>
                                        <li class="fifth_column">
                                            <ul>
                                                <li><a href="#"></a></li>
                                            </ul>
                                        </li>
                                        <li class="sixth_column">
                                            <ul>
                                                <li>
                                                    <a href="#">
                                                        <img class="menu_img" src="/assets/public/images/banners/menu-img-2.jpg" alt="">
                                                    </a>
                                                </li>
                                            </ul>
                                        </li>
                                    </ul>
                                </li>
                                <li class="menu_link"><a class="menu_tab" href="#">Аксессуары</a>
                                    <ul>
                                        <li class="first_column">
                                            <ul>
                                                <li><a href="#">Сумки</a></li>
                                                <li><a href="#">Рюкзаки</a></li>
                                                <li><a href="#">Перчатки</a></li>
                                                <li><a href="#">Бутылки для воды</a></li>
                                                <li><a href="#">Очки</a></li>
                                                <li><a href="#">Носки</a></li>
                                            </ul>
                                        </li>
                                        <li class="second_column">
                                            <ul>
                                                <li><a href="#">Очки</a></li>
                                                <li><a href="#">Носки</a></li>
                                                <li><a href="#">Полотенца</a></li>
                                                <li><a href="#">Суппорты</a></li>
                                            </ul>
                                        </li>
                                        <li class="third_column">
                                            <ul>
                                                <li><a href="#">Каппы</a></li>
                                                <li><a href="#">Боксерские перчатки</a></li>
                                                <li><a href="#">Лямки и бинты</a></li>
                                                <li><a href="#">Другие</a></li>
                                            </ul>
                                        </li>
                                        <li class="fourth_column">
                                            <ul>
                                                <li><a href="#"></a></li>
                                            </ul>
                                        </li>
                                        <li class="fifth_column">
                                            <ul>
                                                <li><a href="#"></a></li>
                                            </ul>
                                        </li>
                                        <li class="sixth_column">
                                            <ul>
                                                <li>
                                                    <a href="#">
                                                        <img class="menu_img" src="/assets/public/images/banners/mans-2.jpg" alt="">
                                                    </a>
                                                </li>
                                            </ul>
                                        </li>
                                    </ul>
                                </li>
                                <li class="menu_link"><a class="menu_tab" href="#">Распродажа</a></li>
                                <li class="menu_link menu_brands"><a class="menu_tab last" href="#">Бренды</a>
                                    <ul>
                                        <li class="first_column brands">
                                            <ul>
                                                <li><a href="#">
                                                    <img class="menu_brand_logo" src="/assets/public/images/banners/abr.png" alt="">Abercrombie & Fitch
                                                </a></li>
                                                <li><a href="#">
                                                    <img class="menu_brand_logo" src="/assets/public/images/banners/nike.png" alt="">Nike
                                                </a></li>
                                                <li><a href="#">
                                                    <img class="menu_brand_logo" src="/assets/public/images/banners/guess.png" alt="">Guess
                                                </a></li>
                                                <li><a href="#">
                                                    <img class="menu_brand_logo" src="/assets/public/images/banners/npc.png" alt="">NPC
                                                </a></li>
                                            </ul>
                                        </li>
                                        <li class="second_column brands">
                                            <ul>
                                                <li><a href="#">
                                                    <img class="menu_brand_logo" src="/assets/public/images/banners/pws.png" alt="">Power System
                                                </a></li>
                                                <li><a href="#">
                                                    <img class="menu_brand_logo" src="/assets/public/images/banners/ua.png" alt="">Under Armour
                                                </a></li>
                                                <li><a href="#">
                                                    <img class="menu_brand_logo" src="/assets/public/images/banners/harb.png" alt="">Harbinger
                                                </a></li>
                                                <li><a href="#">
                                                    <img class="menu_brand_logo" src="/assets/public/images/banners/holl.png" alt="">Hollister
                                                </a></li>
                                            </ul>
                                        </li>
                                        <li class="third_column brands">
                                            <ul>
                                                <li><a href="#">
                                                    <img class="menu_brand_logo" src="/assets/public/images/banners/rb.png" alt="">Ray Ban
                                                </a></li>
                                                <li><a href="#">
                                                    <img class="menu_brand_logo" src="/assets/public/images/banners/tb.png" alt="">Title Boxing
                                                </a></li>
                                            </ul>
                                        </li>
                                        <li class="fourth_column brands">
                                            <ul>
                                                <li><a href="#"></a></li>
                                            </ul>
                                        </li>
                                        <li class="fifth_column brands">
                                            <ul>
                                                <li><a href="#"></a></li>
                                            </ul>
                                        </li>
                                        <li class="sixth_column brands">
                                            <ul>
                                                <li>
                                                    <a href="#">
                                                        <img class="menu_img" src="/assets/public/images/banners/womans-1.jpg" alt="">
                                                    </a>
                                                </li>
                                            </ul>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>

<script>
    export default {
        name: 'Header',
    }
</script>