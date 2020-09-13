<?php
define("ASSET", get_template_directory_uri() . "/assets/");
add_theme_support("woocommerce");
show_admin_bar(false);


function get_variants()
{
    return json_decode(get_post_meta(get_the_ID(), "settings", TRUE), JSON_UNESCAPED_UNICODE);
}

function get_priceHtml()
{
    $variants = get_variants();
    $price  = $variants["settings"]["colors"][0]['price'];
    $sale   = $variants["settings"]["colors"][0]['sale'];
    $htm = "";
    if (empty($variant)) {
        return null;
    }
    foreach ($variants as $k => $variant) {
        if ($variant['price'] < $price && $sale <= $variant['sale']) {
            $price = $variant['price'];
            $sale = $variant['sale'];
        }
    }
    if ($sale > 0) {
        $htm .= "<p class='price'>";
        $htm .= "<ins><span class=\"woocommerce-Price-amount amount\"><bdi><span class=\"woocommerce-Price-currencySymbol\">$</span>{$sale}</bdi></span></ins>";
        $htm .= "<del><span class=\"woocommerce-Price-amount amount\"><bdi><span class=\"woocommerce-Price-currencySymbol\">$</span>{$price}</bdi></span></del>";
        $htm .= "<p>";
    } else {
        $htm .= "<p class='price'>";
        $htm .= "<ins><span class=\"woocommerce-Price-amount amount\"><bdi><span class=\"woocommerce-Price-currencySymbol\">$</span>{$price}</bdi></span></ins>";
        $htm .= "<p>";
    }
    return $htm;
}

function _printf($val)
{
    echo "<pre>";
    var_dump($val);
    echo "</pre>";
    wp_die();
}