---
template: project.j2
title: Linux Notes
author: Evan Widloski
---

[TOC]

## systemd
    
**/etc/systemd/system** - default location for system units
**~/.config/systemd/user** - default location for user units

### Commands

``` .bash
# reload units and timers
systemctl [--user] daemon-reload
# show all units (including disabled)
systemctl [--user] list-units -a
# view logs for unit
# also accepts:
#   -f               | tail the log
#   --user-unit foo  | target user unit instead of system
#   --boot=0         | show logs from current boot (-1 for previous, etc)
journalctl --unit foo
```

### Examples

A simple service unit

**foobar.service**


```
[Unit]
Description=example service

[Service]
WorkingDirectory=/path/to/dir
Environment="FOOBAR=foo"
ExecStart=foobar.sh

[Install]
WantedBy=multi-user.target
```

**foobar.timer** A simple timer unit

``` {.text}
[Unit]
Description=example timer

[Timer]
# run every 15 minutes (aligns to the hour)
OnCalendar=*:0/15
# run timer immediately if script is enabled and is past due
Persistent=true

[Install]
WantedBy=timers.target
```

More examples [here](http://github.com/evidlo/examples/tree/master/systemd).

## Basic Arch Install

``` {.bash}
dhcpcd

timedatectl set-ntp true


fdisk /dev/sda 

# Create 300MB boot, 2GB swap, and leave the rest for root

mkswp /dev/sda2

mkfs.ext4 /dev/sda3

mount /dev/sda3 /mnt

swapon /dev/sda2

# edit /etc/pacman.d/mirrorlist to change mirror order **

pacstrap /mnt base

genfstab -p /mnt >> /mnt/etc/fstab

arch-chroot /mnt

ln -s /usr/share/zoneinfo/America/Indianapolis /etc/localtime

hwclock --systohc --utc

# uncomment en_US locales in /etc/locale.gen **

locale-gen

# enter hostname in /etc/hostname **

mkinitcpio -p linux

passwd

pacman -S grub

grub-install --target=i386-pc --recheck --debug /dev/sda

grub-mkconfig -o /boot/grub/grub.cfg

exit

reboot

pacman -S vim htop git openssh wget

pacman -S xorg-server xf86-video-ati xorg-xinit
```

## Generic Linux Install

``` {.bash}
# list all available block devices
lsblk
# Copy bootable image to flash drive (status=progress requires dd >= 8.24)
# be careful not to mess up the of= argument, or you might overwrite your HD
sudo dd if=foobar.iso of=/dev/sdX status=progress && sync
```

## udev

``` {.bash}
# show device connect/disconnects and fired rules
udevadm monitor
# list all attributes for a particular device (and parents)
udevadm info --attribute-walk --path=/devices/...
```

``` {.bash}
# simple vendorid, productid example
# note SYMLINK is only for block devices (I think, look it up yourself)
SUBSYSTEMS=="usb", ATTRS{idProduct}=="3300", ATTRS{idVendor}=="1e10", MODE="0666", SYMLINK+="foobar"
```

## iptables

### Commands

``` {.bash}
# list all tables
iptables -L -n -v
# (fedora) save iptables rules and remember to disable firewalld
iptables-save > /etc/sysconfig/iptables
```

### Examples

``` {.bash}
# allow ssh
# must allow incoming connection and response

# append rule to input (-A INPUT) on input interface enp6s0f0 (-i enp6s0f0) 
# with destination port 22 (--dport 22).  use 'state' module (-m state)
# and allow new and established connections (--state NEW,ESTABLISHED)
# jump to target ACCEPT (-j ACCEPT)
iptables -A INPUT -i enp6s0f0 -p tcp --dport 22 -m state --state NEW,ESTABLISHED -j ACCEPT

# append rule to output (-A OUTPUT) on output interface enp6s0f0 (-o enp6s0f0) 
# with source port 22 (--sport 22).  use 'state' module (-m state)
# and allow established connections (--state ESTABLISHED)
# jump to target ACCEPT (-j ACCEPT)
iptables -A OUTPUT -o enp6s0f0 -p tcp --sport 22 -m state --state ESTABLISHED -j ACCEPT
```

``` {.bash}
# filter table: flush all chains, and delete all user added chains
iptables -F
iptables -X
# nat table: flush all chains, and delete all user added chains
iptables -t nat -F
iptables -t nat -X
```

## iperf

Do network bandwidth testing

Test speed to device A for 10 s

    iperf -c [other_ip] -p [other_port] -f M
    
Wait for connection from device B

    iperf -s -p 1234

## LVM

### Adding

``` {.bash}
# create new lv `foo` in group `foo_group`
lvcreate -L 10G foo_group -n foo
```

## Deleting

``` {.bash}
lvremove /dev/[vgname]/[lvname]
```

### BTRFS

``` {.bash}
# add all devices to filesystem
btrfs device add /dev/sdb2 /dev/sdc2 /dev/sdd2 /
# convert system to raid10
btrfs balance start -dconvert=raid10 -mconvert=raid10 /
# check balance progress
btrfs balance status /
# get rid of single chunks to get another shot at degraded,rw mount
btrfs balance start -dconvert=raid10,soft -mconvert=raid10,soft  /mount
```

## LXC

<https://www.flockport.com/enable-lxc-networking-in-debian-jessie-fedora-and-others/>

### Config examples

**/etc/lxc/lxc.conf** - set path for containers to be stored (default
/var/lib/lxc)

``` {.bash}
lxc.lxcpath = "/lxc"
```

**/etc/lxc/default.conf** - config options for all newly created
containers to inherit

``` {.bash}
lxc.network.type = veth
lxc.network.link = lxcbr0
lxc.network.flags = up
lxc.network.hwaddr = 00:16:3e:xx:xx:xx
lxc.start.auto = 1

# address
#lxc.network.ipv4 = 192.168.1.1xx
lxc.network.ipv4.gateway = 192.168.1.1

# memory
lxc.cgroup.memory.limit_in_bytes = 512M

# memory + swap
lxc.cgroup.memory.memsw.limit_in_bytes = 1G
```

**/etc/default/lxc-net** - it may be necessary to add
/etc/lxc/dnsasq.conf to the apparmor profile
(/etc/apparmor.d/*dnsmasq*) with read privileges

``` {.bash}
USE_LXC_BRIDGE="true"
LXC_BRIDGE="lxcbr0"
LXC_ADDR="192.168.1.1"
LXC_NETMASK="255.255.255.0"
LXC_NETWORK="192.168.1.0/24"
LXC_DHCP_RANGE="192.168.1.100,192.168.1.199"
LXC_DHCP_MAX="100"
LXC_DHCP_CONFILE="/etc/lxc/dnsmasq.conf"
LXC_DOMAIN=""
```

**/etc/lxc/dnsmasq.conf**

``` {.bash}
dhcp-host=evan,192.168.1.102
```

**iptables config**

``` {.bash}
#!/bin/bash
## Evan Widloski - 2016-11-11
# Diode iptables rules

# filter table: flush all chains, and delete all user added chains
iptables -F
iptables -X
# nat table: flush all chains, and delete all user added chains
iptables -t nat -F
iptables -t nat -X

# set default policies to DROP packets
iptables -P INPUT DROP
iptables -P OUTPUT DROP
iptables -P FORWARD DROP

# allow inbound outbound traffic on host 
iptables -A OUTPUT -o enp6s0f0 -d 0.0.0.0/0 -j ACCEPT 
iptables -A INPUT -i enp6s0f0 -m state --state ESTABLISHED,RELATED -j ACCEPT

# set up chain for sshguard
iptables -N sshguard
iptables -A INPUT -p tcp --dport 22 -j sshguard

# allow ssh
iptables -A INPUT -i enp6s0f0 -p tcp --dport 22 -m state --state NEW,ESTABLISHED -j ACCEPT
iptables -A OUTPUT -o enp6s0f0 -p tcp --sport 22 -m state --state ESTABLISHED -j ACCEPT

# allow mosh
iptables -A INPUT -i enp6s0f0 -p udp --dport 60000:61000 -j ACCEPT
iptables -A OUTPUT -o enp6s0f0 -p udp --sport 60000:61000 -j ACCEPT

# allow connections to varnish service
#iptables -A INPUT -i enp6s0f0 -p tcp --dport 80 -m state --state NEW,ESTABLISHED -j ACCEPT
#iptables -A OUTPUT -o enp6s0f0 -p tcp --sport 80 -m state --state ESTABLISHED -j ACCEPT

# allow host to access LXC targets via network
iptables -A INPUT -m state --state ESTABLISHED,RELATED -j ACCEPT
iptables -A OUTPUT -s 192.168.1.0/24 -j ACCEPT

# allow outbound traffic for lxc containers
iptables -A FORWARD -i lxcbr0 -j ACCEPT
iptables -t nat -A POSTROUTING -s 192.168.1.0/24 -j MASQUERADE

# after incoming packets have been NAT'ed (see below), allow them to pass through
# the forward chain to their intended LXC target
iptables -A FORWARD -m state --state NEW,ESTABLISHED,RELATED -j ACCEPT

##------------ evan --------------
## ssh
iptables -t nat -A PREROUTING -p tcp --dport 20022 -j DNAT --to-destination 192.168.1.102:22
```

### Commands

``` {.bash}
# list container statuses and ip addresses (fancy mode)
lxc-ls -f
```

``` {.bash}
brctl show
brctl delbr virbr0
brctl addbr virbr0
ip link set virbr0 down
```

### New Container Setup

New LXC containers are very barebones and need a bit of setup to be
useful. Here is an overview of steps for various distros.

#### Debian

Setup PATH

``` {.bash}
# add /bin, /sbin to path
echo 'PATH=$PATH:/bin:/sbin'>>.bashrc
```

Install packages

``` {.bash}
# core commands
apt-get install apt-utils vim man tar less iputils-ping

# extra commands
apt-get install git zip autojump wget htop ncdu nload
```

#### Fedora

Install packages

``` {.bash}
# core commands
dnf install vim man

# core commands
dnf install git zip autojump wget htop ncdu nload
```

## Weechat

``` {.bash}
# enable notifications for any messages in buffer (works for Android client, too)
/buffer set highlight_regex .\ast{}.*
```

## MDADM

### Checking state and simulating failure

``` {.bash}
# check RAID state
cat /proc/mdstat  # look for failure, (F), after the drive name: sda1[0](F)

# simulate a failed drive
mdadm --manage --set-faulty /dev/md/pv00 /dev/sda1

# remove faulty state by removing and readding
mdadm --remove /dev/md/pv00 /dev/sda1
mdadm --add /dev/md/pv00 /dev/sda1
```

### Replacing a failed drive (sdc)

``` {.bash}
# set hard drive as failed
# mark as failed and remove
mdadm --manage /dev/md127 --fail /dev/sdc1
mdadm --manage /dev/md127 --remove /dev/sdc1

# write down serial number of failed drive
hdparm -i /dev/sdc1 | grep -i serial
shutdown -h now
# remove broken harddrive, insert the new hardddrive

# copy partition scheme from working harddrive to new harddrive
sfdisk -d /dev/sda | sfdisk /dev/sdc

# add new harddrive
mdadm --manage /dev/md127 --add /dev/sdc1

# verify that array is recovering
cat /proc/mdstat
```

### Notifying on harddrive failure (gmail)

**/etc/exim/exim.conf**

``` {.python}
# add this after `begin routers` in router config section
 send_via_gmail:
     driver = manualroute
     domains = ! +local_domains
     transport = gmail_smtp
     route_list = * gmail-smtp.l.google.com
# add this after `begin transports` in transports config section
 gmail_smtp:
     driver = smtp
     port = 587
     hosts_require_auth = gmail-smtp.l.google.com
     hosts_require_tls = gmail-smtp.l.google.com
# add this after `begin authenaticators` in authentication config section
 gmail_login:
     driver = plaintext
     public_name = LOGIN
     client_send = : sender_email@gmail.com : password_in_plaintext_here
```

**/etc/mdadm.conf**

``` {.text}
MAILADDR destination_email@example.com
AUTO +imsm +1.x -all
ARRAY /dev/md/pv00 level=raid5 num-devices=4 UUID=1327a02b:b19f6696:0e3f8ac7:9615591c
```

### Growing RAID size

This is useful if the RAID array needs to be grown by using up more free
space (no added harddrive)

``` {.bash}
umount /dev/sda
umount /dev/sdb
umount /dev/sdc
umount /dev/sdd

# grow RAID array to 500GB (this will take a while)
mdadm -G /dev/md127 -z 500G

# resize physical volume to fit new RAID partition size
pvresize /dev/md127
```

### Accessing via Live CD

If the array gets screwed up somehow, you can try mounting it on a
livecd.

``` {.bash}
apt install mdadm

# assemble array from block devices
mdadm --assemble --scan

# mount array (assuming lvm)
apt install lvm2

# see if lv's are intact
lvscan

# mount lv
mount /dev/[vgname]/[lvname] /mnt/foo
```

## Installing GRUB on a Live CD Mounted System

``` {.bash}
# mount root lv
mount /dev/[vgname]/root /mnt/root

# mount live CD directories inside mounted lv
for i in /dev /dev/pts /proc /sys /run; do sudo mount -B $i /mnt/root$i; done

# chroot into root lv
chroot /mnt/root

# install grub to each device in array
grub2-install /dev/sda
grub2-install /dev/sdb
grub2-install /dev/sdc
grub2-install /dev/sdd

# update grub config
grub2-mkconfig -o /boot/grub2/grub.cfg
```

## Mounting Images

``` {.bash}
# list the partitions on the image file
fdisk -l /tmp/sdcard.img 
```

    Disk /tmp/sdcard.img: 162 MiB, 169869824 bytes, 331777 sectors
    Units: sectors of 1 * 512 = 512 bytes
    Sector size (logical/physical): 512 bytes / 512 bytes
    I/O size (minimum/optimal): 512 bytes / 512 bytes
    Disklabel type: dos
    Disk identifier: 0x00000000

    Device           Boot Start    End Sectors  Size Id Type
    /tmp/sdcard.img1 *        1  65536   65536   32M  c W95 FAT32 (LBA)
    /tmp/sdcard.img2      65537 331776  266240  130M 83 Linux

``` {.bash}
# use the sector size and the partition start sector to calculate offset (512 * 65537)
sudo mount -o loop,offset=33554944 /tmp/sdcard.img /mnt/tmp
```

## Auto FS

Auto FS + SSHFS allows the system to mount ssh filesystems on access and
then automatically unmount after a certain timeout. The necessary tools
are **autofs** and **sshfs**.

**/etc/auto.master** or **/etc/auto.master.d/foobar.autofs** or
**/etc/autofs/auto.master**

``` {.bash}
# mounts all the entries listed in /etc/auto.sshfs in /mnt/ with the given options
# add the --verbose option here to debug mounting issues
# set --timeout to control when sshfs mount is automatically unmounted
/mnt /etc/auto.sshfs --timeout=180 --ghost
```

**/etc/auto.sshfs**

``` {.bash}
# make a mount to be used by auto.master
foobar -fstype=fuse,rw,IdentityFile=/home/evan/.ssh/foobar,port=22,allow_other :sshfs#foo@example.org:
```

AutoFS runs as root, so ensure that the host fingerprint has been added
to ***root*.ssh/known_hosts**. You can add this easily by attempting to
ssh login to foo@example.org from root.

``` {.bash}
su -
ssh foo@example.org
# enter yes
```

## Resizing LUKS encrypted LVM

``` {.bash}
# expand the block device with fdisk, if necessary

# resize physical volume
pvresize --setphysicalvolumesize 111.8G /dev/sdb2
# be careful about using `-l +100%FREE`.  this broke /home until I manually shrank fedora--vg-home by a few GB
lvextend -l 80G /dev/mapper/fedora--vg-home
resize2fs /dev/mapper/fedora--vg-home
```

## Fixing Nodejs

<https://bugzilla.redhat.com/show_bug.cgi?id=1125868>

## Rsync

``` {.bash}
# Sync permissions only. (useful if you forgot `-p` option in cp)
# Looks at filesize differences to determine if a copy is needed rather
# than timestamp (which gets reset when `-p` is left out of cp.
rsync --archive --size-only /src/foo /dest/bar
```

``` bash
# -a : archive, preserve permissions, creation dates, etc
# -v : verbose
# -h : human readable file sizes

rsync -avh src dest
```

## DNS Tunneling with iodine

Most of this was taken from <http://dev.kryo.se/iodine/wiki/HowtoSetup>

### Domain Setup

On a domain you own (e.g. example.com), create an A record
server.example.com pointing to the ip of a server you own and an NS
record tunnel.example.com pointing to server.example.com.

To verify the setup is working, you can do:

``` {.bash}
# on the server
sudo nc -u -l -p 53

# on another device
dig +trace tunnel.example.com
# you should see some stuff printed out in the console on the server
```

### Server Setup

``` {.bash}
# set iptables rules
iptables -t nat -A POSTROUTING -o eth0 -j MASQUERADE
iptables -A FORWARD -i eth0 -o dns0 -m state --state RELATED,ESTABLISHED -j ACCEPT
iptables -A FORWARD -i dns0 -o eth0 -j ACCEPT

# enable ip forwarding on the server
#  unnecessary if you want to use `ssh -D 1234` for dynamic port forwarding 
#  on the client (as opposed to setting default routes)
echo 1 > /proc/sys/net/ipv4/ip_forward

# install iodine
dnf install iodine

# run iodine (as root in a screen session)
#  `password` is the password to use the tunnel
#  `192.168.99.1` is the ip of the server on the tunnel network
iodined -c -P password -f 192.168.99.1 tunnel.example.com

# note that if iodined is behind a NAT, you'll need to port forward UDP 53 and
#   use the `-p` option to tell iodined the server's external ip
# iodined -c -P password -f 192.168.99.1 tunnel.example.com -p [server.example.com ip]

```

### Client Setup

Alternatively, you can download a script that does this part from
<http://www.doeshosting.com/code/NStun.sh>.

``` {.bash}
# launch iodine client and wait for a 'Connection setup complete' message
sudo iodine -f tunnel.example.com

# either use SSH for dynamic forwarding (one application at a time)  or set up routes

# ssh
ssh -D 1234 tunnel.example.com
# set Firefox to use socks proxy localhost on port 1234

# set up routes
# get the current gateway ip
ip route
# get the tunnel server ip
host server.example.com
# add a route for iodine to communicate with the outside world
sudo ip route add [server.example.com IP] via [current gateway IP]
# delete the default route for traffic
sudo ip route delete default
# add a default route so that all traffic is tunneled
sudo ip route add default via 192.168.99.1
# also DNS may not work, so you might have to manually set it to something reasonable
sudo sh -c "echo nameserver 8.8.4.4 > /etc/resolv.conf"
```

## DNS

``` {.bash}
# lookup the hostname or domain name associated with ip address
# also works for local machines in the search domain
dig -x 1.2.3.4
```

## Booting

**block** - smallest addressable unit of storage

    Block size is defined in the hardware of a hard drive, but the OS can
    define a virtual block size which chains multiple blocks together.

There are three primary boot options involving UEFI and BIOS firmwares

-   UEFI-GPT
    -   required if dualbooting windows
-   BIOS-GPT
-   BIOS-MBR
    -   max addressable disk space is 2^32 * 512 = 2 TiB on a system
        with 512 byte blocks.

## GPT - GUID Partition Table

**protective mbr** - `a small partition at the beginning of the GPT disk
(where the MBR would normally be) that prevents older MBR tools from
damaging the GPT formatting`

This partition contains a fake partition record which spans the entirety
of the disk. MBR programs will see that there is a partition of unknown
type that spans the entire disk and will refuse to operate.

A GPT disk is formatted like so:

<table>
<tr><td>Protective MBR</td><td>512B</td></tr>
<tr><td>GPT Header</td><td>512B</td></tr>
<tr><td>GPT Partition Table</td><td>16KB</td></tr>
<tr><td><b>Partitions</b></td><td>XXX</td></tr>
<tr><td>Backup Partition Table</td><td>16KB</td></tr>
<tr><td>Backup Header</td><td>512B</td></tr>
</table>


So there should be 17KB and 16.5KB of free space at the beginning and
end of a GPT disk.

## Random facts

-   grub2-install invokes efibootmgr to install (aka register) entries
    in the nvram
-   these nvram entries point to .efi executables on the ESP
-   the harddrive UEFI menu entries are for legacy booting these devices
-   efi/boot/bootx64.efi is the .efi executable location for removable
    devices and doesn't require any nvram registration
-   

## SMART Status

    smartctl -a /dev/sdX
    smartctl -t short /dev/sdX

## Network interfaces and bridging

### Simulating network disconnect

``` {.bash}
# add network namespaces (for network isolation)
sudo ip netns add client-ns
sudo ip netns add server-ns

# create pairs of virtual interfaces
sudo ip link add client type veth peer name client-bridge
sudo ip link add server type veth peer name server-bridge
# add virtual interfaces to namespace
sudo ip link set client netns client-ns
sudo ip link set server netns server-ns
# give addresses to each virtual interface
sudo ip netns exec client-ns ip addr add 10.0.0.2/24 dev client
sudo ip netns exec server-ns ip addr add 10.0.0.1/24 dev server
# set virtual interfaces up
sudo ip netns exec client-ns ip link set client up
sudo ip netns exec server-ns ip link set server up
sudo ip link set client-bridge up
sudo ip link set server-bridge up

# add bridge interface
sudo brctl addbr bridge0
# link virtual interfaces to bridge
sudo brctl add if bridge0 client-bridge
sudo brctl add if bridge0 server-bridge
# set bridge up
sudo ip link set bridge0 up

# (in a new terminal) do stuff on the virtual interface
sudo ip netns exec client-ns ping 10.0.0.1

# set bridge down (simulate network offline)
sudo ip link set bridge0 down

# sometimes you might need to use the loopback interface
sudo ip netns exec client-ns ip link set lo up
sudo ip netns exec server-ns ip link set lo up
```

## Ubuntu VNC Server

``` {.bash}
# install x11vnc
sudo apt install x11vnc
# create a password
x11vnc -storepasswd
# start the server
```

To automatically run the server at login, add a startup script:

``` {.bash}
gnome-session-properties
```

Add a new item called "VNC" with the command field set to

``` {.bash}
x11-vnc -forever -loop -noxdamage -repeat -rfbport 5900 -shared -usepw
```

Remember to set the machine to never suspend in the system power
settings.

## Buildroot

### Useful options

-   toolchain > enable wchar support
-   bootloaders > grub2
-   toolchain > C library
-   filesystem images > iso image

## Fedora COPR

List copr repos

    dnf copr list

List copr repos

    dnf copr list

List copr repos

```
dnf copr list
```

Removing copr repos

    sudo dnf copr remove foo/bar

## Broken TTY

    sudo DISPLAY=:0 loadkeys us
    
## QEMU

    qemu-system-x86_64 -cdrom image.iso -boot d -net nic -net user -m 256 -rtc base=localtime -icount 1,sleep=off -rtc clock=vm

## Playing Sound

The `beep` command requires that the `pcspkr` module be loaded.  An alternative is the `play` command (provided by the `sox` package which ships with some systems).

    play -n -c1 synth sin 330 fade h 0.1 0.2 0.1 : synth sin 330 fade h 0.1 0.2 0.1
    
There are a variety of effects and waveforms that can be chained or played simultaneously.

## Connecting to Multiple WiFi Networks Simultaneously

Add a new dummy interface called `wlan0` that is tied to the real interface `wlp3s0`

    sudo iw dev wlp3s0 interface add wlan0 type station
    
Then use NetworkManager as normal to connect to another network with this new interface.

## NMCLI

Connect to a specific BSSID

    nmcli dev wifi connect "XX:XX:XX:XX:XX:XX"
    
List access points

    nmcli dev wifi list
    
## Flatpak Filesystem Access

Give all flatpak programs access to the filesystem by default

    flatpak --user override --filesystem=host

## Syncthing CLI

    # get current device ID
    syncthing cli show system | grep myID
    # add another device
    syncthing cli config devices add --name OTHER_MACHINE --device-id OTHER_ID
    # share a folder
    syncthing cli config folders FOLDER_ID devices add --device-id OTHER_ID
    
## NMAP

Produce prettier NMAP output with custom XSL.  Append `--stylesheet [URL] -oX - | xsltproc -` to the end of the nmap command.

    # bad styling
    sudo nmap -sS 192.168.1.100-200 -p 1234 
    # cleaner styling
    sudo nmap -sS 192.168.1.100-200 -p 1234 --stylesheet https://evan.widloski.com/notes/linux/nmap.xsl -oX - | xsltproc -

## Annoying People with MPV

```
mpv --vo=tct --profile=low-latency --untimed /dev/video0 --vo-tct-width=40 | ssh root@copernicus.ece.illinois.edu -i ~/.ssh/id_rsa.pub 'cat > /dev/pts/15'
```
