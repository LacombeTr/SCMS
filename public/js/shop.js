const saveCart = (cart) => localStorage.setItem("cart", JSON.stringify(cart));

const getCart = () => {
    try {
        return JSON.parse(localStorage.getItem("cart")) || {};
    } catch (e) {
        return {};
    }
}

const updateCartCount = () => {
    let total = Object.keys(JSON.parse(localStorage.getItem("cart"))).length;
    localStorage.setItem("cartCount", total.toString());
}

const addToCart = (name, id, price) => {
    const cart = getCart();

    if (cart[id]) {
        cart[id].quantity += 1;
    } else {
        cart[id] = {id: id, name: name, price: price, quantity: 1};
    }

    console.log(cart);

    saveCart(cart);
    updateCartCount();
    alert(`Produits "${name}" ajouté au panier !`);
}

const sendCommand = async () => {

    const url = "/stripe";
    try {
        const cart = JSON.parse(localStorage.getItem("cart"));

        const response = await fetch(url, {
            method: "POST", body: JSON.stringify(cart),
        });
        if (!response.ok) {
            throw new Error(`Response status: ${response.status}`);
        }

        window.location.href = response.url;
    } catch (error) {
        console.error(error.message);
    }

}
