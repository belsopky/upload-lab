
cd /home/haqtor/Downloads/upload-lab
cat > README.md << 'EOF'
# UploadLab

Professional CTF training lab for File Upload Vulnerabilities

## Quick Start

### Using Docker Hub

```bash
docker run -d -p 8089:80 haqtor/upload-lab:v4.1
```

Open http://localhost:8089

### Build Locally

```bash
git clone https://github.com/belsopky/upload-lab.git
cd upload-lab
docker-compose up --build -d
```

## Challenges

| # | Challenge | Points | Difficulty |
|---|-----------|--------|------------|
| 1 | Unrestricted Upload | 100 | Easy |
| 2 | Content-Type Bypass | 100 | Easy |
| 3 | Path Traversal | 100 | Medium |
| 4 | Blacklist Bypass Case | 100 | Medium |
| 5 | Obfuscated Extension | 100 | Medium |
| 6 | Polyglot Upload | 100 | Hard |
| 7 | Race Condition | 100 | Hard |

## Features

- Flag appears after actual shell execution not just upload
- Challenge status tracking (Not Started / In Progress / Completed)
- Scoring system (700 points total)
- Hints system with toggleable hints
- Reset lab functionality
- Completion page

## Accounts

| Username | Password | Role |
|----------|----------|------|
| wiener | peter | user |
| carlos | montoya | user |
| administrator | h7Tz_Qw2mNv9 | admin |

## Stack

- PHP 8.2
- SQLite
- Apache
- Docker

## Docker Hub

```bash
docker pull haqtor/upload-lab:v4.1
```

https://hub.docker.com/r/haqtor/upload-lab

## Author

Bassam Elsopky (HaQtor)
EOF

git add README.md
git commit -m "Update README with professional format"
git push
```

---. 
