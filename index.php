<?php

$host = 'localhost';
$dbname = 'CSV_DB';
$dbuser = 'root';
$dbpassword = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $dbuser, $dbpassword, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    $sqlQuery = "SELECT * FROM `books`";
    $isbn = !empty($_GET['isbn']) ? '%' . trim($_GET['isbn']) . '%' : null;
    $author = !empty($_GET['author']) ? '%' . trim($_GET['author']) . '%' : null;
    $bookname = !empty($_GET['name']) ? '%' . trim($_GET['name']) . '%' : null;
    
    if (!$isbn && !$author && !$bookname) {
        $statement = $pdo->prepare($sqlQuery);
        $statement->execute();
    } else {
        $sqlQuery = "SELECT * FROM `books` WHERE isbn LIKE ? OR author LIKE ? OR `name` LIKE ?";
        $statement = $pdo->prepare($sqlQuery);
        $statement->execute([$isbn, $author, $bookname]);
    }
} catch (PDOException $e) {
    die($e->getMessage());
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Lesson-12</title>
	<style>
    table { 
        border-spacing: 0;
        border-collapse: collapse;
		margin-top:10px;
    }

    table td, table th {
        border: 1px solid #ccc;
        padding: 5px;
    }
	
	table th {
        background-color: grey;
    }
	
    
</style>
</head>
<body>
<h1>Библиотека успешного человека</h1>

<form method="GET">
    <input type="text" name="isbn" placeholder="ISBN" value="" />
    <input type="text" name="name" placeholder="Название" value="" />
    <input type="text" name="author" placeholder="Автор" value="" />
    <input type="submit" value="Поиск" />
</form>


<?php if ($statement->rowCount() !== 0): ?>
        <table>
            <tr>
                <th>Название</th>
                <th>Автор</th>
                <th>Год</th>
                <th>Жанр</th>
                <th>ISBN</th>
            </tr>
            <?php foreach ($statement as $row): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['name']) ?></td>
                    <td><?php echo htmlspecialchars($row['author']) ?></td>
                    <td><?php echo htmlspecialchars($row['year']) ?></td>
                    <td><?php echo htmlspecialchars($row['genre']) ?></td>
                    <td><?php echo htmlspecialchars($row['isbn']) ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
</div>
</body>
</html>
