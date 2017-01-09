# Contributing to Yclas Common

Looking to contribute something to Yclas? **Here's how you can help.**

## Environment
Recommended PHP 7.0 , MySQL 5.5, Apache 2.2, Linux.


## Git usage
Go to https://github.com/open-classifieds for each repo in the top right theres a button that says Fork. Click there to clone each repo, don't forget to clone common. That will copy the repos to your github user, ex: https://github.com/neo22s?tab=repositories

Clone your project in local
```
git clone git@github.com:neo22s/common.git common
cd common
```

Please check guide for each project.


## Reporting issues

https://github.com/yclas/common/issues

We only accept issues that are bug reports or feature requests. Bugs must be isolated and reproducible problems that we can fix within the Yclas core. Please read the following guidelines before opening any issue.

1. **Search for existing issues.** We get a lot of duplicate issues, and you'd help us out a lot by first checking if someone else has reported the same issue. Moreover, the issue may have already been resolved with a fix available.
2. **Create an isolated and reproducible test case.** Be sure the problem exists in Yclas code.
3. **Include a live example.** Make use of screenshots if needed.
4. **Share as much information as possible.** Include operating system and version, browser and version, version of OC, customized or vanilla build, etc. where appropriate. Also include steps to reproduce the bug.



## Key branches

- master is the development branch.
- We create tags per release from master branch.
 -We have many other branches not in use anymore since we changed the way we use the git flow.


## Pull requests

- Try to submit pull requests against master branch for easier merging
- Try not to pollute your pull request with unintended changes--keep them simple and small
- Try to share which browsers your code has been tested in before submitting a pull request



## Coding standards

- PHP http://kohanaframework.org/3.3/guide/kohana/conventions
- SQL https://github.com/yclas/yclas/wiki/SQL-Coding-Standard

## License

By contributing your code, you agree to license your contribution under the terms of the GPLv3, read [LICENSE](LICENSE)
