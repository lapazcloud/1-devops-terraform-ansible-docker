# Docker Nights 1: DevOps, conceptos y aplicaciones

## Votación app y docker-compose

#### Requisitos

- [Docker 1.12](https://www.docker.com/) o superior
- [docker-compose](https://github.com/docker/compose/releases) 1.8 o superior

Aplicación escrita en PHP y Redis para votar por el Bolívar y The Strongest.

``` shell
$ cd votacion
$ docker-compose up -d
```
La aplicación se compilará y las imágenes se descargarán del Docker Hub. Visita la aplicación en [http://localhost/](http://localhost/).

Una vez que la aplicación este lista para ponerse en producción, la guardamos en Docker Hub:

``` shell
$ docker-compose push
```

## Terraform

#### Requisitos:

* [Terraform](https://www.terraform.io) 0.8.x o superior

En la raíz del proyecto genera una llave SSH, nombrándola `dockernights1`:

``` shell
$ ssh-keygen -t rsa -b 4096 -C "dockernights1"
Generating public/private rsa key pair.
Enter file in which to save the key (/path/.ssh/id_rsa): dockernights1
```

Ingresa a la carpeta `terraform`, ejecutando `plan` y llenando las variables con los keys y tu email:

``` shell
$ cd terraform
$ terraform plan -out dockernights1
var.cloudflare_domain
  Enter a value:
...
```
Ejecuta la creación de la instancia:

``` shell
$ terraform apply dockernights1
```

Terraform se encargará de subir la llave SSH, crear el servidor y añadir el registro con la IP al dominio `web.tusitio.com`.

## Ansible

#### Requisitos:

* [Ansible](https://www.ansible.com) 2.2.x o superior

Toma nota de la ip del servidor que se generó con `terraform`:

``` shell
$ cd terraform
$ terraform show
# Busca el parámetro ipv4_address
...
ipv4_address = 123.456.789.00
...

```

Edita el archivo `hosts` de la carpeta `ansible`:

``` shell
[la_ip_de_tu_servidor]
123.456.789.00
```
Ejecuta el playbook para instalar Docker y otras utilidades

``` shell
$ cd ansible
$ ansible-playbook -i hosts install-docker.yml
```

Ansible agregará la llave GPG y el repositorio oficial de Docker, instalando todas las dependencias.

## [Docker Swarm](https://docs.docker.com/engine/swarm/) Mode

#### Requisitos
* [Docker 1.12](https://www.docker.com/)

Ingresa a tu servidor via SSH y ambia a modo swarm usando tu ip pública:

``` shell
# en la carpeta raiz de este repositorio
$ ssh -i dockernights1 root@<ip_publica>
$ docker swarm init --advertise-addr <ip_publica>
```

Crea una red de tipo `overlay` en Docker

``` shell
$ docker network create -d overlay votacion
```

Crea el servicio `redis` adjuntándolo a la red votación:

``` shell
docker service create --name redis --replicas 1 --network votacion redis:3.0-alpine
```

El servicio `app` corre con la imagen de la aplicación web:

``` shell
docker service create --name app -p 80:3000 --network votacion dockerlapaz/votacion:1.0.0
```

Escala el servicio `app` a 5 replicas

``` shell
docker service scale app=5
```



