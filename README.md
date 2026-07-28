
# UploadLab

> Professional Dockerized CTF laboratory designed to teach and practice real-world File Upload Vulnerabilities through realistic web applications progressive challenges and hands-on exploitation

[![Docker](https://img.shields.io/badge/Docker-Ready-blue?logo=docker)](https://hub.docker.com/r/haqtor/upload-lab)
[![PHP](https://img.shields.io/badge/PHP-8.2-purple?logo=php)]()
[![Apache](https://img.shields.io/badge/Apache-2.4-red?logo=apache)]()
[![SQLite](https://img.shields.io/badge/SQLite-3-blue?logo=sqlite)]()
[![Version](https://img.shields.io/badge/Version-v4.1-red)]()
[![License](https://img.shields.io/badge/License-MIT-green)]()
[![Challenges](https://img.shields.io/badge/Challenges-7-green)]()
[![Points](https://img.shields.io/badge/Total%20Points-700-orange)]()

---

## Table of Contents

- [Overview](#overview)
- [Quick Start](#quick-start)
- [Why UploadLab](#why-uploadlab)
- [Challenges](#challenges)
- [Challenge Write-ups](#challenge-write-ups)
- [Learning Objectives](#learning-objectives)
- [Features](#features)
- [Architecture](#architecture)
- [Project Structure](#project-structure)
- [How to Defend](#how-to-defend)
- [Accounts](#accounts)
- [Docker Hub](#docker-hub)
- [Screenshots](#screenshots)
- [Contributing](#contributing)
- [License](#license)
- [Author](#author)

---

## Overview

| Item | Value |
|:-----|:------|
| Challenges | 7 |
| Difficulty | Easy to Hard |
| Platform | Docker |
| Backend | PHP 8.2 |
| Database | SQLite |
| Total Points | 700 |
| Target Audience | CTF Players Students Pentesters |
| Estimated Time | 1 to 3 Hours |

---

## Quick Start

### Pull from Docker Hub

```bash
docker pull haqtor/upload-lab:v4.1
docker run -d -p 8089:80 haqtor/upload-lab:v4.1
```

Open http://localhost:8089

### Build from Source

```bash
git clone https://github.com/belsopky/upload-lab.git
cd upload-lab
docker-compose up --build -d
```

---

## Why UploadLab

- 7 Realistic Upload Vulnerabilities based on real-world scenarios
- Docker Ready one command to start
- PHP + Apache + SQLite stack
- Real exploitation scenarios not just theory
- Built-in scoreboard with progress tracking
- Reset functionality for repeated practice
- Flags only appear after successful exploitation
- Toggleable hints for guided learning

---

## Challenges

| ID | Challenge | Category | Vulnerability | Points | Difficulty |
|:--:|:----------|:---------|:--------------|:------:|:----------:|
| 1 | Unrestricted Upload | Web Security | No validation | 100 | Easy |
| 2 | Content-Type Bypass | Web Security | Client-side header trust | 100 | Easy |
| 3 | Path Traversal | Web Security | Directory traversal in filename | 100 | Medium |
| 4 | Blacklist Bypass | Web Security | Case-sensitive extension filter | 100 | Medium |
| 5 | Obfuscated Extension | Web Security | Double extension parsing | 100 | Medium |
| 6 | Polyglot Upload | Web Security | Magic bytes bypass | 100 | Hard |
| 7 | Race Condition | Web Security | Time-of-check time-of-use | 100 | Hard |

**Total: 700 Points**

---

## Challenge Write-ups

### Challenge 1: Unrestricted Upload

**Vulnerability:** Remote Code Execution (RCE) via unrestricted file upload

**Scenario:** The upload endpoint performs absolutely no validation on file type extension or content. Any file uploaded is saved directly to the server and becomes publicly accessible via URL.

**Exploitation:**
1. Create a PHP web shell: `<?php system($_GET['cmd']); ?>`
2. Save as `shell.php`
3. Upload through the form
4. Navigate to `http://localhost:8089/uploads1/shell.php?cmd=whoami`
5. The shell executes proving RCE

**Flag:**
```diff
+ CATF{upload_01_unrestricted_upload_saly_3ala_ELNABY}
```

---

### Challenge 2: Content-Type Bypass

**Vulnerability:** Trusting client-provided Content-Type header

**Scenario:** The server validates uploads by checking `$_FILES['file']['type']` only. This value comes from the browser and can be easily forged. The server does not verify actual file content or extension.

**Exploitation:**
1. Create a PHP shell named `shell.php`
2. Intercept the upload request using Burp Suite or any proxy
3. Change the `Content-Type` header from `application/x-php` to `image/jpeg`
4. Forward the request
5. The server accepts the file because it trusts the forged header
6. Access `http://localhost:8089/uploads2/shell.php?cmd=whoami`

**Flag:**
```diff
+ CATF{upload_02_content_type_bypass_saly_3ala_ELNABY}
```

---

### Challenge 3: Path Traversal

**Vulnerability:** Path traversal in multipart filename parameter

**Scenario:** The avatar upload page uses the raw `full_path` from the multipart request as the filename without any sanitization. The path is prepended to `uploads3/avatars/`. By controlling the filename an attacker can write files anywhere on the server.

**Exploitation:**
1. Login with any account (wiener/peter)
2. Intercept the avatar upload request in Burp Suite
3. Modify the `filename` parameter in the raw multipart body to `../../shell.php`
4. The file gets written to the web root instead of `uploads3/avatars/`
5. Access `http://localhost:8089/shell.php?cmd=whoami`

**Flag:**
```diff
+ CATF{upload_03_path_traversal_saly_3ala_ELNABY}
```

---

### Challenge 4: Blacklist Bypass (Case)

**Vulnerability:** Case-sensitive extension blacklist

**Scenario:** The application blocks dangerous extensions (php php3 php4 php5 phtml pht) but only checks lowercase variations. The server is explicitly configured via `.htaccess` to execute `.PHP` (uppercase) files as PHP.

**Exploitation:**
1. Create a PHP shell
2. Rename it to `shell.PHP` (uppercase extension)
3. Upload it
4. The lowercase blacklist does not match `.PHP`
5. The server executes it as PHP because of the `.htaccess` rule
6. Access `http://localhost:8089/uploads4/shell.PHP?cmd=whoami`

**Flag:**
```diff
+ CATF{upload_04_blacklist_bypass_saly_3ala_ELNABY}
```

---

### Challenge 5: Obfuscated Extension

**Vulnerability:** Double extension with Apache AddHandler misconfiguration

**Scenario:** The application validates only the final extension using `pathinfo(... PATHINFO_EXTENSION)`. A file named `shell.php.jpg` passes because `.jpg` is allowed. However the server uses `AddHandler application/x-httpd-php .php` which causes Apache to execute any file containing `.php` in its name regardless of the final extension.

**Exploitation:**
1. Create a PHP shell
2. Rename it to `shell.php.jpg`
3. Upload it
4. The extension check sees `.jpg` and allows it
5. Apache sees `.php` in the filename and executes it as PHP
6. Access `http://localhost:8089/uploads5/shell.php.jpg?cmd=whoami`

**Flag:**
```diff
+ CATF{upload_05_obfuscated_extension_saly_3ala_ELNABY}
```

---

### Challenge 6: Polyglot Upload

**Vulnerability:** Insufficient image validation using `getimagesize()`

**Scenario:** The server uses `getimagesize()` to verify the file starts with valid image magic bytes. This function only checks the file header not the entire content. A polyglot file that starts with image bytes but contains PHP code will pass validation.

**Exploitation:**
1. Create a file that starts with GIF magic bytes: `GIF89a;`
2. Append PHP code after the magic bytes
3. Save as `shell.php`
4. Upload it
5. `getimagesize()` sees valid GIF header and accepts the file
6. Apache executes it as PHP because of the `.php` extension
7. Access `http://localhost:8089/uploads6/shell.php?cmd=whoami`

**Flag:**
```diff
+ CATF{upload_06_polyglot_upload_saly_3ala_ELNABY}
```

---

### Challenge 7: Race Condition

**Vulnerability:** Time-of-check time-of-use (TOCTOU)

**Scenario:** The file is written to disk immediately then scanned after a 1-second delay. If the scan fails the file is deleted. There is a 1-second window where the file exists on disk and can be accessed before deletion.

**Exploitation:**
1. Create a PHP shell named `shell.php`
2. Upload it
3. Immediately send multiple concurrent GET requests to `http://localhost:8089/uploads7/shell.php`
4. One of the requests will hit the file during the 1-second window before deletion
5. The shell executes before the cleanup process removes it

**Flag:**
```diff
+ CATF{upload_07_race_condition_saly_alal_ELNABY}
```

---

## Learning Objectives

After completing this lab you will understand:

- File upload validation mistakes
- MIME Type bypasses
- Extension blacklist bypass techniques
- Path Traversal in file uploads
- Double Extension attacks
- Polyglot payload construction
- TOCTOU Race Conditions
- Secure upload implementation best practices

---

## Features

- Flags appear only after actual shell execution not just upload
- Real-time challenge status tracking
- Scoring system with progress bar (0-700 points)
- Toggleable hints per challenge
- One-click lab reset
- Completion celebration page
- Login system with role-based access
- Dashboard with challenge links

---

## Architecture

```
Browser
    |
    v
Apache (Port 80)
    |
    v
PHP 8.2
    |
    v
SQLite
    |
    v
Filesystem (uploads/)
    |
    v
Flags (after execution)
```

---

## Project Structure

```
upload-lab/
├── Dockerfile
├── docker-compose.yml
├── README.md
├── LICENSE
└── src/
    ├── .htaccess
    ├── config.php
    ├── prepend.php
    ├── index.php
    ├── login.php
    ├── logout.php
    ├── dashboard.php
    ├── scenarios.php
    ├── completion.php
    ├── reset.php
    ├── style.css
    ├── upload1.php
    ├── upload2.php
    ├── upload3.php
    ├── upload4.php
    ├── upload5.php
    ├── upload6.php
    ├── upload7.php
    ├── uploads1/
    ├── uploads2/
    ├── uploads3/
    │   └── avatars/
    ├── uploads4/
    ├── uploads5/
    ├── uploads6/
    ├── uploads7/
    ├── .markers/
    └── .uploads/
```

---

## How to Defend

- Validate file extensions against a whitelist not blacklist
- Validate MIME type on the server side not client side
- Verify magic bytes for expected file types
- Store uploaded files outside the web root
- Rename uploaded files with random names
- Disable script execution in upload directories
- Use antivirus scanning for uploaded files
- Apply least privilege permissions on upload folders
- Implement rate limiting on upload endpoints

---

## Accounts

| Username | Password | Role |
|:---------|:---------|:-----|
| wiener | peter | user |
| carlos | montoya | user |
| administrator | h7Tz_Qw2mNv9 | admin |

---

## Docker Hub

Pull the latest image:

```bash
docker pull haqtor/upload-lab:v4.1
```

[Docker Hub Repository](https://hub.docker.com/r/haqtor/upload-lab)

---

## Screenshots

Add screenshots here

---

## Contributing

PRs are welcome. Feel free to open issues and contribute new challenges.

---

## License

This project is licensed under the MIT License. See [LICENSE](LICENSE) for details.

---

## Author

**Bassam Elsopky (HaQtor)**

- Junior Penetration Tester
- Bug Bounty Hunter
- [GitHub](https://github.com/belsopky)
- [LinkedIn](https://www.linkedin.com/in/bassam-elsopky-814ba1331/)
- [Docker Hub](https://hub.docker.com/r/haqtor/upload-lab)

---

If this project helped you consider giving it a star

---

## Disclaimer

This project is for educational purposes only. Do not use on any system you do not own or have explicit permission to test
