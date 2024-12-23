<?php
function countSistersForBrother($n, $m) {
    return $n+1;
}

$n = 3; // Количество сестер
$m = 2; // Количество братьев

echo "Количество сестер у произвольного брата Алисы: " . countSistersForBrother($n, $m);
