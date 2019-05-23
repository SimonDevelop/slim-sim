#!/bin/bash

if [ $DB == 'sqlite' ] || [ $DB == 'postgres' ]; then
  if [ $DB == 'postgres' ]; then
    psql -c 'create database sim_prod;' -U postgres
    cp .travis/.env.postgresql .env
  else
    cp .travis/.env.sqlite .env
  fi
else
  sh -c "mysql -e 'CREATE DATABASE sim_prod;'"
  cp .travis/.env.mysql .env
fi
