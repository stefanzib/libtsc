<?php

namespace MoniRos\LibTSC;

class Card
{
    protected $userId;
    protected $cardId;
    const POST_URL = 'http://190.216.78.10/operacionestsc/ServiceTSC.asmx/ObtenerOperaciones';
    const ENCRYPTION_KEY = '0p-rac1on3sT$C.G0dz1ll@';

    public function __construct ($userId, $cardId)
    {
        $this->userId = $userId;
        $this->cardId = $cardId;
        $this->encryptedCardData = $this->encryptCardData($userId, $cardId);
        $this->cardInformation = false;
        return $this;
    }

    public function getCardInformation()
    {
        if (false == $this->cardInformation) {
            $this->fetchData();
        }

        return $this->cardInformation;
    }


    public function fetchData()
    {
        $data = ['dataUser' => $this->encryptedCardData];
        $dataString = json_encode($data);
        $ch = curl_init(self::POST_URL);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json', 'Content-Length: ' . strlen($dataString)]);
        $response = curl_exec($ch);
        curl_close($ch);
        $responseData = json_decode($response)->d;
        $this->cardInformation = json_decode($responseData);

        return true;
    }

    private function encryptCardData($userId, $cardId)
    {
        $pass = self::ENCRYPTION_KEY;
        $cardIdTruncated = substr($cardId, 0, 16);
        $data = "{$cardIdTruncated},{$userId}";

        $salt = substr(md5(mt_rand(), true), 8);

        $block = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
        $pad = $block - (strlen($data) % $block);
        $data = $data . str_repeat(chr($pad), $pad);

        $td = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_CBC, '');

        $key_len =  mcrypt_enc_get_key_size($td);
        $iv_len =  mcrypt_enc_get_iv_size($td);

        $salted = $dx = '';

        while (strlen($salted) < ($key_len + $iv_len)) {
            $dx = md5($dx.$pass.$salt, true);
            $salted .= $dx;
        }

        $key = substr($salted,0,$key_len);
        $iv = substr($salted,$key_len,$iv_len);

        mcrypt_generic_init($td, $key, $iv);
        $encryptedData = mcrypt_generic($td, $data);
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);

        return base64_encode('Salted__' . $salt . $encryptedData);
    }

}

