<?php

namespace Eskrano\QuickbaseRest\Sections;

class Reports extends AbstractSection
{
    /**
     * Table reports (all reports)
     * @see https://developer.quickbase.com/operation/getTableReports
     * @param string $table_id
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function tableReports(string $table_id)
    {
        return $this->client->query(
            'GET',
            'reports',
            [],
            [
                'tableId' => $table_id
            ],
        );
    }

    /**
     * Get report
     * @see https://developer.quickbase.com/operation/getReport
     * @param string $table_id
     * @param int $report_id
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getReport(string $table_id, int $report_id)
    {
        return $this->client->query(
            'GET',
            'reports/' . $report_id,
            [],
            [
                'tableId' => $table_id
            ],
        );
    }

    /**
     * Run report
     * @see https://developer.quickbase.com/operation/runReport
     * @param string $table_id
     * @param int $report_id
     * @param int $skip
     * @param int $top
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function runReport(string $table_id, int $report_id, int $skip = 0, int $top = 100)
    {
        return $this->client->query(
            'GET',
            'reports/' . $report_id,
            [],
            [
                'tableId' => $table_id,
                'skip' => $skip,
                'top' => $top,
            ],
        );
    }
}