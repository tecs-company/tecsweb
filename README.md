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
        \Tecs\TecsWeb::TX_DESC => 'Description of the transaction', // mandatory
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
    'https://www.tecs.at/tecsweb/tecsweb.jsp' // URL of TecsWeb payment portal privided by Tecs
);

try {
    $signedUrl = $tecs->createSignedUrl([
        \Tecs\TecsWeb::AMOUNT => '100', // amount in cents (mandatory)
        \Tecs\TecsWeb::TX_ID => '1000010006', // mandatory, must be unique
        \Tecs\TecsWeb::TX_CURRENCY => 'EUR', // mandatory
        \Tecs\TecsWeb::TX_DESC => 'Test', // mandatory
        \Tecs\TecsWeb::RECEIPT_NUMBER => '1', // mandatory
        \Tecs\TecsWeb::RETURN_URL => 'https://tecsweb-php-example.loc/return.php', // mandatory
        \Tecs\TecsWeb::TX_DATE_TIME=> date('YmdHis'), // optional in format YYYYMMDDHHMMSS
        \Tecs\TecsWeb::USER_DATA => 'ONR=S20110112000006;ODT=12.01.2011;IAM=1000;NRI=3;IDY=30;', // optional
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

### Cnancelation of Transaction

When the online shop doesn't receive a response from TecsWeb within some predefined period,
the transaction should be cancelled. For that purpose, the cancellation URL with valid parameters could be called.
Example of generating of cancellation URL in php:

**Note:** Loading the library is the same as above

```php
<?php

$tecs = new \Tecs\TecsWebCancelation(
    'merchantSecretKey', // Private Secret Key provided by Tecs
    '12345678', // Merchant ID provided by Tecs
    'https://www.tecs.at/tecsweb/cancel_transaction.jsp' // URL of TecsWeb payment portal
);

try {
    $URL = $tecs->createSignedUrl([
        \Tecs\TecsWebCancelation::AMOUNT => '100', // amount in cents (mandatory)
        \Tecs\TecsWebCancelation::TX_ID => '1000010129', // mandatory - must be unique
        \Tecs\TecsWebCancelation::TX_CURRENCY => 'EUR', // mandatory
        \Tecs\TecsWebCancelation::TX_DESC => 'Test', // mandatory
        \Tecs\TecsWebCancelation::RECEIPT_NUMBER => '12345', // mandatory
        \Tecs\TecsWebCancelation::RETURN_URL => 'https://tecsweb-fake.loc/cancelationReturn.php', // mandatory
        \Tecs\TecsWebCancelation::ORIG_TX_ID => '1000010128', // mandatory - TX ID to be canceled
        \Tecs\TecsWeb::TX_DATE_TIME=> date('YmdHis'), // optional in format YYYYMMDDHHMMSS
        //\Tecs\TecsWeb::USER_DATA => 'ONR=S20110112000006;ODT=12.01.2011;IAM=1000;NRI=3;IDY=30;', // optional
    ]);
}
catch (\Exception $e) {
    exit($e->getMessage());
}

?>
```


### Processing the Response

When **the payment or cancelation of transaction** is done TecsWeb service sends back the response.
You may implement TecWebResponse helper into your php code.

**!!!IMPORTANT: You should log every response to be able find some mistakes or unprocessed payments when they occur.**
**You may use "getAllData()" to log the whole payload.** 

As above you have to include a class loader. It depends on using composer or not:

```php
<?php

// Composer Way
$loader = require_once __DIR__ . '/vendor/autoload.php';

// Without Composer
require __DIR__ . '/tecsweb/loader.php';

?>
```

**Example of implementing TecsWebResponse:**

```php
<?php

$tecsWebResponse = new \Tecs\TecsWebResponse(
    'mechantSecretKey' // Merchant Secret Key
);

$signCheck = $tecsWebResponse->isSignedCorrectly();

// Bad Sign
if (!$signCheck) {

    // $myTransactionLogger->log('error', $tecsWebResponse->getAllData());

    // do something when sign is not valid
}

// When the response has error
else if ($tecsWebResponse->hasError()) {
    $errorCode = $tecsWebResponse->getResponseCode();
    $errorMessage = $tecsWebResponse->getResponseText();

    // $myTransactionLogger->log('error', $tecsWebResponse->getAllData());

    // do something when error occurs
}

// When is Authorized
else {
    // Getting Data Using Key Constants to prevent mistakes
    $data = $tecsWebResponse->getAllData();

    $transactionId       = $data[ \Tecs\TecsWebResponse::TX_ID ];
    $transactionDateTime = $data[ \Tecs\TecsWebResponse::TX_DATE_TIME ];
    $authorizationNumber = $data[ \Tecs\TecsWebResponse::AUTHORIZATION_NUMBER ];

    // ... etc.

    // Getting Data Using TecWebResponse API
    $transactionId       = $tecsWebResponse->getTXID();
    $transactionDateTime = $tecsWebResponse->getTXDateTime();
    $authorizationNumber = $tecsWebResponse->getAuthorizationNumber();
    // ... etc.

    // $myTransactionLogger->log('info', $tecsWebResponse->getAllData());
}

?>
```

## APIs

### Tecs\TecsWeb

| method            | params                | returns                 |
|-------------------|-----------------------|-------------------------|
| createSign        | data (array)          | Tecs\Genrator\Sign      |
| createSignedUrl   | data (array)          | Tecs\Genrator\SignedUrl |



### Tecs\TecsWebCancellation

| method            | params                | returns                 |
|-------------------|-----------------------|-------------------------|
| createSign        | data (array)          | Tecs\Genrator\Sign      |
| createSignedUrl   | data (array)          | Tecs\Genrator\SignedUrl |


### Tecs\TecsWebResponse

| method            | params                | returns                 |
|-------------------|-----------------------|-------------------------|
| isSignedCorrectly | -                     | boolean                 |
| hasError          | -                     | boolean                 |
| getResponseText   | -                     | string                  |
| getResponseCode   | -                     | string                  |
| getTXID           | -                     | string                  |
| getTXDateTime          | -                     | string                  |
| getAuthorizationNumber | -                     | string                  |
| getVUNumber       | -                     | string                  |
| getOperatorID     | -                     | string                  |
| getSerieNumber    | -                     | string                  |
| getOriginalTXID   | -                     | string                  |
| getSTAN           | -                     | string                  |
| getOriginalSTAN   | -                     | string                  |
| getSVC            | -                     | string                  |
| getUserData       | -                     | string                  |
| getSign           | -                     | string                  |
| getAcquirerName   | -                     | string                  |
| getCardType       | -                     | string                  |
| getCardReferenceNumber | -                     | string                  |
| getAllData        | -                     | string                  |


### Tecs\Generator\Sign

| method            | params                | returns                 |
|-------------------|-----------------------|-------------------------|
| getSign           | -                     | string                  |
| __toString        | -                     | string                  |

### Tecs\Generator\SignedUrl

| method            | params                | returns                 |
|-------------------|-----------------------|-------------------------|
| getSignedUrl      | -                     | string                  |
| __toString        | -                     | string                  |




