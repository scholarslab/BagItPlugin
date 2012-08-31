#! /usr/bin/env bash

if [ -z $PLUGIN_DIR ]; then
  PLUGIN_DIR=`pwd`
fi

if [ -z $OMEKA_DIR ]; then
  export OMEKA_DIR=`pwd`/omeka
  echo "omeka_dir set"
  echo "Plugins `ls $OMEKA_DIR/plugins`"
fi

cd tests/ && phpunit --configuration phpunit_travis.xml --coverage-text
