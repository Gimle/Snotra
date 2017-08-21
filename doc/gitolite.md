Install gitolite
================

On Ubuntu 17.01
---------------

```bash
sudo apt install gitolite3
```
Leave key blank, it will be added in the setup later.

```bash
# Add a local user to for the gitolite to run under.
sudo adduser --system --group --shell /bin/bash --disabled-password git
```

Setup gitolite
==============

Import your public key, so you can manage gitolite with git and make sure snotra can read and manage (rename, change namespace, remove) the repositories.
```bash
# Copy your public key so the git user can use it to set you as administrator.
cp ~/.ssh/id_rsa.pub /tmp/$(whoami).pub
# Changge to be the git user, but keep your environment variabled, so ```${SUDO_USER}``` will contain your username.
sudo su git
# Setup gitolite and set you as the administrator (using the public key you copied before).
gitolite setup -pk /tmp/${SUDO_USER}.pub
# Open the rc file for gitolite for editing.
editor .gitolite.rc
```
Change the UMASK value from 0077 to ```0007``` save and exit. This makes all files created have read and write permission for the user group, which makes snotra able to interact with it. Tip: Using the ```editor``` command will start your default editor. Which editor that will be started can be configured many different ways, so that is not documented here.

```bash
cd repositories
# Change the group of all files to users, so apache can run with mpm_itk as this group to modify the files.
sudo chgrp -R users .
# Set a sticky bit on all the files, so any new file created will be owned by the same group.
find . -type d -print0| xargs -0 chmod g+s
# Find all directories and let the group have full permission.
find . -type d -print0| xargs -0 chmod 770
# Find all files and let the group have full permission.
find . -type f -print0| xargs -0 chmod 660
exit
# Remove the public key from the temp directory.
rm /tmp/$(whoami).pub
```

Administration repository
-------------------------

Clone the administration repository and enter it. You can treat this as a normal repository. Clone it to where you like, and call it / rename it what you like. Just make sure the config.ini file of snorta will be set to this path later on.
```bash
git clone git@server:gitolite-admin.git
cd gitolite-admin
```

To make snotra are able to manage gitolite, the files must writable for apache. More details about this can be found in the web server setup documentation. Make sure you change the group ownership here to the same as for the mpm_itk_module configuration of apache.
```bash
# Recursivly change all files and directories to give full access to he users group.
sudo chgrp -R users .
```

Set the stickybit on all the directories to preserve the group.
```bash
# Find all directories and set the sticky bit on them to make sure new additions also will be available for the users group.
find . -type d -print0| xargs -0 chmod g+s
```

Enter the keydir and organize the keys pr user and host.
```bash
cd keydir
# Create a directory structure for your user/host so keys are easier to manage.
mkdir -p $(whoami)/${HOSTNAME}
# Move your public key into the newly created folder.
mv $(whoami).pub $(whoami)/${HOSTNAME}/.
# Add the change to gitolite.
git add .
git commit -m "Setup: User key structure."
git push
```


Manage repositories
===================

If you want to manage your repositories from the terminal, this is fully possible.

Create a new repository
-----------------------

gitolite will automatically create new repositories.

Move a repository
-----------------
Rename the reposiry on the server, then commit new config file.

Delete a repository
-------------------
Commit new config file, then delete the reposiry on the server.


Uninstall
=========

If you ever want to uninstall gitolite, or want to redo all the installation steps, that is easy also:

```bash
sudo apt purge gitolite3
sudo userdel -r git
```
