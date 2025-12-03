<?php
session_start();
require 'config/config.php';
include 'lib/review_functions.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: auth/auth.php?msg=login-required");
    exit;
}

$user_id = $_SESSION['user_id'];
$travel_id = intval($_POST['travel_id'] ?? 0);
$rating = intval($_POST['rating'] ?? 0);
$comment = trim($_POST['comment'] ?? '');

if (!$travel_id || $rating < 1 || $rating > 5) {
    header("Location: travel_detail.php?id=$travel_id&error=invalid-input");
    exit;
}

addReview($mysqli, $travel_id, $user_id, $rating, $comment);

header("Location: travel_detail.php?id=$travel_id&success=review-added");
exit;
