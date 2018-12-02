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
                if ($m == $n) {
                    $costMatrix[$m][$n] = INF;
                } else {
                    $costMatrix[$m][$n] = $locations[$m]->distance($locations[$n]);
                }
            }
        }

        return $costMatrix;
    }
}