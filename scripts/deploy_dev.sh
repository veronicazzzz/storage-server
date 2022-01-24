#!/bin/bash

ssh root@134.209.97.150 ssh bash -c "'
cd ~/
cd storage-server-dev
./build.sh
'"
