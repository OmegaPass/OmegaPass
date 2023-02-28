# Omega Pass

The last password - Omega Pass

### Installing

**Required tools:** Git, Docker Desktop 

 1. Clone repository to your local machine.
 2. `cd OmegaPass`
 3. `docker-compose up --build` to first build OmegaPass and to startup the containers.
 4. Open [localhost:8000](localhost:8000)

### Self-hosting
**Required tools:** Docker

1. Clone repository to your local machine.
2. ```cd OmegaPass```
3. ``docker-compose up --build`` to build OmegaPass and to startup the containers.
4. Create a ``.env`` file.
5. Paste this code in the .env file.
````
HOST="db"
DATABASE="pwmanager"
USERNAME="root"
PASSWORD="root"
PORT="3306"
````


### Developing
After you created the Docker containers You can start them with `docker-compose start web` again.

## Authors

Contributors names and contact info
  
* [@Armin Burger](https://github.com/techmaved)
* [@Johannes Thiem](https://github.com/Fovty)
* [@Tom](https://github.com/lvlcn-t)
* [@Dennis](https://github.com/wildehilde6)

## Version History

* 0.1.0
    * Initial Release

## License

This project is licensed under the [] License - see the LICENSE.md file for details
