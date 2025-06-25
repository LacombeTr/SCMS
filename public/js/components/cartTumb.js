const cartCounter = document.getElementById("cartCount");
cartCounter.innerText = localStorage.getItem("cartCount") || "0";
