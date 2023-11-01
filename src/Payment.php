<?php

declare(strict_types=1);

namespace Mews\PayTr;

use Mews\PayTr\Exceptions\ClientException;
use Mews\PayTr\Exceptions\InvalidOrderDataException;
use Symfony\Component\HttpClient\CurlHttpClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\HttpClient\Exception\HttpExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use TypeError;

class Payment
{
    /**
     * @var Config
     */
    private $config;

    /**
     * @var int
     */
    private $timeOutLimit = 30;

    /**
     * @var bool
     */
    private $testMode = false;

    /**
     * @var bool
     */
    private $debug = false;

    /**
     * @var Order
     */
    private $order;

    /**
     * @var string
     */
    private $hash;

    /**
     * @var array
     */
    private $postData;

    /**
     * @var HttpClientInterface
     */
    protected $client;

    /**
     * @var Response
     */
    private $response;

    /**
     * Payment constructor.
     * @param array|null $config
     */
    public function __construct(?array $config = [])
    {
        if ($config) {
            $this->setConfig(new Config($config));
        }

        $this->client = new CurlHttpClient();
    }

    /**
     * @return Config
     */
    public function getConfig(): Config
    {
        return $this->config;
    }

    /**
     * @param Config $config
     * @return $this
     */
    public function setConfig(Config $config): self
    {
        $this->config = $config;

        return $this;
    }

    /**
     * @return int
     */
    public function getTimeOutLimit(): int
    {
        return $this->timeOutLimit;
    }

    /**
     * @param int $timeOutLimit
     * @return $this
     */
    public function setTimeOutLimit(int $timeOutLimit): self
    {
        $this->timeOutLimit = $timeOutLimit;

        return $this;
    }

    /**
     * @return bool
     */
    public function isTestMode(): bool
    {
        return $this->testMode;
    }

    /**
     * @param bool $testMode
     * @return $this
     */
    public function setTestMode(bool $testMode): self
    {
        $this->testMode = $testMode;

        return $this;
    }

    /**
     * @return bool
     */
    public function isDebug(): bool
    {
        return $this->debug;
    }

    /**
     * @param bool $debug
     * @return $this
     */
    public function setDebug(bool $debug): self
    {
        $this->debug = $debug;

        return $this;
    }

    /**
     * @return Order
     */
    public function getOrder(): Order
    {
        return $this->order;
    }

    /**
     * @param Order $order
     * @return $this
     */
    public function setOrder(Order $order): self
    {
        $this->order = $order;

        return $this;
    }

    /**
     * @return string
     * @throws InvalidOrderDataException
     */
    protected function generateHash(): string
    {
        try {
            $this->order->getName();
            $this->order->getAddress();
            $this->order->getPhone();

            if ( $this->order->isTransfer() ) {
                return $this->config->getMerchantId() .
                    $this->order->getIp() .
                    $this->order->getId() .
                    $this->order->getEmail() .
                    $this->order->getFormattedAmount() .
                    'eft' .
                    ($this->isTestMode() ? "1" : "0");
            }

            return $this->config->getMerchantId() .
                $this->order->getIp() .
                $this->order->getId() .
                $this->order->getEmail() .
                $this->order->getFormattedAmount() .
                $this->order->getFormattedBasket() .
                $this->order->getNoInstallment() .
                $this->order->getMaxInstallment() .
                $this->order->getCurrency() .
                ($this->isTestMode() ? "1" : "0");
        } catch (TypeError $e) {
            throw new InvalidOrderDataException($e->getMessage());
        }
    }

    /**
     * @return string
     */
    protected function getHash(): string
    {
        return $this->hash;
    }

    /**
     * @param string $hash
     * @return $this
     */
    protected function setHash(string $hash): self
    {
        $this->hash = $hash;

        return $this;
    }

    /**
     * @return string
     */
    public function getHashToken(): string
    {
        return base64_encode(hash_hmac(
            'sha256',
            $this->getHash() . $this->config->getMerchantSalt(),
            $this->config->getMerchantKey(),
            true
        ));
    }

    /**
     * @return array
     */
    public function getPostData(): array
    {
        return $this->postData;
    }

    /**
     * @param array $postData
     * @return $this
     */
    public function setPostData(array $postData): self
    {
        $this->postData = $postData;

        return $this;
    }

    /**
     * @return Response
     */
    public function getResponse(): Response
    {
        return $this->response;
    }

    /**
     * @param Response $response
     * @return $this
     */
    public function setResponse(Response $response): self
    {
        $this->response = $response;

        return $this;
    }

    /**
     * @throws InvalidOrderDataException
     */
    private function prepare(): void
    {
        $this->setHash($this->generateHash());

        $standartData = [
            'merchant_id' => $this->config->getMerchantId(),
            'user_ip' => $this->order->getIp(),
            'merchant_oid' => $this->order->getId(),
            'email' => $this->order->getEmail(),
            'payment_amount' => $this->order->getFormattedAmount()
        ];

        $data = array_merge($standartData, [
            'paytr_token' => $this->getHashToken(),
            'test_mode' => $this->isTestMode(),
            'user_basket' => $this->order->getFormattedBasket(),
            'debug_on' => $this->isDebug(),
            'no_installment' => $this->order->getNoInstallment(),
            'max_installment' => $this->order->getMaxInstallment(),
            'user_name' => $this->order->getName(),
            'user_address' => $this->order->getAddress(),
            'user_phone' => $this->order->getPhone(),
            'merchant_ok_url' => $this->config->getSuccessUrl(),
            'merchant_fail_url' => $this->config->getFailUrl(),
            'timeout_limit' => $this->getTimeOutLimit(),
            'currency' => $this->order->getCurrency(),
        ]);
        
        if ( $this->order->isTransfer() ) {
            $data = array_merge($standartData, [
                'payment_type' => 'eft',
                'paytr_token' => $this->getHashToken(),
                'test_mode' => $this->isTestMode(),
                'user_basket' => $this->order->getFormattedBasket(),
                'debug_on' => $this->isDebug(),
                'no_installment' => $this->order->getNoInstallment(),
                'max_installment' => $this->order->getMaxInstallment(),
                'user_phone' => $this->order->getPhone(),
                'merchant_ok_url' => $this->config->getSuccessUrl(),
                'merchant_fail_url' => $this->config->getFailUrl(),
                'timeout_limit' => $this->getTimeOutLimit(),
                'currency' => $this->order->getCurrency(),
            ]);
        }

        $this->setPostData($data);
    }

    /**
     * @return $this
     * @throws ClientException
     * @throws InvalidOrderDataException
     * @throws TransportExceptionInterface
     */
    public function make(): self
    {
        $this->prepare();
        $request = $this->client->request('POST', $this->config->getApiUrl(), [
            'timeout' => $this->getTimeOutLimit(),
            'body' => $this->getPostData(),
        ]);

        try {
            $content = $request->getContent();
            $this->setResponse((new Response())->setContent(json_decode($content, true)));
        } catch (HttpExceptionInterface $e) {
            throw new ClientException($e->getMessage());
        }

        return $this;
    }

    /**
     * @return bool
     */
    public function checkHash(): bool
    {
        $request = Request::createFromGlobals();

        $hash = base64_encode(hash_hmac(
            'sha256',
            $request->get('merchant_oid') .
            $this->config->getMerchantSalt() .
            $request->get('status') .
            $request->get('total_amount'),
            $this->config->getMerchantKey(),
            true
        ));

        if ($hash == $request->get('hash')) {
            return true;
        }

        return false;
    }
}
