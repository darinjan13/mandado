<?php

class InventoryModel {
    protected static function db() {
        static $pdo = null;
        if ($pdo === null) {
            $pdo = new \PDO('mysql:host=localhost;dbname=inventory', 'root', '');
            $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        }
        return $pdo;
    }

    public static function getAll() {
        return self::db()->query("SELECT * FROM items")->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function find($id) {
        $stmt = self::db()->prepare("SELECT * FROM items WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public static function create($data) {
        $stmt = self::db()->prepare("INSERT INTO items (name, quantity, price) VALUES (?, ?, ?)");
        $stmt->execute([$data['name'], $data['quantity'], $data['price']]);
        return ['id' => self::db()->lastInsertId()] + $data;
    }

    public static function update($id, $data) {
        $stmt = self::db()->prepare("UPDATE items SET name = ?, quantity = ?, price = ? WHERE id = ?");
        $stmt->execute([$data['name'], $data['quantity'], $data['price'], $id]);
        return $data;
    }

    public static function delete($id) {
        $stmt = self::db()->prepare("DELETE FROM items WHERE id = ?");
        $stmt->execute([$id]);
    }
}
