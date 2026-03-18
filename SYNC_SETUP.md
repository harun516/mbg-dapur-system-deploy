# 🔄 GitHub Actions Auto-Sync Setup Guide

## Apa yang akan terjadi?
Setiap ada **update di `main` branch** di repository source ini:
```
https://github.com/YayangSetiaBudi/MBG-dapur-system.git
```

Maka **otomatis akan sync ke** deploy repository:
```
https://github.com/harun516/mbg-dapur-system-deploy.git (branch: main)
```

---

## Flow Diagram
```
Commit & Push ke main (source repo)
        ↓
GitHub Actions trigger
        ↓
Pull latest code
        ↓
Push ke deploy repo main
        ↓
✅ Deploy repo updated otomatis!
```

---

## ⚙️ Setup Steps (HANYA 2 MENIT)

### Step 1: Buat Personal Access Token
1. Buka: https://github.com/settings/tokens
2. Klik **Generate new token** → **Generate new token (classic)**
3. Setting:
   - Token name: `DEPLOY_SYNC_TOKEN`
   - Expiration: `No expiration` (atau sesuai preferensi)
   - Scopes: ✅ Check `repo` (full control)
4. Klik **Generate token**
5. **Copy token** (tampil sekali saja!)

### Step 2: Simpan Token di Repository Settings
1. Buka repo source: https://github.com/YayangSetiaBudi/MBG-dapur-system
2. Pergi ke: **Settings** → **Secrets and variables** → **Actions**
3. Klik **New repository secret**
4. Isi:
   - **Name:** `DEPLOY_TOKEN`
   - **Secret:** (paste token dari Step 1)
5. Klik **Add secret**

### Step 3: Pastikan Workflow File Sudah Ada
File harus ada di: `.github/workflows/sync-to-deploy.yml`

Jika tidak ada, copy ini:

```yaml
name: Sync to Deploy Repository

on:
  push:
    branches:
      - main

jobs:
  sync:
    runs-on: ubuntu-latest
    
    steps:
      - name: Checkout source repository
        uses: actions/checkout@v4
        
      - name: Setup git config
        run: |
          git config --global user.email "github-actions[bot]@users.noreply.github.com"
          git config --global user.name "GitHub Actions"
          
      - name: Push to deploy repository
        run: |
          git remote add deploy "https://${{ secrets.DEPLOY_TOKEN }}@github.com/harun516/mbg-dapur-system-deploy.git"
          git push deploy main --force
        env:
          GIT_AUTHOR_NAME: "GitHub Actions"
          GIT_AUTHOR_EMAIL: "github-actions[bot]@users.noreply.github.com"
          
      - name: Notify success
        run: echo "✅ Synced to https://github.com/harun516/mbg-dapur-system-deploy"
```

### Step 4: Push Files ke Repository
```bash
cd c:\WebApp\latihan\Laravel\YayangBud\MBG-dapur-system

git add .github/workflows/sync-to-deploy.yml
git commit -m "Add auto-sync workflow to deploy repository"
git push origin main
```

---

## ✅ Itu Saja!

Sekarang setiap kali Anda:
```bash
git push origin main
```

Deploy repository akan **otomatis update**! 🚀

---

## 🔍 Cara Verifikasi

### Lihat Status Workflow
1. Buka: https://github.com/YayangSetiaBudi/MBG-dapur-system/actions
2. Lihat workflow "Sync to Deploy Repository"
3. Pastikan ada ✅ (hijau) = berhasil

### Verifikasi Deploy Repo Update
1. Buka: https://github.com/harun516/mbg-dapur-system-deploy
2. Lihat branch main
3. Pastikan commit terbaru sudah ada & sama dengan source repo

---

## 📝 Test Manual

Jika mau test sebelum setup selesai:

```bash
# Manual sync command
git remote add deploy "https://<TOKEN>@github.com/harun516/mbg-dapur-system-deploy.git"
git push deploy main --force
```

---

## 🆘 Troubleshooting

| Problem | Solution |
|---------|----------|
| ❌ Permission denied | Token tidak valid atau expire. Buat token baru di Settings → Tokens |
| ❌ Branch not found | Pastikan target repo punya branch `main` (buat manual di GitHub jika perlu) |
| ❌ Workflow tidak running | Pastikan file di `.github/workflows/sync-to-deploy.yml` |
| ❌ Deploy repo masih lama update | Wait 1-2 menit, refresh halaman GitHub |

---

## 🛑 Mau Hentikan Sync?

Buka Settings → Actions dan disable workflow "Sync to Deploy Repository"

---

## ✨ Advanced: Trigger Manual Workflow

Jika mau trigger sync tanpa push, bisa:
1. Buka: https://github.com/YayangSetiaBudi/MBG-dapur-system/actions
2. Pilih workflow "Sync to Deploy Repository"
3. Klik **Run workflow**

---

**Setup selesai! 🎉 Nikmati auto-sync yang mulus!**
