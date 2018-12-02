<?php

use PHPUnit\Framework\TestCase;

class LocationTest extends TestCase
{
    public function testCanCreateLocation()
    {
        $location = new \Tsp\Location(54.5, 65.4);
        $this->assertInstanceOf(\Tsp\Location::class, $location);
    }

    public function testCalculateDistanceBetweenTwoLocations()
    {
        $location1 = new \Tsp\Location(54.5, 55.1);
        $location2 = new \Tsp\Location(60, 55.1);

        $this->assertEquals($location1->distance($location2), 611);
    }
}