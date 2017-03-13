# Build Tools

Build tools that should assist in building and integration code.

## Version Increment

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
