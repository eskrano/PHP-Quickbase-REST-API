<?php

/**
 * Quickbase API client (https://quickbase.com)
 *
 * @author Alex Priadko <mr.eskrano@gmail.com>
 *
 * @version 1.0.0
 */

namespace Eskrano\QuickbaseRest;

use Eskrano\QuickbaseRest\Interfaces\SectionInterface;
use Eskrano\QuickbaseRest\Sections\Records;
use Eskrano\QuickbaseRest\Sections\Tables;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\RequestOptions;
use Psr\Http\Message\ResponseInterface;

class QuickbaseREST
{
    /**
     * @var bool
     */
    public $convert_json_to_array = true;
    /**
     * cURL resource
     * @var
     */
    protected $guzzle;
    /**
     * @var string
     */
    protected $app_token;
    /**
     * Permament User token
     *
     * @var string
     */
    protected $user_token;
    /**
     * Your quickbase app subdomain
     *
     * ex. {realm}.quickbase.com
     *
     * @var string
     */
    protected $realm;
    /**
     * Loaded components
     * @var array
     */
    protected $loaded_sections = [];
    /**
     * @var PerformanceMonitor
     */
    protected $performance;

    /**
     * @var array|bool[]
     */
    protected array $default_config = [
        'wait_on_exceeded' => true,
    ];

    protected array $config = [];

    /**
     * QuickbaseREST constructor.
     * @param string $realm
     */
    public function __construct(
        string $realm,
        string $user_token,
        bool   $preload_sections = true,
        array  $config = [],
    )
    {
        $this->realm = $realm;
        $this->user_token = $user_token;
        $this->guzzle = new Client([
            'base_uri' => 'https://api.quickbase.com/v1/',
            'headers' => [
                'QB-Realm-Hostname' => $this->realm . '.quickbase.com',
                'User-Agent' => 'EskranoQBClientv1',
                'Authorization' => sprintf("QB-USER-TOKEN %s", $this->user_token),
                'Content-Type' => 'application/json; charset=UTF-8',
            ],
        ]);

        if ($preload_sections) {
            foreach (
                [
                    Records::class
                ] as $section
            ) {
                $this->getSection($section);
            }
        }

        $this->performance = new PerformanceMonitor();

        /**
         * Load config
         */

        $this->config = array_merge($this->default_config, $config);
    }

    /**
     * @param string $section
     * @param bool $force_reload
     * @return mixed|string
     * @throws \Exception
     */
    public function getSection(string $section, bool $force_reload = false)
    {
        if (!(new $section) instanceof SectionInterface) {
            throw new \Exception("Section class must be instance of  SectionInterface");
        }

        if (isset ($this->loaded_sections[$section]) && false === $force_reload) {
            return $this->loaded_sections[$section];
        }

        $section_instance = new $section;
        $section_instance->setClient($this);

        $this->loaded_sections[$section] = $section_instance;

        return $section;
    }

    /**
     * @return Records
     * @throws \Exception
     */
    public function records(): Records
    {
        return $this->getSection(Records::class);
    }

    /**
     * @return Tables
     * @throws \Exception
     */
    public function tables(): Tables
    {
        return $this->getSection(Tables::class);
    }

    /**
     * @param string $method
     * @param string $action
     * @param array $body
     * @param array $query_params
     * @return ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function query(string $method, string $action, array $body, array $query_params = []): ResponseInterface
    {
        try {
            $response = $this->runQuery($method, $action, $body, $query_params);

            return $response;
        } catch (RequestException $exception) {

            if ($this->config['wait_on_exceeded'] && $exception->getResponse()->getStatusCode() === 429) {

                $header_key = 'x-ratelimit-reset';

                if ($exception->getResponse()->hasHeader($header_key)) {
                    $sleep_time = $exception->getResponse()->getHeader($header_key)[0] ?? null;


                    if (null !== $sleep_time && intval($sleep_time) > 0) {
                        sleep(intval($sleep_time));

                        return $this->runQuery($method, $action, $body, $query_params);
                    }
                }
            }

            return $exception->getResponse();
        }
    }

    protected function runQuery(string $method, string $action, array $body, array $query_params = []): ResponseInterface
    {
        $_start = microtime(true);

        $response = $this->guzzle->request(
            $method,
            sprintf("%s?%s", $action, http_build_query($query_params)),
            [
                RequestOptions::BODY => json_encode($body),
            ]
        );

        $total_ms = microtime(true) - $_start;

        $this->getPerformanceMonitor()
            ->save([
                'query' => compact('method', 'action', 'body', 'query_params'),
                'ms' => $total_ms
            ]);

        return $response;
    }

    /**
     * @return PerformanceMonitor
     */
    public function getPerformanceMonitor(): PerformanceMonitor
    {
        return $this->performance;
    }
}