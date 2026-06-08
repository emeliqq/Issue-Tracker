#!/bin/bash

echo "=== Terraform Deployment ==="

cd terraform
terraform apply -auto-approve

echo "=== Ansible Deployment ==="

cd ../ansible
ansible-playbook -i inventory.ini playbook.yml

echo "=== Deployment Completed ==="