<?php
require_once __DIR__ . '/../Controllers/InventoryController.php';

Flight::route('GET /items', [InventoryController::class, 'index']);
Flight::route('POST /items', [InventoryController::class, 'store']);
Flight::route('GET /items/@id', [InventoryController::class, 'show']);
Flight::route('PUT /items/@id', [InventoryController::class, 'update']);
Flight::route('DELETE /items/@id', [InventoryController::class, 'destroy']);
