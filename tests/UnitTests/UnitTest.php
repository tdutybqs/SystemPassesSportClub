<?php

require_once __DIR__ . '/../../src/Infrastructure/AppConfig.php';
require_once __DIR__ . '/../../src/Infrastructure/appRun.php';
require_once __DIR__ . '/../../src/Infrastructure/Logger/NullLogger/Logger.php';
require_once __DIR__ . '/../../src/Infrastructure/Logger/Factory.php';
require_once __DIR__ . '/../../src/Infrastructure/Logger/LoggerInterface.php';

/**
 * Вычисляет расхождение массивов с дополнительной проверкой индекса. Поддержка многомерных массивов
 * @param array $a1
 * @param array $a2
 * @return array
 */
function array_diff_assoc_recursive(array $a1, array $a2): array
{
    $result = [];
    foreach ($a1 as $k1 => $v1) {
        if (false === array_key_exists($k1, $a2)) {
            $result[$k1] = $v1;
            continue;
        }

        if (is_iterable($v1) && is_iterable($a2[$k1])) {
            $resultCheck = array_diff_assoc_recursive($v1, $a2[$k1]);
            if (count($resultCheck) > 0) {
                $result[$k1] = $resultCheck;
            }
            continue;
        }

        if ($v1 !== $a2[$k1]) {
            $result[$k1] = $v1;
        }
    }
    return $result;
}

/**
 * Тестирование приложения
 */
