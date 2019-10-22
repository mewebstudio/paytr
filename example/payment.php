<?php

declare(strict_types=1);

use Mews\PayTr\Exceptions\InvalidOrderDataException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

require __DIR__ . '/../vendor/autoload.php';

$request = Request::createFromGlobals();

if (Request::METHOD_POST != $request->getMethod()) {
    echo RedirectResponse::create('index.php');
    die;
}

$payment = new Mews\PayTr\Payment(
    require __DIR__ . '/../config/paytr.php'
);

$payment
    ->setDebug(true)
    ->setTestMode(true);

// Generate unique order ID
$orderId = date('Ymd') . strtoupper(substr(uniqid(sha1((string)time())), 0, 8));

$order = new Mews\PayTr\Order();
$order
    ->setId($orderId)
    ->setAmount((float)$request->get('amount'))
    ->setCurrency('TL')
    ->setBasket([
        [
            'Cari ödeme',
            82.10,
        ],
        [
            'KDV',
            18.15,
        ],
    ])
    ->setIp($request->getClientIp())
    ->setEmail($request->get('email'))
    ->setName($request->get('name'))
    ->setAddress('Customer address')
    ->setPhone($request->get('phone'))
    // Optional
    //->setNoInstallment(0)
    //->setMaxInstallment(0)
;

try {
    $payment
        ->setOrder($order)
        ->make();
} catch (InvalidOrderDataException $e) {
    var_dump($e->getMessage());
    die;
} catch (TransportExceptionInterface $e) {
    var_dump($e->getMessage());
    die;
} catch (Mews\PayTr\Exceptions\ClientException $e) {
    var_dump($e->getMessage());
    die;
}

require __DIR__ . '/view/header.php';
?>

<div class="container">
    <iframe src="https://www.paytr.com/odeme/guvenli/<?php echo $payment->getResponse()->getToken(); ?>"
            id="paytriframe" frameborder="0"
            scrolling="no" style="width: 100%;"></iframe>
    <script>iFrameResize({}, '#paytriframe');</script>
</div>

<?php
require __DIR__ . '/view/footer.php';
?>
