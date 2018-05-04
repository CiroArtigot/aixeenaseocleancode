Aixeena SEO Clean Code
===============

1.- Description
---------------------------  

**Aixeena SEO Clean Code** it is a plugin to clean the Joomla sites HTML code

With this plugin you can get better results in the performance of your website, compressing the HTML code, disabling core Joomla innecesary scripts and metas.

This plugin help developers to work with their own scripts, taking the posibility to hide the Joomla core scripts, and of insatalled extensions too.

Why not working with a Joomla site without scripts on your code? or working with angular?

This plugin also cleans the code and compress the Joomla HTML code.

The plugin is thinked for Joomla developers who need or love to increase the perfomance of their sites.

This plugin is created by [Ciro Artigot](http://twitter/ciroartigot) to contribute to the Joomla community.

2.- Features
---------------------------
* Remove Joomla meta name generator.
* Remove Joomla base href meta.
* Disable linked scripts (.js), you can disable any script added by Joomla (core or extensions), for example:
```bash
/media/jui/js/jquery.min.js
/media/jui/js/jquery-noconflict.js
/media/jui/js/jquery-migrate.min.js
/media/system/js/caption.js
```
* Disable / hide scripts added by Joomla, for example:
```bash
<script type="text/javascript">
jQuery(window).on('load',  function() {
new JCaption('img.caption');
});
</script>
```
 (note: this option will hide ALL the Joomla Scripts added by the Method AddScriptDeclaration, you will need to insert your own scripts without using it).

* Enable the clean code option to get a clean version of the HTML code.
* Enable the compress code option to get a compress version of the HTML code.

3.- Install / Configuration
--------------------------- 
- Clone the repository or [Download Zip file](https://github.com/CiroArtigot/aixeenaseocleancode/archive/master.zip)
- Install it through Joomla Extension Manager 
- Go to Extensions > Plug-in manager and search a plugin called "System - Aixeena SEO Images". Click it to enable / configure the plugin.

4.- Important notes
---------------------------

* This extension is on beta mode, so if you are going to use it on production sites remember that is GNU licensed and no there is no warranty.

5.- Donate
---------------------------
You can [donate](https://www.paypal.com/donate/?token=YJ_4RSeWoYiDjVYv0nqui0cvJgVJMI7Gp0NoDFs0URpD_VrWNAcwPy5bw3ZLWTcvSKEoW0&country.x=US&locale.x=US) and help my with a beer or a cup of coffe to continue developing free an opensource for Joomla!

6.- Author & License
---------------------------
AixeenaSEO images was developed by [Ciro Artigot](http://twitter.com/ciroartigot).

This extension is licensed under GNU/GPL 2, http://www.gnu.org/licenses/gpl-2.0.html  

7.- Contact
---------------------------
**Email**: ciro.artigot(at)gmail.com  
**Twitter**: [http://twitter.com/ciroartigot](http://twitter.com/ciroartigot)  
**Linked-In**: [Linked In](https://www.linkedin.com/in/ciroartigot)  

8.- Changelog
---------------------------
v.1.0.0 - Beta version  
