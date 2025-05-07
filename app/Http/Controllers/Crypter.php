<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Crypter extends Controller
{
    //******use for decryption of crypto js from vue*********//
    public function cryptoKey(){
        return 'dPHvFpkJGTLXBwSKlqh8QxCLH29ZR3b4';
    }
    
   
    public function encryptCrypto($data){
        $key = $this->cryptoKey();
        $dataJson = json_encode($data);
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('AES-256-CBC'));
        $encrypted = openssl_encrypt($dataJson, 'AES-256-CBC', $key, 0, $iv);
        $encryptedData = base64_encode($iv . $encrypted);
        return $encryptedData;
    }

    public function decryptCrypto($request){
        $key = $this->cryptoKey();

        $data = base64_decode($request);
        if (substr($data, 0, 8) != "Salted__") {
            return false;
        }
        $salt = substr($data, 8, 8);
        $keyAndIV = $this->aes_evpKDF($key, $salt);
        $decryptPassword = openssl_decrypt(
            substr($data, 16),
            "aes-256-cbc",
            $keyAndIV["key"],
            OPENSSL_RAW_DATA, // base64 was already decoded
            $keyAndIV["iv"]
        );
        return $decryptPassword;
    }
    public function aes_evpKDF($password, $salt, $keySize = 8, $ivSize = 4, $iterations = 1, $hashAlgorithm = "md5")
    {
        $targetKeySize = $keySize + $ivSize;
        $derivedBytes = "";
        $numberOfDerivedWords = 0;
        $block = NULL;
        $hasher = hash_init($hashAlgorithm);
        while ($numberOfDerivedWords < $targetKeySize) {
            if ($block != NULL) {
                hash_update($hasher, $block);
            }
            hash_update($hasher, $password);
            hash_update($hasher, $salt);
            $block = hash_final($hasher, TRUE);
            $hasher = hash_init($hashAlgorithm);

            // Iterations
            for ($i = 1; $i < $iterations; $i++) {
                hash_update($hasher, $block);
                $block = hash_final($hasher, TRUE);
                $hasher = hash_init($hashAlgorithm);
            }

            $derivedBytes .= substr($block, 0, min(strlen($block), ($targetKeySize - $numberOfDerivedWords) * 4));

            $numberOfDerivedWords += strlen($block) / 4;
        }
        return array(
            "key" => substr($derivedBytes, 0, $keySize * 4),
            "iv"  => substr($derivedBytes, $keySize * 4, $ivSize * 4)
        );
    }
}
