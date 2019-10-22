<?php

declare(strict_types=1);

namespace Mews\PayTr;

class Config
{
    /**
     * @var string
     */
    private $apiUrl;

    /**
     * @var string
     */
    private $merchantId;

    /**
     * @var string
     */
    private $merchantKey;

    /**
     * @var string
     */
    private $merchantSalt;

    /**
     * @var string
     */
    private $successUrl;

    /**
     * @var string
     */
    private $failUrl;

    public function __construct(?array $config = [])
    {
        if ($config) {
            $this
                ->setApiUrl($config['apiUrl'])
                ->setMerchantId($config['merchantId'])
                ->setMerchantKey($config['merchantKey'])
                ->setMerchantSalt($config['merchantSalt'])
                ->setSuccessUrl($config['successUrl'])
                ->setFailUrl($config['failUrl']);
        }
    }

    /**
     * @return string
     */
    public function getApiUrl(): string
    {
        return $this->apiUrl;
    }

    /**
     * @param string $apiUrl
     * @return $this
     */
    public function setApiUrl(string $apiUrl): self
    {
        $this->apiUrl = $apiUrl;

        return $this;
    }

    /**
     * @return string
     */
    public function getMerchantId(): string
    {
        return $this->merchantId;
    }

    /**
     * @param string $merchantId
     * @return $this
     */
    public function setMerchantId(string $merchantId): self
    {
        $this->merchantId = $merchantId;

        return $this;
    }

    /**
     * @return string
     */
    public function getMerchantKey(): string
    {
        return $this->merchantKey;
    }

    /**
     * @param string $merchantKey
     * @return $this
     */
    public function setMerchantKey(string $merchantKey): self
    {
        $this->merchantKey = $merchantKey;

        return $this;
    }

    /**
     * @return string
     */
    public function getMerchantSalt(): string
    {
        return $this->merchantSalt;
    }

    /**
     * @param string $merchantSalt
     * @return $this
     */
    public function setMerchantSalt(string $merchantSalt): self
    {
        $this->merchantSalt = $merchantSalt;

        return $this;
    }

    /**
     * @return string
     */
    public function getSuccessUrl(): string
    {
        return $this->successUrl;
    }

    /**
     * @param string $successUrl
     * @return $this
     */
    public function setSuccessUrl(string $successUrl): self
    {
        $this->successUrl = $successUrl;

        return $this;
    }

    /**
     * @return string
     */
    public function getFailUrl(): string
    {
        return $this->failUrl;
    }

    /**
     * @param string $failUrl
     * @return $this
     */
    public function setFailUrl(string $failUrl): self
    {
        $this->failUrl = $failUrl;

        return $this;
    }
}
