<?php

namespace Eskrano\QuickbaseRest;

class PerformanceMonitor
{
    /**
     * @var array
     */
    protected $queries = [];

    /**
     * @param array $query
     * @return $this
     */
    public function save(array $query)
    {
        $this->queries[] = $query;

        return $this;
    }

    /**
     * @return array
     */
    public function getQueries()
    {
        return $this->queries;
    }

    /**
     * @return mixed|null
     */
    public function getMostLongestQuery()
    {
        $longest = null;

        foreach ($this->getQueries() as $query) {
            if ($longest !== null && $query['ms'] >= $longest['ms']) {
                $longest = $query;
            } else {
                $longest = $query;
            }
        }

        return $longest;
    }

    public function exportToFile(string $destination)
    {
        $put_result = file_put_contents(
            $destination,
            json_encode($this->getQueries(), JSON_PRETTY_PRINT)
        );

        return $put_result;
    }
}