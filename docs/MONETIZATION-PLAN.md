# ImQR Monetization & Tiers Plan

**Branch:** `feature/monetization-guest-free-paid-plan`  
**Status:** Planning (no implementation yet)  
**Goal:** Reduce friction for try-out, build trust with free tier, monetize via premium (edit anytime, tracking, no branding, scale).

---

## 1. Tier Overview

| Tier | Who | Main value |
|------|-----|------------|
| **Guest** | Anonymous visitors | Full preview + basic customization; login only at save or high-quality download; **10 scans per code** |
| **Free** | Registered users | Unlimited static QR codes, **10 scans per code**, no expiry; **Step 2: no logo** (colors only) — primary hook |
| **Paid (Premium)** | Power users / teams | **Unlimited scans per code**, **Step 2: full customization including logo**, edit anytime, scan tracking, no branding, unlimited saves, teams; **dynamic QR codes** (edit content in 1–2–3 steps without reprinting) |

---

## 2. Guest Mode (Generous to Reduce Friction)

**Principle:** Let people experience the full tool before asking for anything.

- **Allowed without login:**
  - Full preview of all QR types.
  - Basic customization in Step 2 (colors, simple options — **no logo insert**; same as free).
  - See exactly what the QR and landing page will look like.
- **Login/signup required only at:**
  - **Save** (persist QR to account).
  - **High-quality download** (e.g. PNG/SVG above a certain resolution or “premium” export).
- **Scan limit:** **10 scans per code** for guest-created/saved codes. After 10 scans, show “Scan limit reached” or upgrade prompt instead of content.
- **Implementation notes:**
  - Session or anonymous ID for “current draft” so preview state can be kept.
  - After login/signup, optionally attach current draft to the new user.
  - Count scans when the content page is loaded (e.g. `/text/{id}`, `/coupon/{id}`); enforce limit for guest/free.

---

## 3. Free Plan (Build Trust & Habit)

**Principle:** No feeling of being limited for simple, static use.

- **Included:**
  - **Unlimited static QR codes** (current behavior: type + data fixed at creation).
  - **10 scans per code** — after 10 scans, the link shows “Scan limit reached” or upgrade prompt; creates clear reason to upgrade for active use.
  - **No expiry** (QR codes stay valid).
- **Limitations (to differentiate from paid):**
  - **Step 2 (customize QR) locked for logo:** Free users cannot insert a logo — only colors / basic options. **Logo insert = premium only.**
  - **Scan cap: 10 scans per code** (premium = unlimited scans per code).
  - No “edit after creation” (static only).
  - No scan analytics.
  - Branding on landing pages and/or downloads (e.g. “Made with ImQR”).
  - No custom logo, no logo in center of QR (premium only).

---

## 4. Paid Plan (Premium)

**Sell:** Edit anytime, insights, no branding, scale.

- **Included:**
  - **Unlimited scans per code** (no 10-scan cap).
  - **Step 2 full customization:** **Insert logo** (and advanced design: frames, colors, etc.) — only paying users can add a logo in the QR.
  - **Edit anytime** → requires **dynamic QR codes** (see below).
  - **Scan tracking** (counts, optional geo, device, time).
  - **Remove branding** on landing pages and downloads.
  - **Unlimited saves** (if we ever cap free saves).
  - **Teams** (optional later: shared workspace, roles).
- **First premium feature to build:** Dynamic QR codes (edit content in 1–2–3 steps).

---

### Step 2 – QR customization (wizard)

| Tier   | Step 2 access |
|--------|----------------|
| Guest  | Colors / basic options only — **no logo insert** |
| Free   | Colors / basic options only — **no logo insert** (logo locked) |
| Premium| Full customization — **insert logo** + frames, colors, etc. |

**Implementation:** In the create flow, Step 2 shows logo upload only for premium users; for free/guest show disabled control or placeholder with “Add your logo — upgrade to Premium”.

---

## 5. Dynamic QR Codes (Premium – Future)

**Idea:** QR code URL stays the same; only the **destination content** (URL, text, PDF link, etc.) is updated. No need to reprint.

- **Technical direction:**
  - Static QR: encodes final URL (e.g. `https://example.com/text/123`).
  - Dynamic QR: encodes a short redirect URL (e.g. `https://imqr.com/r/abc123`) that:
    - Resolves to current content (from DB).
    - Allows tracking (scan count, optional metadata).
  - Premium user can **edit** the content behind `r/abc123` in 1–2–3 steps (same wizard as create, pre-filled).
