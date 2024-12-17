function openModal(button) {
    const cardItem = button.closest('.card-item');
    const routeSelect = cardItem.querySelector('.route-select');
    const selectedRouteId = routeSelect.value;
    const countryId = cardItem.getAttribute('data-country-id');

    document.getElementById('modal-country-id').value = countryId;
    document.getElementById('modal-route-id').value = selectedRouteId;
    document.getElementById('requestModal').style.display = "block";
}