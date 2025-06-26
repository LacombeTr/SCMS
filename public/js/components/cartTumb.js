const cartCounter = document.getElementById("cartCount");
cartCounter.innerText = localStorage.getItem("cartCount") || "0";

const goToCart = async () => {

    const url = "/cart";

    const cart = JSON.parse(localStorage.getItem("cart"));
    try {

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
