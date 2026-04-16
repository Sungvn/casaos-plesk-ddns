# casaos-plesk-ddns

A lightweight Dynamic DNS (DDNS) webcall endpoint for Plesk, designed as a drop-in replacement for the cPanel DDNS feature used by CasaOS and created by myself.
https://github.com/Sungvn/casaos-cpanel-ddns

---

## 🚀 Features

- Simple HTTP endpoint (`update.php`)
- Works with CasaOS DDNS Webcall
- Updates Plesk DNS A records automatically (homeserver.example.com) -> Url to your casaOS server
- <img width="1280" height="720" alt="image" src="https://github.com/user-attachments/assets/7391da20-4d55-4fc3-9114-0646bc1cedfd" />
- Secure via shared secret
- Minimal dependencies
- Uses Plesk CLI

---

## 📦 Requirements

- Plesk server (AlmaLinux, Ubuntu, etc.)
- Domain managed in Plesk
- Subdomain (e.g. `ddns.example.com`)
- CasaOS (optional, for integration)

---

## ⚙️ Installation

### 1. Create subdomain in Plesk

Example:

```
ddns.example.com
```

<img width="1280" height="720" alt="image" src="https://github.com/user-attachments/assets/9ee0b1c0-007a-48d3-bde0-b731e634cc7b" />


---

### 2. Upload files

Upload these files to:

```
/var/www/vhosts/example.com/ddns.example.com/
```

Files:
- `update.php`
- `config.php`

---

### 3. Configure `config.php`

Copy:

```
config.example.php → config.php
```

Edit:

```php
'secret' => 'your-secret-key',
```

Add your host:

```php
'allowed_hosts' => [
    'homeserver.example.com' => [
        'domain' => 'example.com',
        'subdomain' => 'homeserver',
    ],
],
```

---

### 4. Enable sudo access (IMPORTANT)

Plesk runs Domain as a system user (NOT root), so we must allow DNS commands.

Run:

```bash
EDITOR=nano visudo
```

Add this line at the bottom:

```bash
YOUR_PLESK_DOMAIN_USER ALL=(ALL) NOPASSWD: /usr/sbin/plesk *
```
CTRL+X -> Y -> ENTER (to save changes)

⚠️ Replace `YOUR_PLESK_DOMAIN_USER` with your actual Plesk Domain user (e.g. `domain`).

---

### 5. Enable sudo in config

```php
'use_sudo' => true,
```

---

### 6. Test manually

```bash
curl "https://ddns.example.com/update.php?key=your-secret-key&host=homeserver.example.com"
```

Expected:

```json
{
  "ok": true,
  "changed": true
}
```

---

### 7. CasaOS Integration

In CasaOS DDNS settings:

```
https://ddns.example.com/update.php?key=your-secret-key&host=homeserver.example.com
```

---

## 🧪 Example Response

```json
{
  "ok": true,
  "changed": false,
  "message": "already correct"
}
```

---

## 🔐 Security Notes

- Treat your `secret` like a password
- Do NOT expose `config.php` publicly
- Use HTTPS (SSL required)
- Restrict allowed hosts

---

## ⚠️ Troubleshooting

### Error: must run as root

Fix sudoers:

```bash
EDITOR=nano visudo
```

Add:

```bash
YOUR_PLESK_DOMAIN_USER ALL=(ALL) NOPASSWD: /usr/sbin/plesk *
```
CTRL+X -> Y -> ENTER (to save changes)

---

### Error: Invalid key

Check:
- `config.php` secret
- URL key parameter

---

### Error: Host not allowed

Add host in `allowed_hosts`

---

## 📜 License

MIT License



