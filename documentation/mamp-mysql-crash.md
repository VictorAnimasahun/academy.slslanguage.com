# 🔧 MAMP MySQL Crash Fix (After Improper Shutdown)

## 🆔 Issue ID
MAMP-MYSQL-CRASH-001

---

## 🧩 Problem
After an improper system shutdown, MySQL in MAMP fails to start.

### Symptoms
- Website shows: **"Database connection error"**
- MAMP dashboard:
  - Apache: ✅ Running
  - MySQL: ❌ Not starting / keeps stopping

---

## 🔍 Root Cause
MySQL (InnoDB engine) becomes corrupted due to abrupt shutdown.

Typical error in logs:
```
Neither found #innodb_redo subdirectory, nor ib_logfile* files
Failed to initialize DD Storage Engine
Data Dictionary initialization failed
```

👉 This means critical InnoDB system files are missing or corrupted.

---

## 📍 Log Location
```
/Applications/MAMP/logs/mysql_error.log
```

---

## ✅ Solution (FULL RESET — RECOMMENDED)

⚠️ This will delete all local databases unless backed up.

### Step 1: Stop MAMP completely

---

### Step 2: Backup existing database (optional but recommended)
```bash
cp -R /Applications/MAMP/db/mysql80 ~/mysql_backup
```

---

### Step 3: Remove corrupted database directory
```bash
rm -rf /Applications/MAMP/db/mysql80
```

---

### Step 4: Restore fresh MySQL system files
```bash
cp -R /Applications/MAMP/Library/share/mysql80 /Applications/MAMP/db/mysql80
```

---

### Step 5: Restart MAMP
- Open MAMP
- Click "Start Servers"

✅ MySQL should now start successfully

---

## 🟡 Optional Repair Attempt (rarely works)
```bash
cd /Applications/MAMP/db/mysql80
mkdir -p #innodb_redo
```

Then restart MAMP.

---

## 🧠 Key Insight
This issue is:
- ❌ NOT a PHP issue
- ❌ NOT a database connection issue
- ❌ NOT a port/socket issue

👉 It is a **MySQL engine corruption issue**

---

## 🛡️ Prevention
- Always stop MAMP before shutting down your system
- Avoid force shutdowns while MySQL is running

---

## 🏁 Status
✔️ Fix confirmed working

---

## 🧾 Notes
If database recovery is required:
- Use `innodb_force_recovery`
- Dump databases before resetting

(Not covered in this document)
