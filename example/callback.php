<?php

declare(strict_types=1);

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

require __DIR__ . '/../vendor/autoload.php';

$request = Request::createFromGlobals();

if (Request::METHOD_POST != $request->getMethod()) {
    echo RedirectResponse::create('index.php');
    die;
}

$payment = new Mews\PayTr\Payment();
$payment
    ->setDebug(true)
    ->setTestMode(true);

if ($payment->checkHash()) {
    echo 'OK';
    exit;
}

die('PayTR notification failed: bad hash');
