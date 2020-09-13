<?php
get_header();
$category = get_categories();
$cat = $category[0]->term_id;
?>

<div id="result-product" class="mt-5 mb-4">

</div>
<div class="row">
    <div class="col-md-7">
        <?php query_posts([
            'order' => 'DESC',
            'orderby' => 'date',
            'cat' => $cat,
            'post_type' => 'product',
            'posts_per_page' => 6
        ]);
        if (have_posts()) : while (have_posts()) : the_post();
            ?>
            <h3>Related porudcts</h3>
            <div class="item-product">
                <div class="img">
                    <a href="<?= get_the_permalink() ?>">
                        <img src="<?= get_the_post_thumbnail_url() ?>">
                    </a>
                </div>
                <p><a href="<?= get_the_permalink() ?>"><?= get_the_title() ?></a></p>
            </div>
        <?php
        endwhile; endif;
        wp_reset_query();
        ?>
    </div>
    <div class="col-md-5">
        <hr>
        <h3>Campaign Details</h3>
        <?= get_the_content() ?>
    </div>
</div>
<?php
get_footer();
?>
<script id="product-template" type="text/x-handlebars-template">
    <div class="row">
        <div class="col-md-7">
            <div class="row">
                <div class="col-md-3 left-thumbs">
                    {{#each VIEW_THUMBS}}
                    <div class="item-left">
                        <img src="{{url}}" class="img-fluid"/>
                    </div>
                    {{/each}}
                </div>
                <div class="col-md-9">
                    <a class="luminous" href="{{VIEW_THUMBS.0.url}}?w=1600">
                        <img width="100%" src="{{VIEW_THUMBS.0.url}}" class="img-fluid luminous big-image"/>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-5 single-info">
            <h3><?= get_the_title() ?> {{THUMB_ACTIVE.sku}}</h3>
            <div class="price">
                <ins>${{THUMB_ACTIVE.sale}}</ins>
                <del>${{THUMB_ACTIVE.price}}</del>
            </div>
            <div class="list-parent mt-4">
                <p><strong>Products:</strong> {{thumb_select}}</p>
                <div class="products">
                    {{#each THUMBS.thumbs}}
                    <a href="javascript:;">
                        <div class="item-select {{active ACTIVE.thumb}}" data-key="{{@index}}">
                            <img src="{{this}}" width="150" class="img-fluid"/>
                        </div>
                    </a>
                    {{/each}}
                </div>
                <p class="mt-3"><strong>Colors:</strong> {{color_select}}</p>
                <div class="colors">
                    {{#each (colorActive ACTIVE.thumb)}}
                    <a href="javascript:;">
                        <div data-color="{{slug}}" data-key="{{@index}}" style="background: {{slug}}"
                             class="color-select {{active @index}}">

                        </div>
                    </a>
                    {{/each}}
                </div>
                <p class="mt-3"><strong>Size:</strong> {{size_select}}</p>
                <div class="sizes">
                    {{#each (sizeActive ACTIVE.thumb)}}
                    <a href="javascript:;">
                        <div data-size="{{slug}}" data-key="{{@index}}" class="size-select {{active @index}}">
                            {{name}}
                        </div>
                    </a>
                    {{/each}}
                </div>
                <div class="clear-fix"></div>
                <div class="qty">
                    <p class="mt-3"><strong>Qty:</strong></p>
                    <div class="d-flex align-items-center">
                        <input style="width: 100px;" class="form-control" name="qty" type="number" value="1"/>
                        <div id="clock" class="clock">
                            <span class="ml-3"><i class="fa fa-clock-o"></i></span>
                            <span class="mr-2">
                               <span class="hours"></span>
                               <span class="minutes"></span>
                               <span class="seconds"></span>
                           </span>
                            <span class="text-muted"> left to buy</span>
                        </div>
                    </div>
                </div>
                <button class="btn btn-info w-75 mt-5"><i class="fa fa-shopping-cart"></i> Buy It Now</button>
            </div>
        </div>
    </div>
</script>
<script src="<?= ASSET ?>js/product.js?v=<?= time() ?>"></script>

<script>
    "use strict";

    $(document).ready(function () {
        _renderTimeLeft();

        function _renderTimeLeft() {
            setTimeout(() => {
                let timeNow = (new Date).getTime();
                let day = 24 * 60 * 60 * 1000;
                let end = timeNow - day;
                let deadline = new Date(Date.parse(new Date()) + end);
                initializeClock('clock', deadline);
            }, 2000);
        }

        window.PRODUCT = {
            THUMBS: [],
            BIG_THUMB: null,
            VIEW_THUMBS: [],
            THUMB_ACTIVE: {
                sku: null,
                price: 20.43,
                sale: 10.25,

            },
            ACTIVE: {
                thumb: 0,
                size: 0,
                color: 0,
            }
        }
        try {
            loadProduct("<?= get_the_ID()?>").then(res => {
                PRODUCT.THUMBS = res.data;

                let thumbs = PRODUCT.THUMBS.settings.colors[PRODUCT.ACTIVE.thumb];
                let thumb_view = thumbs.colorThumbs[Object.keys(thumbs.colorThumbs)[0]];
                let thumb_sku = thumbs.sku;
                let thumb_price = thumbs.price;
                let thumb_sale = thumbs.sale;
                _initInfo({
                    sku: thumb_sku,
                    price: thumb_price,
                    sale: thumb_sale
                })
                PRODUCT.VIEW_THUMBS = thumb_view;
                renderTemplate();
                console.log(PRODUCT)
            })
        } catch (e) {
            console.log(e)
        }

        function woo_complieTemplate(idTemplate, data) {
            let html = $("#" + idTemplate).html();
            let template = Handlebars.compile(html);
            return template(data);
        }

        $(document).on("click", ".item-select", function () {
            resetSelectActive('.item-select');
            $(this).addClass("active");
            let _key = $(this).data("key");
            PRODUCT.ACTIVE.thumb = _key;

            let thumbs = PRODUCT.THUMBS.settings.colors[_key];
            let thumb_view = thumbs.colorThumbs[Object.keys(thumbs.colorThumbs)[0]];

            let thumb_sku = thumbs.sku;
            let thumb_price = thumbs.price;
            let thumb_sale = thumbs.sale;
            PRODUCT.VIEW_THUMBS = thumb_view;
            _initInfo({
                sku: thumb_sku,
                price: thumb_price,
                sale: thumb_sale
            });
            renderTemplate(PRODUCT);
        });
        $("body").on("click", ".color-select", function () {
            let _color = $(this).data("color");
            PRODUCT.ACTIVE.color = _color;
            PRODUCT.VIEW_THUMBS = PRODUCT.THUMBS.settings.colors[PRODUCT.ACTIVE.thumb].colorThumbs[_color];
            resetSelectActive('.color-select');
            $(this).addClass("active");
            renderTemplate();
        });
        $("body").on("click", ".size-select", function () {
            let _key = $(this).data("key");
            let _size = $(this).data("size");
            resetSelectActive('.size-select');
            $(this).addClass("active");
            PRODUCT.ACTIVE.size = _size;

        });

        function resetSelectActive(el) {
            $(el).removeClass("active");
        }


        function _initInfo(data) {
            PRODUCT.THUMB_ACTIVE = {
                sku: data.sku,
                price: data.price,
                sale: data.sale
            }
        }

        $("body").on("mouseover", ".item-left", function () {
            let image_url = $(this).find("img").attr("src");
            PRODUCT.BIG_THUMB = $(".big-image").attr("src");
            $(".big-image").attr("src", image_url);

        });
        $("body").on("mouseout", ".item-left", function () {
            $(".big-image").attr("src", PRODUCT.BIG_THUMB);
        });
        $("body").on("click", ".item-left", function () {
            let image_url = $(this).find("img").attr("src");
            PRODUCT.BIG_THUMB = image_url;
            $(".big-image").attr("src", image_url);
            $(".luminous").attr("href", image_url + "?w=1600");
            resetSelectActive(".item-left");
            $(this).addClass("active");
        });

        function renderTemplate(data = window.PRODUCT) {
            $("#result-product").html(woo_complieTemplate("product-template", data));
            _renderTimeLeft();
        }
    });
</script>