class UnitTest
{
    /**
     * Провайдер данных для тестов
     * @return array
     */
    private static function testDataProvider(): array
    {
        $loggerFactory = static function (): LoggerInterface {
            return new Logger();
        };
        $handlers = include __DIR__ . '/../../config/request.handler.php';
        return [
            [
                'testName' => 'Тестирование ситуации когда данные о льготах некорректны. Нет поля - end',
                'in' => [
                    $handlers,
                    'http://localhost:8080/benefit_pass?customer_id=5',
                    'Factory::create',
                    static function () {
                        $config = include __DIR__ . '/../../config/dev/config.php';
                        $config['pathToBenefitPass'] = __DIR__ . '/../data/broken_benefit_pass.json';
                        return AppConfig::createFromArray($config);
                    }
                ],
                'out' => [
                    'httpCode' => 503,
                    'result' => [
                        'status' => 'fail',
                        'message' => 'Отсутствуют обязательные элементы: end'
                    ]
                ]
            ],
            [
                'testName' => 'Тестирование ситуации когда данные о льготах некорректны. Нет поля - end',
                'in' => [
                    $handlers,
                    'http://localhost:8080/benefit_pass?customer_id=5',
                    $loggerFactory,
                    static function () {
                        $config = include __DIR__ . '/../../config/dev/config.php';
                        $config['pathToBenefitPass'] = __DIR__ . '/../data/broken_benefit_pass.json';
                        return AppConfig::createFromArray($config);
                    }
                ],
                'out' => [
                    'httpCode' => 503,
                    'result' => [
                        'status' => 'fail',
                        'message' => 'Отсутствуют обязательные элементы: end'
                    ]
                ]
            ],
            [
                'testName' => 'Тестирование ситуации когда некорректно указан путь до файла с льготами',
                'in' => [
                    $handlers,
                    'http://localhost:8080/benefit_pass?customer_id=5',
                    $loggerFactory,
                    static function () {
                        $config = include __DIR__ . '/../../config/dev/config.php';
                        $config['pathToBenefitPass'] = __DIR__ . '/../data/broken_benаefit_pass.json';
                        return AppConfig::createFromArray($config);
                    }
                ],
                'out' => [
                    'httpCode' => 500,
                    'result' => [
                        'status' => 'fail',
                        'message' => 'Некорректный путь до файла с данными'
                    ]
                ]
            ],
            [
                'testName' => 'Тестирование ситуации когда данные об абонементах некорректны. Нет поля - discount',
                'in' => [
                    $handlers,
                    'http://localhost:8080/pass?customer_id=5',
                    $loggerFactory,
                    static function () {
                        $config = include __DIR__ . '/../../config/dev/config.php';
                        $config['pathToPass'] = __DIR__ . '/../data/broken_pass.json';
                        return AppConfig::createFromArray($config);
                    }
                ],
                'out' => [
                    'httpCode' => 503,
                    'result' => [
                        'status' => 'fail',
                        'message' => 'Отсутствуют обязательные элементы: discount'
                    ]
                ]
            ],
            [
                'testName' => 'Тестирование ситуации когда некорректно указан путь до файла с абонементами',
                'in' => [
                    $handlers,
                    'http://localhost:8080/pass?customer_id=5',
                    $loggerFactory,
                    static function () {
                        $config = include __DIR__ . '/../../config/dev/config.php';
                        $config['pathToPass'] = __DIR__ . '/../data/brokпen_pass.json';
                        return AppConfig::createFromArray($config);
                    }
                ],
                'out' => [
                    'httpCode' => 500,
                    'result' => [
                        'status' => 'fail',
                        'message' => 'Некорректный путь до файла с данными'
                    ]
                ]
            ],
            [
                'testName' => 'Тестирование ситуации когда данные о программах некорректны. Нет поля - duration',
                'in' => [
                    $handlers,
                    'http://localhost:8080/programmes?name=Суставная гимнастика',
                    $loggerFactory,
                    static function () {
                        $config = include __DIR__ . '/../../config/dev/config.php';
                        $config['pathToProgrammes'] = __DIR__ . '/../data/broken_programmes.json';
                        return AppConfig::createFromArray($config);
                    }
                ],
                'out' => [
                    'httpCode' => 503,
                    'result' => [
                        'status' => 'fail',
                        'message' => 'Отсутствуют обязательные элементы: duration'
                    ]
                ]
            ],
            [
                'testName' => 'Тестирование ситуации когда некорректно указан путь до файла с программами',
                'in' => [
                    $handlers,
                    'http://localhost:8080/pass?customer_id=5',
                    $loggerFactory,
                    static function () {
                        $config = include __DIR__ . '/../../config/dev/config.php';
                        $config['pathToProgrammes'] = __DIR__ . '/../data/brokпen_pafss.json';
                        return AppConfig::createFromArray($config);
                    }
                ],
                'out' => [
                    'httpCode' => 500,
                    'result' => [
                        'status' => 'fail',
                        'message' => 'Некорректный путь до файла с данными'
                    ]
                ]
            ],
            // TODO сценарий не тестируется т.к. нет данного handler
//            [
//                'testName' => 'Тестирование ситуации когда данные о клиентах некорректны. Нет поля sex',
//                'in' => [
//                    $handlers,
//                    'http://localhost:8080/customers?customer_id=1',
//                    static function () {
//                    },
//                    static function () {
//                        $config = include __DIR__ . '/../../config/dev/config.php';
//                        $config['pathToCustomers'] = __DIR__ . '/../../Jsons/BrokenJsons/broken_customers.json';
//                        return AppConfig::createFromArray($config);
//                    }
//                ],
//                'out' => [
//                    'httpCode' => 503,
//                    'result' => [
//                        'status' => 'fail',
//                        'message' => 'Отсутствуют обязательные элементы: sex'
//                    ]
//                ]
//            ],
            [
                'testName' => 'Тестирование ситуации когда некорректно указан путь до файла с клиентами',
                'in' => [
                    $handlers,
                    'http://localhost:8080/pass?customer_id=5',
                    $loggerFactory,
                    static function () {
                        $config = include __DIR__ . '/../../config/dev/config.php';
                        $config['pathToCustomers'] = __DIR__ . '/../data/brokпen_pafss.json';
                        return AppConfig::createFromArray($config);
                    }
                ],
                'out' => [
                    'httpCode' => 500,
                    'result' => [
                        'status' => 'fail',
                        'message' => 'Некорректный путь до файла с данными'
                    ]
                ]
            ],
            [
                'testName' => 'Тестирование ситуации когда данные о пурчейзах некорректны. Нет поля - price',
                'in' => [
                    $handlers,
                    'http://localhost:8080/purchased_items?customer_id=1',
                    $loggerFactory,
                    static function () {
                        $config = include __DIR__ . '/../../config/dev/config.php';
                        $config['pathToPurchasedItems'] = __DIR__ . '/../data/broken_purchased_item.json';
                        return AppConfig::createFromArray($config);
                    }
                ],
                'out' => [
                    'httpCode' => 503,
                    'result' => [
                        'status' => 'fail',
                        'message' => 'Отсутствуют обязательные элементы: price'
                    ]
                ]
            ],
            [
                'testName' => 'Тестирование ситуации когда некорректно указан путь до файла с пурчезйами',
                'in' => [
                    $handlers,
                    'http://localhost:8080/purchased_items?customer_id=1',
                    $loggerFactory,
                    static function () {
                        $config = include __DIR__ . '/../../config/dev/config.php';
                        $config['pathToPurchasedItems'] = __DIR__ . '/../data/brokпen_pafss.json';
                        return AppConfig::createFromArray($config);
                    }
                ],
                'out' => [
                    'httpCode' => 500,
                    'result' => [
                        'status' => 'fail',
                        'message' => 'Некорректный путь до файла с данными'
                    ]
                ]
            ],
            // TODO сценарий не тестируется т.к. нет данного handler
//            [
//                'testName' => 'Тестирование ситуации когда данные о клиентах некорректны. Нет поля phone',
//                'in' => [
//                    $handlers,
//                    'http://localhost:8080/employee?customer_id=1',
//                    static function () {
//                    },
//                    static function () {
//                        $config = include __DIR__ . '/../../config/dev/config.php';
//                        $config['pathToCustomers'] = __DIR__ . '/../../Jsons/BrokenJsons/broken_customers.json';
//                        return AppConfig::createFromArray($config);
//                    }
//                ],
//                'out' => [
//                    'httpCode' => 503,
//                    'result' => [
//                        'status' => 'fail',
//                        'message' => 'Отсутствуют обязательные элементы: sex'
//                    ]
//                ]
//            ],
            [
                'testName' => 'Тестирование ситуации когда некорректно указан путь до файла с сотрудниками',
                'in' => [
                    $handlers,
                    'http://localhost:8080/pass?pass_id=5',
                    $loggerFactory,
                    static function () {
                        $config = include __DIR__ . '/../../config/dev/config.php';
                        $config['pathToEmployees'] = __DIR__ . '/../data/employeesf.json';
                        return AppConfig::createFromArray($config);
                    }
                ],
                'out' => [
                    'httpCode' => 500,
                    'result' => [
                        'status' => 'fail',
                        'message' => 'Некорректный путь до файла с данными'
                    ]
                ]
            ],
        ];
    }

