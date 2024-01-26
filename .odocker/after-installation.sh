#!/bin/bash

git checkout -- .gitignore

odocker mysql dusk < .odocker/setup.sql
odocker magento deploy:mode:set developer

odocker magento cache:flush
