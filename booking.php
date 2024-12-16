<?php
include 'db/connect.php'; // Подключение к базе данных

$countriesResult = mysqli_query($conn, "SELECT id, name FROM countries");
$countries = [];

while ($row = mysqli_fetch_assoc($countriesResult)) {
    $countries[] = $row;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $country = $_POST['country'];
    $route = $_POST['route'];
    $arrival = $_POST['arrival'];
    $departure = $_POST['departure'];
    $passengers = (int)$_POST['passengers'];

    // Получение цены за пассажира для выбранного маршрута
    $stmt = mysqli_prepare($conn, "SELECT price_per_passenger FROM routes WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $route);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $routeData = mysqli_fetch_assoc($result);

    if ($routeData) {
        $pricePerPassenger = $routeData['price_per_passenger'];
        $totalPrice = $pricePerPassenger * $passengers;
        $stmt = mysqli_prepare($conn, "INSERT INTO bookings (name, phone, email, country_id, route_id, arrival_date, departure_date, passengers, total_price) VALUES ('$name','$phone','$email','$country','$route','$arrival','$departure','$passengers','$totalPrice')");
        mysqli_stmt_execute($stmt);

        echo "<h2 style ='text-align:center;'>Ваш заказ успешно оформлен!</h2>";
    }
};
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/style.css">
    <link rel="shortcut icon" href="img/icon.png">
    <title>Бронирование тура</title>
    <script defer src="js/main.js"></script>

</head>
<body class="body-booking">
<header id="header" class="header">
        <div class="container header__container">
            <a href="#" class="logo">
                <img class="logo__img" src="img/logo.svg" alt="Логотип">
            </a>
            <nav class="menu">
                <ul class="menu__list">
                    <li class="menu__item">
                        <p class="menu__number">+7 (904) 835-13-80</p>
                        <button id="open-modal-btn" class="menu__btn" href="">
                            Заказать обратный звонок
                        </button>
                        <div class="modal" id="call-modal">
                            <div class="modal-box">
                                <button id="close-call-modal-btn"> <img src="icons/close.png" alt=""></button>
                                <h3>Спасибо, что решили выбрать нас!</h3>
                                <p>Пожалуйста, введите ваше имя и номер телефона, чтобы мы могли созвониться с вами</p>
                                    <form action="db/call.php" method="post" class="call-form" id="call-form">
                                        <input type="text" name="name" class="name" placeholder="Имя" required maxlength="30" pattern="^[А-Яа-яЁё]+$"   oninvalid="setCustomValidity('Введите имя на русском языке')">
                                        <input type="tel" name="tel" class="tel" placeholder="Номер телефона" required minlength="11" maxlength="11" pattern="^8\d{10}$">
                                        <input type="submit" name="submit" class="btn-submit">
                                </form>
                            </div>
                        </div>
                        <script defer src="js/call.js"></script>
                    </li>
                </ul>
            </nav>
        </div>
    </header>
    <section class="container booking">
    <h1>Бронирование тура</h1>
    <form id="bookingForm" method="POST">
        <div class="flex">
            <div class="inputBox">
        <label for="name">Имя:</label>
        <input type="text" id="name" name="name" required placeholder ="Введите ваше имя" maxlength="30" pattern="^[А-Яа-яЁё]+$"   oninvalid="setCustomValidity('Введите имя на русском языке')">
        </div>
        <div class="inputBox">
        <label for="phone">Номер телефона:</label>
        <input type="tel" id="phone" name="phone" required placeholder ="Введите ваш номер телефона" required minlength="11" maxlength="11" pattern="^8\d{10}$" >
        </div>
        <div class="inputBox">
        <label for="email">Почта:</label>
        <input type="email" id="email" name="email" required placeholder ="Введите вашу эл. почту" maxlength='50'>
        </div>
        <div class="inputBox">
        <label for="country">Куда вы хотите отправиться:</label>
        <select id="country" name="country" required>
            <option value="">Выберите страну</option>
            <?php foreach ($countries as $country): ?>
                <option value="<?= htmlspecialchars($country['id']) ?>"><?= htmlspecialchars($country['name']) ?></option>
            <?php endforeach; ?>
        </select>
        </div>
        <div class="inputBox">
        <label for="route">Выбор маршрута:</label>
        <select id="route" name="route" required>
            <option value="">Выберите маршрут</option>
        </select>
        </div>
        <div class="inputBox">
        <label for="arrival">Дата прибытия:</label>
        <input type="date" id="arrival" name="arrival" required placeholder ="Выберите дату прибытия">
        </div>
        <div class="inputBox">
        <label for="departure">Дата отбытия:</label>
        <input type="date" id="departure" name="departure" required placeholder ="Выберите дату отбытия">
        </div>
        <div class="inputBox">
        <label for="passengers">Количество пассажиров:</label>
        <input type="number" id="passengers" name="passengers" min="1" required placeholder ="Введите число пасажиров">
        </div>
        <div class="inputBox">
        <label for="price">Стоимость:</label>
        <input type="text" id="price" name="price" readonly>
        </div>
        <div class="inputBox">
        <button type="submit">Забронировать</button>
        </div>
        </div>
    </form>

    <script src="js/booking.js"></script>
    </section>
    <footer class="footer">
        <div class="footer-container">
            <div class="footer-section">
                <h3>Ссылки</h3>
                <ul>
                    <li><a href="index.html">Главная</a></li>
                    <li><a href="about.html">О нас</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Контакты</h3>
                <p>Телефон: +7 (904) 835-13-80</p>
                <p>Email: <a href="mailto:info@touragency.com">albatros@mail.ru</a></p>
            </div>
            <div class="footer-section">
                <h3>Социальные сети</h3>
                <a href="#"><img src="icons/telegram.png" alt="Telegram"></a>
                <a href="#"><img src="icons/vk.png" alt="VK"></a>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2024 Альбатрос. Все права защищены.</p>
        </div>
    </footer>
</body>
</html>
