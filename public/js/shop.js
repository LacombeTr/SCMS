const saveCart = (cart) => localStorage.setItem("cart", JSON.stringify(cart));

const getCart = () => {
    try {
        return JSON.parse(localStorage.getItem("cart")) || {};
    } catch (e) {
        return {};
    }
}

const updateCartCount = () => {
    const cart = getCart();
    let total = 0;

    for (const [key, value] of Object.entries(cart)) {
        total += value.quantity;
    }

    const cartCounter = document.getElementById("cartCount");
    cartCounter.innerText = total.toString() || "";

    localStorage.setItem("cartCount", total.toString());
}

const addToCart = (name, id, price) => {
    const cart = getCart();

    if (cart[id]) {
        cart[id].quantity += 1;
    } else {
        cart[id] = {id: id, name: name, price: price, quantity: 1};
    }

    saveCart(cart);
    updateCartCount();
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

const displayCartList = () => {
    const cartList = document.getElementById(`cartList`)
    cartList.classList.contains((`hideElement`))
        ? cartList.classList.remove(`hideElement`)
        : cartList.classList.add(`hideElement`)
}
