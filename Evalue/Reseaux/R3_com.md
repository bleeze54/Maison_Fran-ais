# Guide de configuration réseau (ROUTEUR / INTERNE / EXTERNE)

---

## Création des machines virtuelles (vm-add -d)

Les machines sont créées depuis **SilverBlue** à l’aide de la commande `vm-add -d`.

### 🔹 ROUTEUR

```bash
vm-add -d ROUTEUR
```

---

### 🔹 INTERNE

```bash
vm-add -d INTERNE
```

---

### 🔹 EXTERNE

```bash
vm-add -d EXTERNE
```

---

## 1. Configuration du ROUTEUR

### 🔹 Installation des paquets

```bash
apt-get update
apt-get install isc-dhcp-server bind9 iptables
```

### 🔹 Configuration réseau et VLAN

```bash
ip link set eth0 up
ip link add link eth0 name eth0.23 type vlan id 23
ip addr add 10.0.23.254/24 dev eth0.23
ip link set eth0.23 up

ip addr add 192.168.23.254/24 dev eth1
ip link set eth1 up
```

---

## 2. Configuration de la machine EXTERNE

```bash
ip addr add 192.168.23.1/24 dev eth1
ip link set eth1 up
ip route add 10.0.23.0/24 via 192.168.23.254
```

---

## 3. Configuration de la machine INTERNE

### 🔹 Installation des services

```bash
apt-get update
apt-get install bind9 apache2
```

### 🔹 Configuration VLAN

```bash
ip link set eth0 up
ip link add link eth0 name eth0.23 type vlan id 23
ip link show eth0.23
```

> Récupérer l’adresse MAC après `link/ether` (exemple : `56:a7:8a:d9:4b:81`)

---

## 4. Serveur DHCP (ROUTEUR)

```bash
nano /etc/dhcp/dhcpd.conf
```

### 🔹 Fichier `/etc/dhcp/dhcpd.conf`

```conf
option domain-name "monprojet.com";
option domain-name-servers 10.0.23.254;

subnet 10.0.23.0 netmask 255.255.255.0 {
    range 10.0.23.50 10.0.23.100;
    option routers 10.0.23.254;

    host Interne {
        # Mettre l'addresse MAC de INTERNE après ethernet à la place de l'exemple
        hardware ethernet 56:a7:8a:d9:4b:81;
        fixed-address 10.0.23.23;
    }
}
```

```bash
nano /etc/default/isc-dhcp-server
```

### 🔹 Interface DHCP

Fichier `/etc/default/isc-dhcp-server` :

```conf
INTERFACESv4="eth0.23"
```

### 🔹 Redémarrage

```bash
systemctl restart isc-dhcp-server
systemctl status isc-dhcp-server
```

### 🔹 Récupération IP sur INTERNE

```bash
dhclient eth0.23
```

---

## 5. DNS – ROUTEUR (Bind9 master)

```bash
nano /etc/bind/named.conf.local
```

### 🔹 Fichier `/etc/bind/named.conf.local`

```conf
zone "monprojet.com" {
    type master;
    file "/etc/bind/db.monprojet.com";
};

zone "sous.monprojet.com" {
    type master;
    file "/etc/bind/db.sous.monprojet.com";
    allow-transfer { 10.0.23.23; };
};
```

```bash
nano /etc/bind/db.monprojet.com
```

### 🔹 Zone principale `/etc/bind/db.monprojet.com`

```dns
$TTL    604800
@ IN SOA routeur.monprojet.com. admin.monprojet.com. (
    2024010901
    604800
    86400
    2419200
    604800 )

@ IN NS routeur.monprojet.com.
@ IN NS interne.monprojet.com.

routeur  IN A 10.0.23.254
interne  IN A 10.0.23.23

www IN CNAME interne.monprojet.com.

sous IN NS routeur.monprojet.com.
```

### 🔹 Démarrage

```bash
systemctl restart bind9
systemctl status bind9
```

---

## 6. DNS – INTERNE (Slave + zone déléguée)

```bash
nano /etc/bind/named.conf.local
```

### 🔹 `/etc/bind/named.conf.local`

```conf
zone "monprojet.com" {
    type slave;
    file "db.monprojet.com";
    masters { 10.0.23.254; };
};

zone "sous.monprojet.com" {
    type master;
    file "/etc/bind/db.sous.monprojet.com";
};
```

```bash
nano /etc/bind/db.sous.monprojet.com
```

### 🔹 Zone déléguée `/etc/bind/db.sous.monprojet.com`

```dns
$TTL 604800
@ IN SOA interne.sous.monprojet.com. admin.monprojet.com. (
    2026011401
    604800
    86400
    2419200
    604800
)

@ IN NS interne.sous.monprojet.com.

test IN A 10.0.23.50
```

---

## 7. Résolution DNS (INTERNE)

Fichier `/etc/resolv.conf` :

```conf
domain monprojet.com
search monprojet.com
nameserver 10.0.23.254
```

### 🔹 Tests

```bash
host routeur.monprojet.com
host www.monprojet.com
```

---

## 8. NAT & Port Forwarding (ROUTEUR)

```bash
iptables -t nat -A POSTROUTING -o eth0.23 -j MASQUERADE
iptables -t nat -A PREROUTING -i eth1 -p tcp --dport 8080 -j DNAT --to-destination 10.0.23.23:80
iptables -A FORWARD -p tcp -d 10.0.23.23 --dport 80 -j ACCEPT
iptables -A FORWARD -m state --state ESTABLISHED,RELATED -j ACCEPT
```

Le serveur Apache sur **INTERNE** est maintenant accessible depuis **EXTERNE** via :

```
http://10.0.23.254:8080
ou
http://10.0.23.23:80
```

Mettre le site MVC dans :
```
sftp://root@[adresse IP INTERNE host0]
```
