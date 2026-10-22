# API FloraCare — Варіант 18

Базова адреса: http://localhost:8000/api.php. Ресурс: plants. Кодування UTF-8. PHP 8.1+; PDO MySQL та mbstring. API не використовує фреймворків.

## Маршрути
| Метод | URL | Результат |
|---|---|---|
| GET | ?resource=plants | 200, список рослин |
| GET | ?resource=plants&q=Мон | 200, пошук за частиною name |
| GET | ?resource=plants&id=1 | 200, один запис; 404, якщо запису немає |
| POST | ?resource=plants | 201, створений запис; заголовок Location вказує його GET-адресу |
| POST | ?resource=plants&action=water&id=1 | 200, рослина після поливу |

Для поливу id можна передати у JSON/формі замість URL. Якщо ID є в обох місцях, вони мають збігатися. GET із action=water повертає 405; невідома дія — 404. POST створення не приймає id у URL. q використовується тільки для GET-списку. Лічильники стосуються всього каталогу.

## Єдиний формат відповіді
Успіх: {"success":true,"data":...}. Помилка: {"success":false,"error":"Опис помилки"}.

Список повертає масив у data та meta: {"total":3,"needs_water":2}. Для одиничного GET, створення та поливу data — об’єкт рослини:
```json
{"success":true,"data":{"id":1,"name":"Монстера","watering_interval_days":7,"last_watered_at":"2026-10-20","is_succulent":false,"sunlight_needs":"Розсіяне світло","days_since_watered":0,"needs_water":false,"next_watering":"2026-10-27"}}
```
Це приклад структури; ID, дати та обчислені значення залежать від записів і дня запиту. Усі відповіді API мають Content-Type: application/json; charset=utf-8 та Cache-Control: no-store.

## Дані створення
Обов’язкові поля: name (рядок, 1–255 символів), watering_interval_days (ціле число 1–3650), last_watered_at (дійсна дата YYYY-MM-DD від 1000-01-01 до сьогодні). Повідомлення 400 називає некоректне поле.
Необов’язкові: is_succulent (true/false або 1/0), sunlight_needs (1–100 символів). Стандартне освітлення — «Пряме сонце» для сукулента або «Розсіяне світло» для звичайної рослини.
Заради сумісності форм приймаються wateringIntervalDays, lastWatered та isSucculent; канонічні назви мають пріоритет. Канонічні JSON-поля наведено вище.

## curl у Windows
Виконуйте в PowerShell. Підставте порт запущеного сервера та отриманий ID.
```powershell
curl.exe -i "http://localhost:8000/api.php?resource=plants"
curl.exe -i "http://localhost:8000/api.php?resource=plants&id=1"
Set-Content -LiteralPath plant.json -Encoding utf8NoBOM -Value '{"name":"Монстера","watering_interval_days":7,"last_watered_at":"2026-10-01","is_succulent":false}'
curl.exe -i -H "Content-Type: application/json" --data-binary "@plant.json" "http://localhost:8000/api.php?resource=plants"
curl.exe -i -X POST -H "Content-Type: application/json" --data-binary "{}" "http://localhost:8000/api.php?resource=plants&action=water&id=1"
curl.exe -i -X POST -H "Content-Type: application/json" --data-binary "{}" "http://localhost:8000/api.php?resource=plants"
curl.exe -i "http://localhost:8000/api.php?resource=plants&id=999999999"
curl.exe -i -X PUT "http://localhost:8000/api.php?resource=plants"
```
Команда Set-Content з utf8NoBOM потребує PowerShell 7. У старому PowerShell можна створити plant.json редактором як UTF-8 без BOM. POST JSON викликається без сесії та токена; відповідь для порожнього створення — 400 із помилкою поля name.

## Форми та сумісність
Фронтенд з роботи 05 передає FormData і CSRF-токен у заголовку X-CSRF-Token або полі csrf. Для зовнішнього клієнта найпростіше використовувати application/json; для форми потрібні cookie сесії та токен зі сторінки index.php. Міждоменні CORS-запити API не дозволяє; text/plain не приймається як JSON.
Старі адреси api_list.php/api_add.php/api_water.php та add.php/water.php передають запит у api.php і повертають той самий новий формат success/data/error. Список — тільки GET, додавання й полив — тільки POST. Основний інтерфейс користується безпосередньо api.php.

## HTTP-коди
200 — успішний GET або полив; 201 — створено; 400 — неправильні параметри/поля/JSON; 403 — неправильний токен HTML-форми; 404 — невідомий ресурс/дія або відсутній запис; 405 — непідтримуваний метод (є заголовок Allow); 415 — непідтримуваний Content-Type; 500 — помилка сервера/БД. У всіх цих випадках тіло API — JSON узгодженої структури.
