<?php
require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../Models/Rating.php';
require_once __DIR__ . '/../Models/Business.php';

header("Content-Type: application/json; charset=UTF-8");

$db = Database::getInstance()->getConnection();
$ratingModel = new Rating($db);
$businessModel = new Business($db);

$action = $_GET['action'] ?? '';

switch ($action) {
    case 'submit':
        $ratingModel->business_id = $_POST['business_id'] ?? null;
        $ratingModel->name = trim($_POST['name'] ?? '');
        $ratingModel->email = trim($_POST['email'] ?? '');
        $ratingModel->phone = trim($_POST['phone'] ?? '');
        $ratingModel->rating = $_POST['rating'] ?? 0;

        $errors = [];
        if (empty($ratingModel->business_id)) {
            $errors[] = "Business ID is required.";
        }
        if (empty($ratingModel->name)) {
            $errors[] = "Name is required.";
        }
        if (empty($ratingModel->email)) {
            $errors[] = "Email is required.";
        } elseif (!filter_var($ratingModel->email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid email format.";
        }
        if (empty($ratingModel->phone)) {
            $errors[] = "Phone is required.";
        } elseif (!preg_match('/^[0-9]{10}$/', $ratingModel->phone)) {
            $errors[] = "Phone must be exactly 10 digits.";
        }
        if ($ratingModel->rating < 0.5 || $ratingModel->rating > 5) {
            $errors[] = "Rating must be between 0.5 and 5.";
        }

        if (!empty($errors)) {
            echo json_encode(["status" => "error", "message" => implode(" ", $errors)]);
            break;
        }

        if ($ratingModel->save()) {
            $avgRating = $businessModel->getAverageRating($ratingModel->business_id);
            echo json_encode([
                "status" => "success", 
                "message" => "Rating submitted successfully",
                "average_rating" => $avgRating,
                "debug_query" => $ratingModel->last_query
            ]);
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to submit rating"]);
        }
        break;

    default:
        echo json_encode(["status" => "error", "message" => "Invalid action"]);
        break;
}
