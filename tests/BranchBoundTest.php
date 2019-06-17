<?php

use PHPUnit\Framework\TestCase;
use Tsp\TspBranchBound;
use Tsp\TspLocation;

class ranchBoundTest extends TestCase
{
    /** @var TspBranchBound */
    protected $branchBound;

    protected function setUp()
    {
        $this->branchBound = new TspBranchBound(
            new TspLocation(54.5, 50),
            new TspLocation(55.5, 53),
            new TspLocation(58.5, 59),
            new TspLocation(33.5, 49)
        );
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

    public function testGetMatrixColumn()
    {
        $this->branchBound->calculateCostMatrix();
        $column = $this->branchBound->getMatrixColumn(0);
        $this->assertEquals([INF, 221, 708, 2336], $column);
    }

    public function testCalculateMinDivRow()
    {
        $this->branchBound->calculateCostMatrix();
        $this->assertEquals([0, 0, 0, 0], $this->branchBound->calculateMinDivRow());
    }
}