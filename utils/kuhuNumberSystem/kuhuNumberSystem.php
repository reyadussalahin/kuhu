<?php

class KuhuNumberSystem {
    private static $digits = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
    private static $numberOfDigits = 62;

    public static function getDigits() {
        return self::$digits;
    }
    public static function numberOfDigits() {
        return self::$numberOfDigits;
    }
    public static function firstDigit() {
        return self::$digits[0];
    }
    public static function lastDigit() {
        return self::$digits[self::$numberOfDigits - 1];
    }
    public static function nextDigit($digit) {
        if($digit === self::lastDigit()) {
            return self::firstDigit();
        }
        $digitIdx = strpos(self::$digits, $digit);
        return self::$digits[$digitIdx + 1];
    }
    public static function incrementNumber($number) {
        $numberLength = strlen($number);
        $i = $numberLength - 1;
        while($i >= 0 && $number[$i] === self::lastDigit()) {
            $number[$i] = self::firstDigit();
            $i--;
        }
        if($i >= 0) {
            $nextDigit = self::nextDigit($number[$i]);
            $number[$i] = $nextDigit;
        } else {
            $number = $number . self::firstDigit();
        }
        return $number;
    }
}

?>