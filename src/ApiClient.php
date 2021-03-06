<?php
declare(strict_types=1);

namespace CBH\UiscomClient;

use CBH\UiscomClient\Contracts\ConfigurationInterface;
use CBH\UiscomClient\Http\Requester;
use CBH\UiscomClient\Services\DataApi;
use CBH\UiscomClient\Services\CallApi;

/**
 * Class ApiClient.
 */
class ApiClient
{
    /**
     * @var DataApi\ApiWrapper
     */
    private $dataApiWrapper;

    /**
     * @var CallApi\ApiWrapper
     */
    private $callApiWrapper;

    /**
     * ApiClient constructor.
     *
     * @param ConfigurationInterface $config
     */
    public function __construct(ConfigurationInterface $config)
    {
        $requester = new Requester($config);

        $this->dataApiWrapper = new DataApi\ApiWrapper($requester, $config);
        $this->callApiWrapper = new CallApi\ApiWrapper($requester, $config);
    }

    /**
     * @return DataApi\ApiWrapper
     */
    public function dataApi(): DataApi\ApiWrapper
    {
        return $this->dataApiWrapper;
    }

    /**
     * @return CallApi\ApiWrapper
     */
    public function callApi(): CallApi\ApiWrapper
    {
        return $this->callApiWrapper;
    }
}
