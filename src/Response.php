<?php

declare(strict_types=1);

namespace Mews\PayTr;

class Response
{
    /**
     * @var array
     */
    private $content;

    /**
     * @var bool
     */
    private $isSuccess;

    /**
     * @return array
     */
    public function getContent(): array
    {
        return $this->content;
    }

    /**
     * @param array $content
     * @return $this
     */
    public function setContent(array $content): self
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return bool
     */
    public function isSuccess(): bool
    {
        $content = $this->getContent();

        if ('success' == $content['status']) {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function isError(): bool
    {
        $content = $this->getContent();

        if ('success' == $content['status']) {
            return false;
        }

        return true;
    }

    /**
     * @return string|null
     */
    public function getMessage(): ?string
    {
        $content = $this->getContent();

        return isset($content['reason']) ? $content['reason'] : null;
    }

    /**
     * @return string|null
     */
    public function getToken(): ?string
    {
        $content = $this->getContent();

        return isset($content['token']) ? $content['token'] : null;
    }
}
