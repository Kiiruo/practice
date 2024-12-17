function updatePrice(element) {
    const cardItem = element.closest('.card-item');
    const routeSelect = cardItem.querySelector('.route-select');
    const passengerCount = cardItem.querySelector('.passenger-count');
    const priceDisplay = cardItem.querySelector('.price');

    const routePrice = parseFloat(routeSelect.selectedOptions[0].dataset.price) || 0;
    const count = parseInt(passengerCount.value);
    const totalPrice = routePrice * count;

    priceDisplay.innerText = `${totalPrice}₽`;
}