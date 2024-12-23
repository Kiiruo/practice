<?php
session_start(); // Начинаем сессию

// Проверяем, если пользователь не авторизован, перенаправляем на страницу логина
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

// Ваш существующий код для админ-панели идет ниже
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Админ Панель</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            margin: 0;
            padding: 0;
        }

        h1 {
            text-align: center;
            margin-top: 20px;
            color: #333;
        }

        h2 {
            text-align: center;
            margin-top: 40px;
        }

        .form-container {
            max-width: 500px; /* Устанавливаем фиксированную ширину для формы */
            margin: 20px auto; /* Центрируем форму на странице */
        }

        ul {
            list-style: none;
            padding: 0;
            max-width: 500px;
            margin: 20px auto;
        }

        ul li {
            background: #fff;
            margin: 10px 0;
            padding: 10px;
            border-radius: 5px;
            box-shadow: 0 1px 5px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        ul li button {
            background-color: #007bff;
            color: white;
            cursor: pointer;
        }

        ul li button:hover {
            background-color: #0069d9;
        }

        .custom-file-upload {
            display: inline-block;
            padding: 10px;
            cursor: pointer;
            background: #e7e7e7;
            border-radius: 5px;
            margin-top: 10px;
            width: 100%;
            text-align: center;
        }

        #route_image {
            display: none; /* Скрываем стандартный input */
        }

        .image-preview {
            display: block;
            margin-top: 10px;
            max-width: 100%;
            height: auto;
        }

        .btn-upload {
            display: none; /* Скрываем кнопку загрузки сначала */
        }
    </style>
</head>
<body>
    <h1>Управление Турами</h1>
    <div class="form-container">
        <form id="tour-form" method="POST" action="admin_DB.php" enctype="multipart/form-data">
            <div class="form-group">
                <label for="country_name">Страна:</label>
                <input type="text" class="form-control" id="country_name" name="country_name" required>
            </div>

            <div class="form-group">
                <label for="route_name">Название маршрута:</label>
                <input type="text" class="form-control" id="route_name" name="route_name" required>
            </div>

            <div class="form-group">
                <label for="price_per_passenger">Цена за пассажира:</label>
                <input type="number" class="form-control" id="price_per_passenger" name="price_per_passenger" required>
            </div>

            <div class="form-group">
                <label>Фотография маршрута:</label>
                <label class="custom-file-upload" for="route_image">
                    Выбрать файл
                </label>
                <input type="file" class="form-control" id="route_image" name="route_image" accept="image/*" required onchange="previewImage(event);">
                <img id="image-preview" class="image-preview" src="#" alt="Предпросмотр изображения" style="display:none;">
            </div>

            <input type="hidden" id="route_id" name="route_id">

            <button type="submit" class="btn btn-primary" id="submit-button" style="display:none;">Сохранить</button>
        </form>
    </div>

    <h2>Список Туров</h2>
    <ul id="route-list">
        <?php include 'db/fetch_routes.php'; ?>
    </ul>

    <!-- Модальное окно для редактирования маршрута -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Редактировать маршрут</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="edit-route-form">
                        <div class="form-group">
                            <label for="edit_country_name">Страна:</label>
                            <input type="text" class="form-control" id="edit_country_name" name="country_name" required onchange="document.getElementById('edit_submit_button').style.display='block';">
                        </div>
                        <div class="form-group">
                            <label for="edit_route_name">Название маршрута:</label>
                            <input type="text" class="form-control" id="edit_route_name" name="route_name" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_price_per_passenger">Цена за пассажира:</label>
                            <input type="number" class="form-control" id="edit_price_per_passenger" name="price_per_passenger" required>
                        </div>
                        <div class="form-group">
                            <label>Фотография маршрута:</label>
                            <label class="custom-file-upload" for="edit_route_image">
                                Выбрать файл
                            </label>
                            <input type="file" class="form-control" id="edit_route_image" name="route_image" accept="image/*" required onchange="previewEditImage(event);">
                            <img id="edit_image_preview" class="image-preview" src="#" alt="Предпросмотр изображения" style="display:none;">
                        </div>
                        <input type="hidden" id="edit_route_id" name="route_id">
                        <button type="button" class="btn btn-primary" onclick="updateRoute()" id="edit_submit_button">Сохранить изменения</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('country_name').addEventListener('input', function() {
            document.getElementById('submit-button').style.display = this.value ? 'block' : 'none';
        });

        function previewImage(event) {
            const preview = document.getElementById('image-preview');
            const file = event.target.files[0];
            const reader = new FileReader();

            reader.onload = function() {
                preview.src = reader.result;
                preview.style.display = 'block';
            }

            if (file) {
                reader.readAsDataURL(file);
            }
        }

        function editRoute(routeId, countryName, routeName, price) {
            document.getElementById('edit_route_id').value = routeId;
            document.getElementById('edit_country_name').value = countryName;
            document.getElementById('edit_route_name').value = routeName;
            document.getElementById('edit_price_per_passenger').value = price;
            document.getElementById('edit_image_preview').style.display = 'none'; // Скрыть предыдущий просмотр
            $('#editModal').modal('show'); // Показать модальное окно
        }

        function previewEditImage(event) {
            const preview = document.getElementById('edit_image_preview');
            const file = event.target.files[0];
            const reader = new FileReader();

            reader.onload = function() {
                preview.src = reader.result;
                preview.style.display = 'block';
            }

            if (file) {
                reader.readAsDataURL(file);
            }
        }

        function updateRoute() {
            const routeId = document.getElementById('edit_route_id').value;
            const countryName = document.getElementById('edit_country_name').value;
            const routeName = document.getElementById('edit_route_name').value;
            const price = document.getElementById('edit_price_per_passenger').value;

            const xhr = new XMLHttpRequest();
            xhr.open("POST", "admin_DB.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function () {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        alert("Маршрут успешно обновлен.");
                        location.reload(); // Перезагрузить страницу
                    } else {
                        alert("Ошибка: " + xhr.statusText);
                    }
                }
            };
            xhr.send("route_id=" + routeId + "&country_name=" + encodeURIComponent(countryName) +
                      "&route_name=" + encodeURIComponent(routeName) + "&price_per_passenger=" + price);

            $('#editModal').modal('hide'); // Скрыть модальное окно после обновления
        }

        // Для показа кнопки загрузки в зависимости от ввода страны
        document.getElementById('submit-button').style.display = 'none'; // Скрыть кнопку загрузки по умолчанию
        function deleteRoute(routeId) {
            if (confirm("Вы уверены, что хотите удалить этот маршрут?")) {
                const xhr = new XMLHttpRequest();
                xhr.open("POST", "db/delete_route.php", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.onreadystatechange = function () {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        if (xhr.status === 200) {
                            alert("Маршрут успешно удален.");
                            location.reload(); // Перезагрузить страницу
                        } else {
                            alert("Ошибка: " + xhr.statusText);
                        }
                    }
                };
                xhr.send("route_id=" + routeId);
            }
        }
    </script>
</body>
</html>