# Container Builder

[Container Builder](https://container-builder.com) - Docker Compose skeletons for Projects

## What?

Container Builder is a website that allows you to generate Docker Compose configuration files and Dockerfiles for use in your projects. The configuration files are easily editable after-the-fact as your project grows, but Container Builder strives to provide a quick, out-of-the-box develop environment for you and your workflows.

## How?

Container Builder generates a `docker-compose.yml` file, along with a set of `Dockerfile`s, based on the settings you choose. You can then extract them into the root of your project and go from there!

The site itself is built using a [PHP](http://www.php.net) backend to generate the files, with a [VueJS](https://vuejs.org/) frontend.

## Why?

Docker has become one of the go-to development tools for many developers, especially web developers. Even if you do not deploy your site using Docker, it is an awesome development tool for quickly spinning up environments for a project. Most developers also create a kind of standard "base" environment they always work from and modify with each project, and Container Builder is born from that. It gives you a set of defaults that you can modify for each project.

## Who?

[Chris Tankersley](https://twitter.com/dragonmantank), and anyone else who wants to contribute. Even you!

## Goals

Right now, generate clean, easy-to-use Docker configuration.

Eventually, create expandable, ready-for-production Docker configuration.

## Contribution

Find a bug? Have an idea? Want to help? Check out the [Contributing instructions](CONTRIBUTING.md). Please keep in mind we also have a [Code of Conduct](CODE_OF_CONDUCT.md), so please be respectful of other developers.

## License

Container Builder itself is licensed under the [GPLv3](LICENSE). Libraries used in the project are GPL-compatible licenses, and may differ from the main project.