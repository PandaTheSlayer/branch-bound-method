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
     * @return float
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * @return float
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * @param Location $p2
     * @return int
     */
    public function distance(Location $p2)
    {
        $phi1 = deg2rad($this->getLatitude());
        $phi2 = deg2rad($p2->getLatitude());
        $deltaPhi = $phi2 - $phi1;

        $lambda1 = deg2rad($this->getLongitude());
        $lambda2 = deg2rad($p2->getLongitude());
        $deltaLambda = $lambda2 - $lambda1;

        $a = sin($deltaPhi / 2)**2 + cos($phi1) * cos($phi2) * sin($deltaLambda / 2)**2;
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return (int) $distance = self::EARTH_RADIUS * $c;
    }
}