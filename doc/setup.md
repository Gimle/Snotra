Setup / Install
===============

Web server setup
---------------

The base idea about the web server setup is that the public directory is what is exposed publically. Very commonly with php sites is that all files are public. The second part is more common, which is to point all request that is not directly to files that are public to be routed to the index.php file, which will route them further internally. A more in dept guide to this will be available in the [phpFramework](https://github.com/Gimle/phpFramework) project one glorious day.

Site setup
----------

This repository uses git submodules to be sure to clone this repository with submodules.

Local configuration is done in the config.ini file. Makes sure the values there reflect your setup.

Most of the setup should be documented in the [Gimle phpFramework](https://github.com/Gimle/phpFramework) but at the time of writing that is not ready yet. Both documentation for manual setup, and a debian package is still on the horizon.

System setup
------------
If you want snotra to be able to perform git commands as a system user add:
```bash
www-data ALL=(ALL) NOPASSWD: /bin/bash, /usr/bin/git
```
to ```visudo```.

Apache mpm_itk_module addon
---------------------------
```apache
<IfModule mpm_itk_module>
	LimitUIDRange 0 6000
	LimitGIDRange 0 6000
</IfModule>
```
