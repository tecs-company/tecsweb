# TecsWeb Payment Implementation Helpers

A PHP library for implementing secure payment portal TecsWeb

## Installation

### Via Composer

```
composer require tecspayment/tecsweb-b2b-integration-php
```

When using composer you need to insert autoloader to your code like in example:

```php
<?php

$loader = require_once __DIR__ . '/vendor/autoload.php';

?>
```

### From GitHub

```
https://github.com/tecspayment/tecsweb-b2b-integration-php.git
```

or download and extract the zip file if you do not have Git installed:

https://github.com/tecspayment/tecsweb-b2b-integration-php/archive/master.zip

Then you need to use internal loader of the library:

```php
<?php 

require __DIR__ . '/tecsweb/loader.php';

?>
```

The path to loader.php depends on the location where you extracted the zip file or where you cloned the project.


## Usage

### Creating TecsWeb Request Token

This code creates whole signed URL to be inserted into iframe on you page or to be redirected to from your page.

```php
<?php

$tecs = new \Tecs\TecsWeb(
    'mechantSecretKey', // Private Secret Key provided by Tecs
    '12345678', // Merchant ID provided by Tecs
    'https://test.tecs.at/tecsweb/tecswebmvc_start.do' // URL of TecsWeb payment portal privided by Tecs
);

try {
    $requestToken = $tecs->createSignedUrl([
        \Tecs\TecsWeb::AMOUNT => '100', // amount in cents (mandatory)
        \Tecs\TecsWeb::TX_ID => '1000010006', // mandatory, must be unique
        \Tecs\TecsWeb::TX_CURRENCY => 'EUR', // mandatory
        \Tecs\TecsWeb::TX_DESC => 'Test', // mandatory
        \Tecs\TecsWeb::RECEIPT_NUMBER => '1', // mandatory
        \Tecs\TecsWeb::RETURN_URL => 'https://www.your-page-example.com/return.php', // mandatory
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

<iframe src="<?php echo $requestToken ?>" scrolling="no" height="800" width="600"></iframe>

<!-- ...... Your code bellow -->

```

**NOTE:** For using more optional parameters look into Implementation Manual.


### Processing the response - TecsWeb Response Token

When **the payment** is done TecsWeb service sends back the TecsWeb Response Token as GET query.
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

$tecsWebResponseToken = $_GET; 

$tecsWebResponse = new \Tecs\TecsWebResponse(
    'mechantSecretKey', // Merchant Secret Key
    $tecsWebResponseToken // Response Data Format (optional), default is GET query
);

$signCheck = $tecsWebResponse->isSignedCorrectly();

// Bad Sign
if (!$signCheck) {

    // $myTransactionLogger->log('error', $tecsWebResponse->getAllData());

    // do something when sign is not valid
}

// When is Authorized
else {
    // Getting Data Using Key Constants to prevent mistakes
    $data = $tecsWebResponse->getAllData();

    $rsponseCode         = $data[ \Tecs\TecsWebResponse::RESPONSE_CODE ];
    $rsponseText         = $data[ \Tecs\TecsWebResponse::RESPONSE_TEXT ];
    $transactionId       = $data[ \Tecs\TecsWebResponse::TX_ID ];
    $transactionDateTime = $data[ \Tecs\TecsWebResponse::TX_DATE_TIME ];
    $authorizationNumber = $data[ \Tecs\TecsWebResponse::AUTHORIZATION_NUMBER ];

    // ... etc.

    // Getting Data Using TecWebResponse API
    $responseCode        = $tecsWebResponse->getResponseCode();
    $responseText        = $tecsWebResponse->getResponseText();
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

### Tecs\Generator\SignedUrl

| method            | params                | returns                 |
|-------------------|-----------------------|-------------------------|
| getSignedUrl      | -                     | string                  |
| __toString        | -                     | string                  |




