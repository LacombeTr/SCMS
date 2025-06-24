const saveCart = (cart) => localStorage.setItem("cart", JSON.stringify(cart));

const getCart = () => {
    try {
        return JSON.parse(localStorage.getItem("cart")) || [];
    } catch (e) {
        return [];
    }
}

const updateCartCount = () => {
    let total = JSON.parse(localStorage.getItem("cart"));
    localStorage.setItem("cartCount", total.length.toString());
    console.log(total)
}

const addToCart = (name, id) => {
    const cart = getCart();
    cart.push({id: id, name: name});
    saveCart(cart);
    updateCartCount();
    alert(`Produit "${name}" ajouté au panier !`);
}
