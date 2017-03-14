# Build Tools

[![Build Status](https://scrutinizer-ci.com/g/petrica/php-build-tools/badges/build.png?b=master)](https://scrutinizer-ci.com/g/petrica/php-build-tools/build-status/master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/petrica/php-build-tools/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/petrica/php-build-tools/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/petrica/php-build-tools/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/petrica/php-build-tools/?branch=master)

Build tools that should assist in CI/CD environments.

## Requirements

* PHP 5.5.9 or higher, 7.x
* Git client


## 1. Semantic Version Increment

More information about semantic versioning can be found here: http://semver.org/

### 1.1 Introduction 

This is a simple PHP command that can be run to increment the PATCH version part of a version string MAJOR.MINOR.PATCH
by a number of units:
* Reads current version from the ini file. Eg: VERSION=0.1.1 (file name is VERSION)
* Increments the PATCH part of the version and writes the new version to the ini file. Eg: VERSION=0.1.2
* Commits the changes to the repository
* Creates a new tag for the new version. Eg: 0.1.2

Because it uses an ini file, it is therefore easy to integrate the script withing a Jenkins building job and expose the
incremented version as an environment variable.

### 1.2 Run the command

```bash
php command.php version:increment [repository-path] --git-path=git
```

**Arguments**
* repository-path - relative or absolute path to git repository folder. For current folder use `.`

**Options**
* __--version-filename__ (Default: VERSION) - The name of the ini file where the version is stored. Have a look at
[this version file](VERSION).
* __--git-path__ (Default: /usr/bin/git) - Absolute path to git client executable. If you have git added to the environment
path you can use only the name of the executable.
* __--commit-message__ (Default: "Bumped version to %s") - Customize commit message where the `%s` is the token replaced
with the new incremented version.
* __--author-name__ - Change the default git author name for the committed message.
* __--author-email__ - Change the default git author email for the commited message.
* __--dry-run__ - Run the command without doing any actual changes to the version file

The file will read current git tags and if current hash has no tag associated
with it, it will increment patch part of the version file with 1.

If there are no tags defined, the script will assume that this is the first version
and it will not increment the patch part version.

E.g.

VERSION file has the following version: 0.1.0
git tag does not return anything. This is the first version, no action is taken.

VERSION file has the following version: 0.1.0
git tag returns tag 0.1.0 associated with a previous hash. The script
will increment the patch part of the version file with 1.

## Git actions after increment

It is mandatory to create a new tag for the current written version inside VERSION file
and push changes to git.

You will have to use git publisher.

## Run

Run without actually incrementing the file:

```bash
php command.php version:increment . --dry-run=true
```

```bash
php command.php version:increment [path_to_git_repository] --version-filename=VERSION
```

TODO:

Check if incremented version has a tag associated with it.

[Tremend Software Consulting](https://www.tremend.com/)

![Tremend Software Consulting](http://blog.tremend.com/wp-content/uploads/2017/01/LOGO_tremend_cmyk3-300x225.jpg)
