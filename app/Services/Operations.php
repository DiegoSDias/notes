<?php

namespace App\Services;

use Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

class Operations {
    public static function decryptId($value) {
        // check if $value is encrypted
        try {
            $value = Crypt::decrypt(($value));

        } catch (DecryptException $e) {
            return redirect()->route('home');
        }

        return $value;
    }
}