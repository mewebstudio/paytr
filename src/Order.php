<?php

declare(strict_types=1);

namespace Mews\PayTr;

class Order
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var float
     */
    private $amount;

    /**
     * @var string
     */
    private $currency = 'TL';

    /**
     * @var array
     */
    private $basket;

    /**
     * @var int
     */
    private $noInstallment = 0;

    /**
     * @var int
     */
    private $maxInstallment = 0;

    /**
     * @var string
     */
    private $ip;

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string|null
     */
    private $address = null;

    /**
     * @var string|null
     */
    private $phone = null;

    /**
     * @var bool
     */
    private $isTransfer = false;

    /**
     * Order constructor.
     * @param array|null $order
     */
    public function __construct(?array $order = [])
    {
        if ($order) {
            $this
                ->setId($order['id'])
                ->setAmount((float)$order['amount'])
                ->setCurrency($order['TL'])
                ->setBasket($order['basket'])
                ->setIp($order['id'])
                ->setEmail($order['email'])
                ->setName($order['name'])
                ->setAddress($order['address'])
                ->setPhone($order['phone']);

            if (isset($order['isTransfer'])) {
                $this->setTransfer($order['isTransfer']);
            }

            if (isset($order['noInstallment'])) {
                $this->setNoInstallment($order['noInstallment']);
            }

            if (isset($order['maxInstallment'])) {
                $this->setMaxInstallment($order['maxInstallment']);
            }
        }
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return $this
     */
    public function setId(string $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return float
     */
    public function getAmount(): float
    {
        return $this->amount;
    }

    /**
     * @return int
     */
    public function getFormattedAmount(): int
    {
        return (int)str_replace('.', '', (string)($this->getAmount() * 100));
    }

    /**
     * @param float $amount
     * @return $this
     */
    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * @return string
     */
    public function getCurrency(): string
    {
        return $this->currency;
    }

    /**
     * @param string $currency
     * @return $this
     */
    public function setCurrency(string $currency): self
    {
        $this->currency = $currency;

        return $this;
    }

    /**
     * @return array
     */
    public function getBasket(): array
    {
        return $this->basket;
    }

    /**
     * @return string
     */
    public function getFormattedBasket(): string
    {
        return base64_encode(json_encode($this->getBasket()));
    }

    /**
     * @param array $basket
     * @return $this
     */
    public function setBasket(array $basket): self
    {
        $this->basket = $basket;

        return $this;
    }
    
    /**
     * @return $this
     */
    
    public function setTransfer(bool $isTransfer): self
    {
        $this->isTransfer = $isTransfer;

        return $this;
    }

    /**
     * @return string
     */

    public function isTransfer(): bool
    {
        return $this->isTransfer;
    }

    /**
     * @return string
     */
    public function getIp(): string
    {
        return $this->ip;
    }

    /**
     * @param string $ip
     * @return $this
     */
    public function setIp(string $ip): self
    {
        $this->ip = $ip;

        return $this;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return $this
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return int
     */
    public function getNoInstallment(): int
    {
        return $this->noInstallment;
    }

    /**
     * @param int $noInstallment
     * @return Order
     */
    public function setNoInstallment(int $noInstallment): self
    {
        $this->noInstallment = $noInstallment;
        return $this;
    }

    /**
     * @return int
     */
    public function getMaxInstallment(): int
    {
        return $this->maxInstallment;
    }

    /**
     * @param int $maxInstallment
     * @return $this
     */
    public function setMaxInstallment(int $maxInstallment): self
    {
        $this->maxInstallment = $maxInstallment;

        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getAddress(): ?string
    {
        return $this->address;
    }

    /**
     * @param string|null $address
     * @return $this
     */
    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPhone(): ?string
    {
        return $this->phone;
    }

    /**
     * @param string|null $phone
     * @return $this
     */
    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }
}
