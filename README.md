# PromiseLane API

> A project governance platform for freelancers and service providers to track the full lifecycle of client work — from requirements and agreements to delivery and payment.

![Laravel](https://img.shields.io/badge/Laravel-12-red?style=flat-square&logo=laravel)
![PHP](https://img.shields.io/badge/PHP-8.2+-blue?style=flat-square&logo=php)
![MySQL](https://img.shields.io/badge/MySQL-8.0-orange?style=flat-square&logo=mysql)
![JWT](https://img.shields.io/badge/Auth-JWT-green?style=flat-square)
![Status](https://img.shields.io/badge/Status-In%20Development-yellow?style=flat-square)

---

## Problem Statement

Freelancers and small service providers lack a structured system to track the full lifecycle of client work. As a result:

- Requirements are scattered across chats, emails, and calls
- Scope creep occurs due to unclear agreements
- Deliverables are disputed — *"this is not what I asked"*
- Payments are delayed or denied due to lack of proof

**PromiseLane connects:** What was requested → What was agreed → What was delivered → What was paid.

---

## Tech Stack

| Layer | Technology |
|-------|-----------|
| Backend Framework | Laravel 12 |
| Language | PHP 8.2+ |
| Database | MySQL 8.0 |
| Authentication | JWT (tymon/jwt-auth) |
| API Style | RESTful — versioned `/api/v1/` |
| Frontend (upcoming) | React + Vite |

---

## Architecture

- Thin Controllers → Service Classes → Eloquent Models
- API Resources for response transformation
- Repository pattern for data access
- Role-based access control (RBAC)
- Immutable activity logging

---

## Features (Phase 1 — In Progress)

- [x] Project scaffold — Laravel 12 + JWT setup
- [ ] User registration & login with JWT
- [ ] Token refresh & logout
- [ ] Project CRUD with status management
- [ ] Milestone tracking with payment linkage
- [ ] Requirements management with scope flagging
- [ ] Deliverable uploads per milestone
- [ ] Manual payment recording & status tracking
- [ ] Immutable activity log & timeline
- [ ] Shareable read-only project link (token-based)
- [ ] Dashboard — active projects, pending payments, overdue milestones

---

## Getting Started

### Requirements

- PHP 8.2+
- Composer
- MySQL 8.0+
- Node.js (for frontend — upcoming)

### Installation

```bash
# Clone the repository
git clone https://github.com/yourusername/promiselane-api.git
cd promiselane-api

# Install dependencies
composer install

# Setup environment
cp .env.example .env
php artisan key:generate

# Configure your database in .env
DB_DATABASE=promiselane_db
DB_USERNAME=root
DB_PASSWORD=

# Run migrations
php artisan migrate

# Generate JWT secret
php artisan jwt:secret

# Start development server
php artisan serve
```

---

## API Structure

```
/api/v1/auth        → Register, Login, Refresh, Logout
/api/v1/profile     → View own profile
/api/v1/projects    → Project CRUD
/api/v1/milestones  → Milestone management
/api/v1/requirements→ Requirements tracking
/api/v1/deliverables→ File uploads per milestone
/api/v1/payments    → Payment recording
/api/v1/activity    → Project timeline & logs
/api/v1/share       → Shareable project links
```

---

## Project Roadmap

## Project Roadmap

| Phase | Theme | Status |
|-------|-------|--------|
| Phase 1 | MVP Core — Projects, Milestones, Requirements, Timeline | 🔄 In Progress |
| Phase 2 | Payments & Invoicing — Razorpay, GST, Receipts | ⏳ Planned |
| Phase 3 | Client Portal & Collaboration | ⏳ Planned |
| Phase 4 | Team & Agency Support | ⏳ Planned |
| Phase 5 | Automation & AI | ⏳ Planned |
| Phase 6 | Analytics & Business Intelligence | ⏳ Planned |
| Phase 7 | Integrations & Ecosystem | ⏳ Planned |
| Phase 8 | Enterprise & Compliance | ⏳ Planned |
| Phase 9 | Community & Platform | ⏳ Planned |

---

## License

---

> Built as a portfolio project by a backend developer focused on real-world problem solving with Laravel.
> All rights reserved. Not licensed for redistribution or commercial use.

---

