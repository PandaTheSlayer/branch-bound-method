<?php


namespace Tsp;

use Tsp\Location;


class BranchBound
{
    private $locations = [];
    private $costMatrix = [];

    public function __construct(Location ...$locations)
    {
        $this->locations = $locations;
    }

    /**
     * @return array
     */
    public function getCostMatrix() : array
    {
        if (empty($this->costMatrix))
            $this->costMatrix = $this->calculateCostMatrix();

        return $this->costMatrix;
    }


    public function calculateCostMatrix() : array
    {
        $locations = $this->locations;

        $costMatrix = [];

        for ($m = 0; $m < count($locations); $m++) {
            for ($n = 0; $n < count($locations); $n++) {
                $m == $n ? $costMatrix[$m][$n] = INF
                         : $costMatrix[$m][$n] = $locations[$m]->distance($locations[$n]);
            }
        }

        $this->costMatrix = $costMatrix;

        return $costMatrix;
    }

    public function calculateMinRow() : array
    {
        if (empty($this->costMatrix)) {
            $this->costMatrix = $this->calculateCostMatrix();
        }

        $minRowArr = [];
        foreach ($this->costMatrix as $key => $row) {
            $minRowArr[$key] = min($this->costMatrix[$key]);
        }

        return $minRowArr;
    }

    public function getMatrixColumn(int $pos) : array
    {
        return array_column($this->getCostMatrix(), $pos);
    }

    public function calculateMinDivRow() : array
    {
        $minRow = $this->calculateMinRow();
        $minDivRow = [];

        foreach ($minRow as $key => $value) {
            $column = $this->getMatrixColumn($key);
            $row = [];
            foreach ($column as $c) {
                $row[] = $c - $minRow[$key];
            }

            $minDivRow[] = min($row);
        }

        return $minDivRow;
    }
}