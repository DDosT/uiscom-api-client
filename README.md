#### Пример использования:

```php
use \CBH\UiscomClient\Configuration\Configuration;
use \CBH\UiscomClient\ApiFactory;

...

$httpClient = new GuzzleHttp\Client();
$config = new Configuration($httpClient);

// Если необходимо логировать работу клиента — можно передать объект,
// реализующий интерфейс Psr\Log\LoggerInterface
$logger = new Monolog\Logger('log_channel');
$config->setLogger($logger);

// Создаем api клиент
$apiClient = ApiFactory::makeApiClientWithApiKey('api_key', $config);

...

// Пример обращения к методу start.simple_call сервиса CallApi
// https://comagic.github.io/call-api/#start.simple_call
try {
    $call = $apiClient->callApi()->getSimpleCall();
    $call->setOperatorFirst();
    $call->setContactPhoneNumber('71111111111');
    $call->setOperatorPhoneNumber('72222222222');
    ...
    $callSessionEntity = $call->execute();
} catch (CBH\UiscomClient\Exceptions\BaseException $e) {
    ...
}
```

##### Можно передать параметры через конструктор
```php
$params = [
    'first_call' => 'operator',
    'contact'    => '71111111111',
    'operator'   => '72222222222',
    ...
];
try {
    $call = $apiClient->callApi()->getSimpleCall($params);
    $callSessionEntity = $call->execute();
} catch (CBH\UiscomClient\Exceptions\BaseException $e) {
    ...
}
```

##### Пример отчета по звонкам
```php
$config = new \CBH\UiscomClient\Configuration\Configuration(new GuzzleHttp\Client());
$apiClient = \CBH\UiscomClient\ApiFactory::makeApiClientWithApiKey('api_key', $config);

try {
    $callsReport = $apiClient->dataApi()->getCallsReport();

    $callsReport->setPaginator(new \CBH\UiscomClient\Services\DataApi\Pagination\Paginator(20));

    $callsReport->setFields([
        \CBH\UiscomClient\Services\DataApi\Fields\Call::CALL_SESSION_ID,
        \CBH\UiscomClient\Services\DataApi\Fields\Call::CALL_API_EXTERNAL_ID,
    ]);

    $callsReport->setDateFrom(new DateTime('2020-04-20 00:00:00'));
    $callsReport->setDateTill(new DateTime('2020-04-20 23:59:00'));

    $filter = new \CBH\UiscomClient\Services\DataApi\Filters\FilterWrapper(
        \CBH\UiscomClient\Services\DataApi\Filters\FilterWrapper::AND_COND
    );

    $filter->addFilter(new \CBH\UiscomClient\Services\DataApi\Filters\Filter(
        \CBH\UiscomClient\Services\DataApi\Fields\Call::SOURCE,
        \CBH\UiscomClient\Services\DataApi\Filters\Comparison::EQUAL,
        \CBH\UiscomClient\Constants::CALL_SOURCE_CALL_API
    ));
    $filter->addFilter(new \CBH\UiscomClient\Services\DataApi\Filters\Filter(
        \CBH\UiscomClient\Services\DataApi\Fields\Call::CALL_API_EXTERNAL_ID,
        'like',
        '%some_id%'
    ));
    $callsReport->setFilter($filter);

    print_r($callsReport->execute());
} catch (\Exception $e) {
    ...
}
```

##### Пример отчета по расходам
```php
$config = new \CBH\UiscomClient\Configuration\Configuration(new GuzzleHttp\Client());
$apiClient = \CBH\UiscomClient\ApiFactory::makeApiClientWithApiKey('api_key', $config);

try {
    $financeReport = $apiClient->dataApi()->getFinancialCallLegsReport();

    $financeReport->setPaginator(new \CBH\UiscomClient\Services\DataApi\Pagination\Paginator(20));

    $financeReport->setFields([
        \CBH\UiscomClient\Services\DataApi\Fields\FinancialCallLeg::CALL_SESSION_ID,
        \CBH\UiscomClient\Services\DataApi\Fields\FinancialCallLeg::TOTAL_CHARGE,
        \CBH\UiscomClient\Services\DataApi\Fields\FinancialCallLeg::START_TIME,
        \CBH\UiscomClient\Services\DataApi\Fields\FinancialCallLeg::BONUSES_CHARGE,
        \CBH\UiscomClient\Services\DataApi\Fields\FinancialCallLeg::COST_PER_MINUTE,
    ]);

    $financeReport->setDateFrom(new DateTime('2020-04-20 00:00:00'));
    $financeReport->setDateTill(new DateTime('2020-04-20 23:59:00'));

    $filter = new \CBH\UiscomClient\Services\DataApi\Filters\Filter(
        \CBH\UiscomClient\Services\DataApi\Fields\FinancialCallLeg::CALL_SESSION_ID,
        \CBH\UiscomClient\Services\DataApi\Filters\Comparison::EQUAL,
        123456789
    );

    $financeReport->setFilter($filter);

    print_r($financeReport->execute());
} catch (\Exception $e) {
    ...
}
```

#### Расширение клиента, собственной реализацией методов
На примере https://comagic.github.io/data-api/Account/
```php
class GetAccount extends CBH\UiscomClient\Services\DataApi\Resources\AbstractDataApiResource
{
    /**
     * Имя метода ресурса, как он называется на стороне UIS
     *
     * @return string
     */
    public function getResourceName(): string
    {
        return 'get.account';
    }

    /**
     * @return string
     */
    public function getMethodName(): string
    {
        return 'getAccount';
    }

    public function buildParamsForRequest(): array
    {
        return [];
    }
}

...

$httpClient = new GuzzleHttp\Client();
$config = new Configuration($httpClient);
$apiClient = ApiFactory::makeApiClientWithApiKey('api_key', $config);

$apiClient->dataApi()->registerResource(new GetAccount());
```
