<?php
/*
 * This file is part of Amirax Base62.
 *
 * (c) 2017 Amirax <dev@amirax.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Amirax\Base62;

use Amirax\Base62;
use Amirax\Base62Exception;
use PHPUnit\Framework\TestCase;

class Base62Test extends TestCase
{
    const CHARACTERS = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-_ ';

    public function testRandomBytes()
    {
        $base62 = new Base62();
        for ($i = 0; $i < 10; $i++) {
            $rawData = random_bytes(25);
            $encodedData = $base62->encode($rawData);
            $this->assertEquals($rawData, $base62->decode($encodedData));
            $this->assertNotEmpty($encodedData);
        }
    }

    public function testRandomStrings()
    {
        $base62 = new Base62();
        for ($i = 0; $i < 10; $i++) {
            // Generate random string by character list
            $randCharKeys = array_rand(str_split(self::CHARACTERS), 15);
            $rawData = implode('', array_map(function ($idx) {
                return self::CHARACTERS[$idx];
            }, $randCharKeys));

            $encodedData = $base62->encode($rawData);
            $this->assertEquals($rawData, $base62->decode($encodedData));
            $this->assertNotEmpty($encodedData);
        }
    }

    public function testMultilangChars()
    {
        $phrases = [
            'Hello World!',
            'Привет Мир!',
            'Hallo Welt!',
            '¡Hola, mundo!',
            'γειά σου κόσμος',
            '你好世界',
            'こんにちは世界',
        ];

        $base62 = new Base62();
        foreach ($phrases as $phrase) {
            $encodedData = $base62->encode($phrase);
            $this->assertEquals($phrase, $decodedData = $base62->decode($encodedData));
            $this->assertNotEmpty($encodedData);
        }
    }

    public function testLeadingZero()
    {
        $base62 = new Base62();
        $rawData = hex2bin('0ba5722c99514fb47713');
        $encodedData = $base62->encode($rawData);
        $this->assertEquals($rawData, $base62->decode($encodedData));
        $this->assertNotEmpty($encodedData);
    }

    public function testInteger()
    {
        $base62 = new Base62();
        for ($i = 0; $i < 10; $i++) {
            $rawData = (int) rand(100000, 999999);
            $encodedData = $base62->encode($rawData);
            $this->assertEquals($rawData, $base62->decode($encodedData));
            $this->assertNotEmpty($encodedData);
        }
    }

    public function testCustomAlphabet()
    {
        $base62 = new Base62('', 'abcdefghijklmnopqrstuvwxyz');
        for ($i = 0; $i < 10; $i++) {
            $rawData = (int) rand(100000, 999999);
            $encodedData = $base62->encode($rawData);
            $this->assertEquals($rawData, $base62->decode($encodedData));
            $this->assertRegExp('/[a-z]+/', $encodedData);
            $this->assertNotEmpty($encodedData);
        }
    }

    public function testEmptyAlphabet()
    {
        $this->expectException(Base62Exception::class);
        new Base62('', '');
    }

    public function testTooShortAlphabet()
    {
        $this->expectException(Base62Exception::class);
        new Base62('', '0');
    }

    public function testAlphabetRepeatedChars()
    {
        $this->expectException(Base62Exception::class);
        new Base62('', 'abcdeFghijklmnoFpqrstuvwxyz');
    }

    public function testSalt()
    {
        $rawData = 'Hello World!';
        $base62default = new Base62();
        $base62salted = new Base62('my_salt');

        $encodedDataDefault = $base62default->encode($rawData);
        $encodedDataSalted = $base62salted->encode($rawData);

        $this->assertNotEmpty($encodedDataDefault);
        $this->assertNotEmpty($encodedDataSalted);
        $this->assertNotEquals($encodedDataDefault, $encodedDataSalted);
    }
}
