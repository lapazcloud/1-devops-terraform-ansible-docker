# Docker Nights 1: DevOps, conceptos y aplicaciones


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


