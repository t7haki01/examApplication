# Estimated(?) version of tools used
1. Symfony used 4.0 - 4.1, 
2. VirtualBox 6.0.14 (released October 15 2019)
3. Vagrant 2.2.6 (October 14, 2019)
- developed in 11/12.2018

# Original guide to deploy
1. Make sure that vagrant and virtual box (or Another virtual machine but required to be specified in .vagrant file)
2. Install the vagrant plugin especially, if OS is Windows(*Recommend to run in administrator)
- vagrant plugin install vagrant-vbguest
- vagrant plugin install vagrant-winnfsd
3. vagrant up
4. vagrant ssh
5. cd /vagrant
6. composer create-project symfony/skeleton symfonyApp
- http://localhost:4567/