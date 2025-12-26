# Database Migration Guide
## From Old Schema (koil_form) to New Modern Schema (kovil)

This guide will help you safely migrate your existing database to the new schema without losing any data.

---

## 📋 Pre-Migration Checklist

### 1. **CRITICAL: Create a Complete Backup**

```bash
# Full database backup
mysqldump -u your_username -p koil_form > backup_$(date +%Y%m%d_%H%M%S).sql

# Or backup with compression
mysqldump -u your_username -p koil_form | gzip > backup_$(date +%Y%m%d_%H%M%S).sql.gz
```

### 2. **Test on a Copy First**

```bash
# Create a test database
mysql -u your_username -p -e "CREATE DATABASE koil_form_test;"

# Restore your backup to test database
mysql -u your_username -p koil_form_test < backup_YYYYMMDD_HHMMSS.sql

# Test migration on the test database first
mysql -u your_username -p koil_form_test < migration_old_to_new.sql
```

### 3. **Verify Current Database State**

```sql
-- Check record counts before migration
SELECT 'family' as table_name, COUNT(*) as count FROM family
UNION ALL
SELECT 'child', COUNT(*) FROM child
UNION ALL
SELECT 'book', COUNT(*) FROM book
UNION ALL
SELECT 'event', COUNT(*) FROM event
UNION ALL
SELECT 'receipt', COUNT(*) FROM receipt
UNION ALL
SELECT 'matrimony', COUNT(*) FROM matrimony
UNION ALL
SELECT 'users', COUNT(*) FROM users;
```

### 4. **Schedule Downtime**

- Put your application in maintenance mode
- Inform users about the scheduled maintenance
- Ensure no one is accessing the database during migration

---

## 🚀 Migration Process

### Step 1: Stop Your Application

```bash
# If using systemd
sudo systemctl stop your-app-name

# Or if using Apache/PHP
sudo systemctl stop apache2
# or
sudo systemctl stop nginx
```

### Step 2: Run the Migration Script

```bash
# Navigate to the db folder
cd /Users/apple/development/php/kovilapp/db

# Execute the migration
mysql -u your_username -p koil_form < migration_old_to_new.sql
```

### Step 3: Verify Migration Success

```sql
-- Connect to your database
mysql -u your_username -p koil_form

-- Check if new tables exist
SHOW TABLES;

-- Should see these new tables:
-- - ftree
-- - subscription_events
-- - receipt_books
-- - member_subscriptions
-- - receipt_details

-- Verify data counts
SELECT * FROM migration_summary;

-- Check family table modifications
DESCRIBE family;

-- Verify new columns exist
SELECT COUNT(*) FROM family WHERE same_as_permanent IS NOT NULL;
SELECT COUNT(*) FROM family WHERE ftree_id IS NOT NULL;
```

### Step 4: Test Your Application

1. **Test User Login**
   - Try logging in with existing user accounts
   - Verify role-based access works

2. **Test Family Management**
   - View family records
   - Check that all data is visible
   - Verify member details display correctly

3. **Test Child Records**
   - View child information
   - Ensure all child data is intact

4. **Test Subscription System**
   - Check if old events were migrated
   - Verify receipt books were created
   - Test creating new subscriptions

5. **Test Matrimony Module**
   - View matrimony profiles
   - Ensure all data is preserved

### Step 5: Regenerate Family Trees

```bash
# Access your application
# Navigate to the family tree regeneration feature
# This will populate the new ftree table
```

---

## 📊 What Changed in the Migration

### New Tables Created
1. **`ftree`** - Stores family tree structures
2. **`subscription_events`** - Modern event management (replaces `event`)
3. **`receipt_books`** - Book management system (replaces `book`)
4. **`member_subscriptions`** - Links members to events with subscriptions
5. **`receipt_details`** - Detailed receipt information (replaces `receipt`)

### Tables Modified
1. **`family`**
   - Added: `same_as_permanent` (for address management)
   - Added: `ftree_id` (links to family tree)
   - Added: `w_education_details`, `w_occupation_details`
   - Removed: `_2000_bk_no`, `_2000_rc_no`
   - Changed: Most fields now allow NULL (more flexible)

2. **`child`**
   - Removed: `c_dob_old` (old date format field)
   - Updated: Character set improvements

3. **`users`**
   - Updated: Character set from utf8mb3 to utf8

### Tables Removed (with backup)
- ❌ `book` → Backed up to `_backup_book`
- ❌ `event` → Backed up to `_backup_event`
- ❌ `receipt` → Backed up to `_backup_receipt`
- ❌ `matrimonyold` → Removed (was already a backup table)
- ❌ `trust` → Removed (functionality integrated elsewhere)

### Data Migration
- ✅ All `event` records → `subscription_events`
- ✅ All `book` records → `receipt_books`
- ⚠️ `receipt` records → Need manual review (backed up to `_backup_receipt`)

---

## 🔍 Post-Migration Tasks

### 1. Review Legacy Receipts

The old `receipt` table data has been preserved in `_backup_receipt` but needs manual review:

