<?php
declare(strict_types=1);


namespace app\aspect;

use app\service\UserService;
use Hyperf\Di\Aop\AbstractAspect;
use Hyperf\Di\Aop\ProceedingJoinPoint;
class DebugAspect extends AbstractAspect
{
    public array $classes = [
        UserService::class . '::first',
    ];

    public function process(ProceedingJoinPoint $proceedingJoinPoint)
    {
        var_dump('test aop');
        return $proceedingJoinPoint->process();
    }
}