# TecsWeb Payment Implementation Helpers

A PHP library for implementing secure payment portal TecsWeb

## Installation

### Via Composer

```
composer require tecs-company/tecsweb
```

When using composer you need to insert autoloader to your code like in example:

```php
<?php

$loader = require_once __DIR__ . '/vendor/autoload.php';

?>
```

### From GitHub

```
git clone https://github.com/tecs-company/tecsweb.git
```

or download and extract the zip file if you do not have Git installed:

https://github.com/tecs-company/tecsweb/archive/master.zip

Then you need to use internal loader of the library:

```php
<?php 

require __DIR__ . '/tecsweb/loader.php';

?>
```

The path to loader.php depends on the location where you extracted the zip file or where you cloned the project.


## Usage


### Creating a sign only

This code creates only sign to be used as a parameter in URL to TecsWeb. It is usable when you create the URL your own way, or you use a javascript to create iframe widget. 

```php
<?php

$tecs = new \Tecs\TecsWeb(
    'mechantSecretKey', // Private Secret Key provided by Tecs
    '12345678', // Merchant ID provided by Tecs
    'https://example.tecs.at/payment' // URL of TecsWeb payment portal provided by Tecs
);

try {
    $sign = $tecs->createSign([
        \Tecs\TecsWeb::AMOUNT => '100', // amount in cents - mandatory
        \Tecs\TecsWeb::TX_ID => '1000010006', // mandatory
        \Tecs\TecsWeb::TX_CURRENCY => 'EUR', // mandatory
        \Tecs\TecsWeb::TX_DESC => 'Descriotion of the transaction', // mandatory
        \Tecs\TecsWeb::RECEIPT_NUMBER => '123', // mandatory
        \Tecs\TecsWeb::RETURN_URL => 'https://tecsweb-php-example.loc/return.php', // mandatory
        \Tecs\TecsWeb::USER_DATA => 'ONR=S20110112000006;ODT=12.01.2011;IAM=1000;NRI=3;IDY=30;', // optional
    ]);
}
catch (\Exception $e) {
    // Do some error handling
}

```

### Creating a signed URL

This code creates whole signed URL to be inserted into iframe on you page or to be redirected to from your page.

```php
<?php

$tecs = new \Tecs\TecsWeb(
    'mechantSecretKey', // Private Secret Key provided by Tecs
    '12345678', // Merchant ID provided by Tecs
    'https://example.tecs.at/payment' // URL of TecsWeb payment portal privided by Tecs
);

try {
    $signedUrl = $tecs->createSignedUrl([
        \Tecs\TecsWeb::AMOUNT => '100', // amount in cents (mandatory)
        \Tecs\TecsWeb::TX_ID => '1000010006', // mandatory
        \Tecs\TecsWeb::TX_CURRENCY => 'EUR', // mandatory
        \Tecs\TecsWeb::TX_DESC => 'Test', // mandatory
        \Tecs\TecsWeb::RECEIPT_NUMBER => '1', // mandatory
        \Tecs\TecsWeb::RETURN_URL => 'https://tecsweb-php-example.loc/return.php', // mandatory
        \Tecs\TecsWeb::USER_DATA => 'ONR=S20110112000006;ODT=12.01.2011;IAM=1000;NRI=3;IDY=30;', // optional
        \Tecs\TecsWeb::TX_DATE_TIME=> date('YmdHis'), // optional in format YYYYMMDDHHMMSS
    ]);
}
catch (\Exception $e) {
    // Do some error handling
}

```

**HTML Template example**

```html

<!-- ...... Your code above -->

<iframe src="<?php echo $signedUrl ?>" scrolling="no" height="400" width="400"></iframe>

<!-- ...... Your code bellow -->

```

**NOTE:** For using more optional parameters look into Implementation Manual.

