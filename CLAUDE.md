# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

**Simkom System** is a student organization management platform for an Indonesian university environment. It handles organization membership, activities (kegiatan), financial tracking (keuangan), and audit logging — all behind a role-based access system.

Key domain terms (Indonesian):
- **Mahasiswa** — student
- **Pengurus** — organization leader/board member
- **Bendahara** — treasurer
- **Pembina** — faculty advisor
- **Kegiatan** — activity/event
- **Organisasi** — student organization
- **Keuangan** — finances

## Tech Stack

- **Backend:** Laravel 13 (PHP ^8.3), Eloquent ORM, MySQL
- **Frontend:** Blade templates, Tailwind CSS 4.3, Vite 8
- **Key packages:** Spatie ActivityLog, Maatwebsite Excel, BarryVDH DomPDF
- **Auth:** Custom role-based middleware (`RoleMiddleware`)

## Common Commands

```bash
# Start all dev services concurrently (server + queue + log pail + Vite)
composer run dev

# Or individually
php artisan serve
npm run dev

# Run tests
composer run test

# Lint PHP
./vendor/bin/pint

# Build frontend
npm run build

# Fresh setup from scratch
composer run setup
```

## Architecture

### Role-Based Routing

All routes are in `routes/web.php`. Protected routes are grouped by role prefix and guarded by `auth` + `role:{roleName}` middleware via `RoleMiddleware.php`:

| Prefix | Role | Purpose |
|--------|------|---------|
| `/mahasiswa` | mahasiswa | Browse & join organisations and activities |
| `/pengurus` | pengurus | Manage org activities, finances, and members |
| `/bendahara` | bendahara | Financial input and reporting |
| `/pembina` | pembina | Oversight of advised organisations |
| `/admin` | admin | System-wide management |

### Controller Namespacing

Controllers are namespaced by role under `app/Http/Controllers/`:
- `Admin/`, `Auth/`, `Bendahara/`, `Mahasiswa/`, `Pembina/`, `Pengurus/`

### Models & Key Relationships

- `User` → has one `Mahasiswa` or `Pembina`, has many `AnggotaOrganisasi`
- `Organisasi` → has many `Kegiatan`, `AnggotaOrganisasi`, `PendaftaranAnggota`
- `Kegiatan` → has many `DokumenKegiatan`, `KeuanganKegiatan`, `PendaftaranPesertaKegiatan`; status enum: `Pending | Mendatang | Berlangsung | Selesai | Dibatalkan`
- `AnggotaOrganisasi` — pivot with `jabatan` (position): Ketua, Pengurus, Bendahara, Anggota Biasa

Activity logging (Spatie) is enabled on `User`, `Organisasi`, and `Kegiatan`.

### Views

Blade templates live in `resources/views/pages/{role}/`. Shared UI components (sidebar, topbar, toast, stat-card, status-badge, modals) are in `resources/views/components/`. Layouts are in `resources/views/layouts/`.

### Exports

Export classes in `app/Exports/` use Maatwebsite Excel. PDF generation uses DomPDF via `barryvdh/laravel-dompdf`. Export-specific Blade views are in `resources/views/exports/`.

### Sessions, Cache & Queue

All three use the `database` driver (not file/redis). The queue must be running during development — `composer run dev` starts it automatically via `php artisan queue:listen`.

## Environment

Copy `.env.example` to `.env` and configure:
- `DB_*` — MySQL connection
- No mail or external service setup required for local dev (both use `log` driver)
