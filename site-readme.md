# {{WP_BOILERPLATE_SLUG}}

## Development


### Requirements

- [Lando] - `brew install --cask lando`
- [Dnsmasq] - `brew install dnsmasq`
- [SOPS] - `brew install sops`
- [PHP] - `brew install php@8.0`
- [Composer] - `brew install composer`


### Setup

1. Clone this repo
2. Run `composer run decrypt` to decrypt the config files
3. Run `lando start` to get the website's dependencies installed and the app started up
4. Run `lando dep setup:init staging` to configure the website, compile theme files, and pull down the database and uploads. (Replace `staging` with `production` to pull down database and uploads from prod.)


#### Local URL

This website should be available at [http://{{WP_BOILERPLATE_SLUG}}.hmdev](http://{{WP_BOILERPLATE_SLUG}}.hmdev). In order for that to work you will need to have [Dnsmasq] installed and configured. See Lando's [Developing offline] documentation for some help on how to do that. Additionally, to get the HTTPS version of the local website working, see Lando's [Trusting the CA] documentation.


### Commands

[Lando] is used for the development environment and [WordPress Deployer] is used for deployment. You can always run `lando` or `lando dep` to see what commands are available to you, however these are probably the ones you'll use the most:

(You'll need an [SSH agent] running on your machine to use any Deployer commands.)

| Command                                              | Description                                                                                                                                                             |
| ---------------------------------------------------- | ----------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `lando start`                                        | Starts the app in an isolated container                                                                                                                                 |
| `lando stop`                                         | Stops the app                                                                                                                                                           |
| `lando destroy`                                      | Cleans out the app development environment. (Note: your uploads and database will be deleted as well)                                                                   |
| `lando build`                                        | Compiles SCSS and JS files (using the [Front End Compiler] tool)                                                                                                        |
| `lando composer`                                     | Runs [Composer] commands                                                                                                                                                |
| `lando composer run post-install`                    | Runs the post-install script which simply installs any [Git Package Manager] dependencies. You should run this if you update `compile.json` in the theme                |
| `lando wp`                                           | Runs [WP-CLI] commands                                                                                                                                                  |
| `lando dep deploy [staging/production]`              | Deploys the code (cloning from its respective repo branch, except for compiled files, so you will want to make sure you run `lando build` first)                        |
| `lando dep deploy:extra_files [staging/production]`  | Deploys only the compiled SCSS and JS files (this should be considered a hotfix, a new release will not be deployed, just the new compiled files)                       |
| `lando dep db:[push/pull] [staging/production]`      | Pushes/pulls the database to/from the server                                                                                                                            |
| `lando dep db:backup [staging/production]`           | Exports and downloads a dump of the database. Omit the stage to make a backup of your local database.                                                                   |
| `lando dep db:fix`                                   | Converts the local database encoding from `utf8mb4_unicode_520_ci` to `utf8mb4_unicode_ci`. Run this if you get a `Unknown collation` error while pushing the database. |
| `lando dep uploads:[push/pull] [staging/production]` | Pushes/pulls the uploads to/from the server                                                                                                                             |
| `lando ssh-fix`                                      | Fixes SSH auth sock permission for MacOS users. If you ever run into the issue where your SSH Agent quit working in Lando, try running this to fix it.                  |
| `composer run [encrypt/decrypt]`                     | Encrypts/decrypts config files using [SOPS]. You should run this if you make any changes to `auth.json` or `config.yml`                                                 |

(Append `-vvv` to any of the above commands to get the full output.)


### External Database Connection

If you want to be able to connect to the site's local database using something like [Sequel Pro], run `lando info` and you'll find the connection information you need under `external_connection` in the `database` service. The port used changes every time you start the app in Lando to avoid conflicts with other apps, but everything else should stay the same:

```
Host: 127.0.0.1
Port: (Different every time you run the app)
Username: root
Password: (Blank)
```


## Server Environment

The deploy script will set up the remote environment with the following file structure:

*`public_path` and `deploy_path` can be configured in `config.yml`.*

```
{{public_path}} - The publicly viewable directory. This will get symlinked to the current release.
{{deploy_path}} - The directory where all files are deployed to.
{{deploy_path}}/.dep - Storage for Deployer information
{{deploy_path}}/current - A symlink to the current release.
{{deploy_path}}/releases - Where all the deployments are stored.
{{deploy_path}}/shared - Where files that are shared between releases are stored such as uploads, wp-config.php, and .htaccess.
```


### Deploying to a server for the first time

To get the site up and running on a new server, you will want to make sure that the information for that specific stage is defined in `config.yml`. You can then run the following commands in order:

```
lando build
lando dep deploy [stage]
lando dep setup:remote [stage]
lando dep db:push [stage]
lando dep uploads:push [stage]
```


[Composer]: https://getcomposer.org/doc
[Developing offline]: https://docs.lando.dev/guides/offline-dev.html
[Dnsmasq]: https://thekelleys.org.uk/dnsmasq/doc.html
[Front End Compiler]: https://github.com/itsahappymedium/fec
[Git Package Manager]: https://github.com/itsahappymedium/gpm
[Lando]: https://lando.dev
[PHP]: https://php.net
[Sequel Pro]: https://sequelpro.com
[SOPS]: https://github.com/mozilla/sops
[SSH agent]: https://docs.github.com/en/github/authenticating-to-github/generating-a-new-ssh-key-and-adding-it-to-the-ssh-agent
[Trusting the CA]: https://docs.lando.dev/config/security.html#trusting-the-ca
[WordPress Deployer]: https://github.com/itsahappymedium/wp-deployer
[WP-CLI]: https://developer.wordpress.org/cli/commands