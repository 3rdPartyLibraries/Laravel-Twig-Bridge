# Installation

Require this package with Composer

```bash
composer require cfkarakulak/Twigra
```

# Quick Start

Once Composer has installed or updated your packages you need to register Twigra with Laravel itself. Open up config/app.php and find the providers key, towards the end of the file, and add 'Twigra\ServiceProvider', to the end:

```php
'providers' => [
    Twigra\ServiceProvider::class,
],
```

Now find the aliases key, again towards the end of the file, and add 'Twig' => 'Twigra\Facade\Twig', to have easier access to the Twigra (or Twig\Environment):

```php
'aliases' => [
    'Twig' => Twigra\Facade\Twig::class,
],
```

Pass config (twigra.php to local config path)

At this point you can now begin using twig like you would any other view

```php
//app/Http/routes.php
//twig template resources/views/hello.twig
Route::get('/', function () {
    return View::make('hello');
});
```

You can create the twig files in resources/views with the .twig file extension.
```bash
resources/views/hello.twig
```
