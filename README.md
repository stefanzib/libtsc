# LibTSC
A small library to get data from personalized TSC Cards (Tarjeta Sin Contacto) using User ID (Documento Nacional de Identidad) and Card ID.

## Installation using Composer

```php
"repositories": [
    {
        "type": "git",
        "url": "https://github.com/tpenaranda/libtsc"
    }
]

"require": {
    "moniros/libtsc": "1.0.*"
}
```

## Usage

```php
$userId = 30022584;
$cardId = 42410201004148786;
$card = new \MoniRos\LibTSC\Card($userId, $cardId);

$jsonData = $card->getCardInformation();
```

## How it works?
Basically the lib takes the userId/cardId, encrypts the information using the TSC "super secret key" :P ('0p-rac1on3sT$C.G0dz1ll@') and process a POST request to TSC Server (kind of API, http://190.216.78.10/operacionestsc/ServiceTSC.asmx/ObtenerOperaciones).
In short terms, as a personal challenge I managed to port js code found on the TSC application apk to PHP.

