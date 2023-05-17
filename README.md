# Omega Pass

The last password - Omega Pass

[![PHPUnit](https://github.com/2223-Project-1/OmegaPass/actions/workflows/phpunit.yml/badge.svg?branch=master)](https://github.com/2223-Project-1/OmegaPass/actions/workflows/phpunit.yml)

### Self-hosting
**Required tools:** Docker, Docker Compose, Git

1. Clone repository to your local machine.
2. ``cd OmegaPass``
3. Create a file named .env with ``touch .env``.
4. Paste this into the .env file.
```
HOST="db"
DATABASE="pwmanager"
USERNAME="root"
PASSWORD="root"
PORT="3306"
````
5. ``docker-compose up --build -d`` to build OmegaPass and to startup the containers.
6. Open [localhost:8000](localhost:8000)

There you go. You now have a instance of OmegaPass running on your own machine. It comes with its own MySQL database and PhpMyAdmin for managing the database in the browser on [localhost:8081](localhost:8081). **Since you are running your own copy of OmegaPass, the team behind OmegaPass isn't responsable for changes you make to the source code.**


### Developing
All source code is mounted directly to Docker and its containers. You see changes instantly after reloading of course.

To compile **scss stylesheets** ([installation guide](https://sass-lang.com/install)) you need to run this command:
```bash
sass --no-source-map --style compressed public/css --watch
``` 

To minify JavaScript files using **UglifyJS** ([installation guide](https://github.com/mishoo/UglifyJS#install)), you can use the following command:
```bash
npx uglifyjs public/js/{{ script }}.js -o public/js/{{ script }}.min.js --compress --mangle
```
**Replace `{{ script }}` with the name of your JavaScript file.**

## Authors

Contributors names and contact info
  
* [@Armin Burger](https://github.com/techmaved)
* [@Johannes Thiem](https://github.com/Fovty)
* [@Tom](https://github.com/lvlcn-t)
* [@Dennis](https://github.com/WildeHilde6)

## Version History

* 0.1.0
    * Initial Release

## License

This project is licensed under the [] License - see the LICENSE.md file for details
