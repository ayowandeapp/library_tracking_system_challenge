<?php

# 1.
$a = range(1, 20);

# 2.
$f = array_filter($a, function ($num) {
    return $num >= 10;
});

# 3.
echo "original array";
print_r($a);
echo "filtered number";
print_r($f);
