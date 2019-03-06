#!/bin/sh
PATH="/usr/local/bin:$PATH"
. $HOME/.nvm/nvm.sh
nvm use 6

#
# Run WordPress coding standards tasks.
#
PHPFILES=$(git diff --cached --name-only --diff-filter=AM | grep '.php' | tr -d '\n')
if [[ $PHPFILES ]]
then
	vendor/bin/phpcs --config-set installed_paths vendor/wp-coding-standards/wpcs
	vendor/bin/phpcs --standard=WordPress --report=source $PHPFILES
	vendor/bin/phpcs --standard=WordPress $PHPFILES
fi

#
# Run Sass lint task.
#
SASSFILES=`git diff --cached --name-only --diff-filter=AM | grep '.scss$'`
if [[ -z $SASSFILES ]]
then
	echo ""
else
	for file in ${SASSFILES}
	do
		cssFilesStr="$cssFilesStr$cssFilesSeparator$file"
		cssFilesSeparator=", "
	done

	lint=`node_modules/.bin/sass-lint "$cssFilesStr" -v -q`
	echo "$lint"

	if [[ "$lint" == *"problem"* ]]
		then
			hasLintErrors="true"
	fi
fi

#
# Report errors
#
if [ $PHPFILES ] && [[ $(vendor/bin/phpcs -q --standard=WordPress --report=source $PHPFILES) ]]
then
	echo "Fix all issues before commit."
	exit 1
fi

if [ $hasLintErrors ]; then
	echo "Fix all issues before commit."
	exit 1
fi

exit 0
