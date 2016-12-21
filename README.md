# Docker Nights 1: DevOps, conceptos y aplicaciones


## Terraform

#### Requisitos:

* [Terraform](https://www.terraform.io) 0.8.x o superior

En la raíz del proyecto genera una llave SSH, nombrándola `dockernights1`:

``` shell
ssh-keygen -t rsa -b 4096 -C "dockernights1"
Generating public/private rsa key pair.
Enter file in which to save the key (/path/.ssh/id_rsa): dockernights1
```

Ingresa a la carpeta `terraform`, ejecutando `plan` y llenando las variables con los keys y tu email:

``` shell
cd terraform
terraform plan -out dockernights1
var.cloudflare_domain
  Enter a value:
...
```
Ejecuta la creación de la instancia:

``` shell
terraform apply dockernights1
```

Terraform se encargará de subir la llave SSH, crear el servidor y añadir el registro con la IP al dominio `web.tusitio.com`.
