# Weglot Companion

Weglot Companion WordPress Plugin

---
This package can be used to scaffold a modern WordPress plugin. Its architecture is based on our [SquareOne Framework](https://github.com/moderntribe/square-one). Follow these steps to get started:

1. Press the "Use template" button at the top of this repo to create a new repo with the contents of this skeleton.
2. Run `php ./configure.php` to run a script that will replace all placeholders throughout all the files.
3. Manually delete `undo-configure.php` once you're satisfied with the replacement.
4. Commit the `composer.lock` file.
5. Adjust this README to suit your plugin.
6. Submit your repo to [packagist](https://packagist.org/).
7. Create a release in GitHub.
---

### Requirements
- php7.4+
- docker
- docker-compose v2+
- nvm or fnm
- node 16+
- yarn 1.22+
- npm 8.3+

### Quick Start First Run

1. Install [SquareOne Docker (so)](https://github.com/moderntribe/square1-global-docker#squareone-docker)
2. Run: `nvm use`
3. Run: `yarn install`
4. Run: `yarn mix`
5. Run: `so bootstrap`
6. Activate your plugin(s) in the WordPress dashboard and start developing!

### Pull Requests / Building

Ensure you run `nvm use` and `yarn prod` and commit the `resources/dist` folder before submitting a PR, so the plugin includes the latest front-end production build.

### Front end

Front end building is powered by [Laravel Mix](https://laravel-mix.com/).

#### Installation
```bash
nvm use; yarn install
```

#### Usage

Run once: `nvm use` and then...

Build for development:

```bash
yarn dev
```

Watch for file changes:

```bash
yarn watch
```

Poll for file changes:

```bash
yarn watch-poll
```

Watch with hot module replacement:

```bash
yarn hot
```

Build for production:

```bash
yarn prod
```

See more options: `yarn mix --help`

### Installing this plugin

#### Composer

If you're using our [SquareOne Framework](https://github.com/moderntribe/square-one) with [Tribe Libs](https://github.com/moderntribe/tribe-libs) v3.4+ you can likely just create a release and composer require the plugin. However, if this results in composer conflicts that can't be resolved, use the private composer method below.

```bash
composer require moderntribe/weglot-companion
```

#### Private Composer Installer

Every published [release](https://github.com/moderntribe/weglot-companion/releases) automatically creates a `weglot-companion.zip` which is a fully built and vendor scoped WordPress plugin, about a minute after the release is published. To manually install, visit a release and download and extract the zip in your WordPress plugins folder.

However, the best way to include the release zip is by using the fantastic [ffraenz/private-composer-installer](https://github.com/ffraenz/private-composer-installer) plugin.

Add a custom repository to your project's `repository` key in `composer.json`:

```json
  "repositories": [
    {
      "type": "package",
      "package": {
        "name": "moderntribe/weglot-companion",
        "version": "1.0.0",
        "type": "wordpress-plugin",
        "dist": {
          "type": "zip",
          "url": "https://github.com/moderntribe/weglot-companion/releases/download/{%VERSION}/weglot-companion.zip"
        },
        "require": {
          "ffraenz/private-composer-installer": "^5.0"
        }
      }
    },
 ],
```

> NOTE: Simply update the version above and run `composer update` to upgrade the plugin in the future.

Then, add the plugin definition to the require section:

```json
  "require": {
    "moderntribe/weglot-companion": "*",
  }
```

Tell composer where to put your WordPress plugins/themes via the `extra` section.

> NOTE: Adjust the paths based on your project.

```json
  "extra": {
    "wordpress-install-dir": "wp",
    "installer-paths": {
      "wp-content/mu-plugins/{$name}": [
        "type:wordpress-muplugin"
      ],
      "wp-content/plugins/{$name}": [
        "type:wordpress-plugin"
      ],
      "wp-content/themes/{$name}": [
        "type:wordpress-theme"
      ]
    }
  },
```  

You may have to allow this plugin in your config as well:

```json
    "allow-plugins": {
      "composer/installers": true,
      "ffraenz/private-composer-installer": true,
    }
```

Finally, install the plugin:

```bash
composer update
```

### Tests

Automated testing is powered by [wp-browser](https://wpbrowser.wptestkit.dev/) and [Codeception](http://codeception.com/).

`so test` is a proxy command for the [codeception cli](https://codeception.com/docs/reference/Commands).

Run all test suites:

```bash
so project:test
```

Or, run an individual test suite: `unit, integration, functional, webdriver`, e.g.

```bash
so test run integration
```

Or, run with debugging enabled:

```bash
so -- test run integration --debug
```

### Credits

- Based on [Spatie Skeleton](https://github.com/spatie/package-skeleton-php)

### License

GNU General Public License GPLv2 (or later). Please see [License File](LICENSE.md) for more information.

### Modern Tribe

![https://tri.be/contact/](https://moderntribe-common.s3.us-west-2.amazonaws.com/marketing/ModernTribe-Banner.png)
