<?php
$data = [
    ['Иванов', 'Математика', 5],
    ['Иванов', 'Математика', 4],
    ['Иванов', 'Математика', 5],
    ['Петров', 'Математика', 5],
    ['Сидоров', 'Физика', 4],
    ['Иванов', 'Физика', 4],
    ['Петров', 'ОБЖ', 4],
];

$superduper = [];

foreach ($data as $row) {
    if (isset($superduper[$row[1]])) {
        if (isset($superduper[$row[1]][$row[0]])) {
            $superduper[$row[1]][$row[0]] += $row[2];
        } else {
            $superduper[$row[1]][$row[0]] = $row[2];
        }
    } else {
        $superduper[$row[1]] = array($row[0] => $row[2]);
    }
}

ksort($superduper);
foreach ($superduper as $key => $value) {
    ksort($superduper[$key]);
}

$names = array_unique(array_column($data, 0));

?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Баллы школьников</title>
    <style>
        table { border-collapse: collapse; width: 50%; }
        th, td { border: 1px solid black; padding: 8px; text-align: left; }
    </style>
</head>
<body>
    <table>
        <thead>
            <th></th>
            <?php foreach ($superduper as $key => $value): ?>
                <th><?php echo($key)?></th>
            <?php endforeach; ?>
        </thead>
        <tbody>
            <?php foreach ($names as $name): ?>
            <tr>
                <td><?php echo($name)?></td>
                <?php foreach ($superduper as $key => $value): ?>
                    <td><?php echo(isset($value[$name]) ? $value[$name] : '')?></td>
                <?php endforeach; ?>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
