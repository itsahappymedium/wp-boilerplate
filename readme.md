# ![Happy Medium](content/themes/theme/img/favicon.png) Happy Medium's WordPress Boilerplate

A simple, yet robust boilerplate for a new WordPress site.


## Features

 - An easy to use [Docker] container à la [Lando]
 - Encrypted configuration files to keep your credentials secure via [SOPS]
 - A built-in deployment solution using our [WordPress Deployer] tool which under the hood uses [Deployer]
 - Front-end package management using our [Git Package Manager] tool
 - Front-end JavaScript/SCSS compiling/minifying using our our [Front End Compiler] tool
 - Plugin/package management via [Composer] (Pre-configured to pull plugins and packages from [WordPress Packagist] as well as default [Packagist])
 - A built-in [setup](setup.php) script to easily get you up and running
 - Best of all, all tools listed above excluding Lando and SOPS are installed in the container for you automatically. Additionally, excluding Composer itself, those tools are all PHP packages, so your development tools are written in the same language that your website is written in


### Additional WordPress Features

 - A custom "manager" role for clients that prevents them from breaking anything on accident
 - [Advanced Custom Fields Pro] JSON field group syncing so that field groups are version-controlled
 - Comments completely disabled by default to prevent spam
 - A customized login screen
 - A couple of small helper functions


## Requirements

 - [Lando]
 - [Dnsmasq]
 - [SOPS] - Only needed if you're planning on using SOPS for config file encryption
 - [PHP] - Only needed for the setup script and SOPS shortcut commands (The website will use a separate PHP installation in the container)
 - [Composer] - Only needed for SOPS shortcut commands (The website will use a separate Composer installation in the container)


## Prerequisites

In order for your website to be available on your machine via a custom URL, you'll want to have [Dnsmasq] set up. See Lando's [Developing offline] documentation for some help on how to do that. By default, this boilerplate uses the `.hmdev` TLD making the local version of your website available at http://SITE-SLUG.hmdev. This can be changed by modifying the `proxy` value in `.lando.yml`. Furthermore, to get the HTTPS version of your local website working, see Lando's [Trusting the CA] documentation.


## Getting Started


### Step 1

Clone this repository (replace `SITE-SLUG` with your website's slug):

```bash
$ git clone git@github.com:itsahappymedium/wp-boilerplate.git SITE-SLUG
```


### Step 2

Run the setup script:

```bash
$ cd SITE-SLUG && php setup.php
```


### Step 3

Take a look at `config.yml` and see if everything looks right, make any needed edits.

Also look at `.lando.yml` and make any needed changes like PHP and MySQL version.

While you're at it, make any needed changes to `composer.json`. For example, if you're not a [Happy Medium] developer or a [KodeHappy] customer then you won't have access to those package repositories, so remove them from the `repositories` section as well as any non-public packages from those repositories listed in the `require` section.

At Happy Medium we use a private repository to distribute any premium plugins we use such as [Advanced Custom Fields Pro] and [Gravity Forms], so if you are a Happy Medium developer or KodeHappy customer, be sure to enter your access keys in `auth.json`.


### Step 4

Start your engines:

```bash
$ lando start && lando dep setup:local
```

You should be given the URL of the website on your local machine as well as the username and password generated for your admin user.

*Tip: If the URL is displayed in red, try running `lando rebuild`. If it's still red after that, you probably have an issue with your dnsmasq setup.*


### Step 5

You're pretty much all up and going at this point! Visit the local website in your browser and make sure everything is working. Try logging into the admin dashboard at `/wordpress/wp-admin`.

One last thing before you start building, run `composer run encrypt` to generate the encrypted copies of your `auth.json` and `config.yml` files that will be committed to the repo.

Happy building!


## Setup script configuration

The setup script will prompt you to enter some information about your website. It'll then do three things, search and replace variables with those values in some files, delete some files, and rename some files. It's pretty simple, see the [setup.php](setup.php) source code to see what's going on under the hood.

Furthermore, default values for these variables can be set using environment variables, for example:

```bash
export WP_BOILERPLATE_VENDOR="itsahappymedium"
```


## License

MIT. See the [license.md file](license.md) for more info.


[Advanced Custom Fields Pro]: https://advancedcustomfields.com
[Composer]: https://getcomposer.org
[Deployer]: https://deployer.org
[Developing offline]: https://docs.lando.dev/guides/offline-dev.html
[Dnsmasq]: https://thekelleys.org.uk/dnsmasq/doc.html
[Docker]: https://docker.com
[Front End Compiler]: https://github.com/itsahappymedium/fec
[Git Package Manager]: https://github.com/itsahappymedium/gpm
[Gravity Forms]: https://gravityforms.com
[Happy Medium]: https://itsahappymedium.com
[KodeHappy]: https://kodehappy.com
[Lando]: https://lando.dev
[Packagist]: https://packagist.org
[PHP]: https://php.net
[SOPS]: https://github.com/mozilla/sops
[Trusting the CA]: https://docs.lando.dev/config/security.html#trusting-the-ca
[WordPress Deployer]: https://github.com/itsahappymedium/wp-deployer
[WordPress Packagist]: https://wpackagist.org