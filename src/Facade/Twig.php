<?php

/**
 * This file is part of the Twigra package.
 *
 * @copyright Robert Crowe <hello@vivalacrowe.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Twigra\Facade;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Twig\Environment
 * @see \Twigra\Bridge
 */
class Twig extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'twig';
    }
}
