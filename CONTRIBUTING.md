# Contributing to Open eShop

Looking to contribute something to Open eShop? **Here's how you can help.**

## Environment
Recommended PHP 5.5 , MySQL 5.5, Apache 2.2, Linux

For development we recommend you to create a vhost called eshop.lo this will enable debug/profiler tools, disable cache and disable minify

```
Host file:
127.0.0.1   eshop.lo
```

```
Vhost apache:
<VirtualHost *:80>
ServerName eshop.lo
DocumentRoot /var/www/eshop/
</VirtualHost>
```

## Git usage
Example to clone project on local:

```
git clone git@github.com:open-classifieds/open-eshop.git eshop
cd eshop
git submodule init
git submodule update
```

This will clone the open-eshop project + submodule at oc/common https://github.com/open-classifieds/common


## Reporting issues

https://github.com/open-classifieds/open-eshop/issues

We only accept issues that are bug reports or feature requests. Bugs must be isolated and reproducible problems that we can fix within the Open eShop core. Please read the following guidelines before opening any issue.

1. **Search for existing issues.** We get a lot of duplicate issues, and you'd help us out a lot by first checking if someone else has reported the same issue. Moreover, the issue may have already been resolved with a fix available.
2. **Create an isolated and reproducible test case.** Be sure the problem exists in Open eShop code.
3. **Include a live example.** Make use of screenshots if needed.
4. **Share as much information as possible.** Include operating system and version, browser and version, version of OC, customized or vanilla build, etc. where appropriate. Also include steps to reproduce the bug.



## Key branches

- master is the development branch.
- We create tags per release from master branch.
 -We have many ther branches not in use since we changed the way we use the git flow.


## Pull requests

- Try to submit pull requests against master branch for easier merging
- Try not to pollute your pull request with unintended changes--keep them simple and small
- Try to share which browsers your code has been tested in before submitting a pull request



## Coding standards

- PHP http://kohanaframework.org/3.3/guide/kohana/conventions
- SQL https://github.com/open-classifieds/openclassifieds2/wiki/SQL-Coding-Standard

## License

By contributing your code, you agree to license your contribution under the terms of the GPLv3: Read [LICENSE](LICENSE)