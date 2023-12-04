#!/bin/bash

! (find . -type f -name "*.php" -o -name "*.html" -o -name "*.js" -o -name "*.twig" | \
    xargs egrep '(Emailadresse|EMailadresse)')

if [ $? -eq 1 ]
then
  echo "Please use \"E-Mail-Adresse\" instead."
  exit 1
fi