- **Product flow:**
  - Create as “dynamic” (premium only) → get short link + QR image.
  - Edit from dashboard: “Edit content” → 1–2–3 step form → save; next scan shows new content.
- **Implementation outline (later):**
  - New route(s): e.g. `/r/{slug}` (redirect + optional tracking).
  - New table or columns: `redirect_slug`, `is_dynamic`, `user_id`, `plan`.
  - Middleware/checks: only premium can create/edit dynamic QRs.

---

## 6. Implementation Phases (Suggested)

### Phase 0 – Current state (no changes in this branch yet)
- Document plan (this file).
- Branch: `feature/monetization-guest-free-paid-plan`.

### Phase 1 – Auth & tier model
- [ ] User model: add `plan` (e.g. `free`, `premium`) and optional `plan_expires_at`.
- [ ] Auth: login/register (if not already); optional “Continue as guest” for preview.
- [ ] Middleware or helpers: `guest`, `free`, `premium` checks.

### Phase 2 – Guest mode
- [ ] Allow full preview + basic customization without login.
- [ ] Require login/signup only at **save** or **high-quality download**.
- [ ] Optional: “Save draft in session” and attach to user after signup.

### Phase 3 – Free vs premium gating & scan limits
- [ ] **Step 2 – Logo locked for free/guest:** In the create wizard (Step 2: customize QR), hide or disable the “Insert logo” / logo upload for free and guest users; show upsell (“Add your logo — upgrade to Premium”). Allow only colors / basic options for free. Premium gets full Step 2 including logo.
- [ ] **Scan counting:** Increment scan count when content page is loaded (e.g. in controller for `/text/{id}`, `/coupon/{id}`, etc.); add `scan_count` (or scan_events) to DB.
- [ ] **Scan limit for guest/free:** 10 scans per code; when `scan_count >= 10` for a free/guest-owned QR, show “Scan limit reached” / upgrade view instead of content.
- [ ] Free: unlimited static QRs, **10 scans per code**, no expiry; **no logo in Step 2**; branding on pages/downloads.
- [ ] Premium: **unlimited scans per code**, **logo in Step 2**, no branding, “Edit” button on saved QRs (still static at first), later scan stats.
- [ ] Gates: “Step 2 logo / insert logo”, “Remove branding”, “Edit QR”, “Scan stats”, “Unlimited scans” → premium only.

### Phase 4 – Dynamic QR (premium)
- [ ] Short redirect URLs: `/r/{slug}`.
- [ ] DB: `is_dynamic`, `redirect_slug`, link to same content fields as static.
- [ ] Create dynamic QR (premium only); edit flow in 1–2–3 steps.
- [ ] Redirect resolver + optional scan logging.

### Phase 5 – Scan tracking & teams (later)
- [ ] Scan events table; dashboard for counts (and optional geo/device).
- [ ] Teams: workspaces, roles, shared QRs (optional).

---

## 7. Files / Areas to Touch (When Implementing)

- **Models:** `User` (plan, expiry), `QrCode` (user_id if not present, is_dynamic, redirect_slug, **scan_count** or relation to scan_events).
- **Migrations:** `plan`, `plan_expires_at` on users; `is_dynamic`, `redirect_slug`, `user_id` on qr_codes; **scan_count** on qr_codes (or `scan_events` table) for enforcing 10-scan limit.
- **Controllers:** `QrCodeController` (save/download gating), new or extended controller for `/r/{slug}` and edit.
- **Routes:** `web.php` – guest vs auth routes; `/r/{slug}` redirect.
- **Views:** Login/signup prompts at save and high-quality download; **Step 2 wizard:** disable or hide logo upload for free/guest + premium upsell (“Add logo — upgrade”); premium upsell for edit/tracking/no branding.
- **Config:** e.g. `config/plans.php` for limits and feature flags (e.g. `free_scan_limit_per_code => 10`).

---

## 8. Summary

| Item | Action |
|------|--------|
| **Branch** | `feature/monetization-guest-free-paid-plan` (created) |
| **Plan** | This document |
| **Next** | Phase 1 when ready: auth + `User.plan` and tier checks; then Phase 2 (guest mode), then Phase 3 (free vs premium gating), then Phase 4 (dynamic QR for premium). |

Dynamic QR is the main premium differentiator: “Edit anytime in 1–2–3 steps without reprinting.”
