<?php

use PHPUnit\Framework\TestCase;
use Tsp\BranchBound;
use Tsp\Location;

class BranchBoundTest extends TestCase
{
    public function testAddLocation()
    {
        $bnb = new BranchBound();
        $location1 = new Location(54.5, 66.5);
        $location2 = new Location(55.5, 66.6);

        $bnb->addLocation($location1);
        $bnb->addLocation($location2);
        $this->assertEquals([$location1, $location2], $bnb->getLocations());
    }

    public function testCalculateMatrix()
    {
        $branchBound = new BranchBound();
        $branchBound->addLocation(new Location(54.5, 50));
        $branchBound->addLocation(new Location(55.5, 53));
        $branchBound->addLocation(new Location(58.5, 59));
        $branchBound->addLocation(new Location(33.5, 49));

        $this->assertEquals([
            [INF,  221,  708,  2336, 221],
            [221,  INF,  492,  2465, 221],
            [708,  492,  INF,  2878, 492],
            [2336, 2465, 2878, INF,  2336]
        ], $branchBound->calculateCostMatrix());
    }
}