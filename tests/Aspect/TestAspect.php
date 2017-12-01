<?php

namespace Go\Zend\Framework\Tests\Aspect;

use Go\Aop\Aspect;
use Go\Aop\Intercept\MethodInvocation;
use Go\Lang\Annotation\Around;

/**
 * @package Go\Zend\Framework\Tests\Aspect
 */
class TestAspect implements Aspect
{
    /**
     * @param MethodInvocation $invocation
     * @Around("execution(public Go\Zend\Framework\Tests\Advice\TestAdvice->get*(*))")
     */
    public function aspectAdvice(MethodInvocation $invocation)
    {
    }
}