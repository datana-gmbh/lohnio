#!/bin/bash

! (find . -type f -name "*Test.php" | \
    xargs egrep '@group\s+temp')

if [ $? -eq 1 ]
then
  echo "Please remove \"@group\" annotation from tests."
  exit 1
fi
