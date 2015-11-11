# Drupal.sk presentation website

## Contents
* database/ - Scrubbed database + scrubbing script
* designs/ - PSDs and static HTML&CSS
* docroot/ - Website directory.
* scripts/ - local scripts
* .gitignore - Gitignore.

## Requirements
* Install composer: https://getcomposer.org/doc/00-intro.md
* Install Drush version 8: http://docs.drush.org/en/master/install/

## Getting the site up and running.
* Get your copy of the code:
  * Fork this repository. ( https://help.github.com/articles/fork-a-repo/ )
  * Clone your repository.
  * `git clone git@github.com:[YOUR-NAME]/Drupal.sk.git Drupal.sk`
  * `cd Drupal.sk`
* Prepare your database and fill the credentials into your new local config.
  * `cp docroot/settings/default.settings.local.php docroot/settings/settings.local.php`
  * edit this config: `docroot/settings/settings.local.php`
* Install the site (it will use the Drupal.cz distribution).
  * Use install script located in `scripts/reinstall.sh`.
  
## Contributing
* We are using GitFlow(https://www.atlassian.com/git/tutorials/comparing-workflows/gitflow-workflow/) branching strategy
  * you need to create ```feature/NAME``` branch for each issue
  * after you finish work on issue, create pull request against ```develop``` branch 
* Commit your changes. ( http://chris.beams.io/posts/git-commit/ )
* Create pull request. https://help.github.com/articles/creating-a-pull-request/

