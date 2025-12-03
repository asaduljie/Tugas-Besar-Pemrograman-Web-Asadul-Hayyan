<?php
function getReservationsForTravel($mysqli, $travel_id) {
    $stmt = $mysqli->prepare("SELECT * FROM reservations WHERE travel_id = ? ORDER BY created_at DESC");
    $stmt->bind_param("i", $travel_id);
    $stmt->execute();
    return $stmt->get_result();
}
?>
