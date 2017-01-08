#!/usr/bin/env bash

ORIGIN=$1
{
    git ls-remote --heads $ORIGIN  | sed 's?.*refs/heads/??'
} || {
   echo "Nie mogłem się połączyć z repozytorium!"
}