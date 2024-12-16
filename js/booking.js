document.getElementById('country').addEventListener('change', function() {
    const countryId = this.value;

    if (countryId) {
        fetch(`db/get_routes.php?country_id=${countryId}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Сеть не в порядке; статус: ' + response.status);
                }
                return response.json();
            })
            .then(data => {
                const routeSelect = document.getElementById('route');
                routeSelect.innerHTML = '<option value="">Выберите маршрут</option>';

                if (data.routes && data.routes.length > 0) {
                    data.routes.forEach(route => {
                        const option = document.createElement('option');
                        option.value = route.id;
                        option.dataset.price = route.price_per_passenger; // Сохраните цену маршрута в атрибуте data
                        option.textContent = route.name;
                        routeSelect.appendChild(option);
                    });
                } else {
                    const option = document.createElement('option');
                    option.value = '';
                    option.textContent = 'Нет доступных маршрутов';
                    routeSelect.appendChild(option);
                }
            })
            .catch(error => {
                console.error('Ошибка при загрузке маршрутов:', error);
            });
    } else {
        const routeSelect = document.getElementById('route');
        routeSelect.innerHTML = '<option value="">Выберите маршрут</option>';
    }
});

// Обработка изменения маршрута
document.getElementById('route').addEventListener('change', function() {
    calculateTotalPrice();
});

// Обработка изменения количества пассажиров
document.getElementById('passengers').addEventListener('input', function() {
    calculateTotalPrice();
});

function calculateTotalPrice() {
    const routeSelect = document.getElementById('route');
    const priceInput = document.getElementById('price');
    const passengersInput = document.getElementById('passengers');

    // Получаем цену за пассажира
    const selectedOption = routeSelect.options[routeSelect.selectedIndex];
    const pricePerPassenger = selectedOption ? parseFloat(selectedOption.dataset.price) : 0;

    // Получаем количество пассажиров
    const passengers = parseInt(passengersInput.value) || 0;

    // Рассчитываем общую стоимость
    const totalPrice = pricePerPassenger * passengers;

    // Обновляем поле стоимости
    priceInput.value = totalPrice.toFixed(2); // Ограничиваем до 2 знаков после запятой
}