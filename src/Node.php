<?php


namespace Panda\Tsp;

class Node
{
    public $path = array();
    public $reducedMatrix = array();
    public $cost;
    public $vertex;
    public $level;

    /**
     * Constructor
     *
     * @param array $parentMatrix The parentMatrix of the costMatrix.
     * @param array $path An array of integers for the path.
     * @param integer $level The level of the node.
     * @param integer $i          They are corresponds to visiting city j from city i
     * @param integer $j
     */
    public function __construct($parentMatrix, $path, $level, $i, $j)
    {
        // stores ancestors edges of state space tree
        $this->path = $path;

        // skip for root node
        if ($level != 0) {
            // add current edge to path
            $this->path[] = array($i, $j);
        }

        // copy data from parent node to current node
        $this->reducedMatrix = $parentMatrix;

        // Change all entries of row i and column j to infinity
        // skip for root node
        for ($k = 0; $level != 0 && $k < count($parentMatrix); $k++) {
            // set outgoing edges for city i to infinity
            $this->reducedMatrix[$i][$k] = INF;
            // set incoming edges to city j to infinity
            $this->reducedMatrix[$k][$j] = INF;
        }

        // Set (j, 0) to infinity
        // here start node is 0
        $this->reducedMatrix[$j][0] = INF;

        $this->level = $level;
        $this->vertex = $j;
    }
}
