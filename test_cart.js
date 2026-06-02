const items = [];
function addToCart(product, variant, quantity) {
    const variantId = variant ? variant.id : null;
    const existingItem = items.find(
        item => item.product_id === product.id && item.variant_id === variantId
    );
    if (existingItem) {
        existingItem.quantity += quantity;
    } else {
        items.push({
            product_id: product.id,
            variant_id: variantId,
            quantity: quantity,
        });
    }
}
addToCart({id: 1}, {id: 2}, 3); // 3 M size
addToCart({id: 1}, {id: 3}, 2); // 2 L size
console.log(items);
