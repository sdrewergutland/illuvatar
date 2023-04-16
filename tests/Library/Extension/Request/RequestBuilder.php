<?php

declare(strict_types=1);

namespace App\Tests\Library\Extension\Request;

use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\BrowserKit\CookieJar;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;

final class RequestBuilder
{
    private string $method = Request::METHOD_GET;
    private ?string $uri = null;

    /**
     * @var array<string, string|array<int|string>>
     */
    private array $parameters = [];
    /**
     * @var array <string, string|int|null>
     */
    private array $queryParameters = [];
    /**
     * @var array<string, string>
     */
    private array $server = [];
    private ?string $content = null;
    /**
     * @var array<string, int|string>
     */
    private array $uriParamsInPath = [];
    /**
     * @var array<int, Cookie>
     */
    private array $cookies = [];
    /**
     * @var array<string,UploadedFile>
     */
    private array $filesToUpload = [];

    public function __construct()
    {
    }

    /**
     * @return array{string, string, array<string, string|array<int|string>>, array<string, UploadedFile>, array<string, string>, string|null}
     */
    public function build(?CookieJar $cookieJar = null): array
    {
        assert($this->canBuild());

        if (!empty($this->cookies)) {
            if (!$cookieJar) {
                throw new \InvalidArgumentException('When setting cookies, you need a jar');
            }
            foreach ($this->cookies as $cookie) {
                $cookieJar->set($cookie);
            }
        }

        return [
            $this->method,
            $this->assembleUri(),
            $this->parameters,
            $this->filesToUpload,
            $this->server,
            $this->content,
        ];
    }

    private function assembleUri(): string
    {
        $uri = $this->uri;
        assert(null !== $uri);
        foreach ($this->uriParamsInPath as $key => $value) {
            $uri = str_replace('{' . $key . '}', (string) $value, $uri);
        }

        if (preg_match('/\{.*\}/', $uri)) {
            throw new \InvalidArgumentException('There are still non-replaced params in your uri "' . $uri . '". Sure you did not forget something?');
        }

        if ($this->queryParameters) {
            $uri .= '?' . http_build_query($this->queryParameters);
        }

        return $uri;
    }

    public function asPost(): self
    {
        $this->withMethod(Request::METHOD_POST);

        if (null === $this->content) {
            $this->withBodyAsJson([]);
        }

        return $this;
    }

    public function asDelete(): self
    {
        $this->withMethod(Request::METHOD_DELETE);

        return $this;
    }

    public function withQueryParameter(string $key, string|int|null $value): self
    {
        $this->queryParameters[$key] = $value;

        return $this;
    }

    public function withFileToUpload(string $key, string $fileName, string $filePath): self
    {
        $this->filesToUpload[$key] = new UploadedFile(
            path: $filePath,
            originalName: $fileName,
        );

        $this->content = null;

        return $this;
    }

    public function withMethod(string $method): self
    {
        $this->method = $method;

        return $this;
    }

    public function withUriParamInPath(string $key, string|int $value): self
    {
        $this->uriParamsInPath[$key] = $value;

        return $this;
    }

    public function withUri(string|\BackedEnum $uri): self
    {
        if ($uri instanceof \BackedEnum) {
            $uri = (string) $uri->value;
        }

        $this->uri = $uri;

        return $this;
    }

    public function withServerHeader(string $name, string $value): self
    {
        $this->server[$name] = $value;

        return $this;
    }

    public function removeServerHeader(string $name): self
    {
        unset($this->server[$name]);
        ksort($this->server);

        return $this;
    }

    /**
     * @param string|array<int|string> $value
     */
    public function withParameter(string $name, string|array $value): self
    {
        $this->parameters[$name] = $value;

        return $this;
    }

    /**
     * @param array<string,mixed> $body
     */
    public function withBodyAsJson(array $body): self
    {
        $this->content = json_encode($body, JSON_THROW_ON_ERROR);

        return $this;
    }

    public function withHttpBasicAuth(string $user, string $password): self
    {
        $this
            ->withServerHeader('PHP_AUTH_USER', $user)
            ->withServerHeader('PHP_AUTH_PW', $password)
        ;

        return $this;
    }

    public function withCookie(Cookie $cookie): self
    {
        $this->cookies[] = $cookie;

        return $this;
    }

    private function canBuild(): bool
    {
        if (null === $this->uri) {
            return false;
        }

        return true;
    }
}
