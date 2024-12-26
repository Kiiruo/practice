<?php
session_start();
include 'db/connect.php';
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php"); // Перенаправляем на страницу входа
    exit;
}

$callers_sql = "SELECT * FROM callers";
$callers_result = $conn->query($callers_sql);


$routes_sql = "SELECT routes.id, routes.name, routes.price_per_passenger, countries.name AS country_name 
               FROM routes JOIN countries ON routes.country_id = countries.id";
$routes_result = $conn->query($routes_sql);

$bookings_sql = "SELECT bookings.id, bookings.name, bookings.phone, bookings.email, 
                 bookings.arrival_date, bookings.departure_date, bookings.passengers, bookings.total_price, 
                 routes.name AS route_name 
                 FROM bookings JOIN routes ON bookings.route_id = routes.id";
$bookings_result = $conn->query($bookings_sql);
?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <title>Админ Панель</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        .callers-container {
            text-align: center;
            max-width: 300px;
            margin-left: 20px;
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 5px;
            box-shadow: 0 1px 5px rgba(0, 0, 0, 0.1);
            float: right;
        }

        h1 {
            text-align: center;
        }

        h2 {
            margin-top: 40px;
        }

        .list-group-item {
            justify-content: space-between;
            align-items: center;
            padding: 10px;
        }

        .action-buttons {
            display: flex;
            gap: 5px;
        }

        .main-content {
            overflow: hidden;
        }

        #list-group-item {
            display: flex;
            justify-content: space-between;
        }
    </style>
</head>

<body>
    <h1>Управление Турами</h1>

    <div class="container">
        <div class="row main-content">

            <div class="col-md-8">
                <h2>Список Туров</h2>
                <ul class="list-group" id="route-list">
                    <?php
                    if ($routes_result && $routes_result->num_rows > 0) {
                        while ($route = $routes_result->fetch_assoc()) {
                            echo "<li class='list-group-item' id='list-group-item'>";
                            echo "<div>";
                            echo "<strong>{$route['name']}</strong> - {$route['country_name']} - {$route['price_per_passenger']} руб.";
                            echo "</div>";
                            echo "<div class='action-buttons'>";
                            echo "<button class='btn btn-info btn-sm' onclick='editRoute({$route['id']}, \"{$route['country_name']}\", \"{$route['name']}\", {$route['price_per_passenger']});'>Редактировать</button>";
                            echo "<button class='btn btn-danger btn-sm' onclick='deleteRoute({$route['id']});'>Удалить</button>";
                            echo "</div>";
                            echo "</li>";
                        }
                    } else {
                        echo "<li class='list-group-item'>Нет маршрутов.</li>";
                    }
                    ?>
                </ul>
                <h2>Добавить маршрут</h2>
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
                        <input type="number" class="form-control" id="price_per_passenger" name="price_per_passenger"
                            required>
                    </div>

                    <div class="form-group">
                        <label>Фотография маршрута:</label>
                        <input type="file" class="form-control" id="route_image" name="route_image" accept="image/*">
                    </div>

                    <input type="hidden" id="route_id" name="route_id">

                    <button type="submit" class="btn btn-primary">Сохранить</button>
                </form>
            </div>

            <div class="callers-container">
                <h3>Обратные звонки</h3>
                <ul class="list-group" id="call">
                    <?php if ($callers_result && $callers_result->num_rows > 0): ?>
                        <?php while ($caller = $callers_result->fetch_assoc()): ?>
                            <li class="list-group-item">
                                <strong>
                                    <?php echo htmlspecialchars($caller['name']); ?>
                                </strong><br>
                                Телефон:
                                <?php echo htmlspecialchars($caller['telephone']); ?>
                            </li>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <li class="list-group-item">Нет запросов на обратный звонок.</li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>
    <h2 style="text-align:center">Бронирования</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Имя</th>
                <th>Телефон</th>
                <th>Email</th>
                <th>Маршрут</th>
                <th>Дата прибытия</th>
                <th>Дата отъезда</th>
                <th>Пассажиры</th>
                <th>Итоговая цена</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($bookings_result && $bookings_result->num_rows > 0): ?>
                <?php while ($booking = $bookings_result->fetch_assoc()): ?>
                    <tr>
                        <td>
                            <?php echo htmlspecialchars($booking['id']); ?>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($booking['name']); ?>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($booking['phone']); ?>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($booking['email']); ?>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($booking['route_name']); ?>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($booking['arrival_date']); ?>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($booking['departure_date']); ?>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($booking['passengers']); ?>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($booking['total_price']); ?> руб.
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="9">Нет броней.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel"
        aria-hidden="true">
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
                            <input type="text" class="form-control" id="edit_country_name" name="country_name" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_route_name">Название маршрута:</label>
                            <input type="text" class="form-control" id="edit_route_name" name="route_name" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_price_per_passenger">Цена за пассажира:</label>
                            <input type="number" class="form-control" id="edit_price_per_passenger"
                                name="price_per_passenger" required>
                        </div>
                        <input type="hidden" id="edit_route_id" name="route_id">
                        <button type="button" class="btn btn-primary" onclick="updateRoute()">Сохранить
                            изменения</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function editRoute(routeId, countryName, routeName, price) {
            document.getElementById('edit_route_id').value = routeId;
            document.getElementById('edit_country_name').value = countryName;
            document.getElementById('edit_route_name').value = routeName;
            document.getElementById('edit_price_per_passenger').value = price;
            $('#editModal').modal('show');
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
                    alert(xhr.responseText);
                    location.reload();
                }
            };
            xhr.send("route_id=" + routeId + "&country_name=" + encodeURIComponent(countryName) +
                "&route_name=" + encodeURIComponent(routeName) + "&price_per_passenger=" + price);
            $('#editModal').modal('hide');
        }

        function deleteRoute(routeId) {
            if (confirm("Вы уверены, что хотите удалить этот маршрут?")) {
                const xhr = new XMLHttpRequest();
                xhr.open("POST", "db/delete_route.php", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.onreadystatechange = function () {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        alert(xhr.responseText);
                        location.reload();
                    }
                };
                xhr.send("route_id=" + routeId);
            }
        }
    </script>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>

<?php
$conn->close();
?>