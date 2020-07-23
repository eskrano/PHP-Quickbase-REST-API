<?php

namespace Eskrano\QuickbaseRest\Sections;

class Records extends AbstractSection
{

    /**
     * Query records
     * @param string $from
     * @param string $where
     * @param array $select
     * @param array $sortBy
     * @param array $groupBy
     * @param array $options
     * @return mixed
     */
    public function query(
        string $from,
        string $where,
        array $select = [],
        array $sortBy = [],
        array $groupBy = [],
        array $options = []
    )
    {
        $request_body = compact('from', 'where', 'select', 'sortBy', 'groupBy', 'options');

        foreach ($request_body as $k => $v) {
            if (is_array($v) && count($v) == 0) {
                unset($request_body[$k]);
            }
        }
        $request_handle = $this->client->query(
            'POST',
            'records/query',
            $request_body
        );


        return json_decode($request_handle->getBody()->getContents(), $this->client->convert_json_to_array);
    }

    public function deleteRecords(string $from, string $where)
    {
        return $this->client->query(
            'DELETE',
            'records',
            compact('from','where')
        );
    }

    /**
     * @param $to
     * @param array $data
     * @param array $fieldsToReturn
     * @return false|string
     */
    public function addRecords($to, array $data, array $fieldsToReturn = [])
    {

        $request_body = [];

        foreach ($data as $rid => $value) {
            $_append = $value;
            $request_body[] = $this->buildRequestBodyData($_append);
        }

        return $this->_insert(
            $to,
            $request_body,
            $fieldsToReturn
        );
    }

    /**
     * @param $to
     * @param array $data
     * @param int $key_fid
     * @param array $fieldsToReturn
     * @return false|string
     */
    public function editRecords($to, array $data, int $key_fid = 3, array $fieldsToReturn = [])
    {
        $request_body = [];

        foreach ($data as $rid => $value) {
            $_append = $value;
            $_append[$key_fid] = $rid;

            $request_body[] = $this->buildRequestBodyData($_append);
        }

        return $this->_insert(
            $to,
            $request_body,
            $fieldsToReturn
        );
    }


    /**
     * @param array $data
     * @return array
     */
    protected function buildRequestBodyData(array $data = [])
    {
        $return = [];

        foreach ($data as $k => $value) {
            $return[$k] = compact('value');
        }

        return $return;
    }

    /**
     * @param $to
     * @param array $data
     * @param array $fieldsToReturn
     * @return false|string
     */
    protected function _insert($to, array $data, array $fieldsToReturn = [])
    {
        $body = compact('to', 'data', 'fieldsToReturn');

        foreach ($body as $k => $v) {
            if (is_array($v) && count($v) == 0) {
                unset($body[$k]);
            }
        }

        $response = $this->client->query(
            'POST',
            'records',
            $body
        );

        return json_decode(
            $response->getBody()->getContents(),
            $this->client->convert_json_to_array
        );
    }
}