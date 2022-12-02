# Laravel Share

[![Latest Version on Packagist](https://img.shields.io/packagist/v/gofatoni/laravel-share.svg?style=flat-square)](https://packagist.org/packages/gofatoni/laravel-share)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/gofatoni/laravel-share/master.svg?style=flat-square)](https://travis-ci.org/gofatoni/laravel-share)
[![SensioLabsInsight](https://img.shields.io/sensiolabs/i/dde6008b-ccc6-4a3f-8a98-37d76532f956.svg?style=flat-square)](https://insight.sensiolabs.com/projects/dde6008b-ccc6-4a3f-8a98-37d76532f956)
[![Total Downloads](https://img.shields.io/packagist/dt/gofatoni/laravel-share.svg?style=flat-square)](https://packagist.org/packages/gofatoni/laravel-share)

Share links exist on almost every page in every project, creating the code for these share links over and over again can be a pain in the ass.
With Laravel Share you can generate these links in just seconds in a way tailored for Laravel.

### Available services

* Facebook
* Twitter
* Linkedin
* WhatsApp
* Reddit
* Telegram

## Installation

You can install the package via composer:

``` bash
composer require retenvi/laravel-share
```


If you don't use auto-discovery, add the ServiceProvider to the providers array in config/app.php

```php
// config/app.php
'providers' => [
    ...
    Retenvi\Share\Providers\ShareServiceProvider::class,
];
```

And optionally add the facade in config/app.php

```php
// config/app.php
'aliases' => [
    ...
    'Share' => Retenvi\Share\ShareFacade::class,
];
```

Publish the package config & resource files.

```bash
php artisan vendor:publish --provider="Retenvi\Share\Providers\ShareServiceProvider"
```

> You might need to republish the config file when updating to a newer version of Laravel Share

This will publish the ```laravel-share.php``` config file to your config folder, ```share.js``` in ```public/js/``` and ```laravel-share.php``` in your ```resources/lang/vendor/en/``` folder.

### Fontawesome

Since this package relies on Fontawesome, you will have to require it's css, js & fonts in your app.
You can do that by requesting a embed code [via their website](http://fontawesome.io/get-started/) or by installing it locally in your project.

Laravel share supports Font Awesome v4 and v5, by default v4 is used. You can specify the version you want to use in ```config/laravel-share.php```

### Javascript

Load jquery.min.js & share.js by adding the following lines to your template files.

```html
<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
<script src="{{ asset('js/share.js') }}"></script>
```

## Usage

### Creating one share link

#### Facebook

``` php
Share::page('https://retenv.com')->facebook();
```

#### Twitter

``` php
Share::page('https://retenv.com', 'Your share text can be placed here')->twitter();
```

#### Reddit

``` php
Share::page('https://retenv.com', 'Your share text can be placed here')->reddit();
```

#### Linkedin

``` php
Share::page('https://retenv.com', 'Share title')->linkedin('Extra linkedin summary can be passed here')
```

#### Whatsapp

``` php
Share::page('https://retenv.com')->whatsapp()
```

#### Telegram

``` php
Share::page('https://retenv.com', 'Your share text can be placed here')->telegram();
```

### Sharing the current url

Instead of manually passing an url, you can opt to use the `currentPage` function.

```php
Share::currentPage()->facebook();
```

### Creating multiple share Links

If want multiple share links for (multiple) providers you can just chain the methods like this.

```php
Share::page('https://retenv.com', 'Share title')
	->facebook()
	->twitter()
	->linkedin('Extra linkedin summary can be passed here')
	->whatsapp();
```

This will generate the following html

```html
<div id="social-links">
	<ul>
		<li><a href="https://www.facebook.com/sharer/sharer.php?u=https://retenvi.com" class="social-button " id=""><span class="fa fa-facebook-official"></span></a></li>
		<li><a href="https://twitter.com/intent/tweet?text=my share text&amp;url=https://retenvi.com" class="social-button " id=""><span class="fa fa-twitter"></span></a></li>
		<li><a href="http://www.linkedin.com/shareArticle?mini=true&amp;url=https://retenvi.com&amp;title=my share text&amp;summary=dit is de linkedin summary" class="social-button " id=""><span class="fa fa-linkedin"></span></a></li>
		<li><a href="https://wa.me/?text=https://retenvi.com" class="social-button " id=""><span class="fa fa-whatsapp"></span></a></li>    
	</ul>
</div>
```
### Optional parameters

#### Add extra classes, id's or titles to the social buttons

You can simply add extra class(es), id('s) or title(s) by passing an array as the third parameter on the page method.

```php
Share::page('https://retenvi.com', null, ['class' => 'my-class', 'id' => 'my-id', 'title' => 'my-title'])
    ->facebook();
```

Which will result in the following html

```html
<div id="social-links">
	<ul>
		<li><a href="https://www.facebook.com/sharer/sharer.php?u=https://retenvi.com" class="social-button my-class" id="my-id"><span class="fa fa-facebook-official"></span></a></li>
	</ul>
</div>
```

#### Custom wrapping

By default social links will be wrapped in the following html

```html
<div id="social-links">
	<ul>
		<!-- social links will be added here -->
	</ul>
</div>
```

This can be customised by passing the prefix & suffix as a parameter.

```php
Share::page('https://retenvi.com', null, [], '<ul>', '</ul>')
            ->facebook();
```

This will output the following html.

```html
<ul>
	<li><a href="https://www.facebook.com/sharer/sharer.php?u=https://retenvi.com" class="social-button " id=""><span class="fa fa-facebook-official"></span></a></li>
</ul>
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Credits

- [Joren Van Hocht](https://github.com/jorenvh)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
