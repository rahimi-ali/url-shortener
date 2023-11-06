<?php

$config = json_decode(file_get_contents(__DIR__ . '/../config/database.json'), true);

$pdo = new PDO($config['dsn'], $config['user'], $config['password']);

foreach (scandir(__DIR__) as $file) {
    if (preg_match('/(.+)\.sql/', $file)) {
        try {
            $pdo->exec(file_get_contents(__DIR__ . '/' . $file));
        } catch (Throwable $e) {
            echo "couldn't migrate $file\n";
            echo $e->getMessage() . "\n";
        }
    }
}