<?php

namespace Tests\Infrastructure\Http;

use Infrastructure\Http\Route;
use Infrastructure\Http\Contracts\HttpMethod;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class RouteTest extends TestCase
{
    #[Test]
    public function itMatchesRequestMethod(): void
    {
        $route = new Route('test', HttpMethod::Get, fn() => null);

        $this->assertFalse($route->match('http://www.example.com/test', HttpMethod::Post));
    }

    #[Test]
    public function itCanMatchSimplePath(): void
    {
        $route = new Route('test', HttpMethod::Get, fn() => null);

        $this->assertIsArray($route->match('http://www.example.com/test', HttpMethod::Get));

        $this->assertFalse($route->match('http://www.example.com/tested', HttpMethod::Get));
    }

    #[Test]
    public function itCanMatchPathWithEndingSlash(): void
    {
        $route = new Route('test', HttpMethod::Get, fn() => null);

        $this->assertIsArray($route->match('http://www.example.com/test/', HttpMethod::Get));
    }

    #[Test]
    public function itCanMatchPathWithStartingSlash(): void
    {
        $route = new Route('/test', HttpMethod::Get, fn() => null);

        $this->assertIsArray($route->match('http://www.example.com/test', HttpMethod::Get));
    }

    #[Test]
    public function itCanMatchPathWithRegex(): void
    {
        $route = new Route('/test/.+', HttpMethod::Get, fn() => null);

        $this->assertIsArray($route->match('http://www.example.com/test/abcd', HttpMethod::Get));

        $this->assertFalse($route->match('http://www.example.com/test/', HttpMethod::Get));
    }

    #[Test]
    public function itCanMatchPathWithParams(): void
    {
        $route = new Route('/test/:id', HttpMethod::Get, fn() => null);

        $this->assertIsArray($route->match('http://www.example.com/test/abcd', HttpMethod::Get));

        $this->assertFalse($route->match('http://www.example.com/test/', HttpMethod::Get));
    }

    #[Test]
    public function itReturnsParams(): void
    {
        $route = new Route('/test/:id/inner/:innerID', HttpMethod::Get, fn() => null);

        $matches = $route->match('http://www.example.com/test/abcd/inner/1234', HttpMethod::Get);

        $this->assertEquals('abcd', $matches['id']);
        $this->assertEquals('1234', $matches['innerID']);
    }

    #[Test]
    public function itReturnsCustomRegexNamedGroupsAsParams(): void
    {
        $route = new Route('/test/(?<group1>.+)', HttpMethod::Get, fn() => null);

        $matches = $route->match('http://www.example.com/test/asdf', HttpMethod::Get);

        $this->assertEquals('asdf', $matches['group1']);
    }
}