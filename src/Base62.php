<?php
/*
 * This file is part of Amirax Base62.
 *
 * (c) 2017 Amirax <dev@amirax.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Amirax;

/**
 * This is the Amirax Base62 main class.
 *
 * @author Max Voronov <maxivoronov@gmail.com>
 */
class Base62 implements Base62Interface
{
    const MAX_ASCII = 256;

    protected $salt;
    protected $alphabet;

    /**
     * Base62 constructor
     *
     * @param string $salt
     * @param string $alphabet
     * @throws Base62Exception
     */
    public function __construct(
        $salt = '',
        $alphabet = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'
    ) {
        if (empty($alphabet)) {
            throw new Base62Exception('Alphabet can\'t be empty');
        }

        if (strlen($alphabet) < 2) {
            throw new Base62Exception('Alphabet must contain at least 2 unique characters');
        }

        if ($this->hasRepeatedChars($alphabet)) {
            throw new Base62Exception('Alphabet can\'t contain repeated characters');
        }

        if (!is_string($salt)) {
            throw new Base62Exception('Salt must be a string value');
        }

        $this->salt = $salt;
        $this->alphabet = $this->stableShuffle($alphabet, $salt);
    }

    /**
     * @inheritdoc
     */
    public function encode($source)
    {
        $source = array_map(function ($character) {
            return ord($character);
        }, str_split($source));

        $result = $this->converter($source, self::MAX_ASCII, strlen($this->alphabet));
        $result = array_map(function ($idx) {
            return $this->alphabet[$idx];
        }, $result);

        return implode('', $result);
    }

    /**
     * @inheritdoc
     */
    public function decode($source)
    {
        $source = array_map(function ($character) {
            return strpos($this->alphabet, $character);
        }, str_split($source));

        $result = $this->converter($source, strlen($this->alphabet), self::MAX_ASCII);
        $result = array_map(function ($ascii) {
            return chr($ascii);
        }, $result);

        return implode('', $result);
    }

    /**
     * Array converter algorithm
     * Based on: https://codegolf.stackexchange.com/a/21672
     *
     * @param array $source
     * @param integer $sourceBase
     * @param integer $distBase
     * @return array
     */
    protected function converter(array $source, $sourceBase, $distBase)
    {
        $result = [];
        while ($count = count($source)) {
            $remainder = 0;
            $quotient = [];
            for ($i = 0; $i < $count; $i++) {
                $accumulator = $source[$i] + $remainder * $sourceBase;
                $digit = (int) ($accumulator / $distBase);
                $remainder = $accumulator % $distBase;
                if (count($quotient) || $digit) {
                    array_push($quotient, $digit);
                }
            }
            array_unshift($result, $remainder);
            $source = $quotient;
        }

        return $result;
    }

    /**
     * Shuffle alphabet by given salt
     *
     * @param string $alphabet
     * @param string $salt
     * @return string
     */
    protected function stableShuffle($alphabet, $salt)
    {
        $saltLength = strlen($salt);
        if (!$saltLength) {
            return $alphabet;
        }

        for ($i = strlen($alphabet) - 1, $v = 0, $p = 0; $i > 0; $i--, $v++) {
            $v %= $saltLength;
            $p += $int = ord($salt[$v]);
            $idx = ($int + $v + $p) % $i;
            $temp = $alphabet[$idx];
            $alphabet[$idx] = $alphabet[$i];
            $alphabet[$i] = $temp;
        }

        return $alphabet;
    }

    /**
     * Check duplicated symbols in string
     *
     * @param string $data
     * @return bool
     */
    protected function hasRepeatedChars($data)
    {
        for ($i = 0; $i < strlen($data); $i++) {
            if ($i !== strpos($data, $data[$i])) {
                return true;
            }
        }
        return false;
    }
}
