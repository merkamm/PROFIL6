<?php
// 1. Start session (nutné pro hlášky)
session_start();

try {
    // 2. Připojení k databázi SQLite
    $db = new PDO("sqlite:profile.db");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 3. Vytvoření tabulky podle zadání
    $db->exec("CREATE TABLE IF NOT EXISTS interests (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL UNIQUE
    )");
} catch (PDOException $e) {
    die("Chyba připojení k databázi: " . $e->getMessage());
}