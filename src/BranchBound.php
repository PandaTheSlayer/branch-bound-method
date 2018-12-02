<?php


namespace Tsp;

use Tsp\Location;


class BranchBound
{
    protected $locations = [];
    protected $costMatrix = [];

    /**
     * @return array
     */
    public function getLocations()
    {
        return $this->locations;
    }

    /**
     * @return array
     */
    public function getCostMatrix()
    {
        return $this->costMatrix;
    }

    public function addLocation(Location $location)
    {
        $this->locations[] = $location;
    }

    public function calculateCostMatrix()
    {
        $locations = $this->getLocations();

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

    public function calculateMinRow()
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
}