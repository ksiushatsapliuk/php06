CREATE DATABASE IF NOT EXISTS practicum6 CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE practicum6;
CREATE TABLE IF NOT EXISTS plants (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    watering_interval_days INT NOT NULL,
    last_watered_at DATE NOT NULL,
    is_succulent TINYINT(1) NOT NULL DEFAULT 0,
    sunlight_needs VARCHAR(100) NOT NULL DEFAULT 'Розсіяне світло',
    CONSTRAINT chk_interval CHECK (watering_interval_days BETWEEN 1 AND 3650),
    CONSTRAINT chk_succulent CHECK (is_succulent IN (0, 1))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
