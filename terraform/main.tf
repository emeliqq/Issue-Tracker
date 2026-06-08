terraform {
  required_providers {
    azurerm = {
      source  = "hashicorp/azurerm"
      version = "~>4.0"
    }
  }
}

provider "azurerm" {
  features {}
  subscription_id = "ba247842-5fa5-41dc-b574-24b22c10618d"
}

resource "azurerm_resource_group" "rg" {
  name     = "lab-apache-rg"
  location = "swedencentral"
}

resource "azurerm_public_ip" "public_ip" {
  name                = "lab-public-ip"
  location            = azurerm_resource_group.rg.location
  resource_group_name = azurerm_resource_group.rg.name
  allocation_method   = "Static"
}

resource "azurerm_virtual_network" "vnet" {
  name                = "lab-vnet"
  address_space       = ["10.0.0.0/16"]
  location            = azurerm_resource_group.rg.location
  resource_group_name = azurerm_resource_group.rg.name
}

resource "azurerm_subnet" "subnet" {
  name                 = "lab-subnet"
  resource_group_name  = azurerm_resource_group.rg.name
  virtual_network_name = azurerm_virtual_network.vnet.name
  address_prefixes     = ["10.0.1.0/24"]
}

resource "azurerm_network_security_group" "nsg" {
  name                = "lab-nsg"
  location            = azurerm_resource_group.rg.location
  resource_group_name = azurerm_resource_group.rg.name

  security_rule {
    name                       = "SSH"
    priority                   = 1001
    direction                  = "Inbound"
    access                     = "Allow"
    protocol                   = "Tcp"
    source_port_range          = "*"
    destination_port_range     = "22"
    source_address_prefix      = "*"
    destination_address_prefix = "*"
  }

  security_rule {
    name                       = "HTTP"
    priority                   = 1002
    direction                  = "Inbound"
    access                     = "Allow"
    protocol                   = "Tcp"
    source_port_range          = "*"
    destination_port_range     = "80"
    source_address_prefix      = "*"
    destination_address_prefix = "*"
  }
}

resource "azurerm_network_interface" "nic" {
  name                = "lab-nic"
  location            = azurerm_resource_group.rg.location
  resource_group_name = azurerm_resource_group.rg.name

  ip_configuration {
    name                          = "internal"
    subnet_id                     = azurerm_subnet.subnet.id
    private_ip_address_allocation = "Dynamic"
    public_ip_address_id          = azurerm_public_ip.public_ip.id
  }
}

resource "azurerm_network_interface_security_group_association" "assoc" {
  network_interface_id      = azurerm_network_interface.nic.id
  network_security_group_id = azurerm_network_security_group.nsg.id
}

resource "azurerm_linux_virtual_machine" "vm" {
  name                = "lab-apache-vm"
  resource_group_name = azurerm_resource_group.rg.name
  location            = azurerm_resource_group.rg.location
  size                = "Standard_B2ts_v2"

  admin_username = "emilia"

  network_interface_ids = [
    azurerm_network_interface.nic.id
  ]

admin_ssh_key {
  username = "emilia"

  public_key = "ssh-rsa AAAAB3NzaC1yc2EAAAADAQABAAABAQCd60r03M3hPIUBoFOm7VUs+hxg8H6iZDeK7YOcheV9xhTsIaAQq1o/QMDfnGE7Hxk8kk4R7VayhOwJ7/hYhhCpeEJhjbI6HcEZVH/1saUM/8slN6+vwU7gy0OOMotrAjeQzWRb6JHweO2EA88w67B/DgDN1hXXuMfhRwMFif7JHQgIu2/Vj7qj3Fb2OQhD7yMDkdbCt/ThCBAdNjVSZA9dy6T3O2jKFeyQhouu73HdyuzF3hHP1tnfhrrfGV5/qmDkFQq5wq/83HRBGh3wQbI/Tnx/npqHqzInXh8UrQvymDByHu2WiagfjXS5QDDwcMR+t6B1sToUvoQ+uYXXI043"
}

  os_disk {
    caching              = "ReadWrite"
    storage_account_type = "Standard_LRS"
  }

  source_image_reference {
    publisher = "Canonical"
    offer     = "0001-com-ubuntu-server-jammy"
    sku       = "22_04-lts-gen2"
    version   = "latest"
  }
}