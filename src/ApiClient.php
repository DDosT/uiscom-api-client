<?php
declare(strict_types=1);

namespace CBH\UiscomClient;

use CBH\UiscomClient\Configuration\ConfigurationInterface;
use CBH\UiscomClient\Http\ApiRequest;

/**
 * Class ApiClient.
 */
class ApiClient
{
    /**
     * @var DataApi\Wrapper
     */
    private $dataApiWrapper;

    /**
     * @var CallApi\Wrapper
     */
    private $callApiWrapper;

    /**
     * @var ConfigurationInterface
     */
    private $config;

    private $requester;

    /**
     * ApiClient constructor.
     *
     * @param ConfigurationInterface $config
     */
    public function __construct(ConfigurationInterface $config)
    {
        $this->dataApiWrapper = new DataApi\Wrapper();
        $this->callApiWrapper = new CallApi\Wrapper();
        $this->requester = new ApiRequest();

        $this->config = $config;
    }

    /**
     * @return DataApi\Wrapper
     */
    public function dataApi()
    {
        return $this->dataApiWrapper;
    }

    /**
     * @return CallApi\Wrapper
     */
    public function callApi()
    {
        return $this->callApiWrapper;
    }
}