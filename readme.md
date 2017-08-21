Snotra
======

A lightweight php based gitolite manager and git browser in development.

This project exists because our small team wanted to use git internally, and want to use gitolite as the main git management, but want a simple web interface for some functionality.


What it is
----------
* Made to run on a linux server where all the users have ssh access, and can clone and manage their home directories.
* Let users manage their public keys.
* Create, rename and delete repositories.
* Gitolite will be the master and have all git responsibilities. If you want to use another frontend, Snotra can be replaced by any other gitolite frontend on the fly.
* Shows the git commands used by the server, so users can see exactly what is going on in their repositories.


What it is not
--------------
* Any kind of replacement for those users who are looking for a big software package to things like advanced access control or management with.
* A highly funded software to tend to everyones needs.

If you are interested in looking into how to set it up and use it, or you just want to see a bit more on how gitolite can be used, more documentation is available in the [doc](doc) directory.

How can it be used?
-------------------
The main goal for this project is for users to manage their ssh keys in a web interface. This feature requires access to the gitolite admin repository. That said, Snotra can be used for any of the features mentioned in the supported setups. If installed locally without gitolite admin, it can still be used as a web based repository brower. The features are very limited compared to different desktop programs available.


Supported setups
----------------

What needs to be installed on the same server as Snotra for the feature to be available.

| Feature | Gitolite | Gitolite admin | User space |
|---------|:--------:|:--------------:|:----------:|
| Manage user ssh keys ||x||
| Manage user repository access ||x||
| Manage user repositories |||x|
| Add repositories ||x||
| Rename and delete repositories |x|x||
| Browse any repository ||x||
| Browse user repositories |||x|

* Gitolite = The gitolite main install.
* Gitolite admin = The gitolite admin repository checkout.
* User space = A local user space on the server.

Sign in options
---------------

* pam auth: Local users can login with their local login credentials. They will be bound to their home directories, and if permissions is set to support it, they can mange their checkouts as well.
* <del>LDAP auth.</del> Not yet supported.
* <del>MariaDB/MySQL auth.</del> Not yet supported.
* <del>OpenID auth.</del> Not yet supported.

Attributions
------------
* Logo: [Git logo](https://git-scm.com/downloads/logos).
* Fonts: [Google fonts](https://fonts.google.com/).
* Font awesome: [Font awesome](http://fontawesome.io/).
* jQuery: [jQuery](http://jquery.com/).
* php framework: [Gimle](https://github.com/Gimle/phpFramework).
