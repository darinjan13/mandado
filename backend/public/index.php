<?php

require __DIR__ . '/../vendor/autoload.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

function db()
{
    static $pdo = null;
    if ($pdo === null) {
        $pdo = new \PDO('mysql:host=localhost;dbname=inventory', 'root', '');
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }
    return $pdo;
}

Flight::route('GET /items', function () {
    $stmt = db()->query("SELECT * FROM items");
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
    Flight::json($items);
});

Flight::route('GET /items/@id', function ($id) {
    $stmt = db()->prepare("SELECT * FROM items WHERE id = ?");
    $stmt->execute([$id]);
    $item = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($item) {
        Flight::json($item);
    } else {
        Flight::json(['error' => 'Item not found'], 404);
    }
});

Flight::route('POST /items', function () {
    $data = Flight::request()->data->getData();
    $errors = [];

    if (empty(trim($data['name'] ?? ''))) {
        $errors['name'] = 'Name is required.';
    }
    if (empty(trim($data['quantity'] ?? ''))) {
        $errors['quantity'] = 'Quantity is required.';
    }
    if (empty(trim($data['price'] ?? ''))) {
        $errors['price'] = 'Price is required.';
    }

    if (!empty($errors)) {
        Flight::json(['errors' => $errors], 422);
        return;
    }

    $stmt = db()->prepare("INSERT INTO items (name, quantity, price) VALUES (?, ?, ?)");
    $stmt->execute([
        $data['name'],
        $data['quantity'],
        $data['price'],
    ]);

    Flight::json(['message' => 'Item added successfully']);
});

Flight::route('PUT /items/@id', function ($id) {
    $data = Flight::request()->data->getData();
    $errors = [];

    // Validation
    if (empty(trim($data['name'] ?? ''))) {
        $errors['name'] = 'Name is required.';
    }
    if (empty(trim($data['quantity'] ?? ''))) {
        $errors['quantity'] = 'Quantity is required.';
    }
    if (empty(trim($data['price'] ?? ''))) {
        $errors['price'] = 'Price is required.';
    }

    if (!empty($errors)) {
        Flight::json(['errors' => $errors], 422);
        return;
    }

    $stmt = db()->prepare("UPDATE items SET name = ?, quantity = ?, price = ? WHERE id = ?");
    $stmt->execute([
        $data['name'],
        $data['quantity'],
        $data['price'],
        $id
    ]);

    Flight::json(['message' => 'Item updated successfully']);
});

Flight::route('DELETE /items/@id', function ($id) {
    $stmt = db()->prepare("DELETE FROM items WHERE id = ?");
    $stmt->execute([$id]);

    Flight::json(['message' => 'Item deleted successfully']);
});

Flight::start();

?>