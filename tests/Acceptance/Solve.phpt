--TEST--

--FILE--
<?php

require __DIR__ . '/../../vendor/autoload.php';

use Panda\Tsp\BranchBound;

try
{
	$tsp = BranchBound::getInstance();
	$tsp->addLocation(array('id'=>'newquay', 'latitude'=>50.413608, 'longitude'=>-5.083364));
	$tsp->addLocation(array('id'=>'manchester', 'latitude'=>53.480712, 'longitude'=>-2.234377));
	$tsp->addLocation(array('id'=>'london', 'latitude'=>51.500152, 'longitude'=>-0.126236));
	$tsp->addLocation(array('id'=>'birmingham', 'latitude'=>52.483003, 'longitude'=>-1.893561));
	$ans = $tsp->solve();
	echo "\nTotal cost: " . ceil($ans['cost']) . "\n\n";
}
catch (Exception $e)
{
	echo $e;
	exit;
}
?>
--EXPECT--
1. newquay
2. manchester
3. london
4. birmingham

Path:
1 -> 3
3 -> 4
4 -> 2
2 -> 1

Total cost: 645
