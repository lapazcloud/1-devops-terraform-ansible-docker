// Need to create a DigitalOcean account, then the API key
variable "digitalocean_token" {}
// This in case your domain is under Cloudflare
variable "cloudflare_email" {}
variable "cloudflare_token" {}
variable "cloudflare_domain" {}

// Our cloud provider :)
provider "digitalocean" {
	token = "${var.digitalocean_token}"
}

// Cloudflare domain records manager
provider "cloudflare" {
    email = "${var.cloudflare_email}"
    token = "${var.cloudflare_token}"
}

// Generate a SSH key with "ssh-keygen -t rsa -b 4096"
// name it dockernights1
resource "digitalocean_ssh_key" "dockernights1" {
	name = "Docker Nights 1 Key"
	public_key = "${file("../dockernights1.pub")}"
}

// Let's create a server
resource "digitalocean_droplet" "web" {
	image = "ubuntu-14-04-x64"
	name = "web"
	region = "nyc2"
	size = "512mb"
	ssh_keys = ["${digitalocean_ssh_key.dockernights1.id}"]
}

// Cloudflare Domain registration
resource "cloudflare_record" "web" {
	domain = "${var.cloudflare_domain}"
	name = "@"
	value = "${digitalocean_droplet.web.ipv4_address}"
	type = "A"
}
