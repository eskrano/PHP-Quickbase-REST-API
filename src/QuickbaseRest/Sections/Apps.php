<?php

namespace Eskrano\QuickbaseRest\Sections;

class Apps extends AbstractSection
{
    /**
     * Create new app
     * @see https://developer.quickbase.com/operation/createApp
     * @param string $name
     * @param string $description
     * @param bool $assign_token
     * @param array $variables
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function createApp(
        string $name,
        string $description,
        bool $assign_token = true,
        array $variables = []
    )
    {
        return $this->client->query(
            'POST',
            'apps',
            [
                'name' => $name,
                'description' => $description,
                'assignToken' => $assign_token,
                'variables' => $variables
            ]
        );
    }

    /**
     * Get app info
     * @see https://developer.quickbase.com/operation/getApp
     * @param string $app_id
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getApp(string $app_id)
    {
        return $this->client->query(
            'GET',
            'apps/'.$app_id,
            []
        );
    }
}