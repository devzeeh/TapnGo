# TapnGo

---

## Overview

Tap 'n Go is a cost-effective, RFID-based contactless fare and retail payment system designed for local transportation (e.g., jeepneys) and small-scale retailers (e.g., sari-sari stores) in semi-urban and rural areas.

The system allows commuters and customers to simply “tap” their RFID card to pay fares or purchases, removing the need for cash handling. It provides an affordable, secure, and efficient digital payment alternative for communities that lack access to high-cost payment infrastructures.

---

## Features

- Implemented card registration, authentication, and balance tracking with MySQL persistence.
- Added card top-up/reload functionality, allowing customers to add funds via Stripe online payments or operator-assisted reload.
- Built tap-to-pay workflows with transaction history and refund support for transparency.
- Backend logic written in vanilla PHP with CRUD operations for account and transaction management.
- Frontend dashboard developed in vanilla HTML, CSS, JavaScript for operators to monitor transactions and balances.
- Integrated PHPMailer to automatically send e-receipts to customers after reloads, payments, and other transactions.
- Designed an inclusive discount & loyalty system for students, seniors, and PWDs.
- Conducted market analysis (SAM, TAM, SOM), prototype costing, and testing as part of the Technopreneurship curriculum.


---

## Tech Stack

| Layer | Technology |
|---|---|
| Frontend | HTML, CSS, JS |
| Backend | PHP |
| Database | MySQL |
| Libraries | Stripe API, PHPMailer |
| Hardware Components | NodeMCU ESP8266, RC522 RFID |

---

