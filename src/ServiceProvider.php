<?php

/**
 * This file is part of the Twigra package.
 *
 * @copyright Robert Crowe <hello@vivalacrowe.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Twigra;

use Illuminate\View\ViewServiceProvider;
use InvalidArgumentException;
use Twig\Lexer;
use Twig\Extension\DebugExtension;
use Twig\Extension\ExtensionInterface;
use Twig\Extension\EscaperExtension;
use Twig\Loader\ArrayLoader;
use Twig\Loader\ChainLoader;
use Twig_Environment;

/**
 * Bootstrap Laravel Twigra.
 *
 * You need to include this `ServiceProvider` in your app.php file:
 *
 * <code>
 *     'providers' => [
 *         'Twigra\ServiceProvider'
 *     ];
 * </code>
 */
class ServiceProvider extends ViewServiceProvider
{
    /**
     * {@inheritdoc}
     */
    public function register()
    {
        $this->registerOptions();
        $this->registerLoaders();
        $this->registerEngine();
        $this->registerAliases();
    }

    /**
     * {@inheritdoc}
     */
    public function boot()
    {
        $this->loadConfiguration();
        $this->registerExtension();
    }

    /**
     * Check if we are running on PHP 7.
     *
     * @return bool
     */
    protected function isRunningOnPhp7()
    {
        return version_compare(PHP_VERSION, '7.0-dev', '>=');
    }

    /**
     * Load the configuration files and allow them to be published.
     *
     * @return void
     */
    protected function loadConfiguration()
    {
        $configPath = __DIR__ . '/../config/twigra.php';

        $this->publishes([$configPath => $this->app->configPath('twigra.php')], 'config');
        $this->mergeConfigFrom($configPath, 'twigra');
    }

    /**
     * Register the Twig extension in the Laravel View component.
     *
     * @return void
     */
    protected function registerExtension()
    {
        $this->app['view']->addExtension(
            $this->app['twig.extension'],
            'twig',
            function () {
                return $this->app['twig.engine'];
            }
        );
    }

    /**
     * Register Twig config option bindings.
     *
     * @return void
     */
    protected function registerOptions()
    {
        $this->app->bindIf('twig.extension', function () {
            return $this->app['config']->get('twigra.twig.extension');
        });

        $this->app->bindIf('twig.options', function () {
            $options = $this->app['config']->get('twigra.twig.environment', []);

            // Check whether we have the cache path set
            if (! isset($options['cache']) || is_null($options['cache'])) {
                // No cache path set for Twig, lets set to the Laravel views storage folder
                $options['cache'] = storage_path('framework/views/twig');
            }

            return $options;
        });

        $this->app->bindIf('twig.extensions', function () {
            $load = $this->app['config']->get('twigra.extensions.enabled', []);

            // Is debug enabled?
            // If so enable debug extension
            $options = $this->app['twig.options'];
            $isDebug = (bool) (isset($options['debug'])) ? $options['debug'] : false;

            if ($isDebug) {
                array_unshift($load, DebugExtension::class);
            }

            return $load;
        });

        $this->app->bindIf('twig.lexer', function () {
            return null;
        });
    }

    /**
     * Register Twig loader bindings.
     *
     * @return void
     */
    protected function registerLoaders()
    {
        // The array used in the ArrayLoader
        $this->app->bindIf('twig.templates', function () {
            return [];
        });

        $this->app->bindIf('twig.loader.array', function ($app) {
            return new ArrayLoader($app['twig.templates']);
        });

        $this->app->bindIf('twig.loader.viewfinder', function () {
            return new Twig\Loader(
                $this->app['files'],
                $this->app['view']->getFinder(),
                $this->app['twig.extension']
            );
        });

        $this->app->bindIf(
            'twig.loader',
            function () {
                return new ChainLoader([
                    $this->app['twig.loader.array'],
                    $this->app['twig.loader.viewfinder'],
                ]);
            },
            true
        );
    }

    /**
     * Register Twig engine bindings.
     *
     * @return void
     */
    protected function registerEngine()
    {
        $this->app->bindIf(
            'twig',
            function () {
                $extensions = $this->app['twig.extensions'];
                $lexer = $this->app['twig.lexer'];
                $twig = new Bridge(
                    $this->app['twig.loader'],
                    $this->app['twig.options'],
                    $this->app
                );

                foreach ($this->app['config']->get('twigra.twig.safe_classes', []) as $safeClass => $strategy) {
                    $twig->getExtension(EscaperExtension::class)->addSafeClass($safeClass, $strategy);
                }

                // Instantiate and add extensions
                foreach ($extensions as $extension) {
                    // Get an instance of the extension
                    // Support for string, closure and an object
                    if (is_string($extension)) {
                        try {
                            $extension = $this->app->make($extension);
                        } catch (\Exception $e) {
                            throw new InvalidArgumentException(
                                "Cannot instantiate Twig extension '$extension': " . $e->getMessage()
                            );
                        }
                    } elseif (is_callable($extension)) {
                        $extension = $extension($this->app, $twig);
                    } elseif (! is_a($extension, ExtensionInterface::class)) {
                        throw new InvalidArgumentException('Incorrect extension type');
                    }

                    $twig->addExtension($extension);
                }

                // Set lexer
                if (is_a($lexer, Lexer::class)) {
                    $twig->setLexer($lexer);
                }

                return $twig;
            },
            true
        );

        $this->app->alias('twig', Twig_Environment::class);
        $this->app->alias('twig', Bridge::class);

        $this->app->bindIf('twig.compiler', function () {
            return new Engine\Compiler($this->app['twig']);
        });

        $this->app->bindIf('twig.engine', function () {
            return new Engine\Twig(
                $this->app['twig.compiler'],
                $this->app['twig.loader.viewfinder'],
                $this->app['config']->get('twigra.twig.globals', [])
            );
        });
    }

    /**
     * Register aliases for classes that had to be renamed because of reserved names in PHP7.
     *
     * @return void
     */
    protected function registerAliases()
    {
        if (! $this->isRunningOnPhp7() and ! class_exists('Twigra\Extension\Laravel\String')) {
            class_alias('Twigra\Extension\Laravel\Str', 'Twigra\Extension\Laravel\String');
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            'twig.extension',
            'twig.options',
            'twig.extensions',
            'twig.lexer',
            'twig.templates',
            'twig.loader.array',
            'twig.loader.viewfinder',
            'twig.loader',
            'twig',
            'twig.compiler',
            'twig.engine',
        ];
    }
}
