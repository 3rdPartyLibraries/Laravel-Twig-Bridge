<?php

/**
 * This file is part of the Twigra package.
 *
 * @copyright Robert Crowe <hello@vivalacrowe.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Twigra\Extension\Laravel;

use Noodlehaus\Config as ConfigRepository;
use Twig\TwigFunction;
use Twig\Extension\AbstractExtension;

/**
 * Access Laravels config class in your Twig templates.
 */
class Config extends AbstractExtension
{
    /**
     * @var \Illuminate\Config\Repository
     */
    protected $config;

    /**
     * Create a new config extension
     *
     * @param \Illuminate\Config\Repository
     */
    public function __construct(ConfigRepository $config)
    {
        $this->config = $config;
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'Twigra_Extension_Laravel_Config';
    }

    /**
     * {@inheritDoc}
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('config', [$this->config, 'get']),
            new TwigFunction('config_get', [$this->config, 'get']),
            new TwigFunction('config_has', [$this->config, 'has']),
        ];
    }
}
