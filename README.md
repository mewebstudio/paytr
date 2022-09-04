#PayTR ödeme sistemi için PHP kütüphanesi (PHP 7.1.3+) 

Kurulum:
```bash
composer require mews/paytr
```

Örnek kullanım, `example` dizini içerisindedir.
```php
$payment = new Mews\PayTr\Payment(
    require __DIR__ . '/../config/paytr.php'
);
```
ile oluşturulan nesnede varsayılan ayarlar kullanılır. Bu ayarlar `config/paytr.php` içerisindedir.
Buradaki ayarları projenizdeki PayTR hesabınıza uygun şekilde güncellemeniz gerekmektedir.

İstenirse farklı ayarlarla nesneyi oluşturabilirsiniz. Örnek:
```php
$payment = new Mews\PayTr\Payment([
    'apiUrl' => 'https://www.paytr.com/odeme/api/get-token',
    'merchantId' => 'XXXXXX',
    'merchantKey' => 'XXXXXXXXXXXX',
    'merchantSalt' => 'XXXXXXXXXXXX',
    'successUrl' => 'https://paytr.test/example/index.php?status=success',
    'failUrl' => 'https://paytr.test/example/index.php?status=fail',
]);
```
veya
```php
$payment = new Mews\PayTr\Payment();
$payment->setConfig(new Mews\PayTr\Config([
    'apiUrl' => 'https://www.paytr.com/odeme/api/get-token',
    'merchantId' => 'XXXXXX',
    'merchantKey' => 'XXXXXXXXXXXX',
    'merchantSalt' => 'XXXXXXXXXXXX',
    'successUrl' => 'https://paytr.test/example/index.php?status=success',
    'failUrl' => 'https://paytr.test/example/index.php?status=fail',
]));
```

* `example` dizini içerisindeki `index.php` ve `payment.php` ödeme işlemleri için örnektir.
* `callback.php` ise, ödeme sonrası PayTR' den gelecek ödeme sonucunu işleyen kısımdır.
