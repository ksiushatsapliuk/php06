# FloraCare — Практична робота 06 — Варіант 18

## Запуск
1. Увімкніть MySQL. Перевірте config.php (порт 3307, база practicum6).
2. Імпортуйте schema.sql. Для перенесення записів із роботи 05 виконайте один раз у порожню таблицю: INSERT INTO practicum6.plants SELECT * FROM practicum5.plants;
3. У папці php06 запустіть:
   C:\php\php.exe -d extension_dir=C:\php\ext -d extension=pdo_mysql -d extension=mbstring -S localhost:8000
4. Відкрийте http://localhost:8000/index.php.

## Структура
- api.php — єдина точка входу й маршрутизація.
- api_common.php — єдиний формат JSON, читання параметрів і тіла.
- API.md — контракт і готові команди curl.
- classes/Garden.php — підготовлені запити до БД; Plant і Succulent — логіка рослин.
- script.js — наявний AJAX-інтерфейс, переведений на api.php.
- bootstrap.php та lib/functions.php — час, захист форм і перевірка даних.
- api_list.php, api_add.php, api_water.php, add.php і water.php — сумісні адреси, що передають запити єдиному API.

Стиль, зображення, сортування, редагування, видалення і фільтр поливу збережено з роботи 05. У JSON API неправильні дані мають код 400 за умовою роботи 06; класична форма редагування зберігає код 422. База practicum5 не змінюється.
