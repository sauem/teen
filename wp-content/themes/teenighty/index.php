<?php
get_header(); ?>

    <div class="row">
        <?php query_posts([
            'post_type' => 'product',
            'posts_per_page' => 12
        ]);
        while (have_posts()) : the_post();
            global $product;
            $variants = get_variants();

            ?>
            <div class="col-md-3">
                <div class="item-product">
                    <div class="img">
                        <a href="<?= get_the_permalink()?>">
                            <img src="<?=get_the_post_thumbnail_url()?>">
                        </a>
                    </div>
                    <p><a href="<?= get_the_permalink()?>"><?= get_the_title() ?></a></p>
                    <p><?= get_priceHtml()?></p>
                </div>
            </div>

        <?php endwhile;
        wp_reset_query(); ?>
    </div>
<?php
get_footer();