```sql
-- View legacy receipts that need attention
SELECT * FROM _backup_receipt;

-- These receipts need to be manually linked to:
-- - Appropriate members (member_id)
-- - Appropriate events (event_id)
-- - Receipt books (book_id)
```

### 2. Clean Up Backup Tables (After Verification)

Once you've verified everything works correctly (wait at least 1-2 weeks):

```sql
-- Drop backup tables
DROP TABLE IF EXISTS _backup_book;
DROP TABLE IF EXISTS _backup_event;
DROP TABLE IF EXISTS _backup_receipt;
```

### 3. Update Application Configuration

Ensure your application code is updated to use the new schema:

```php
// Example: Update queries that referenced old tables
// OLD: SELECT * FROM event
// NEW: SELECT * FROM subscription_events

// OLD: SELECT * FROM book
// NEW: SELECT * FROM receipt_books
```

---

## 🆘 Troubleshooting

### Migration Failed Midway

```bash
# Restore from backup
mysql -u your_username -p koil_form < backup_YYYYMMDD_HHMMSS.sql

# Review error messages
# Fix any issues
# Run migration again
```

### Foreign Key Constraint Errors

```sql
-- Check for orphaned records
SELECT * FROM member_subscriptions ms
LEFT JOIN family f ON ms.member_id = f.id
WHERE f.id IS NULL;

-- Fix orphaned records before re-running migration
```

### Character Set Issues

```sql
-- If you see character encoding issues
ALTER DATABASE koil_form CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Then re-run migration
```

### Missing Data

```sql
-- Compare counts before and after
-- Check backup tables
SELECT COUNT(*) FROM _backup_book;
SELECT COUNT(*) FROM receipt_books;

-- If counts don't match, investigate
```

---

## 📞 Verification Queries

### Complete Data Integrity Check

```sql
-- 1. Verify all families migrated
SELECT COUNT(*) as family_count FROM family;

-- 2. Verify children are linked
SELECT COUNT(*) as child_count FROM child;
SELECT COUNT(*) as orphaned_children 
FROM child c 
LEFT JOIN family f ON c.fam_id = f.id 
WHERE f.id IS NULL;

-- 3. Verify events migrated
SELECT COUNT(*) as old_events FROM _backup_event;
SELECT COUNT(*) as new_events FROM subscription_events;

-- 4. Verify books migrated
SELECT COUNT(*) as old_books FROM _backup_book;
SELECT COUNT(*) as new_books FROM receipt_books;

-- 5. Check new table structure
SHOW CREATE TABLE subscription_events;
SHOW CREATE TABLE receipt_books;
SHOW CREATE TABLE member_subscriptions;

-- 6. Verify foreign keys
SELECT 
    TABLE_NAME,
    COLUMN_NAME,
    CONSTRAINT_NAME,
    REFERENCED_TABLE_NAME,
    REFERENCED_COLUMN_NAME
FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
WHERE REFERENCED_TABLE_SCHEMA = 'koil_form'
AND TABLE_NAME IN ('member_subscriptions', 'receipt_books', 'receipt_details');
```

---

## 🔄 Rollback Procedure

If you need to rollback to the old schema:

```bash
# Stop your application
sudo systemctl stop your-app-name

# Restore from backup
mysql -u your_username -p koil_form < backup_YYYYMMDD_HHMMSS.sql

# Start your application
sudo systemctl start your-app-name
```

---

## ✅ Final Checklist

After migration, verify:

- [ ] All tables exist (run `SHOW TABLES;`)
- [ ] Record counts match expectations
- [ ] User login works
- [ ] Family records display correctly
- [ ] Child records are linked properly
- [ ] Matrimony profiles are intact
- [ ] Subscription system is functional
- [ ] Reports generate correctly
- [ ] No application errors in logs
- [ ] Backup files are safely stored
- [ ] Documentation is updated

---

## 📝 Support Information

### Common Issues and Solutions

1. **"Table already exists" errors**
   - The script uses `IF NOT EXISTS` - this is normal
   - Migration can be re-run safely

2. **Character set warnings**
   - These are usually safe to ignore
   - Data integrity is preserved

3. **Foreign key constraint failures**
   - Indicates data integrity issues
   - Review and clean data before re-running

### Database Schema Differences Summary

| Feature | Old Schema | New Schema |
|---------|-----------|------------|
| Events | `event` table | `subscription_events` (enhanced) |
| Books | `book` table | `receipt_books` (with event linking) |
| Receipts | `receipt` table | `receipt_details` + `member_subscriptions` |
| Family Tree | Not structured | `ftree` table (structured JSON) |
| Address Management | Manual | `same_as_permanent` flag |
| Foreign Keys | None | Full referential integrity |
| Character Set | utf8mb3 | utf8 / utf8mb4 |

---

## 🎯 Success Criteria

Your migration is successful when:

1. ✅ All data from old tables is preserved
2. ✅ New tables are created and populated
3. ✅ Application works without errors
4. ✅ Users can log in and access their data
5. ✅ Reports and exports function correctly
6. ✅ No data loss occurred
7. ✅ Performance is maintained or improved

---

**Need Help?** Review the comments in `migration_old_to_new.sql` for detailed technical information about each migration step.

