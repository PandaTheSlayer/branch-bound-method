<?php


namespace Panda\Tsp;

use Exception;
use RuntimeException;
use Panda\Tsp\Location;

class BranchBound
{
    protected $n = 0;
    protected $locations = [];
    protected $costMatrix = [];

    /**
     * @var    array  BranchBound instances container.
     */
    protected static $instances = [];

    /**
     * Constructor
     * @param array $costMatrix
     */
    public function __construct($costMatrix = [])
    {
        if ($costMatrix) {
            $this->costMatrix = $costMatrix;
            $this->n = count($this->costMatrix);
        }
    }

    /**
     * Method to get an instance of a BranchBound.
     *
     * @param string $name The name of the BranchBound.
     * @param array $locations An array of locations.
     *
     * @return  object  BranchBound instance.
     *
     * @throws  Exception if an error occurs.
     */
    public static function getInstance($name = 'BranchBound', $locations = null)
    {
        // Reference to array with instances
        $instances = &self::$instances;

        // Only instantiate if it does not already exist.
        if (!isset($instances[$name])) {
            // Instantiate the BranchBound.
            $instances[$name] = new BranchBound();
        }

        $instances[$name]->locations = [];
        $instances[$name]->costMatrix = [];

        // Load the data.
        if ($locations) {
            if ($instances[$name]->load($locations) == false) {
                throw new RuntimeException('BranchBound::getInstance could not load locations');
            }
        }

        return $instances[$name];
    }

    public function load($locations)
    {
        if (empty($locations)) {
            return false;
        }

        foreach ($locations as $location) {
            if (empty($location)) {
                return false;
            }

            if ($this->addLocation($location) == false) {
                return false;
            }
        }

        return $this->loadMatrix();
    }

    public function loadMatrix()
    {
        if (empty($this->locations)) {
            return false;
        }

        $this->costMatrix = [];
        $n_locations = count($this->locations);
        for ($i = 0; $i < $n_locations; $i++) {
            echo $i + 1 . ". " . $this->locations[$i]->id . "\n";
            for ($j = 0; $j < $n_locations; $j++) {
                $distance = INF;
                if ($i != $j) {
                    $loc1 = $this->locations[$i];
                    $loc2 = $this->locations[$j];
                    $distance = Location::distance(
                        $loc1->latitude,
                        $loc1->longitude,
                        $loc2->latitude,
                        $loc2->longitude
                    );
                }
                $this->costMatrix[$i][$j] = $distance;
            }
        }

        $this->n = count($this->costMatrix);

        return true;
    }

    public function addLocation($location)
    {
        try {
            $location = Location::getInstance($location);
        } catch (Exception $e) {
            return false;
        }

        $this->locations[] = $location;

        return true;
    }

    protected function rowReduction(&$reducedMatrix, &$row)
    {
        // initialize row array to INF
        $row = array_fill(0, $this->n, INF);

        // row[i] contains minimum in row i
        for ($i = 0; $i < $this->n; $i++) {
            for ($j = 0; $j < $this->n; $j++) {
                if ($reducedMatrix[$i][$j] < $row[$i]) {
                    $row[$i] = $reducedMatrix[$i][$j];
                }
            }
        }

        // reduce the minimum value from each element in each row.
        for ($i = 0; $i < $this->n; $i++) {
            for ($j = 0; $j < $this->n; $j++) {
                if ($reducedMatrix[$i][$j] !== INF && $row[$i] !== INF) {
                    $reducedMatrix[$i][$j] -= $row[$i];
                }
            }
        }
    }

    protected function columnReduction(&$reducedMatrix, &$col)
    {
        // initialize row array to INF
        $col = array_fill(0, $this->n, INF);

        // col[i] contains minimum in row i
        for ($i = 0; $i < $this->n; $i++) {
            for ($j = 0; $j < $this->n; $j++) {
                if ($reducedMatrix[$i][$j] < $col[$j]) {
                    $col[$j] = $reducedMatrix[$i][$j];
                }
            }
        }

        // reduce the minimum value from each element in each row.
        for ($i = 0; $i < $this->n; $i++) {
            for ($j = 0; $j < $this->n; $j++) {
                if ($reducedMatrix[$i][$j] !== INF && $col[$j] !== INF) {
                    $reducedMatrix[$i][$j] -= $col[$j];
                }
            }
        }
    }

    protected function calculateCost(&$reducedMatrix)
    {
        // initialize cost to 0
        $cost = 0;

        // Row Reduction
        $row = [];
        $this->rowReduction($reducedMatrix, $row);

        // Column Reduction
        $col = [];
        $this->columnReduction($reducedMatrix, $col);

        // the total expected cost
        // is the sum of all reductions
        for ($i = 0; $i < $this->n; $i++) {
            $cost += ($row[$i] !== INF) ? $row[$i] : 0;
            $cost += ($col[$i] !== INF) ? $col[$i] : 0;
        }

        return $cost;
    }

    public function printPath($list)
    {
        echo "\nPath:\n";
        for ($i = 0; $i < count($list); $i++) {
            $start = $list[$i][0] + 1;
            $end = $list[$i][1] + 1;
            echo $start . " -> " . $end . "\n";
        }
    }

    public function solve()
    {
        if (empty($this->costMatrix)) {
            if (!$this->loadMatrix()) {
                return false;
            }
        }

        $costMatrix = $this->costMatrix;
        // Create a priority queue to store live nodes of
        // search tree;
        $pq = new PriorityQueue();

        // create a root node and calculate its cost
        // The TSP starts from first city i.e. node 0
        $root = new Node($costMatrix, null, 0, -1, 0);
        // get the lower bound of the path starting at node 0
        $root->cost = $this->calculateCost($root->reducedMatrix);

        // Add root to list of live nodes;
        $pq->insert($root, $root->cost);

        // Finds a live node with least cost,
        // add its children to list of live nodes and
        // finally deletes it from the list.
        while ($pq->valid()) {
            // Find a live node with least estimated cost
            $min = $pq->extract();

            // Clear the max estimated nodes
            $pq = new PriorityQueue();

            // i stores current city number
            $i = $min->vertex;

            // if all cities are visited
            if ($min->level == $this->n - 1) {
                // return to starting city
                $min->path[] = [$i, 0];
                // print list of cities visited;
                $this->printPath($min->path);

                // return optimal cost & etc.
                return ['cost' => $min->cost, 'path' => $min->path, 'locations' => $this->locations];
            }

            // do for each child of min
            // (i, j) forms an edge in space tree
            for ($j = 0; $j < $this->n; $j++) {
                if ($min->reducedMatrix[$i][$j] !== INF) {
                    // create a child node and calculate its cost
                    $child = new Node($min->reducedMatrix, $min->path, $min->level + 1, $i, $j);

                    /* Cost of the child =
                        cost of parent node +
                        cost of the edge(i, j) +
                        lower bound of the path starting at node j
                    */
                    $child->cost = $min->cost + $min->reducedMatrix[$i][$j]
                        + $this->calculateCost($child->reducedMatrix);

                    // Add child to list of live nodes
                    $pq->insert($child, $child->cost);
                }
            }

            // free node as we have already stored edges (i, j) in vector
            // So no need for parent node while printing solution.
            $min = null;
        }
    }
}
