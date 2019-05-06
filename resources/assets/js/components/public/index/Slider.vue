<template>
    <div>
        <section v-if="sliders.length" class="slider">
            <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                <ol class="carousel-indicators">
                    <template v-for="(item, index) in sliders">
                        <li data-target="#carouselExampleIndicators"
                            :data-slide-to="index"
                            :class="(index === 0) ? 'active' : ''">
                        </li>
                    </template>
                </ol>
                <div class="carousel-inner" role="listbox">
                    <template v-for="(item, index) in sliders">
                        <div :class="(index === 0) ? 'carousel-item active' : 'carousel-item'"
                             :style="'background-image: url(/app/public/images/slider/'+item.image_origin+')'">
                            <div class="carousel-caption d-none d-md-block">
                                <h2>{{item.title}}</h2>
                                <p v-html="item.description"></p>
                                <a v-if="item.url !== null"
                                   href="javascript:void(0)"
                                   @click="openLinkSlider(item.url)"
                                   class="more_info">
                                    Подробнее
                                </a>
                            </div>
                        </div>
                    </template>
                </div>
                <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>
            </div>
        </section>
    </div>
</template>

<script>
    export default {
        name: 'Slider',
        props: ['sliders'],
        methods: {
            openLinkSlider: function (url) {
                let domain = location.protocol+'//'+location.hostname;
                if (url.indexOf(domain) !== -1) {
                    url = url.replace(domain, '');

                    this.$router.push(url);

                    return true;
                }

                window.location.href = url;
            }
        }
    }
</script>