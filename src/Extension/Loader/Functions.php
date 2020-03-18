<?php

/**
 * This file is part of the Twigra package.
 *
 * @copyright Robert Crowe <hello@vivalacrowe.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Twigra\Extension\Loader;

use Twig\TwigFunction;

/**
 * Extension to expose defined functions to the Twig templates.
 *
 * See the `extensions.php` config file, specifically the `functions` key
 * to configure those that are loaded.
 */
class Functions extends Loader
{
    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'Twigra_Extension_Loader_Functions';
    }

    /**
     * {@inheritDoc}
     */
    public function getFunctions()
    {
        $load      = $this->config->get('twigra.extensions.functions', []);
        $functions = [];

        foreach ($load as $method => $callable) {
            list($method, $callable, $options) = $this->parseCallable($method, $callable);

            $function = new TwigFunction(
                $method,
                function () use ($callable) {
                    return call_user_func_array($callable, func_get_args());
                },
                $options
            );

            $functions[] = $function;
        }

        return $functions;
    }
}
