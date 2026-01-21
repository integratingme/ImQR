# Deployment Guide - Cloudflare Setup

## Opcije za Hostovanje Laravel Aplikacije sa Cloudflare

Cloudflare nije hosting provider, već CDN i security layer. Evo opcija:

### Opcija 1: Cloudflare + Hosting Provider (Preporučeno)

Koristite Cloudflare kao CDN ispred hosting providera koji podržava PHP/Laravel.

#### Hosting Provideri koji rade dobro sa Cloudflare:
- **DigitalOcean** (App Platform ili Droplet)
- **Vultr** (Cloud Compute)
- **Linode** (Managed Kubernetes)
- **Railway**
- **Render**
- **Fly.io**
- **Laravel Forge** (VPS management)

#### Koraci:

1. **Deploy aplikaciju na hosting provider**
2. **Dodajte domen na Cloudflare**
3. **Konfigurišite DNS**
4. **Omogućite SSL/TLS**

---

## Opcija 2: Cloudflare Tunnel (Za Development/Testing)

Cloudflare Tunnel omogućava da eksponujete lokalni server kroz Cloudflare bez otvaranja portova.

### Instalacija Cloudflare Tunnel:

```bash
# macOS
brew install cloudflare/cloudflare/cloudflared

# Linux
wget https://github.com/cloudflare/cloudflared/releases/latest/download/cloudflared-linux-amd64.deb
sudo dpkg -i cloudflared-linux-amd64.deb
```

### Pokretanje Tunnela:

```bash
# 1. Login
cloudflared tunnel login

# 2. Kreiraj tunnel
cloudflared tunnel create qr-generator

# 3. Kreiraj DNS record
cloudflared tunnel route dns qr-generator yourdomain.com

# 4. Pokreni tunnel
cloudflared tunnel run qr-generator
```

### Konfiguracija (config.yml):

```yaml
tunnel: <tunnel-id>
credentials-file: /path/to/credentials.json

ingress:
  - hostname: qr.yourdomain.com
    service: http://localhost:8000
  - service: http_status:404
```

---

## Opcija 3: Railway (Lako + Cloudflare)

Railway je dobar izbor jer automatski deploy-uje Laravel aplikacije.

### Koraci za Railway:

1. **Kreiraj account na [railway.app](https://railway.app)**
2. **Konektuj GitHub repository**
3. **Dodaj MySQL database**
4. **Konfiguriši environment variables**
5. **Dodaj custom domain**
6. **Konektuj Cloudflare DNS**

### Railway Environment Variables:

```env
APP_NAME="QR Generator"
APP_ENV=production
APP_KEY=base64:... (generiši sa: php artisan key:generate)
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=... (Railway database host)
DB_PORT=3306
DB_DATABASE=railway
DB_USERNAME=railway
DB_PASSWORD=... (Railway database password)

FILESYSTEM_DISK=public
```

---

## Opcija 4: Render (Free Tier Available)

### Koraci za Render:

1. **Kreiraj account na [render.com](https://render.com)**
2. **Kreiraj novi Web Service**
3. **Konektuj GitHub repository**
4. **Konfiguriši build i start komande**

### Render Build Settings:

**Build Command:**
```bash
composer install --no-dev --optimize-autoloader && npm install && npm run build && php artisan optimize
```

**Start Command:**
```bash
php artisan serve --host=0.0.0.0 --port=$PORT
```

**Environment Variables:**
```env
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:...
APP_URL=https://yourdomain.com
```

---

## Cloudflare Konfiguracija

### 1. DNS Setup

Dodajte A record ili CNAME u Cloudflare DNS:

```
Type: A
Name: @ (ili subdomain)
Content: [IP adresa servera]
Proxy: Proxied (orange cloud)
```

### 2. SSL/TLS Settings

1. Idite na **SSL/TLS** u Cloudflare dashboard
2. Postavite na **Full (strict)**
3. Omogućite **Always Use HTTPS**

### 3. Page Rules (Opciono)

Kreirajte page rule za cache:

```
URL: yourdomain.com/*
Settings:
- Cache Level: Standard
- Browser Cache TTL: 4 hours
```

### 4. Security Settings

- **WAF**: Omogućite (Web Application Firewall)
- **Bot Fight Mode**: Omogućite
- **Rate Limiting**: Konfigurišite za API endpoints

---

## Production Checklist

### Pre Deployment:

- [ ] `APP_ENV=production` u `.env`
- [ ] `APP_DEBUG=false`
- [ ] Generisan `APP_KEY`
- [ ] Optimizovani assets (`npm run build`)
- [ ] Cache konfigurisan (`php artisan config:cache`)
- [ ] Storage link kreiran (`php artisan storage:link`)
- [ ] Database migracije pokrenute
- [ ] File permissions postavljene (755 za direktorijume, 644 za fajlove)

### Post Deployment:

- [ ] SSL sertifikat aktivan
- [ ] HTTPS redirect radi
- [ ] File uploads rade
- [ ] QR kodovi se generišu
- [ ] Database konekcija radi
- [ ] Storage link radi

---

## Environment Variables Template

Kreirajte `.env.production` fajl:

```env
APP_NAME="QR Generator"
APP_ENV=production
APP_KEY=base64:YOUR_GENERATED_KEY
APP_DEBUG=false
APP_URL=https://yourdomain.com

LOG_CHANNEL=stack
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=your-db-host
DB_PORT=3306
DB_DATABASE=your-database
DB_USERNAME=your-username
DB_PASSWORD=your-password

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=public
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

MEMCACHED_HOST=127.0.0.1

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"
```

---

## Build Commands za Production

```bash
# 1. Install dependencies
composer install --no-dev --optimize-autoloader
npm install

# 2. Build assets
npm run build

# 3. Optimize Laravel
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 4. Run migrations
php artisan migrate --force

# 5. Create storage link
php artisan storage:link
```

---

## Troubleshooting

### Problem: Assets se ne učitavaju
**Rešenje:** Proverite da li je `npm run build` pokrenuto i da li su fajlovi u `public/build`

### Problem: File uploads ne rade
**Rešenje:** Proverite permissions na `storage/app/public` direktorijumu (chmod 755)

### Problem: 500 Error
**Rešenje:** Proverite Laravel logove (`storage/logs/laravel.log`) i proverite da li je `APP_KEY` postavljen

### Problem: Mixed Content (HTTP/HTTPS)
**Rešenje:** Osigurajte se da je `APP_URL` postavljen na HTTPS URL

---

## Preporučeni Hosting Provideri

1. **Railway** - Najlakše za Laravel, automatski deployment
2. **Render** - Free tier, dobar za početak
3. **DigitalOcean App Platform** - Managed Laravel hosting
4. **Laravel Forge** - Najbolje za VPS management
5. **Fly.io** - Global edge deployment

---

## Cloudflare Optimizacije

### 1. Caching
- Cache statičke assets (CSS, JS, images)
- Ne cache-ujte dinamičke stranice (QR generation)

### 2. Compression
- Omogućite Brotli compression u Cloudflare

### 3. Minification
- Omogućite auto-minify za CSS, JS, HTML

### 4. Image Optimization
- Koristite Cloudflare Images za uploaded fajlove (opciono)

---

Za dodatnu pomoć, proverite:
- [Cloudflare Docs](https://developers.cloudflare.com/)
- [Laravel Deployment Docs](https://laravel.com/docs/deployment)
