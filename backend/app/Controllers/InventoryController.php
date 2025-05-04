<?php

require_once __DIR__ . '/../Models/InventoryModel.php';

class InventoryController {
    public static function index() {
        $items = InventoryModel::getAll();
        Flight::json($items);
    }

    public static function store() {
        $data = Flight::request()->data->getData();
        $result = InventoryModel::create($data);
        Flight::json(['message' => 'Item added', 'data' => $result]);
    }

    public static function show($id) {
        $item = InventoryModel::find($id);
        Flight::json($item);
    }

    public static function update($id) {
        $data = Flight::request()->data->getData();
        $result = InventoryModel::update($id, $data);
        Flight::json(['message' => 'Item updated', 'data' => $result]);
    }

    public static function destroy($id) {
        InventoryModel::delete($id);
        Flight::json(['message' => 'Item deleted']);
    }
}
