<?php
require 'init.php';

// --- LOGIKA ZPRACOVÁNÍ (POST) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // PŘIDÁNÍ ZÁJMU
    if (isset($_POST['add'])) {
        $name = trim($_POST['name']);
        
        if (empty($name)) {
        $_SESSION['message'] = "Pole nesmí být prázdné.";
        } else {
            try {
                $stmt = $db->prepare("INSERT INTO interests (name) VALUES (?)");
                $stmt->execute([$name]);
                $_SESSION['message'] = "Zájem byl přídán.";
            } catch (PDOException $e) {
                $_SESSION['message'] = "Tento zájem už existuje.";
            }
        }
    }

    // MAZÁNÍ ZÁJMU
    if (isset($_POST['delete'])) {
        $id = $_POST['id'];
        $stmt = $db->prepare("DELETE FROM interests WHERE id = ?");
        $stmt->execute([$id]);
        $_SESSION['message'] = "Zájem byl odstraněn.";
    }

    // EDITACE ZÁJMU
    if (isset($_POST['edit'])) {
        $id = $_POST['id'];
        $name = trim($_POST['name']);
        
        if (!empty($name)) {
            $stmt = $db->prepare("UPDATE interests SET name = ? WHERE id = ?");
            $stmt->execute([$name, $id]);
            $_SESSION['message'] = "Zájem byl upraven.";
        }
    }

    // PRG Pattern: Přesměrování po odeslání
    header("Location: index.php");
    exit;
}

// --- LOGIKA ZOBRAZENÍ (GET) ---
// Načtení všech zájmů
$query = $db->query("SELECT * FROM interests");
$interests = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <title>IT Profil 6.0</title>
    <link rel="stylesheet" href="style.css"> </head>
<body>
    <div class="card">
        <h1>Merka Matous</h1>
        
        <?php if (isset($_SESSION['message'])): ?>
            <p class="message"><?= $_SESSION['message'] ?></p>
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>

        <form method="post">
            <input type="text" name="name" placeholder="Nový zájem">
            <button type="submit" name="add">Přidat zájem</button>
        </form>

        <h2>Dovednosti</h2>
        <ul>
            <li>poloppo</li>
            <li>matematyk</li>
            <li>jordán</li>
        </ul>
    </div>

    <div class="card">
        <h2>Zájmy</h2>
        <ul>
            <?php foreach ($interests as $i): ?>
                <li>
                    <form method="post" style="display:inline;">
                        <input type="hidden" name="id" value="<?= $i['id'] ?>">
                        <input type="text" name="name" value="<?= htmlspecialchars($i['name']) ?>">
                        <button type="submit" name="edit">Upravit</button>
                        <button type="submit" name="delete">Smazat</button>
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</body>
</html>