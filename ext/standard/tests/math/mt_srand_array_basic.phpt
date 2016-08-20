--TEST--
Test mt_srand_array() - basic function mt_srand_array()
--FILE--
<?php

function generate_some() {
	$t = [];
	for ($i = 0; $i < 100; ++$i) {
		$t[] = mt_rand();
	}
	return $t;
}

function test_seed($a) {
	// Print mt_srand_array result
	var_dump(mt_srand_array($a));
	$ar = generate_some();
	@mt_srand_array($a);
	$br = generate_some();
	// Print true if the seed produces the same sequence.
	var_dump($ar === $br);
}

function test_seed_2($a) {
	// Print mt_srand_array result
	var_dump(mt_srand_array($a));
	$ar = generate_some();
	mt_srand_array(array_map(function($x) { return intval(fmod(floatval($x), 0x100000000)); }, $a));
	$br = generate_some();
	// Print true if the seed produces the same sequence with intval conversion.
	var_dump($ar === $br);
}

echo "Seed = missing. Expected: warning, NULL:\n";
var_dump(mt_srand_array());

echo "Seed = not array. Expected: warning, NULL, false:\n";
test_seed(NULL);
test_seed(true);
test_seed(false);
test_seed(1);
test_seed(1.2);
test_seed('x');

echo "Seed = empty array. Expected: warning, NULL, false:\n";
test_seed([]);

echo "Seed = integer array = correct usage. Expected: NULL, true:\n";
test_seed([1]);
test_seed([1, 2]);
test_seed([1, 2, 3]);

echo "Seed = array with a reference to integer = correct usage. Expected: NULL, true:\n";
$x = 1;
test_seed_2([&$x]);

echo "Seed = array with integral floats = correct usage. Expected: NULL, true:\n";
test_seed_2([1.0, 2.0, 3.0, 1.0e9]);

echo "Seed = array with integral strings = correct usage. Expected: NULL, true:\n";
test_seed_2(['1', '+2', '-3']);

echo "Seed = array with too big values. Silently truncated. Expected: NULL, true:\n";
test_seed_2([0x123ffffffff]);
test_seed_2([-0x123ffffffff]);

echo "Seed = array with non well formed numeric string. Expected: NULL, true:\n";
test_seed_2(['123xyz']);

echo "Seed = non-integer array. Silently converted. Expected: NULL, true:\n";
test_seed_2([NULL]);
test_seed_2([true]);
test_seed_2([false]);
test_seed_2([1.2, 24.7]);
test_seed_2(['']);
test_seed_2(['x']);
test_seed_2(['1.1']);
test_seed_2([[]]);
test_seed_2([[1]]);

echo "Same results from mt_rand with same seed values:\n";
mt_srand_array([1,2,3,4,5]);
$a = generate_some();
mt_srand_array([1,2,3,4,5]);
$b = generate_some();
var_dump($a == $b);

echo "Different results from mt_rand with different seed values:\n";
mt_srand_array([1,2,3,4,5]);
$a = generate_some();
mt_srand_array([1,2,7,4,5]);
$b = generate_some();
var_dump($a != $b);
?>
--EXPECTF--
Seed = missing. Expected: warning, NULL:

Warning: mt_srand_array() expects exactly 1 parameter, 0 given in %s on line %d
NULL
Seed = not array. Expected: warning, NULL, false:

Warning: mt_srand_array() expects parameter 1 to be array, null given in %s on line %d
NULL
bool(false)

Warning: mt_srand_array() expects parameter 1 to be array, boolean given in %s on line %d
NULL
bool(false)

Warning: mt_srand_array() expects parameter 1 to be array, boolean given in %s on line %d
NULL
bool(false)

Warning: mt_srand_array() expects parameter 1 to be array, integer given in %s on line %d
NULL
bool(false)

Warning: mt_srand_array() expects parameter 1 to be array, float given in %s on line %d
NULL
bool(false)

Warning: mt_srand_array() expects parameter 1 to be array, string given in %s on line %d
NULL
bool(false)
Seed = empty array. Expected: warning, NULL, false:

Warning: mt_srand_array(): expects parameter 1 to be an array with at least 1 element in %s on line %d
NULL
bool(false)
Seed = integer array = correct usage. Expected: NULL, true:
NULL
bool(true)
NULL
bool(true)
NULL
bool(true)
Seed = array with a reference to integer = correct usage. Expected: NULL, true:
NULL
bool(true)
Seed = array with integral floats = correct usage. Expected: NULL, true:
NULL
bool(true)
Seed = array with integral strings = correct usage. Expected: NULL, true:
NULL
bool(true)
Seed = array with too big values. Silently truncated. Expected: NULL, true:
NULL
bool(true)
NULL
bool(true)
Seed = array with non well formed numeric string. Expected: NULL, true:
NULL
bool(true)
Seed = non-integer array. Silently converted. Expected: NULL, true:
NULL
bool(true)
NULL
bool(true)
NULL
bool(true)
NULL
bool(true)
NULL
bool(true)
NULL
bool(true)
NULL
bool(true)
NULL
bool(true)
NULL
bool(true)
Same results from mt_rand with same seed values:
bool(true)
Different results from mt_rand with different seed values:
bool(true)
