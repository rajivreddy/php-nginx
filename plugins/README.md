WordPress Plugins
==============

Any plugins in this directory will be installed whenever an openshift application gets deployed using this project template. We include 3 plugins by default, but you can install new plugins simply by adding them to this directory. When the application deploy script is run, this directory will be included in a clean copy of wordpress. This allows you to not have to concern yourself with maintaing changes in the wordpress core application code. If a plugin update is required, the best practice is to install by replacing the previous version in this directory and commiting your changes to the devel branch to verify that the update works as advertised.

DeSman Connector
-------

Utilizes the libraries included by the AWS/SDK to transfer media uploads to the s3 object storage used by applications deployed through DeSMan. 


WordFence
-------

An all around Security and Performance utility plugin that comes highly recommended by many who use it.