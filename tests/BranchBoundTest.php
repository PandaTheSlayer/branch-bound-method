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
}