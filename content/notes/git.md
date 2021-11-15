---
title: Git Notes
date: 2020
---

## Modifying a pull request

``` bash
git remote add [pr_username] git@github.com:[pr_username]/[reponame]
git fetch [pr_username]
# make your commits
git push
```

## Pushing to multiple remotes

git remote add origin https://user@gitlab.com/myrepo 
git remote set-url --push --add origin https://user@github.com/myrepo 
git remote set-url --push --add origin https://user@gitlab.com/myrepo
