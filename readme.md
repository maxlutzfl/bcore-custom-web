# Custom Web
<a href="https://github.com/maxlutzfl/bcore-custom-web/wiki">See wiki for more</a>

## Getting started

All terminal commands should be run from the base directory. We will store WP with the `/web` directory to keep things organized. 

**Install WP**
Open terminal from the base directory
```
$ get-wp
```
This command will grab the latest WP version and unzip the contents into the `/web` directory, without the `/wp-contents` directory since we already have that available from this repo.

**gulp.watch SASS and Javascript changes and run BrowserSync**
```
$ gulp --url example.com.local
```

**or without BrowserSync** 
```
$ gulp
```


