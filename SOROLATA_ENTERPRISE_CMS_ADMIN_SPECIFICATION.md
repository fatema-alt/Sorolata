# Sorolata Commerce Platform
## PART 06 — Enterprise CMS & Admin Panel Specification

### 1. Vision & Human-Crafted Admin Philosophy
The Admin Panel is the merchant-facing core of Sorolata. Inspired by **Stripe Dashboard**, **Linear**, **Notion**, **Framer**, and **Filament**, the interface is designed as an ultra-fast, high-aesthetic productivity workstation.

**Core Tenets:**
- **Zero AI Gimmicks**: Strictly clean, honest, human-engineered craft. No sparkles, no robot badges, no magic wands, and no artificial generation shortcuts.
- **Maximum 3-Click Navigation**: Any order, product, artisan, or setting reachable within 3 clicks or via `Ctrl + K`.
- **Progressive Disclosure**: Clean surfaces by default; detailed technical specs revealed through structured tabs.
- **Micro-Interactions & Velocity**: Instant search, fluid keyboard navigation, responsive tables, and zero jarring page reloads.

---

### 2. Admin Architecture & Layout Structure

```
+-----------------------------------------------------------------------------------+
|  [Logo] Sorolata Studio  |  [⌘K Search Products, Orders, Actions]  | [Bell] [User] |
+-----------------------------------------------------------------------------------+
| SIDEBAR            | MAIN CONTENT WORKSPACE                                       |
| - Dashboard        | +----------------------------------------------------------+ |
| - Orders (12)      | | KPI Widgets: Sales ৳ | Orders | AOV | Low Stock Alert    | |
| - Products         | +----------------------------------------------------------+ |
| - Collections      | | Visual Revenue Trend & Regional Performance               | |
| - Categories       | +----------------------------------------------------------+ |
| - Districts        | | Recent Heirloom Orders (Live Fulfillment Status)         | |
| - Artisans         | +----------------------------------------------------------+ |
| - Customers        | | Top Artisans Guilds & GI Specialty Artifacts             | |
| - Reviews          | +----------------------------------------------------------+ |
| - Builder          |                                                              |
| - Media Library    |                                                              |
| - Marketing        |                                                              |
| - Reports          |                                                              |
| - Settings         |                                                              |
| - Audit Logs       |                                                              |
+--------------------+--------------------------------------------------------------+
```

---

### 3. Subsystem Breakdown

#### 3.1 Command Palette (`Ctrl + K` / `Cmd + K`)
Global modal search indexed in real-time across:
- **Products**: Title, SKU, Artisan Name, District.
- **Orders**: Order Number, Customer Name, Phone, Email.
- **Customers**: Name, Location, Lifetime Value.
- **Navigation Shortcuts**: Quick jumps to Settings, Shipping Rules, Coupons, Builder, Media Library.

#### 3.2 Executive KPI Dashboard
- **Live Metrics**: Today's Sales (৳), Net Revenue, Orders Count, Conversion Rate (%), Average Order Value (৳), Low Stock Threshold Alerts.
- **Visual Performance**: 7-Day Revenue Trend graph.
- **Heirloom Logistics**: Live orders awaiting curator packaging and courier dispatch.
- **Artisan Guild Insights**: Top performing districts (Sonargaon, Tangail, Bogura) and master artisan ateliers.

#### 3.3 Product Management & Tabbed Editor
- **Data Table**: High-density grid with thumbnail preview, title, SKU, artisan provenance, pricing, live stock badge, visibility toggle, and bulk actions.
- **Tabbed Editor (No Endless Scrolling)**:
  1. *General*: Title, Subtitle, Short & Long Story, Status, Visibility.
  2. *Pricing*: Regular, Sale, Cost Price, Wholesale, Tax Class.
  3. *Inventory*: SKU, Barcode, Stock Quantity, Reserved Stock, Low Stock Threshold, Out of Stock Behavior.
  4. *Shipping*: Weight, Dimensions, Packaging Weight, Shipping Class, Free Shipping Eligibility.
  5. *Heritage & Provenance*: District, Artisan Guild, Craft Type, Handmade Duration, Atelier Workshop, Care Instructions.
  6. *Media & Visuals*: Gallery Assets, Lifestyle Imagery, Process Video Link.
  7. *SEO & Rich Snippets*: Meta Title, Description, Canonical URL, SERP Preview.
  8. *Variants*: Unlimited multi-attribute SKU variants with independent pricing and stock.

#### 3.4 Order Management & Audit Timeline
- **Order Details**: Customer contact, shipping destination, gift wrap presentation instructions, item snapshots.
- **Interactive State Machine**: Transition order states (`Pending` → `Confirmed` → `Packed` → `Shipped` → `Delivered` → `Completed`).
- **Audit Timeline**: Immutable ledger of every state change, staff note, and customer notification timestamp.

#### 3.5 Customer & Review Moderation
- **Customer Profiles**: Total orders placed, lifetime spend (LTV), saved addresses, review history.
- **Review Center**: Verification badge checking, 1-click approve/reject moderation, and official curator reply.

#### 3.6 Settings & Administrative Activity Logs
- **Store Configuration**: Branding, Currency, Payment Gateways (bKash, Nagad, Rocket, SSLCommerz, Stripe, PayPal, COD), Shipping rules, Tax rates.
- **Activity Log**: Comprehensive audit trail recording user logins, product edits, price modifications, order transitions, and setting changes.
