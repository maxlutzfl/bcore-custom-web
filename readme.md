# Custom Web
<a href="https://github.com/maxlutzfl/bcore-custom-web/wiki">See wiki for more</a>

## Getting started
- All terminal commands should be run from the base directory. We will store WP with the `/web` directory to keep things organized. 

** Setup `gulpfile.js` **
- Set `themeName` to your project's theme directory name (should be a child theme)
- Set `developmentUrl` to your project's MAMP URL

**Install WP**
```
$ get-wp
```
*If this command fails, run `chmod u+x bin/get-wp` to make sure you have permission. This command will grab the latest WP version and unzip the contents into the `/web` directory, without the `/wp-contents` directory since we already have that available from this repo.*

**gulp.watch for changes in your theme's SASS or Javascript and run BrowserSync** 
```
$ gulp
```


