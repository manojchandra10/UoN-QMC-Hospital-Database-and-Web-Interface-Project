# Queen's Medical Centre (QMC) - Staff Portal

**Coursework Project** | **PHP & MySQL** 

##  This is a full-stack hospital management system developed as part of my coursework for **COMP4039** Module at UoN. 

The goal was to digitise the manual record-keeping system for the Queen's Medical Centre (Nottingham). It replaces paper records with a secure web portal that handles patient admissions, clinical testing, and staff logistics.

**Key constraints:** Built using **Raw PHP** (no frameworks like Laravel) to show core understanding of server-side logic, session management, and database normalisation.

## Tech Stack
**Backend:** PHP 8.2 (Procedural style)<br>
**Database:** MariaDB / MySQL
<br>**Frontend:** HTML5, CSS3
<br>**DevOps:** Docker & Docker Compose

## Key Features

### For Medical Staff (Doctors)
**Patient Dashboard:** Search patients by NHS Number or Name.
<br>**Admissions:** Real-time ward allocation logic (tracking beds and admission history).
<br>**Prescriptions:** Dynamic form to prescribe tests; auto-creates new test types if they don't exist in the DB.
<br>**Parking Permits:** Automated fee calculation logic (Monthly vs. Yearly) with status tracking.

### For Administrators
**Security Auditing:** A custom-built `audit_log` system that tracks *every* INSERT/UPDATE action (Who, What, When) for accountability.
<br>**Role-Based Access Control (RBAC):** Hardened login system protecting admin routes from unauthorized access.
<br>**Staff Management:** approve parking permits and onboard new consultants.

## Login Credentials
 The database comes pre-seeded with these users for testing:

| Role | Username | Password |
| :--- | :--- | :--- |
| **Admin** | `jelina` | `iron99` |
| **Doctor** | `mceards` | `lord456` |
