# Quick Fix for "Unknown column 'ftree_id'" Error

## Problem
After running the migration, you're getting this error:
```
PHP Fatal error: Unknown column 'ftree_id' in 'SELECT' in function.php:2228
```

This happens when viewing member pages because the `ftree_id` column is missing from the `family` table.

---

## Solution

Run the **complete fix script** on your production server:

### Step 1: Upload the fix script to your server

Upload `complete_migration_fix.sql` to your server's db folder.

### Step 2: Connect to your server via SSH

```bash
ssh your_username@admin.kakaverivilayankulam.com
```

### Step 3: Navigate to the db folder

```bash
cd /home/kakaveri/web/admin.kakaverivilayankulam.com/public_html/db
# Or wherever you placed the fix script
```

### Step 4: Run the fix script

```bash
mysql -u YOUR_DB_USERNAME -p koil_form < complete_migration_fix.sql
```

Enter your database password when prompted.

### Step 5: Verify the fix

You should see output like:
```
✓ Added ftree_id column
✓ Added same_as_permanent column
✓ Added index for ftree_id
✓ FIX COMPLETED SUCCESSFULLY!
```

### Step 6: Test your application

1. Go back to your browser
2. Refresh the page (Ctrl+F5 or Cmd+Shift+R)
3. Try viewing a member page
4. The error should be gone!

---

## What This Fix Does

The `complete_migration_fix.sql` script:

1. ✅ Adds the missing `ftree_id` column to the `family` table
2. ✅ Adds the `same_as_permanent` column (for address management)
3. ✅ Adds `w_education_details` column (if missing)
4. ✅ Adds `w_occupation_details` column (if missing)
5. ✅ Creates necessary indexes
6. ✅ Verifies everything was added correctly

**Safe to run:** The script checks if columns already exist before adding them, so it won't cause errors if you run it multiple times.

---

## Alternative: Quick Fix (ftree_id only)

If you just want to fix the immediate error quickly, use:

```bash
mysql -u YOUR_DB_USERNAME -p koil_form < fix_ftree_column.sql
```

This only adds the `ftree_id` column that's causing the error.

---

## Diagnostic Script

To check what's missing before running the fix:

```bash
mysql -u YOUR_DB_USERNAME -p koil_form < check_migration_completeness.sql
```

This will show you which columns and tables are missing.

---

## Why Did This Happen?

The migration script included a statement to add `ftree_id`:

```sql
ALTER TABLE `family` ADD COLUMN IF NOT EXISTS `ftree_id` int(11) DEFAULT NULL;
```

However, some older versions of MySQL/MariaDB don't support `IF NOT EXISTS` in `ALTER TABLE ADD COLUMN` statements, which may have caused this step to fail silently.

The fix scripts use a different approach that works with all MySQL/MariaDB versions.

---

## Files Provided

1. **`complete_migration_fix.sql`** ⭐ **RECOMMENDED**
   - Comprehensive fix for all potentially missing columns
   - Safe to run multiple times
   - Includes verification

2. **`fix_ftree_column.sql`**
   - Quick fix for just the ftree_id column
   - Minimal changes

3. **`check_migration_completeness.sql`**
   - Diagnostic tool
   - Shows what's missing
   - Run before/after fix

4. **`fix_missing_ftree_id.sql`**
   - Alternative simple fix
   - Uses different syntax

---

## After Running the Fix

✅ **Your member view page should work**  
✅ **No more "Unknown column 'ftree_id'" errors**  
✅ **All migration features enabled**  
✅ **Family tree functionality ready**

---

## If You Still Have Issues

1. **Verify the column was added:**
   ```sql
   mysql -u username -p koil_form
   DESCRIBE family;
   ```
   Look for `ftree_id` in the output.

2. **Check for other errors:**
   Look at your PHP error logs for any other missing columns.

3. **Clear PHP cache (if using OpCache):**
   ```bash
   sudo systemctl restart php-fpm
   # or
   sudo systemctl restart apache2
   ```

4. **Clear browser cache:**
   Hard refresh your browser (Ctrl+F5).

---

## Need Help?

If you're still seeing errors after running the fix:

1. Check the output of `check_migration_completeness.sql`
2. Look for other missing columns in the PHP error logs
3. Make sure you're running the script on the correct database

---

## Quick Command Reference

```bash
# Check what's missing
mysql -u username -p koil_form < check_migration_completeness.sql

# Run the complete fix
mysql -u username -p koil_form < complete_migration_fix.sql

# Verify it worked
mysql -u username -p koil_form -e "DESCRIBE family" | grep ftree_id

# Should output:
# ftree_id  int(11)  YES  NULL
```

---

**This fix should resolve your error immediately!** 🚀

