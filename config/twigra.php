<?php

return [
    'twigra' => [
        'twig' => [
            /*
            |--------------------------------------------------------------------------
            | Extension
            |--------------------------------------------------------------------------
            |
            | File extension for Twig view files.
            |
            */
            'extension' => 'twig',

            /*
            |--------------------------------------------------------------------------
            | Accepts all Twig environment configuration options
            |--------------------------------------------------------------------------
            |
            | http://twig.sensiolabs.org/doc/api.html#environment-options
            |
            */
            'environment' => [

                // When set to true, the generated templates have a __toString() method
                // that you can use to display the generated nodes.
                // default: false
                'debug' => environment('App.Debug', false),

                // The charset used by the templates.
                // default: utf-8
                'charset' => 'utf-8',

                // The base template class to use for generated templates.
                // default: Twigra\Twig\Template
                'base_template_class' => 'Twigra\Twig\Template',

                // An absolute path where to store the compiled templates, or false to disable caching. If null
                // then the cache file path is used.
                // default: cache file storage path
                'cache' => null,

                // When developing with Twig, it's useful to recompile the template
                // whenever the source code changes. If you don't provide a value
                // for the auto_reload option, it will be determined automatically based on the debug value.
                'auto_reload' => true,

                // If set to false, Twig will silently ignore invalid variables
                // (variables and or attributes/methods that do not exist) and
                // replace them with a null value. When set to true, Twig throws an exception instead.
                // default: false
                'strict_variables' => false,

                // If set to true, auto-escaping will be enabled by default for all templates.
                // default: 'html'
                'autoescape' => 'html',

                // A flag that indicates which optimizations to apply
                // (default to -1 -- all optimizations are enabled; set it to 0 to disable)
                'optimizations' => -1,
            ],

            /*
            |--------------------------------------------------------------------------
            | Safe Classes
            |--------------------------------------------------------------------------
            |
            | When set, the output of the `__string` method of the following classes will not be escaped.
            | default: Laravel's Htmlable, which the HtmlString class implements.
            |
            */
            'safe_classes' => [
                \Illuminate\Contracts\Support\Htmlable::class => ['html'],
            ],

            /*
            |--------------------------------------------------------------------------
            | Global variables
            |--------------------------------------------------------------------------
            |
            | These will always be passed in and can be accessed as Twig variables.
            | NOTE: these will be overwritten if you pass data into the view with the same key.
            |
            */
            'globals' => [
                'salak' => 'amk',
            ],
        ],

        'extensions' => [

            /*
            |--------------------------------------------------------------------------
            | Extensions
            |--------------------------------------------------------------------------
            |
            | Enabled extensions.
            |
            | `Twig\Extension\DebugExtension` is enabled automatically if twig.debug is TRUE.
            |
            */
            'enabled' => [
                'Twigra\Extension\Loader\Facades',
                'Twigra\Extension\Loader\Filters',
                'Twigra\Extension\Loader\Functions',

                'Twigra\Extension\Laravel\Url',
                'Twigra\Extension\Laravel\Config',
                'Twigra\Extension\Laravel\Dump',
                'Twigra\Extension\Laravel\Translator',
            ],

            /*
            |--------------------------------------------------------------------------
            | Facades
            |--------------------------------------------------------------------------
            |
            | Available facades. Access like `{{ Config.get('foo.bar') }}`.
            |
            */
            'facades' => [
                'Config', 'Form',
            ],

            /*
            |--------------------------------------------------------------------------
            | Functions
            |--------------------------------------------------------------------------
            |
            | Available functions. Access like `{{ secure_url(...) }}`.
            |
            */
            'functions' => [

            ],

            /*
            |--------------------------------------------------------------------------
            | Filters
            |--------------------------------------------------------------------------
            |
            | Available filters. Access like `{{ variable|filter }}`.
            |
            | Each filter can take an optional array of options. These options are
            | passed directly to `Twig\TwigFilter`.
            |
            | So for example, to mark a filter as safe you can do the following:
            |
            | <code>
            |     'studly_case' => [
            |         'is_safe' => ['html']
            |     ]
            | </code>
            |
            | The options array also takes a `callback` that allows you to name the
            | filter differently in your Twig templates than what is actually called.
            |
            | <code>
            |     'snake' => [
            |         'callback' => 'snake_case'
            |     ]
            | </code>
            |
            */
            'filters' => [
                'get' => 'data_get',
            ],
        ],
    ],
];
