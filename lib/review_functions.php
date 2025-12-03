<?php
function getReviewsByTravel($mysqli, $travel_id) {
  $stmt = $mysqli->prepare("SELECT r.*, u.username FROM reviews r LEFT JOIN users u ON r.user_id=u.id WHERE r.travel_id=? ORDER BY r.created_at DESC");
  $stmt->bind_param("i", $travel_id);
  $stmt->execute();
  return $stmt->get_result();
}

function getAverageRating($mysqli, $travel_id) {
  $stmt = $mysqli->prepare("SELECT AVG(rating) avg FROM reviews WHERE travel_id=?");
  $stmt->bind_param("i", $travel_id);
  $stmt->execute();
  $res = $stmt->get_result()->fetch_assoc();
  return $res['avg'] ?? 0;
}

function getReviewsByUser($mysqli, $user_id) {
    $stmt = $mysqli->prepare("
        SELECT * FROM reviews 
        WHERE user_id=? 
        ORDER BY created_at DESC
    ");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    return $stmt->get_result();
}

function getTravelTitle($mysqli, $travel_id) {
    $stmt = $mysqli->prepare("SELECT title FROM travels WHERE id=?");
    $stmt->bind_param("i", $travel_id);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_assoc();
    return $res['title'] ?? 'Paket Dihapus';
}

function addReview($mysqli, $travel_id, $user_id, $rating, $comment) {
    $stmt = $mysqli->prepare("
        INSERT INTO reviews (travel_id, user_id, rating, comment)
        VALUES (?, ?, ?, ?)
    ");
    $stmt->bind_param("iiis", $travel_id, $user_id, $rating, $comment);
    return $stmt->execute();
}

function getReviewById($mysqli, $id) {
    $stmt = $mysqli->prepare("SELECT * FROM reviews WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

function updateReview($mysqli, $id, $rating, $comment) {
    $stmt = $mysqli->prepare("UPDATE reviews SET rating=?, comment=? WHERE id=?");
    $stmt->bind_param("isi", $rating, $comment, $id);
    return $stmt->execute();
}


?>
