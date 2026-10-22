<?php
require_once __DIR__ . '/Plant.php';
require_once __DIR__ . '/Succulent.php';

class Garden 
{
    private PDO $pdo;

    public function __construct(PDO $pdo) 
    {
        $this->pdo = $pdo;
    }

    // Один запит для списку; пошук виконується у БД через підготовлений LIKE.
    public function getAllPlants(string $query = ''): array
    {
        if ($query !== '') return $this->findByName($query);
        return $this->pdo->query('SELECT * FROM plants ORDER BY id DESC')->fetchAll();
    }

    public function findByName(string $name): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM plants WHERE name LIKE :q ESCAPE '!' ORDER BY id DESC");
        $stmt->execute([':q' => '%' . strtr($name, ['!' => '!!', '%' => '!%', '_' => '!_']) . '%']);
        return $stmt->fetchAll();
    }

    public function getCounts(): array
    {
        $stmt = $this->pdo->prepare('SELECT COUNT(*) AS total, COALESCE(SUM(DATE_ADD(last_watered_at, INTERVAL watering_interval_days DAY) <= :today), 0) AS due FROM plants');
        $stmt->execute([':today' => date('Y-m-d')]);
        return $stmt->fetch();
    }

    public function getPlantById(int $id): ?array 
    {
        $stmt = $this->pdo->prepare('SELECT * FROM plants WHERE id = :id');
        $stmt->execute([':id' => $id]);
        $plant = $stmt->fetch();
        return $plant ?: null;
    }

    /**
     * Варіант 18: addPlant() - INSERT
     */
    public function addPlant(string $name, int $interval, string $lastWatered, bool $isSucculent, string $sunlightNeeds = 'Пряме сонце'): bool 
    {
        $sql = 'INSERT INTO plants (name, watering_interval_days, last_watered_at, is_succulent, sunlight_needs) 
                VALUES (:name, :interval, :last_watered, :is_succulent, :sunlight_needs)';
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':name' => $name,
            ':interval' => $interval,
            ':last_watered' => $lastWatered,
            ':is_succulent' => $isSucculent ? 1 : 0,
            ':sunlight_needs' => $sunlightNeeds
        ]);
    }

    /**
     * UPDATE запису
     */
    public function updatePlant(int $id, string $name, int $interval, string $lastWatered, bool $isSucculent, string $sunlightNeeds): bool 
    {
        $sql = 'UPDATE plants 
                SET name = :name, watering_interval_days = :interval, last_watered_at = :last_watered, 
                    is_succulent = :is_succulent, sunlight_needs = :sunlight_needs 
                WHERE id = :id';
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':name' => $name,
            ':interval' => $interval,
            ':last_watered' => $lastWatered,
            ':is_succulent' => $isSucculent ? 1 : 0,
            ':sunlight_needs' => $sunlightNeeds
        ]);
    }

    /**
     * Варіант 18: markWatered($id)
     */
    public function markWatered(int $id): bool 
    {
        $stmt = $this->pdo->prepare('UPDATE plants SET last_watered_at = CURDATE() WHERE id = :id');
        return $stmt->execute([':id' => $id]);
    }

    /**
     * Варіант 18: deletePlant($id)
     */
    public function deletePlant(int $id): bool 
    {
        $stmt = $this->pdo->prepare('DELETE FROM plants WHERE id = :id');
        return $stmt->execute([':id' => $id]);
    }
}
