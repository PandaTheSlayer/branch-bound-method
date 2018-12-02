<?php

use PHPUnit\Framework\TestCase;
use Tsp\BranchBound;
use Tsp\Location;

class BranchBoundTest extends TestCase
{
    /** @var BranchBound */
    protected $branchBound;

    protected function setUp()
    {
        $branchBound = new BranchBound();
        $branchBound->addLocation(new Location(54.5, 50));
        $branchBound->addLocation(new Location(55.5, 53));
        $branchBound->addLocation(new Location(58.5, 59));
        $branchBound->addLocation(new Location(33.5, 49));

        $this->branchBound = $branchBound;
    }

    public function testAddLocation()
    {
        $locationsBefore = $this->branchBound->getLocations();
        $locationNew = new Location(54.5, 66.5);

        $locationsAfter = array_merge($locationsBefore, [$locationNew]);

        $this->branchBound->addLocation($locationNew);
        $this->assertEquals($locationsAfter, $this->branchBound->getLocations());
    }

    public function testCalculateMatrix()
    {
        $this->assertEquals([
            [INF,  221,  708,  2336],
            [221,  INF,  492,  2465],
            [708,  492,  INF,  2878],
            [2336, 2465, 2878, INF ]
        ], $this->branchBound->calculateCostMatrix());
    }

    public function testCalculateMinRow()
    {
        $minRowArr = $this->branchBound->calculateMinRow();
        $this->assertEquals([221, 221, 492, 2336], $minRowArr);
    }
}