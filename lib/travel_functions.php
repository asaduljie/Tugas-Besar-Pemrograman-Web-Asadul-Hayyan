<?php
function getAllTravels($mysqli) {
    return $mysqli->query("SELECT * FROM travels ORDER BY created_at DESC");
}
function getLimitedTravels($mysqli, $limit) {
    $stmt = $mysqli->prepare("SELECT * FROM travels ORDER BY created_at DESC LIMIT ?");
    $stmt->bind_param("i", $limit);
    $stmt->execute();
    return $stmt->get_result();
}
function getTravelById($mysqli, $id) {
    $stmt = $mysqli->prepare("SELECT * FROM travels WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

function searchTravels($mysqli, $q, $location, $min_price, $max_price, $order) {
    $sql = "SELECT * FROM travels WHERE 1=1";
    $params = []; $types = '';

    if ($q !== '') {
        $sql .= " AND (title LIKE ? OR location LIKE ?)";
        $like = "%$q%";
        $params[] = &$like; $params[] = &$like; $types .= 'ss';
    }
    if ($location !== '') {
        $sql .= " AND location = ?";
        $params[] = &$location; $types .= 's';
    }
    if ($min_price > 0) {
        $sql .= " AND price >= ?";
        $params[] = &$min_price; $types .= 'i';
    }
    if ($max_price > 0) {
        $sql .= " AND price <= ?";
        $params[] = &$max_price; $types .= 'i';
    }

    // order
    if ($order === 'price_asc') $sql .= " ORDER BY price ASC";
    else if ($order === 'price_desc') $sql .= " ORDER BY price DESC";
    else $sql .= " ORDER BY created_at DESC";

    $stmt = $mysqli->prepare($sql);
    if ($params) {
        array_unshift($params, $types);
        call_user_func_array(array($stmt, 'bind_param'), $params);
    }
    $stmt->execute();
    return $stmt->get_result();
}

function getAllLocations($mysqli) {
    return $mysqli->query("SELECT DISTINCT location FROM travels ORDER BY location ASC");
}
?>
