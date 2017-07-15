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
 * This is the Amirax Base62 interface.
 *
 * @author Max Voronov <maxivoronov@gmail.com>
 */
interface Base62Interface
{
    /**
     * Base62 Encoder
     *
     * @param string|integer $source
     * @return string
     */
    public function encode($source);

    /**
     * Base62 Decoder
     *
     * @param $source
     * @return string
     */
    public function decode($source);
}
