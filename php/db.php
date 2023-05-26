<?php
$config = [
    'host' => 'localhost',
    'name' => 'testsystem',
    'user' => 'root',
    'password' => '',
    'charset' => 'utf-8',
];

$db = new PDO ('mysql:host=' . $config['host'] . ';dbname=' . $config['name'], $config['user'], $config['password']);
$db->query('SET character_set_connection = ' . $config['charset'] . ';');
$db->query('SET character_set_client = ' . $config['charset'] . ';');
$db->query('SET character_set_results = ' . $config['charset'] . ';');

// Дополнительные функции

function getCustomDate($timestamp) {
    return gmdate('d.m.y H:i:s', $timestamp);
}

function getCustomInterval($timestamp) {
    return gmdate('H ч. i мин. s сек.', $timestamp);
}

$illegalSQL = ['%', '_', '*', '?'];

function isLegalSQL($string) {
    if (in_array($string, $illegalSQL)) header("Location: ../redirect/404.html");
}