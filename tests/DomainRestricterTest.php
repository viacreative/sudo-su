<?php

use Illuminate\Support\Facades\Config;
use VIACreative\SudoSu\DomainRestricter;

class DomainRestricterTest extends PHPUnit\Framework\TestCase
{
    /**
     * @var \VIACreative\SudoSu\DomainRestricter
     */
    protected $restricter;

    public function setUp()
    {
        parent::setUp();

        $this->restricter = new DomainRestricter();
    }

    /** @test */
    public function it_allows_for_wildcard_domains()
    {
        $allowedDomains = [
            '*.bar.dev'
        ];

        Config::shouldReceive('get')
            ->once()
            ->andReturn($allowedDomains);

        $this->assertTrue($this->restricter->check('http://foo.bar.dev'));
        $this->assertFalse($this->restricter->check('http://bar.foo.dev'));
    }

    /** @test */
    public function it_allows_for_a_specific_domain()
    {
        $allowedDomains = [
            'foo-bar.dev'
        ];

        Config::shouldReceive('get')
            ->once()
            ->andReturn($allowedDomains);

        $this->assertTrue($this->restricter->check('http://foo-bar.dev'));
        $this->assertFalse($this->restricter->check('http://some-other.local'));
    }

    /** @test */
    public function it_allows_for_localhost()
    {
        $allowedDomains = [
            'localhost'
        ];

        Config::shouldReceive('get')
            ->once()
            ->andReturn($allowedDomains);

        $this->assertTrue($this->restricter->check('http://localhost'));
        $this->assertFalse($this->restricter->check('http://some-app.local'));
    }

    /** @test */
    public function it_allows_for_specific_ports()
    {
        $allowedDomains = [
            'localhost:8080'
        ];

        Config::shouldReceive('get')
            ->once()
            ->andReturn($allowedDomains);

        $this->assertTrue($this->restricter->check('http://localhost:8080'));
        $this->assertFalse($this->restricter->check('http://localhost:80'));
    }
}
