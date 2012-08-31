#! /usr/bin/env bash

if [ -z $OMEKA_BRANCH ]; then
  OMEKA_BRANCH=stable-1.5
fi

PLUGIN_DIR=`pwd`
OMEKA_DIR=`pwd`/omeka

function phpDependecies {
  pear install pear/PEAR
  pear install pear/Archive_Tar
  phpenv rehash
}

function createdb {
  mysql -e "create database IF NOT EXISTS omeka_test;" -uroot;
}

function cloneOmeka {
  git clone https://github.com/omeka/Omeka.git $OMEKA_DIR
}

function checkOutBranch {
  cd $OMEKA_DIR && git checkout $OMEKA_BRANCH
}


function linkPlugins {
  #cd $OMEKA_DIR/plugins && ln -s $PLUGIN_DIR/BagIt
  cd $OMEKA_DIR/plugins && ln -s $PLUGIN_DIR
}

function moveConfigFiles {
  mv $OMEKA_DIR/application/config/config.ini.changeme $OMEKA_DIR/application/config/config.ini
  mv $OMEKA_DIR/application/tests/config.ini.changeme $OMEKA_DIR/application/tests/config.ini
}


function setUpOmeka {
  # set up testing config
  sed -i 's/db.host = ""/db.host = "localhost"/' $OMEKA_DIR/application/<D-d>tests/config.ini
  sed -i 's/db.username = ""/db.username = "root"/' $OMEKA_DIR/application/tests/config.ini
  sed -i 's/db.dbname = ""/db.dbname = "omeka_test"/' $OMEKA_DIR/application/tests/config.ini 
  sed -i 's/email.to = ""/email.to = "test@example.com"/' $OMEKA_DIR/application/tests/config.ini
  sed -i 's/email.administator = ""/email.administrator = "admin@example.com"/' $OMEKA_DIR/application/tests/config.ini
  sed -i 's/paths.maildir = ""/paths.maildir = "\/tmp"/' $OMEKA_DIR/application/tests/config.ini
  sed -i 's/paths.imagemagick = ""/paths.imagemagick = "\/usr\/bin\/"/' $OMEKA_DIR/application/tests/config.ini

  sed -i 's/256M/512M/' $OMEKA_DIR/application/tests/bootstrap.php

}

function main {
  phpDependecies
  createdb
  cloneOmeka
  checkOutBranch
  linkPlugins
  moveConfigFiles
  setUpOmeka
}

main
