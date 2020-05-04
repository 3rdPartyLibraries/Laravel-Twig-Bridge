<?php

/**
 * This file is part of the TwigBridge package.
 *
 * @copyright Robert Crowe <hello@vivalacrowe.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Twigra\Extension\Laravel;

use App\Kernel\Library\UrlGenerator;
use Illuminate\Support\Str as IlluminateStr;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Access Laravels url class in your Twig templates.
 */
class Url extends AbstractExtension
{
    /**
     * @var \Illuminate\Routing\UrlGenerator
     */
    protected $url;

    /**
     * @var \Illuminate\Routing\Router
     */
    protected $router;

    /**
     * Create a new url extension
     *
     * @param \Illuminate\Routing\UrlGenerator
     * @param \Illuminate\Routing\Router
     */
    public function __construct(UrlGenerator $url)
    {
        $this->url = $url;
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'TwigBridge_Extension_Laravel_Url';
    }

    /**
     * {@inheritDoc}
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('asset', [$this->url, 'asset'], ['is_safe' => ['html']]),
            new TwigFunction('route', [$this->url, 'route'], ['is_safe' => ['html']]),
        ];
    }
}
