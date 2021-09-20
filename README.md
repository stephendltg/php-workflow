# php-workflow

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
make production
```

or

```bash
sudo docker-compose up
```

And then point your browser to `http://localhost:3000`.

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

**Docker list container:**
> make ps

**Dockers containers logs:**
> make logs
> make logs server

**Docker container bash:**
> make bash server

Enjoy use composer in bash


### RELEASE

> make svn

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
