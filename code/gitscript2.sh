#!/bin/bash

path=$1
# repo_name=$2

cd $path
# git pull <远程主机名> <远程分支名>:<本地分支名>
"C:/Program Files/Git/bin/git.exe" pull http://ssm_hos_code:ssm_hos_code123@47.93.186.50:8081/ssm/code.git master
