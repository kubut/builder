# -*- mode: ruby -*-
# vi: set ft=ruby :
require 'yaml'

settings = YAML.load_file 'vagrant.conf/vagrant.yml'

# Vagrantfile API/syntax version. Don't touch unless you know what you're doing!
VAGRANTFILE_API_VERSION = "2"

Vagrant.configure(VAGRANTFILE_API_VERSION) do |config|
  config.vm.box = settings['server']['box_name']
  config.vm.provision :shell, path: "vagrant.conf/bootstrap.sh"
  config.vm.network "private_network", ip: settings['server']['ip']
  config.vm.network :forwarded_port, guest: 22, host: settings['server']['ssh_port'], id: "ssh", auto_correct: true
  config.ssh.port = settings['server']['ssh_port']
  config.ssh.username = "vagrant"
  config.ssh.password = "vagrant"
  config.vm.synced_folder ".", "/vagrant", type: settings['server']['share_type']


  config.vm.provider "virtualbox" do |v|
    v.name = settings['server']['name']
    v.memory = settings['server']['memory']
    v.cpus = settings['server']['cpu']
  end

  config.vm.provider "vmware_fusion" do |v, override|
    v.vmx["memsize"] = settings['server']['memory']
    v.vmx["numvcpus"] = settings['server']['cpu']
  end
end
