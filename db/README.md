# Database Migration Package

This folder contains everything you need to safely migrate your database from the old schema (`koil_form.sql`) to the new modern schema (`kovil-new.sql`) without losing any data.

---

## 📦 Package Contents

### Schema Files
- **`koil_form.sql`** - Your current/old database schema (production)
- **`kovil-new.sql`** - The new modern database schema (target)

### Migration Scripts
1. **`migration_old_to_new.sql`** ⭐ **MAIN SCRIPT**
   - Complete migration from old to new schema
   - Preserves all data
   - Creates backup tables
   - Adds foreign key constraints
   - **Run this to upgrade your database**

2. **`verify_migration.sql`** 🔍
   - Comprehensive verification report
   - Run BEFORE migration (save output)
   - Run AFTER migration (compare output)
   - Checks data integrity, table structure, and migration status

3. **`rollback_migration.sql`** ⚠️
   - Emergency rollback script
   - Reverts to old schema if needed
   - Use only if migration fails

### Documentation
1. **`MIGRATION_GUIDE.md`** 📖
   - Detailed step-by-step guide
   - Troubleshooting tips
   - Testing procedures
   - Complete technical documentation

2. **`MIGRATION_CHECKLIST.md`** ✅
   - Quick reference checklist
   - Pre/post migration tasks
   - Success criteria
   - Emergency contacts template

3. **`README.md`** 📄
   - This file - overview of everything

---

## 🚀 Quick Start Guide

### 1️⃣ Backup Your Database (CRITICAL!)
```bash
cd /Users/apple/development/php/kovilapp/db
mysqldump -u YOUR_USERNAME -p koil_form > backup_$(date +%Y%m%d_%H%M%S).sql
```

### 2️⃣ Run Pre-Migration Verification
```bash
mysql -u YOUR_USERNAME -p koil_form < verify_migration.sql > verification_before.txt
```

### 3️⃣ Test on a Copy First
```bash
# Create test database
mysql -u YOUR_USERNAME -p -e "CREATE DATABASE koil_form_test;"

# Copy your data to test
mysql -u YOUR_USERNAME -p koil_form_test < backup_*.sql

# Run migration on test
mysql -u YOUR_USERNAME -p koil_form_test < migration_old_to_new.sql
```

### 4️⃣ Run Actual Migration
```bash
# Stop your application first!
mysql -u YOUR_USERNAME -p koil_form < migration_old_to_new.sql
```

### 5️⃣ Verify Migration Success
```bash
mysql -u YOUR_USERNAME -p koil_form < verify_migration.sql > verification_after.txt
diff verification_before.txt verification_after.txt
```

### 6️⃣ Test Your Application
- Login with existing accounts
- View family/member records
- Test all modules
- Check for errors

---

## 📊 What Changes in the Migration

### New Tables (Created)
| Table | Purpose | Replaces |
|-------|---------|----------|
| `ftree` | Family tree structure storage | New feature |
| `subscription_events` | Modern event management | `event` table |
| `receipt_books` | Receipt book management | `book` table |
| `member_subscriptions` | Member-Event relationships | New feature |
| `receipt_details` | Detailed receipt information | `receipt` table |

### Modified Tables
| Table | Changes |
|-------|---------|
| `family` | • Added: `same_as_permanent`, `ftree_id`, `w_education_details`, `w_occupation_details`<br>• Removed: `_2000_bk_no`, `_2000_rc_no`<br>• Most fields now allow NULL |
| `child` | • Removed: `c_dob_old`<br>• Character set updates |
| `users` | • Character set updated to utf8 |

### Removed Tables
| Table | Status |
|-------|--------|
| `book` | Backed up to `_backup_book`, then removed |
| `event` | Backed up to `_backup_event`, then removed |
| `receipt` | Backed up to `_backup_receipt`, then removed |
| `trust` | Removed (functionality integrated) |
| `matrimonyold` | Removed (was already a backup table) |

### New Features
- ✅ Foreign key constraints for data integrity
- ✅ Better character encoding (utf8/utf8mb4)
- ✅ More flexible schema (NULL values allowed)
- ✅ Structured family tree storage
- ✅ Enhanced subscription management system

---

## ⚠️ Important Warnings

### DO NOT:
- ❌ Run migration without backing up first
- ❌ Run migration on production without testing first
- ❌ Delete backup files immediately after migration
- ❌ Skip the verification steps
- ❌ Continue if you see SQL errors

### DO:
- ✅ Create multiple backups
- ✅ Test on a copy of the database first
- ✅ Stop your application during migration
- ✅ Read the MIGRATION_GUIDE.md thoroughly
- ✅ Keep backup tables for at least 2 weeks

---

## 🔄 Migration Process Overview

