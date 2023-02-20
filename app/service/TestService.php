<?php
declare(strict_types = 1);
/**
 * The file is part of Dcr/framework
 *
 *
 */

namespace app\service;

use App\Repository\TestRepository;
use DI\Attribute\Inject;
use support\Db;

/**
 * Class TestService
 * @package App\Service
 */
class TestService
{
    /**
     * @var TestRepository
     */
    #[Inject]
    public TestRepository $testRepository;

    /**
     * 构造方式注入
     * TestService constructor.
     *
     * @param  TestRepository  $t
     */
    public function __construct(TestRepository $t)
    {
//        $this->testRepository = $t;
    }

    public function testDi(): void
    {
        echo 'test Di';
        echo PHP_EOL;
        $this->testRepository->fromRepos();
    }
}
