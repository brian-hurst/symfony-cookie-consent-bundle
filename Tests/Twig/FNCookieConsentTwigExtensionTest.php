<?php

declare(strict_types=1);

/*
 * This file is part of the ConnectHolland CookieConsentBundle package.
 * (c) Connect Holland.
 */

namespace FatalNetwork\CookieConsentBundle\Tests\Twig;

use FatalNetwork\CookieConsentBundle\Twig\FNCookieConsentTwigExtension;
use PHPUnit\Framework\TestCase;
use Symfony\Bridge\Twig\AppVariable;
use Symfony\Component\HttpFoundation\Request;

class FNCookieConsentTwigExtensionTest extends TestCase
{
    /**
     * @var FNCookieConsentTwigExtension
     */
    private $fnCookieConsentTwigExtension;

    public function setUp(): void
    {
        $this->fnCookieConsentTwigExtension = new FNCookieConsentTwigExtension();
    }

    public function testGetFunctions(): void
    {
        $functions = $this->fnCookieConsentTwigExtension->getFunctions();

        $this->assertCount(2, $functions);
        $this->assertSame('fncookieconsent_isCookieConsentSavedByUser', $functions[0]->getName());
        $this->assertSame('fncookieconsent_isCategoryAllowedByUser', $functions[1]->getName());
    }

    public function testIsCookieConsentSavedByUser(): void
    {
        $request  = new Request();

        $appVariable = $this->createMock(AppVariable::class);
        $appVariable
            ->expects($this->once())
            ->method('getRequest')
            ->wilLReturn($request);

        $context = ['app' => $appVariable];
        $result  = $this->fnCookieConsentTwigExtension->isCookieConsentSavedByUser($context);

        $this->assertSame($result, false);
    }

    public function testIsCategoryAllowedByUser(): void
    {
        $request  = new Request();

        $appVariable = $this->createMock(AppVariable::class);
        $appVariable
            ->expects($this->once())
            ->method('getRequest')
            ->wilLReturn($request);

        $context = ['app' => $appVariable];
        $result  = $this->fnCookieConsentTwigExtension->isCategoryAllowedByUser($context, 'analytics');

        $this->assertSame($result, false);
    }
}