```
┌─────────────────┐
│   1. BACKUP     │ ← MOST IMPORTANT!
└────────┬────────┘
         │
┌────────▼────────┐
│   2. VERIFY     │ ← Run verify_migration.sql
└────────┬────────┘
         │
┌────────▼────────┐
│   3. TEST       │ ← Test on copy first!
└────────┬────────┘
         │
┌────────▼────────┐
│  4. MIGRATE     │ ← Run migration_old_to_new.sql
└────────┬────────┘
         │
┌────────▼────────┐
│  5. VERIFY      │ ← Verify success
└────────┬────────┘
         │
┌────────▼────────┐
│   6. TEST APP   │ ← Test application thoroughly
└────────┬────────┘
         │
    ┌────▼────┐
    │ SUCCESS │
    └─────────┘
```

---

## 🆘 Emergency Procedures

### If Migration Fails:

**Option 1: Use Rollback Script**
```bash
mysql -u YOUR_USERNAME -p koil_form < rollback_migration.sql
```

**Option 2: Restore from Backup**
```bash
mysql -u YOUR_USERNAME -p koil_form < backup_YYYYMMDD_HHMMSS.sql
```

---

## 📋 Recommended Reading Order

1. **Start here:** `README.md` (this file)
2. **Read next:** `MIGRATION_CHECKLIST.md` (quick reference)
3. **Deep dive:** `MIGRATION_GUIDE.md` (detailed guide)
4. **When ready:** Execute migration scripts

---

## 🔍 Quick Health Check

Run these commands to verify current state:

```sql
-- Check which schema you're on
SELECT CASE 
    WHEN EXISTS (SELECT 1 FROM information_schema.tables 
                 WHERE table_schema = DATABASE() AND table_name = 'book')
    THEN 'OLD SCHEMA (Pre-Migration)'
    WHEN EXISTS (SELECT 1 FROM information_schema.tables 
                 WHERE table_schema = DATABASE() AND table_name = 'subscription_events')
    THEN 'NEW SCHEMA (Post-Migration)'
    ELSE 'UNKNOWN'
END as 'Current Schema Status';

-- Count your records
SELECT 
    (SELECT COUNT(*) FROM family) as families,
    (SELECT COUNT(*) FROM child) as children,
    (SELECT COUNT(*) FROM users) as users,
    (SELECT COUNT(*) FROM matrimony) as matrimony_profiles;
```

---

## 📞 Support & Help

### Getting Help

1. **Read the documentation:**
   - Start with `MIGRATION_GUIDE.md`
   - Check troubleshooting section

2. **Review the scripts:**
   - SQL scripts have detailed comments
   - Explains what each step does

3. **Check verification output:**
   - `verify_migration.sql` shows detailed status
   - Compare before/after reports

### Common Questions

**Q: How long does migration take?**  
A: Depends on data volume. Usually 5-30 minutes for small to medium databases.

**Q: Will I lose any data?**  
A: No, all data is preserved and backed up. The script creates backup tables.

**Q: Can I undo the migration?**  
A: Yes, use `rollback_migration.sql` or restore from backup.

**Q: Do I need to update my application code?**  
A: Yes, code that references old tables (book, event, receipt) needs updating.

**Q: What if I see errors during migration?**  
A: Stop immediately, restore from backup, review errors, and fix before retrying.

---

## ✨ Features of This Migration Package

- ✅ **Safe:** Creates backups before any destructive operations
- ✅ **Verified:** Includes comprehensive verification scripts
- ✅ **Reversible:** Rollback script and backup files available
- ✅ **Well-documented:** Detailed guides and inline comments
- ✅ **Tested:** Designed to handle edge cases and errors gracefully
- ✅ **Data-preserving:** All existing data is migrated or backed up

---

## 📝 Version Information

- **Created:** December 23, 2025
- **Old Schema:** koil_form.sql (Legacy)
- **New Schema:** kovil-new.sql (Modern)
- **Migration Script Version:** 1.0
- **Compatibility:** MariaDB 10.x, MySQL 5.7+

---

## 🎯 Next Steps

1. [ ] Read this README completely
2. [ ] Review MIGRATION_CHECKLIST.md
3. [ ] Read MIGRATION_GUIDE.md thoroughly
4. [ ] Create database backup
5. [ ] Test on a copy of your database
6. [ ] Execute migration on production
7. [ ] Verify and test thoroughly
8. [ ] Monitor for 1-2 weeks
9. [ ] Clean up backup tables

---

## 📄 File Summary

```
db/
├── koil_form.sql                  # Old schema (current production)
├── kovil-new.sql                  # New schema (target)
├── migration_old_to_new.sql       # ⭐ Main migration script
├── verify_migration.sql           # 🔍 Verification script
├── rollback_migration.sql         # ⚠️ Emergency rollback
├── MIGRATION_GUIDE.md             # 📖 Detailed documentation
├── MIGRATION_CHECKLIST.md         # ✅ Quick reference
└── README.md                      # 📄 This file
```

---

**Ready to migrate?** Start with the **MIGRATION_CHECKLIST.md** for a step-by-step guide!

**Questions?** Read **MIGRATION_GUIDE.md** for detailed information and troubleshooting.

**Good luck with your migration! 🚀**




