# A3M  
### 

A3M (Account Authentication & Authorization) is a CodeIgniter 2.x package that leverages bleeding edge web technologies 
like OpenID and OAuth to create a user-friendly user experience. It gives you the CRUD to get working right away 
without too much fuss and tinkering! Designed for building webapps from scratch without all that tiresome 
login / logout / admin stuff thats always required.

## Original Authors

**Jakub** [@kubanishku](https://twitter.com/kubanishku/)
**PengKong** [@pengkong](https://github.com/pengkong)
		
## Key Features & Design Goals

* Native Sign Up, Sign In with 'Remember me' and Sign Out
* Native account Forgot Password and Reset Password
* Facebook/Twitter/Google/Yahoo/OpenID Sign Up, Sign In and Sign Out
* Manage Account Details, Profile Details and Linked Accounts
* reCAPTCHA Support, SSL Support, Language Files Support
* Gravatar support for picture selection (via account profile)
* Create a painless user experience for sign up and sign in
* Create code that is easily understood and re-purposed
* Utilize Twitter Bootstrap (a fantastic CSS / JS library)
* Graceful degradation of JavaScript and CSS
* Proper usage of CodeIgniter's libraries, helpers and plugins
* Easily Configurable via config file

## Folder structure  

* `/application/` - what you should be editing / creating in
* `/system/` - default CodeIgniter system folder (don't touch!)
* `/resource/` - css / images / javascript (folder configurable via `constants.php`)
* `/user_guide/` - latest guide for CI (can be deleted, just for CI reference)

## 3rd Party Libraries & Plugins

* [recaptcha_pi.php](http://code.google.com/p/recaptcha/) - recaptcha-php-1.11
* [facebook_pi.php](https://github.com/facebook/facebook-php-sdk/) - v.3.2.3
* [twitter_pi.php](https://github.com/jmathai/twitter-async) - Updated to latest release - [Jan 07, 2014](https://github.com/jmathai/twitter-async/commits/master)
* [phpass_pi.php](http://www.openwall.com/phpass/) - Version 0.3 / genuine _(latest)_ 
* [openid_pi.php](http://sourcecookbook.com/en/recipes/60/janrain-s-php-openid-library-fixed-for-php-5-3-and-how-i-did-it) - php-openid-php5.3
* [gravatar.php](https://github.com/rsmarshall/Codeigniter-Gravatar) - codeigniter (6/25/2012) rls

## Dependencies

* CURL
* DOM or domxml 
* GMP or Bcmatch

## Installation Instructions
Check out our wiki: https://github.com/donjakobo/A3M/wiki/Installation-Instructions
for help on getting started.

## Help and Support  
* Found a bug? Try forking and fixing it. 
* Open an issue if you want to discuss/highlight it
* Go to StackOverflow under the tag `codeigniter-a3m` http://stackoverflow.com/questions/tagged/codeigniter-a3m if you have implementation issues (installation problems, etc;)

# [HTML5 Boilerplate](http://html5boilerplate.com)

HTML5 Boilerplate is a professional front-end template for building fast,
robust, and adaptable web apps or sites.

This project is the product of many years of iterative development and combined
community knowledge. It does not impose a specific development philosophy or
framework, so you're free to architect your code in the way that you want.

* Source: [https://github.com/h5bp/html5-boilerplate](https://github.com/h5bp/html5-boilerplate)
* Homepage: [http://html5boilerplate.com](http://html5boilerplate.com)
* Twitter: [@h5bp](http://twitter.com/h5bp)


## Quick start

Choose one of the following options:

1. Download the latest stable release from
   [html5boilerplate.com](http://html5boilerplate.com/) or a custom build from
   [Initializr](http://www.initializr.com).
2. Clone the git repo — `git clone
   https://github.com/h5bp/html5-boilerplate.git` - and checkout the tagged
   release you'd like to use.


## Features

* HTML5 ready. Use the new elements with confidence.
* Cross-browser compatible (Chrome, Opera, Safari, Firefox 3.6+, IE6+).
* Designed with progressive enhancement in mind.
* Includes [Normalize.css](http://necolas.github.com/normalize.css/) for CSS
  normalizations and common bug fixes.
* The latest [jQuery](http://jquery.com/) via CDN, with a local fallback.
* The latest [Modernizr](http://modernizr.com/) build for feature detection.
* IE-specific classes for easier cross-browser control.
* Placeholder CSS Media Queries.
* Useful CSS helpers.
* Default print CSS, performance optimized.
* Protection against any stray `console.log` causing JavaScript errors in
  IE6/7.
* An optimized Google Analytics snippet.
* Apache server caching, compression, and other configuration defaults for
  Grade-A performance.
* Cross-domain Ajax and Flash.
* "Delete-key friendly." Easy to strip out parts you don't need.
* Extensive inline and accompanying documentation.


## Documentation

Take a look at the [documentation table of contents](doc/TOC.md). This
documentation is bundled with the project, which makes it readily available for
offline reading and provides a useful starting point for any documentation you
want to write about your project.


## Contributing

Anyone and everyone is welcome to [contribute](CONTRIBUTING.md). Hundreds of
developers have helped make the HTML5 Boilerplate what it is today.
