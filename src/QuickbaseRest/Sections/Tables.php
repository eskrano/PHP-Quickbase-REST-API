<?php

namespace Eskrano\QuickbaseRest\Sections;

class Tables extends AbstractSection
{

    /**
     * Create table
     *
     * @param string $app_id
     * @param string $table_name
     * @param string $description
     * @param string $single_record_name
     * @param string $plural_record_name
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function create(
        string $app_id,
        string $table_name,
        string $description,
        string $single_record_name,
        string $plural_record_name
    )
    {
        $create = [
            'name' => $table_name,
            'description' => $description,
            'singleRecordName' => $single_record_name,
            'pluralRecordName' => $plural_record_name
        ];


        return $this->client->query(
            'POST',
            'tables',
            $create,
            [
                'appId' => $app_id,
            ]
        );
    }

    /**
     * Get all tables from the APP
     *
     * @param string $app_id
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getTablesFromApp(string $app_id)
    {
        return $this->client->query(
            'GET',
            'tables',
            [],
            [
                'appId' => $app_id
            ]
        );
    }

    /**
     * Get one table info
     *
     * @param string $app_id
     * @param string $table_id
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getTable(string $app_id, string $table_id)
    {
        return $this->client->query(
            'GET',
            'tables/' . $table_id,
            [],
            [
                'appId' => $app_id
            ]
        );
    }

    /**
     * Update table
     *
     * @param string $app_id
     * @param string $table_id
     * @param array $update
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function updateTable(string $app_id, string $table_id, array $update)
    {
        $update_final = [
            'name' => $update['table_name'],
            'description' => $update['description'],
            'singleRecordName' => $update['single_record_name'],
            'pluralRecordName' => $update['plural_record_name'],
        ];

        return $this->client->query(
            'POST',
            'tables/' . $table_id,
            $update_final,
            [
                'appId' => $app_id,
            ]
        );
    }


    public function deleteTable(string $app_id, string $table_id)
    {
        return $this->client->query(
            'DELETE',
            'tables/' . $table_id,
            [],
            [
                'appId' => $app_id
            ]
        );
    }
}