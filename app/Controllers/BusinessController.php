<?php
require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../Models/Business.php';

header("Content-Type: application/json; charset=UTF-8");

$db = Database::getInstance()->getConnection();
$business = new Business($db);

$action = $_GET['action'] ?? '';

switch ($action) {
    case 'read':
        $page = $_GET['page'] ?? 1;
        $limit = $_GET['limit'] ?? 10;
        $offset = ($page - 1) * $limit;

        $total = $business->countAll();
        $stmt = $business->read($limit, $offset);
        $businesses = $stmt->fetchAll();

        echo json_encode([
            "status" => "success",
            "data" => $businesses,
            "pagination" => [
                "total" => (int)$total,
                "page" => (int)$page,
                "limit" => (int)$limit,
                "pages" => ceil($total / $limit)
            ]
        ]);
        break;

    case 'readOne':
        $business->id = $_GET['id'] ?? null;
        if ($business->id) {
            $data = $business->readOne();
            echo json_encode(["status" => "success", "data" => $data]);
        } else {
            echo json_encode(["status" => "error", "message" => "ID missing"]);
        }
        break;

    case 'create':
    case 'update':
        $business->id = $_POST['id'] ?? null;
        $business->name = trim($_POST['name'] ?? '');
        $business->address = trim($_POST['address'] ?? '');
        $business->phone = trim($_POST['phone'] ?? '');
        $business->email = trim($_POST['email'] ?? '');

        $errors = [];
        if (empty($business->name)) {
            $errors[] = "Business name is required.";
        }
        if (!empty($business->email) && !filter_var($business->email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid email format.";
        }
        if (!empty($business->phone) && !preg_match('/^[0-9]{10}$/', $business->phone)) {
            $errors[] = "Phone number must be exactly 10 digits.";
        }

        if ($action === 'update' && empty($business->id)) {
            $errors[] = "Business ID is missing for update.";
        }

        if (!empty($errors)) {
            echo json_encode(["status" => "error", "message" => implode(" ", $errors)]);
            break;
        }

        $result = ($action === 'create') ? $business->create() : $business->update();
        if ($result) {
            echo json_encode(["status" => "success", "message" => "Business " . ($action === 'create' ? "added" : "updated") . " successfully"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to " . ($action === 'create' ? "add" : "update") . " business"]);
        }
        break;

    case 'delete':
        $business->id = $_POST['id'] ?? null;
        if ($business->id) {
            if ($business->delete()) {
                echo json_encode(["status" => "success", "message" => "Business deleted successfully"]);
            } else {
                echo json_encode(["status" => "error", "message" => "Failed to delete business"]);
            }
        } else {
            echo json_encode(["status" => "error", "message" => "ID missing"]);
        }
        break;

    default:
        echo json_encode(["status" => "error", "message" => "Invalid action"]);
        break;
}
