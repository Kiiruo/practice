<?php
include('db/connect.php');
$countries_sql = "SELECT id, name FROM countries";
$countries_result = $conn->query($countries_sql);
function findImageFile($countryName)
{
    $imageDir = 'img/'; // Путь к папке с изображениями
    $formats = ['jpg', 'jpeg', 'png', 'webp'];

    // Проверка существования изображения в разных форматах без транслитерации
    foreach ($formats as $format) {
        $filePath = "{$imageDir}{$countryName}.{$format}"; // Используем русское название
        if (file_exists($filePath)) {
            return $filePath; // Возвращаем путь к существующему файлу
        }
    }

    return "images/default.jpg"; // Изображение по умолчанию
}
?>
<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Альбатрос</title>
    <link rel="stylesheet" href="styles/style.css">
    <link rel="shortcut icon" href="img/icon.png">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0&icon_names=arrow_forward" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
    <script defer src="js/main.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.js"></script>
    <script src="https://malsup.github.io/jquery.form.js"></script>
    <script>
        // wait for the DOM to be loaded 
        $(document).ready(function () {
            // bind 'myForm' and provide a simple callback function 
            $('#call-form').ajaxForm(function () {
                alert("Спасибо! В скорем времени с вами свяжутся наши агенты");
            });
        });
    </script>
</head>

<body>
    <header id="header" class="header">
        <div class="container header__container">
            <a href="#" class="logo"> <img class="logo__img" src="img/logo.svg" alt="Логотип"> </a>
            <nav class="menu">
                <ul class="menu__list">
                    <li class="menu__item">
                        <p class="menu__number">+7 (904) 835-13-80</p>
                        <button id="open-modal-btn" class="menu__btn" href=""> Заказать обратный звонок </button>
                        <div class="modal" id="call-modal">
                            <div class="modal-box">
                                <button id="close-call-modal-btn"> <img src="icons/close.png" alt=""></button>
                                <h3>Спасибо, что решили выбрать нас!</h3>
                                <p>Пожалуйста, введите ваше имя и номер телефона, чтобы мы могли созвониться с вами</p>
                                <form action="db/call.php" method="post" class="call-form" id="call-form">
                                    <input type="text" name="name" class="name" placeholder="Имя" required
                                        maxlength="30" pattern="^[А-Яа-яЁё]+$"
                                        oninvalid="setCustomValidity('Введите имя на русском языке')">
                                    <input type="tel" name="tel" class="tel" placeholder="Номер телефона" required
                                        minlength="11" maxlength="11" pattern="^8\d{10}$">
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
    <main>
        <section class="attention">
            <div class="attention-text"></div>
            <h3 class="attention-title">Самые лучшие курорты только у</h3>
            <h1 class="attention-name"> <img class="flag1" src="img/flag.png" alt="">АЛЬБАТРОС<img class="flag2"
                    src="img/flag.png" alt=""></h1>
            <div class="attention-info">
                <p>Быстрый подбор подходящего для вас тура БЕСПЛАТНО</p>
                <a href="booking.php"><button class="attention-btn">ПОДОБРАТЬ ТУР</button></a>
            </div>
            <script src="js/bg.js"></script>
        </section>
        <section class="container">
            <div class="tour-company-card">
                <div class="card-content">
                    <h1>Туристическое агенство "Альбатрос"</h1>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Voluptatem dolore maxime laboriosam
                        doloribus quos ex. Dolores aut eius dicta numquam tempore iste ad, id saepe.</p>
                    <h2>Почему выбирают нас:</h2>
                    <ol>
                        <li>Опытные специалисты с многолетним стажем.</li>
                        <li>Индивидуальный подход к каждому клиенту.</li>
                        <li>Гарантия низких цен и высокого качества услуг.</li>
                        <li>Доступ ко всем актуальным турпакетам и предложениям.</li>
                    </ol>
                    <a href="about.html" class="card-button-us">Узнать больше</a>
                </div>
            </div>
        </section>
        <section class="container services">
            <h1 class="heading-title">Мы предлагаем</h1>
            <div class="box-container">
                <div class="box-service">
                    <img src="icons/vacation.png" alt="">
                    <h2>Лучшие курорты</h2>
                </div>
                <div class="box-service">
                    <img src="icons/hotel.png" alt="">
                    <h2>Лучшие отели</h2>
                </div>
                <div class="box-service">
                    <img src="icons/memories.png" alt="">
                    <h2>Незабываемые воспоминания</h2>
                </div>
                <div class="box-service">
                    <img src="icons/price.png" alt="">
                    <h2>Низкие цены</h2>
                </div>
            </div>
            <img src="img/logo_line.png" class="line">
        </section>
        <section class="container swiper">
            <div class="card-wrapper">
                <h2 class="card-info">Самые популярные туры</h2>
                <ul class="card-list swiper-wrapper">
                    <?php
                    if ($countries_result->num_rows > 0) {
                        while ($country_row = $countries_result->fetch_assoc()) {
                            $country_id = $country_row['id'];
                            $country_name = $country_row['name'];
                            $image_path = findImageFile($country_name);
                            $routes_sql = "SELECT id, name, price_per_passenger FROM routes WHERE country_id = $country_id";
                            $routes_result = $conn->query($routes_sql);
                            ?>
                            <li class="card-item swiper-slide">
                                <a class="card-link">
                                    <img src="<?php echo $image_path; ?>" alt="<?php echo $country_name; ?>" class="card-image">
                                    <p class="badge <?php echo strtolower($country_name); ?>">
                                        <?php echo $country_name; ?>
                                    </p>
                                    <h2 class="card-title">Очень важная информация</h2>
                                    <select class="route-select" onchange="updatePrice(this)">
                                        <option value="">Выберите маршрут</option>
                                        <?php
                                        if ($routes_result->num_rows > 0) {
                                            while ($route_row = $routes_result->fetch_assoc()) {
                                                $route_id = $route_row['id'];
                                                $route_name = $route_row['name'];
                                                $route_price = $route_row['price_per_passenger'];
                                                echo "<option value='$route_id' data-price='$route_price'>$route_name - $route_price₽</option>"; // В валюте "₽"
                                            }
                                        }
                                        ?>
                                    </select>
                                    <input type="number" min="1" class="passenger-count styled-input" value="1"
                                        onchange="updatePrice(this)">
                                    <p>Цена: <span class="price">0₽</span></p>
                                    <button class="card-button" onclick="submitBookingForm('');">Забронировать!</button>
                                </a>
                            </li>
                            <?php
                        }
                    } else {
                        echo "<li>Нет доступных туров</li>";
                    }
                    $conn->close();
                    ?>
                </ul>
                <div class="swiper-pagination"></div>
                <div class="swiper-slide-button swiper-button-prev"></div>
                <div class="swiper-slide-button swiper-button-next"></div>
                <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
                <script src="js/swiper.js"></script>
                <script>
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
                </script>
                <script>
                    function submitBookingForm(countryId, routeId) {
                        window.location.href = 'booking.php';
                    }
                </script>
            </div>
        </section>
    </main>
    <footer class="footer">
        <div class="footer-container">
            <div class="footer-section">
                <h3>Ссылки</h3>
                <ul>
                    <li><a href="about.html">О нас</a></li>
                    <li><a href="booking.php">Подбор тура</a></li>
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