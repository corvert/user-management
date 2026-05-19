# Technical Specification: User Management & Time Tracking System

## 1. Project Overview
A Laravel-based web application designed to manage users, roles, and daily office attendance. The system features granular permission management, automated notifications, and an approval workflow for time logs.

## 2. Tech Stack
* **Framework:** Laravel 11.x
* **Database:** MySQL 8.0+
* **Role Management:** [Spatie Laravel-Permission](https://spatie.be/docs/laravel-permission/v6/introduction)
* **Mailing:** Laravel Mailables (SMTP/Mailgun/SES)
* **Task Scheduling:** Laravel Scheduler (for automated reminders)

---

## 3. Role & Permission Architecture (Spatie)
The system uses a Role-Based Access Control (RBAC) model. Permissions are structured around modules with three specific properties: `view`, `create`, and `deactivate`.

### 3.1. Default Roles
| Role | Description |
| :--- | :--- |
| **Superadmin** | Full system access. Can manage users, roles, and global settings. |
| **Manager** | Oversees a team. Can view and approve/reject user time logs. |
| **User** | Standard employee. Can log their own arrival and departure times. |

### 3.2. Configurable Permissions Matrix
| Module | Permission: `view` | Permission: `create` | Permission: `deactivate` |
| :--- | :--- | :--- | :--- |
| **User Management** | List users | Add new users | Disable user accounts |
| **Role Management** | View roles/permissions | Create new roles | Disable specific roles |
| **Time Logs** | View history | Submit daily logs | Archive/Void logs |

---

## 4. Database Schema (Migrations)

### `users` table
* `id` (BigIncrements)
* `name` (String)
* `email` (String, Unique)
* `password` (String)
* `status` (Boolean, default: true) — Used for the 'deactivate' property.
* `timestamps`

### `time_logs` table
* `id` (BigIncrements)
* `user_id` (Foreign Key -> users.id)
* `log_date` (Date)
* `arrival_time` (Time, nullable)
* `departure_time` (Time, nullable)
* `status` (Enum: `pending`, `approved`, `rejected`) - Default: `pending`
* `approved_by` (Foreign Key -> users.id, nullable)
* `timestamps`

---

## 5. Functional Requirements

### 5.1. User & Role Management (Superadmin)
* Only the Superadmin (or users with `create users` permission) can register new accounts.
* The creator assigns a specific role (`Manager` or `User`) during account creation.
* The `deactivate` property allows admins to disable accounts without deleting historical data.

### 5.2. Daily Time Tracking (User)
* Users enter their **Arrival Time** and **Departure Time** daily.
* **Locking Mechanism:** Once a log entry's status is changed to `approved` by a Manager, the User is strictly prohibited from modifying that specific record.

### 5.3. Approval Workflow (Manager)
* Managers access a dashboard showing `pending` logs from Users.
* Managers can either **Approve** or **Reject** the data.
* Upon approval, the system records the Manager's ID and timestamps the action.

### 5.4. Automated Email Notifications
* **Trigger:** A scheduled task runs daily (e.g., at 19:00).
* **Logic:** The script identifies active users who have not completed their `arrival_time` or `departure_time` for the current date.
* **Action:** Dispatches an email notification reminding the user to complete their daily check-in/out.

---

## 6. Logic & Constraints
* **Validation:** `departure_time` must be chronologically after `arrival_time`.
* **Security:** Use Laravel Middleware to enforce Spatie permissions on every route (e.g., `can:view logs`).
* **Data Integrity:** Time logs should be unique per user per date (`unique(user_id, log_date)`).
