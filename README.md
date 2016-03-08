# LibTSC
A small library to get data from personalized TSC Cards (Tarjeta Sin Contacto) using User ID (Documento Nacional de Identidad) and Card ID.

## Installation using composer

```php
require "moniros/libtsc": "1.0.*"
```

## Usage

```php
$userId = 30022584;
$cardId = 42410201004148786;
$card = new \MoniRos\LibTSC\Card($userId, $cardId);

$jsonData = $card->getCardInformation();
```

