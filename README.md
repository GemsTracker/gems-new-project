# GemsTracker 2 New project

GemsTracker (GEneric Medical Survey Tracker) is a software package for (complex) distribution of questionnaires and forms during clinical research and quality registrations in healthcare.

This is the start for a new project from where the GemsTracker components are added for all functionality.

See [gemstracker library](https://github.com/GemsTracker/gemstracker-library) for the library and issues

# License
GemsTracker is licensed under the New BSD License - see the [LICENSE](LICENSE.txt) file for details

# Contribute
Help on improving and developing the library is appreciated

# Development
Development can be done using docker. Start by copying `.env.example` to `.env` and modify if necessary.
Then, shortcuts are available for frequent docker commands:

    ./dev up          # Start containers
    ./dev init        # Performs composer install
    ./dev composer run development-enable      # Enable development mode

After the first two commands, the application is available through http://gemstracker.test/ . Additionally, http://adminer.test/ provides access to the database and http://mailhog.test/ collects all sent e-mails.

To use local repositories instead of the Github vestions add `path` statements to your `composer.json`: 

        {
            "type": "path",
            "url": "lib/gemstracker/gems-api"
        },


Make sure to also perform the following commands to populate the database:

    ./dev php vendor/bin/phinx migrate
    ./dev php vendor/bin/phinx seed:run

Other useful commands:

    ./dev npm update        # Update npm modules
    ./dev npm run build     # Rebuild css/javascript
    ./dev npm run dev       # Live rebuild css/javascript

To rebuild these from local repositories, replace in `package.json`: 

    "gems-js": "github:GemsTracker/gems-js",

With a local reference: 

    "gems-js": "file:./lib/gemstracker/gems-js",