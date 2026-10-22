# FloraCare — Практична робота 06 — Варіант 18

## Запуск

C:\php\php.exe -d extension_dir=C:\php\ext -d extension=pdo_mysql -d extension=mbstring -S localhost:8000

## Структура
- api.php — єдина точка входу й маршрутизація.
- api_common.php — єдиний формат JSON, читання параметрів і тіла.
- API.md — контракт і готові команди curl.
- classes/Garden.php — підготовлені запити до БД; Plant і Succulent — логіка рослин.
- script.js — наявний AJAX-інтерфейс, переведений на api.php.
- bootstrap.php та lib/functions.php — час, захист форм і перевірка даних.

Стиль, зображення, сортування, редагування, видалення і фільтр поливу збережено з роботи 05. У JSON API неправильні дані мають код 400 за умовою роботи 06; класична форма редагування зберігає код 422.