<?php

namespace AppBundle\Service\TokenHandler;


class TokenGenerator
{
    public function createConfirmationToken() : string{
        $str = $this->randomString();
        $token = crypt($str,$this->randomString(5));
        return $token;
    }

    private function randomString($length = 15) {
        $str = "";
        $characters = array_merge(range('A','Z'), range('a','z'), range('0','9'));
        $max = count($characters) - 1;
        for ($i = 0; $i < $length; $i++) {
            $rand = mt_rand(0, $max);
            $str .= $characters[$rand];
        }
        return $str;
    }
}