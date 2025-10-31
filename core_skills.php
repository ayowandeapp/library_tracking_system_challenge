<?php

# 1.
$numbers = [];
for ($i = 0; $i < 10; $i++) {
    $numbers[] = rand(1, 20);
}

# 2.
$f = array_filter($a, function ($num) {
    return $num >= 10;
});

# 3.
echo "original array";
print_r($numbers);
echo "filtered number";
print_r($f);
