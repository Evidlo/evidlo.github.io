---
title: Git Notes
date: 2020
---

## Modifying a pull request

``` bash
git remote add [pr_username] git@github.com:[pr_username]/[reponame]
git fetch [pr_username]
git checkout -b [new_branch_name] [pr_username]/master
# make your commits
git push [pr_username] HEAD:master
```

## Pushing to multiple remotes

```
git remote add origin https://user@gitlab.com/myrepo 
git remote set-url --push --add origin https://user@github.com/myrepo 
git remote set-url --push --add origin https://user@gitlab.com/myrepo

```

## Deleting Remote Branch

```
# delete a local branch
git branch -d foobar
# delete a remote branch
git push origin --delete foobar

```
