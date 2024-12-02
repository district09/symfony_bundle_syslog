# Digipolis Symfony syslog bundle (for ELK stack)

[![Latest Stable Version](https://poser.pugx.org/digipolisgent/syslog-bundle/v/stable)](https://packagist.org/packages/digipolisgent/syslog-bundle)
[![Latest Unstable Version](https://poser.pugx.org/digipolisgent/syslog-bundle/v/unstable)](https://packagist.org/packages/digipolisgent/syslog-bundle)
[![Total Downloads](https://poser.pugx.org/digipolisgent/syslog-bundle/downloads)](https://packagist.org/packages/digipolisgent/syslog-bundle)
[![License](https://poser.pugx.org/digipolisgent/syslog-bundle/license)](https://packagist.org/packages/digipolisgent/syslog-bundle)

[![Build Status](https://travis-ci.org/digipolisgent/symfony_bundle_syslog.svg?branch=develop)](https://travis-ci.org/digipolisgent/symfony_bundle_syslog)
[![Maintainability](https://api.codeclimate.com/v1/badges/f787bf03a4c2552f0a3d/maintainability)](https://codeclimate.com/github/digipolisgent/symfony_bundle_syslog/maintainability)
[![Test Coverage](https://api.codeclimate.com/v1/badges/f787bf03a4c2552f0a3d/test_coverage)](https://codeclimate.com/github/digipolisgent/symfony_bundle_syslog/test_coverage)

Configures the symfony (monolog) syslog logger to output in a format that kibana
can parse. No manual configuration is required.

## Installation

Make sure Composer is installed globally, as explained in the
[installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.

### Applications that use Symfony Flex

Open a command console, enter your project directory and execute:

```console
composer require digipolisgent/syslog-bundle
```

### Applications that don't use Symfony Flex

#### Step 1: Download the Bundle

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```console
composer require digipolisgent/syslog-bundle
```

#### Step 2: Enable the Bundle

Then, enable the bundle by adding it to the list of registered bundles
in the `config/bundles.php` file of your project:

```php
// config/bundles.php

return [
    // ...
    DigipolisGent\SyslogBundle\DigipolisGentSyslogBundle::class => ['all' => true],,
];
```

### Configuration (with or without Symfony Flex)

Add a parameter to `.env` to set the base URL & identifier for the site.
This is used to identify logging of different websites.

Replace the base url and identifier with site URL and site name.

```dotenv
# .env

###
# SYSLOG configuration (Kibana).
###
DIGIPOLIS_SYSLOG_DEFAULT_BASE_URL=https://domain.ext
DIGIPOLIS_SYSLOG_IDENTIFIER=sitename
```
