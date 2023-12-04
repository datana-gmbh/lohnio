#!/bin/bash

# ####
#
# USAGE example
#   cd tests/
#   ./check-abstractions-in-testcases.sh Integration App\Tests\Integration\IntegrationTestCase -p FunctionalTestCase -p WebTestCase
#
# ####

# e.g.: Integration
TYPE=$1
shift

# e.g.: App\Tests\Integration\IntegrationTestCase
FAVORISE=$1
shift

print_help() {
  echo "USAGE: ./check-abstractions-in-testcases.sh Integration App\Tests\Integration\IntegrationTestCase -p FunctionalTestCase -p WebTestCase"
}

# check usage
if [ -z "$TYPE" ]; then
  print_help
  exit 1
fi

PENALISE=""
while getopts ":p:" opt; do
  case $opt in
    p)
      # concat every -p
      PENALISE="${PENALISE}|${OPTARG}"
      ;;
    \?)
      echo "Invalid option: -$OPTARG" >&2
      exit 1
      ;;
    :)
      echo "Option -$OPTARG requires an argument." >&2
      exit 1
      ;;
  esac
done

if [ -z "$PENALISE" ]; then
  print_help
  exit 1
fi

# strip first "|"
PENALISE=${PENALISE:1}

FILES=$(
  find "./${TYPE}" -type f -name "*.php" | \
    xargs grep --line-number -E "\s+(${PENALISE})(\s+|$)"
)

if [ -z "$FILES" ]; then
  exit 0
fi

while IFS= read -r FILE; do
    DATA=(${FILE//:/ })
    NAME="${PWD}${DATA[0]:1}"
    LINE=${DATA[1]}

    echo "::error file=${NAME},line=${LINE},col=0::Do not extends this class, please use \"${FAVORISE}\" instead."
done <<< $FILES

exit 1
