# php-sample

## POST INSTALL

### Install docker

```bash Ubuntu debian linuxMint
sudo sh scripts/install-docker-ubuntu.sh
```

```bash Alpine
sh scripts/install-docker-alpine.sh
```

---

## INSTALL

__Use Makefile:__

```bash
sudo make production
```

or

```bash
sudo docker-compose up -d
```

And then point your browser to `http://localhost`.

You can then scale the server to multiple instances:

```bash
sudo docker-compose up -d --scale=server=3
```

---

## FOR DEV LINUX OR MACOS ONLY


### INSTALL

**Install node environment:**: 
> make nvm
__Close your terminal et re-open this__
> nvm install v14.16.1
> nvm use v14.16.1
> make install

**Docker start:**
> sudo make dev
> docker ps -a

__Get container name prefix:__  yoonest-sample_*

ex:
> docker exec -it yoonest-sample_server_1 bash

Enjoy use composer in bash


### RELEASE

> make svn

### MIGRATE DATABASE


---

## DATABASE

### BACKUP

```bash
sudo scripts/mariadb_backup.sh
```

### RESTORE

```bash
sudo scripts/mariadb_restore.sh
```

---
