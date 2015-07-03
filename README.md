WordPress as a Platform Application
================

This repo contains the build scripts necessary to deploy Highly Available WordPress Applications using
DeSMan Object Storage for uploads and git version control for all code provided by the project team.
The WordPress core is _not_ tracked as part of this repository. A collection of plugins have been included
which will make the installation more secure, and custom configuration is included to link the application
that is deployed in Openshift to the Infrastructure we've included in the environment for object storage
and MySQL databases.

Updating
-------

To update WordPress, Simply replace the version number in `VERSION` to the exact version number of the 
preferred release. You can also downgrade in a similar fashion. Any plugins or themes you add should be 
added to the appropriate `plugins` or `themes` directories in the root of the repository.

If a newer version of a plugin or theme is released, it's recommended that you download and install it here
rather than through the wordpress admin panel due to the highly available nature of DeSMan Managed websites.

Plugins
------------

There are several plugins included with this project to help secure the frontend and provide a means for
storing uploaded media (i.e. `wp-content/uploads`)

* WordFence
* DeSMan Connector



Extra information
-----------

We've added the `.inetu` directory as a place to keep local copies of files on the sftp server without including
them in the repository. Any MySQL exports and storage downloads __SHOULD__ be kept here or in another directory
that has been added to `.gitignore` to avoid degrading repository performance.