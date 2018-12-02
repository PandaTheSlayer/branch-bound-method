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

        $this->calculateMinRow($costMatrix);
        $this->costMatrix = $costMatrix;

        return $costMatrix;
    }

    private function calculateMinRow(array &$costMatrix)
    {
        foreach ($costMatrix as $key => $row) {
            $costMatrix[$key][] = min($costMatrix[$key]);
        }

        return $costMatrix;
    }
}