    /**
     * Запускает тесты
     * @return void
     * @throws JsonException - не удалось преобразовать в json
     */
    public static function runTests(): void
    {
        foreach (static::testDataProvider() as $testItem) {
            echo "----------" . $testItem['testName'] . "---------\n";
            $appResult = app(...$testItem['in']); // Распаковать в функцию массив

            //Assert
            if ($appResult['httpCode'] === $testItem['out']['httpCode']) {
                echo "    OK - код ответа\n";
            } else {
                echo "    Fail - код ответа. Ожидалось " . $testItem['out']['httpCode'] . ", а получил " . $appResult['httpCode'] . "\n";
            }

            $actualResult = json_decode(json_encode($appResult['result'], JSON_THROW_ON_ERROR), true, 512,
                JSON_THROW_ON_ERROR);

            // Лишние элементы
            $unnecessaryElements = array_diff_assoc_recursive($actualResult, $testItem['out']['result']);

            // Недостающие элементы
            $missingElements = array_diff_assoc_recursive($testItem['out']['result'], $actualResult);

            $errMsg = '';

            if (count($unnecessaryElements) > 0) {
                $errMsg .= sprintf("          Есть лишние элементы %s\n",
                    json_encode($unnecessaryElements, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE));
            }
            if (count($missingElements) > 0) {
                $errMsg .= sprintf("          Есть недостающие элементы %s\n",
                    json_encode($missingElements, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE));
            }

            if ('' === $errMsg) {
                echo "    OK - данные ответа валидны\n";
            } else {
                echo "    Fail - данные ответа валидны\n" . $errMsg;
            }
        }
    }
}

try {
    UnitTest::runTests();
} catch (JsonException $e) {
    var_dump($e);
}
