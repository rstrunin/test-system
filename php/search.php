<?php
require_once 'db.php';

if (isset($_POST['query'])) {
    $inpText = $_POST['query'];
    $sql = 'SELECT id, title FROM tests WHERE title LIKE :title';
    $stmt = $db->prepare($sql);
    $stmt->execute(['title' => '%' . $inpText . '%']);
    $rows = $stmt->fetchAll();
    foreach($rows as $row)
        $result[] = ["id" => $row["id"], "title" => $row["title"]];
    echo json_encode($result);
}