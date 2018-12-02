<?php


namespace Tsp;


/**
 * Class Location
 * @package Tsp
 */
class Location
{
    const EARTH_RADIUS = 6371;

    /**
     * @var float
     */
    public $latitude;
    /**
     * @var float
     */
    public $longitude;

    /**
     * Location constructor.
     * @param float $latitude
     * @param float $longitude
     */
    public function __construct($latitude, $longitude)
    {
        $this->latitude = $latitude;
        $this->longitude = $longitude;
    }


    /**
     * @param Location $p2
     * @return int
     */
    public function distance(Location $p2)
    {
        $phi1 = deg2rad($this->latitude);
        $phi2 = deg2rad($p2->latitude);
        $deltaPhi = $phi2 - $phi1;

        $lambda1 = deg2rad($this->longitude);
        $lambda2 = deg2rad($p2->longitude);
        $deltaLambda = $lambda2 - $lambda1;

        $a = sin($deltaPhi / 2)**2 + cos($phi1) * cos($phi2) * sin($deltaLambda / 2)**2;
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return (int) $distance = self::EARTH_RADIUS * $c;
    }

}