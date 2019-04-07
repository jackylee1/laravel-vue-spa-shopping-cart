<template>
    <div>
        <div class="col-md-3 col-sm-6 col-6">
            <div class="thumb-wrapper">
                <div class="img-box">
                    <a href="item.html">
                        <img src="images/items/t-shirt.png" class="img-responsive img-fluid" alt="">
                    </a>
                </div>
                <div class="thumb-content">
                    <a href="item.html"><h4>Рубашка поло Nike Grey XXL</h4></a>
                    <p class="item-price"><strike>256 грн</strike><span>198 грн</span></p>
                    <div class="add_to_cart">
                        <a href="#" class="btn"><i class="fas fa-shopping-cart"></i>Купить</a>
                        <a href="#" class="hrt"><i class="far fa-heart"></i></a>
                    </div>
                    <div class="quick_view">
                        <a href="" data-toggle="modal" data-target="#quick_view1"><i class="fa fa-search"></i></a>
                    </div>
                </div>
            </div>
            <!-- Modal -->
            <div class="modal fade" id="quick_view1" tabindex="-1" role="dialog" aria-labelledby="quick_view1Title"
                 aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="quick_view1Title">Быстрый просмотр товара</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-5">
                                    <img src="images/items/t-shirt.png" class="img-responsive img-fluid" alt="">
                                </div>
                                <div class="col-7 variations">
                                    <h5>Рубашка поло Nike Grey XXL</h5>
                                    <p>Код товара: 524873</p>
                                    <p class="item-price">Цена: <strike>256 грн.</strike><span>198 грн.</span></p>
                                    <form class="form-horizontal " method="post" action="">
                                        <div class="form-group">
                                            <div class="btn-group btn-group-justified" data-toggle="buttons">
                                                <label class="btn btn-primary active">
                                                    <input type="radio" name="options" id="option1" autocomplete="off" checked>S
                                                </label>
                                                <label class="btn btn-primary">
                                                    <input type="radio" name="options" id="option2" autocomplete="off">M
                                                </label>
                                                <label class="btn btn-primary">
                                                    <input type="radio" name="options" id="option3" autocomplete="off">L
                                                </label>
                                                <label class="btn btn-primary">
                                                    <input type="radio" name="options" id="option4" autocomplete="off">XL
                                                </label>
                                                <label class="btn btn-primary">
                                                    <input type="radio" name="options" id="option5" autocomplete="off">XXL
                                                </label>
                                                <label class="btn btn-primary">
                                                    <input type="radio" name="options" id="option6" autocomplete="off">3XL
                                                </label>
                                                <label class="btn btn-primary">
                                                    <input type="radio" name="options" id="option7" autocomplete="off">4XL
                                                </label>
                                            </div>
                                        </div>
                                    </form>
                                    <div class="add_to_cart">
                                        <a href="#" class="btn"><i class="fas fa-shopping-cart"></i>Купить</a>
                                        <a href="#" class="hrt"><i class="far fa-heart"></i></a>
                                    </div>
                                </div>
                                <div class="col-12 more_details centered">
                                    <a href="item.html">Просмотеть детали...</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'Products'
    }
</script>