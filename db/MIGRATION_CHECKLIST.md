# Database Migration Quick Checklist

## 📁 Files Provided

1. **migration_old_to_new.sql** - Main migration script
2. **verify_migration.sql** - Verification script (run before/after)
3. **rollback_migration.sql** - Emergency rollback script
4. **MIGRATION_GUIDE.md** - Detailed documentation
5. **MIGRATION_CHECKLIST.md** - This file

---

## ✅ Pre-Migration Steps

### 1. Backup (CRITICAL!)
```bash
cd /Users/apple/development/php/kovilapp/db
mysqldump -u YOUR_USERNAME -p koil_form > backup_$(date +%Y%m%d_%H%M%S).sql
```
- [ ] Database backup created
- [ ] Backup file size verified (should be > 0 bytes)
- [ ] Backup stored in safe location
- [ ] Test restoration capability verified

### 2. Pre-Migration Verification
```bash
mysql -u YOUR_USERNAME -p koil_form < verify_migration.sql > verification_before.txt
```
- [ ] Verification report generated
- [ ] Record counts noted
- [ ] No critical data integrity issues found

### 3. Test Environment
```bash
# Create test database
mysql -u YOUR_USERNAME -p -e "CREATE DATABASE koil_form_test;"
mysql -u YOUR_USERNAME -p koil_form_test < backup_*.sql
mysql -u YOUR_USERNAME -p koil_form_test < migration_old_to_new.sql
```
- [ ] Test database created
- [ ] Migration tested successfully
- [ ] Application tested on test database
- [ ] No errors encountered

### 4. Application Preparation
- [ ] Application put in maintenance mode
- [ ] Users notified of downtime
- [ ] All active sessions closed
- [ ] Web server stopped

---

## 🚀 Migration Steps

### Step 1: Execute Migration
```bash
mysql -u YOUR_USERNAME -p koil_form < migration_old_to_new.sql
```
- [ ] Migration script executed
- [ ] No errors displayed
- [ ] Success message shown

### Step 2: Post-Migration Verification
```bash
mysql -u YOUR_USERNAME -p koil_form < verify_migration.sql > verification_after.txt
```
- [ ] Post-migration verification completed
- [ ] Schema status shows "POST_MIGRATION"
- [ ] Record counts match expectations

### Step 3: Compare Results
```bash
diff verification_before.txt verification_after.txt
```
- [ ] Before/after comparison reviewed
- [ ] Data counts are consistent
- [ ] New tables exist
- [ ] Old tables removed or backed up

---

## 🧪 Testing Checklist

### Database Level
- [ ] All new tables exist (ftree, subscription_events, receipt_books, member_subscriptions, receipt_details)
- [ ] Old tables removed (book, event, receipt, trust, matrimonyold)
- [ ] Backup tables exist (_backup_book, _backup_event, _backup_receipt)
- [ ] Foreign key constraints created
- [ ] Indexes created correctly

### Application Level
- [ ] Application starts without errors
- [ ] User login works
- [ ] Family records display correctly
- [ ] Child records linked properly
- [ ] Member search functions
- [ ] Matrimony module works
- [ ] Subscription/event system functions
- [ ] Reports generate correctly
- [ ] No PHP errors in logs

### Data Integrity
- [ ] No orphaned child records
- [ ] Family relationships intact
- [ ] All user accounts accessible
- [ ] Matrimony profiles complete
- [ ] Subscription data migrated
- [ ] No missing critical data

---

## 📊 Quick Verification Queries

Run these in MySQL to spot-check:

```sql
-- Check new tables exist
SHOW TABLES LIKE 'subscription_events';
SHOW TABLES LIKE 'receipt_books';
SHOW TABLES LIKE 'ftree';

-- Check old tables removed
SHOW TABLES LIKE 'book';  -- Should be empty
SHOW TABLES LIKE 'event'; -- Should be empty

-- Check data counts
SELECT COUNT(*) FROM family;
SELECT COUNT(*) FROM child;
SELECT COUNT(*) FROM subscription_events;
SELECT COUNT(*) FROM receipt_books;

-- Check new columns
DESCRIBE family;  -- Look for same_as_permanent and ftree_id

-- Check for orphaned records
SELECT COUNT(*) FROM child c 
LEFT JOIN family f ON c.fam_id = f.id 
WHERE f.id IS NULL;  -- Should be 0
```

---

## ⚠️ If Something Goes Wrong

### Option 1: Use Rollback Script
```bash
mysql -u YOUR_USERNAME -p koil_form < rollback_migration.sql
```
- [ ] Rollback executed
- [ ] Old schema restored
- [ ] Application tested
- [ ] Issue documented

### Option 2: Restore from Backup
```bash
mysql -u YOUR_USERNAME -p koil_form < backup_YYYYMMDD_HHMMSS.sql
```
- [ ] Backup restored
- [ ] Data verified
- [ ] Application tested
- [ ] Root cause identified

---

## 🎯 Success Criteria

Migration is successful when ALL of these are true:

- ✅ No SQL errors during migration
- ✅ All expected tables exist
- ✅ Record counts match (family, child, users, matrimony)
- ✅ Foreign key constraints in place
- ✅ Application starts without errors
- ✅ Users can log in
- ✅ All modules function correctly
- ✅ No data loss detected
- ✅ Reports generate correctly
- ✅ Backup tables preserved for safety

---

## 📝 Post-Migration Tasks (Within 1 Week)

- [ ] Monitor application logs for errors
- [ ] Collect user feedback
- [ ] Regenerate family trees (populate ftree table)
- [ ] Review and migrate legacy receipts from _backup_receipt
- [ ] Update application documentation
- [ ] Train users on any new features

---

## 🧹 Cleanup Tasks (After 2 Weeks)

Only do this after confirming everything works perfectly:

```sql
-- Drop backup tables
DROP TABLE IF EXISTS _backup_book;
DROP TABLE IF EXISTS _backup_event;
DROP TABLE IF EXISTS _backup_receipt;
```

- [ ] Backup tables dropped
- [ ] Old backup files archived
- [ ] Documentation updated

---

## 📞 Emergency Contacts

**Before migration, fill in:**

- Database Admin: ____________________
- Application Developer: ____________________
- Hosting Provider: ____________________
- Backup Location: ____________________

---

## 📌 Important Notes

1. **DO NOT** delete backup files immediately after migration
2. **DO NOT** skip testing on a test database first
3. **DO** monitor the application closely for 1-2 weeks
4. **DO** keep multiple backup copies
5. **DO** document any issues encountered

---

## 🔍 Common Issues & Solutions

### Issue: Foreign key constraint errors
**Solution:** Check for orphaned records before migration

### Issue: Character encoding problems
**Solution:** Ensure database uses utf8mb4_unicode_ci

### Issue: Missing data after migration
**Solution:** Check backup tables (_backup_*)

### Issue: Application errors after migration
**Solution:** Update application code to use new table names

### Issue: Slow performance
**Solution:** Run OPTIMIZE TABLE on all tables after migration

---

## ✨ Migration Status

**Migration Date:** _________________  
**Executed By:** _________________  
**Status:** [ ] Success [ ] Failed [ ] Rolled Back  
**Notes:**

_____________________________________________
_____________________________________________
_____________________________________________

---

**END OF CHECKLIST**

For detailed information, refer to `MIGRATION_GUIDE.md`

