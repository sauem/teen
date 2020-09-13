Handlebars.registerHelper("active", function (key, index = null) {

    if (key === 0 || key === "0") {
        return "active";
    }
    return "";
})
Handlebars.registerHelper("colorActive", function (key) {
    return PRODUCT.THUMBS.settings.colors[key].colorInfos
})

Handlebars.registerHelper("sizeActive", function (key) {
    return PRODUCT.THUMBS.settings.colors[key].sizeInfos
})

async function loadProduct(product_id) {
    return await $.ajax({
        url: '/wp-admin/admin-ajax.php',
        type: 'GET',
        data: {product_id: product_id, action: 'single_product'},
        cache: false
    });
}
