# Generic Member Grouping System

## Overview

Implement a flexible member grouping system that allows admins to create custom grouping types (e.g., Region, Zone, Chapter, Committee) and assign members to groups. Keep the existing Kattalai field unchanged.

---

## 1. Database Schema

Create three new tables:

### `group_types` - Define grouping categories

```sql
CREATE TABLE `group_types` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(100) NOT NULL,
    `slug` VARCHAR(50) NOT NULL,
    `description` VARCHAR(255) DEFAULT NULL,
    `is_active` TINYINT(1) DEFAULT 1,
    `is_required` TINYINT(1) DEFAULT 0,
    `display_order` INT DEFAULT 0,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

### `groups` - Define actual groups for each group type

```sql
CREATE TABLE `groups` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `group_type_id` INT(11) NOT NULL,
    `name` VARCHAR(100) NOT NULL,
    `code` VARCHAR(20) DEFAULT NULL,
    `description` VARCHAR(255) DEFAULT NULL,
    `is_active` TINYINT(1) DEFAULT 1,
    `display_order` INT DEFAULT 0,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `group_type_id` (`group_type_id`),
    CONSTRAINT `fk_group_type` FOREIGN KEY (`group_type_id`) REFERENCES `group_types`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

### `member_groups` - Assign members to groups

```sql
CREATE TABLE `member_groups` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `member_id` INT(11) NOT NULL,
    `group_type_id` INT(11) NOT NULL,
    `group_id` INT(11) NOT NULL,
    `assigned_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `member_group_type` (`member_id`, `group_type_id`),
    KEY `member_id` (`member_id`),
    KEY `group_id` (`group_id`),
    CONSTRAINT `fk_member` FOREIGN KEY (`member_id`) REFERENCES `family`(`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_group` FOREIGN KEY (`group_id`) REFERENCES `groups`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

**Note:** Unique constraint on (member_id, group_type_id) ensures one group per type per member.

---

## 2. Folder Structure

```
groups/
├── index.php           # Group Types displayed as cards (entry point)
├── view.php            # Master-detail: Groups sidebar + Members panel
├── print.php           # Print member list for a group
├── export.php          # Export member list (CSV/Excel)
└── ajax/
    ├── get_members.php     # Get members for a group (AJAX)
    └── search_members.php  # Search members within a group

settings/
├── groups.php          # Admin: Manage group types and groups (all CRUD)
└── ajax/
    ├── add_group_type.php
    ├── update_group_type.php
    ├── delete_group_type.php
    ├── add_group.php
    ├── update_group.php
    ├── delete_group.php
    └── toggle_status.php
```

---

## 3. Navigation Menu

### Add "Groups" to Main Navigation

Update the main navigation menu (sidebar or header) to include:

```html
<li class="nav-item">
    <a class="nav-link" href="/groups/">
        <i class="bi bi-diagram-3"></i>
        <span>Groups</span>
    </a>
</li>
```

**Placement:** After "Members" menu item, before "Settings"

**Icon:** `bi-diagram-3` (Bootstrap Icons)

---

## 4. Groups Module UI

### 4.1 Group Types Cards (`groups/index.php`)

**URL:** `/groups/` or `/groups/index.php`

**Page Title:** Groups

**Layout:** Card-based grid (responsive: 3 columns on desktop, 2 on tablet, 1 on mobile)

**Each Group Type Card Contains:**
```
┌─────────────────────────────────────┐
│  [Icon]  Region                     │
│                                     │
│  Regional grouping of members       │
│                                     │
│  ┌─────────┐  ┌─────────────┐      │
│  │ 4       │  │ 156         │      │
│  │ Groups  │  │ Members     │      │
│  └─────────┘  └─────────────┘      │
│                                     │
│  [View Groups →]                    │
└─────────────────────────────────────┘
```

**Card Features:**
- Group type name (bold)
- Description (muted text, truncated if long)
- Stats: Number of groups | Total members across all groups
- Click anywhere on card → goes to `view.php?type_id=X`
- Hover effect for interactivity

**Empty State:**
- If no group types exist, show:
  - Friendly message: "No group types created yet"
  - "Go to Settings" button → links to `settings/groups.php`

---

### 4.2 Master-Detail View (`groups/view.php?type_id=X`)

**URL:** `/groups/view.php?type_id=X`

**Breadcrumb:** Groups > [Group Type Name]

**Layout:** Two-panel master-detail layout

```
┌─────────────────────────────────────────────────────────────────────────────┐
│  Groups > Region                                                             │
├─────────────────────┬───────────────────────────────────────────────────────┤
│                     │                                                       │
│  GROUPS             │  MEMBERS - North Region                    [Print][↓] │
│  ─────────────────  │  ─────────────────────────────────────────────────── │
│                     │                                                       │
│  ┌───────────────┐  │  45 members                                          │
│  │ ● North    45 │  │                                                       │
│  └───────────────┘  │  ┌─────────────────────────────────────────────────┐ │
│  ┌───────────────┐  │  │ 🔍 Search by name, ID, mobile...               │ │
│  │   South    38 │  │  └─────────────────────────────────────────────────┘ │
│  └───────────────┘  │                                                       │
│  ┌───────────────┐  │  ┌────────┬─────────────────┬──────────┬──────────┐ │
│  │   East     42 │  │  │ ID     │ Name            │ Mobile   │ Village  │ │
│  └───────────────┘  │  ├────────┼─────────────────┼──────────┼──────────┤ │
│  ┌───────────────┐  │  │ N001   │ Rajan Kumar     │ 98765... │ Thirun.. │ │
│  │   West     31 │  │  │ N002   │ Senthil Murugan │ 98765... │ Periya.. │ │
│  └───────────────┘  │  │ N003   │ Lakshmi Devi    │ 98765... │ Thirun.. │ │
│                     │  │ ...    │ ...             │ ...      │ ...      │ │
│                     │  └────────┴─────────────────┴──────────┴──────────┘ │
│                     │                                                       │
│                     │                              Page 1 of 3 [< 1 2 3 >] │
│                     │                                                       │
└─────────────────────┴───────────────────────────────────────────────────────┘
```

**Left Sidebar - Groups List:**
- Fixed width (250-300px)
- List all groups in this group type
- Each group shows: Name + Member count
- Selected group is highlighted
- Click group → loads members in right panel (AJAX)
- First group is auto-selected on page load

**Right Panel - Members List:**
- Shows members of selected group
- Header: Group name + member count + action buttons (Print, Export)
- Search bar for filtering members
- Sortable table with columns: ID, Name, Mobile, Village, Actions
- Pagination (using `members_per_page` setting)
- Row click → view member details (`../member/viewmember.php?id=X`)

**URL Updates:**
- When clicking a group, update URL to `view.php?type_id=X&group_id=Y`
- Allows bookmarking and direct linking to a specific group

**Empty States:**
- No groups in type: "No groups created. Go to Settings to add groups."
- No members in group: "No members assigned to this group yet."

---

### 4.3 Print View (`groups/print.php?group_id=X`)

**URL:** `/groups/print.php?group_id=X`

**Layout:** Clean, print-optimized

```
┌─────────────────────────────────────────────────────────────────┐
│                    [Organization Logo]                           │
│                    Organization Name                             │
│                                                                 │
│              Region: North - Member List                         │
│                   Generated: 28-Dec-2025                         │
├─────────────────────────────────────────────────────────────────┤
│                                                                 │
│  Total Members: 45                                              │
│                                                                 │
│  ┌────┬─────────────────┬────────────┬──────────────────┐      │
│  │ #  │ Name            │ Mobile     │ Village          │      │
│  ├────┼─────────────────┼────────────┼──────────────────┤      │
│  │ 1  │ Rajan Kumar     │ 9876543210 │ Thirunagar       │      │
│  │ 2  │ Senthil Murugan │ 9876543211 │ Periyakulam      │      │
│  │ ...│ ...             │ ...        │ ...              │      │
│  └────┴─────────────────┴────────────┴──────────────────┘      │
│                                                                 │
└─────────────────────────────────────────────────────────────────┘
```

**Features:**
- Auto-print on page load (optional)
- No navigation elements
- Organization header from settings
- Group type and group name
- Date stamp
- Page numbers for multi-page

---

### 4.4 Export (`groups/export.php?group_id=X&format=csv|excel`)

**URL:** `/groups/export.php?group_id=X&format=csv`

**Formats:**
- `csv` - Comma-separated values
- `excel` - Excel format (.xlsx)

**Columns:** Member ID, Name, Father Name, Mobile, Village, etc.

**Filename:** `{GroupTypeName}_{GroupName}_Members_{Date}.csv`

---

## 5. Settings: Manage Groups (`settings/groups.php`)

**URL:** `/settings/groups.php`

**Access:** Admin only

**Link from Settings Index:** Add a card in `/settings/index.php`:
```
┌─────────────────────────────────────┐
│  [Icon] Member Groups               │
│                                     │
│  Manage group types and groups      │
│  for organizing members             │
│                                     │
│  [Manage →]                         │
└─────────────────────────────────────┘
```

**Layout:** Drill-down navigation (two views in single file)

---

### 5.1 View 1: Group Types List (`settings/groups.php`)

**Page Title:** Manage Member Groups

**Layout:**
```
┌─────────────────────────────────────────────────────────────────────┐
│  Manage Member Groups                              [+ Add Type]     │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│  ┌────┬──────────┬──────────┬────────┬──────────┬────────┬───────┐ │
│  │ #  │ Name     │ Slug     │ Groups │ Required │ Status │Actions│ │
│  ├────┼──────────┼──────────┼────────┼──────────┼────────┼───────┤ │
│  │ 1  │ Region → │ region   │ 4      │ Yes      │ 🟢     │ [✎][🗑]│ │
│  │ 2  │ Zone →   │ zone     │ 3      │ No       │ 🟢     │ [✎][🗑]│ │
│  │ 3  │ Committee│ committee│ 5      │ No       │ ⚪     │ [✎][🗑]│ │
│  └────┴──────────┴──────────┴────────┴──────────┴────────┴───────┘ │
│                                                                     │
│  💡 Click on group type name to manage its groups                   │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

**Table Columns:**
- **#** - Row number
- **Name** - Group type name (clickable → navigates to groups list)
- **Slug** - URL-friendly identifier
- **Groups** - Number of groups under this type
- **Required** - Yes/No (whether members must be assigned)
- **Status** - 🟢 Active / ⚪ Inactive (visual indicator)
- **Actions** - [✎] Edit | [🗑] Delete

**Click Behavior:**
- Clicking on group type name → navigates to `settings/groups.php?type_id=X`

---

### 5.2 View 2: Groups List (`settings/groups.php?type_id=X`)

**Page Title:** [Group Type Name] Groups

**Layout:**
```
┌─────────────────────────────────────────────────────────────────────┐
│  ← Back to Group Types                                              │
│                                                                     │
│  Region Groups                                      [+ Add Group]   │
│  Manage groups under "Region" type                                  │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│  ┌────┬──────────┬──────┬─────────┬────────┬───────────────────┐   │
│  │ #  │ Name     │ Code │ Members │ Status │ Actions           │   │
│  ├────┼──────────┼──────┼─────────┼────────┼───────────────────┤   │
│  │ 1  │ North    │ N    │ 45      │ 🟢     │ [✎] [🗑]          │   │
│  │ 2  │ South    │ S    │ 38      │ 🟢     │ [✎] [🗑]          │   │
│  │ 3  │ East     │ E    │ 42      │ 🟢     │ [✎] [🗑]          │   │
│  │ 4  │ West     │ W    │ 31      │ 🟢     │ [✎] [🗑]          │   │
│  └────┴──────────┴──────┴─────────┴────────┴───────────────────┘   │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

**Header Elements:**
- Back link → returns to group types list
- Group type name as title
- Subtitle: "Manage groups under [Type] type"
- [+ Add Group] button

**Table Columns:**
- **#** - Row number
- **Name** - Group name
- **Code** - Short code (optional)
- **Members** - Number of members assigned to this group
- **Status** - 🟢 Active / ⚪ Inactive
- **Actions** - [✎] Edit | [🗑] Delete

---

### 5.3 Navigation Flow

```
settings/groups.php
    │
    ├── Click "Region" ──────────► settings/groups.php?type_id=1
    │                                      │
    │                                      └── Click "← Back" ──► settings/groups.php
    │
    ├── Click "Zone" ────────────► settings/groups.php?type_id=2
    │
    └── Click "Committee" ───────► settings/groups.php?type_id=3
```

---

### 5.4 Add/Edit Group Type Modal

```
┌─────────────────────────────────────┐
│ Add Group Type              [×]     │
├─────────────────────────────────────┤
│                                     │
│ Name *                              │
│ [________________________]          │
│                                     │
│ Slug *                              │
│ [________________________]          │
│ (auto-generated from name)          │
│                                     │
│ Description                         │
│ [________________________]          │
│                                     │
│ ☐ Required for all members          │
│                                     │
│ Display Order                       │
│ [_1_]                               │
│                                     │
│         [Cancel]  [Save]            │
└─────────────────────────────────────┘
```

---

### 5.5 Add/Edit Group Modal

```
┌─────────────────────────────────────┐
│ Add Group                   [×]     │
├─────────────────────────────────────┤
│                                     │
│ Name *                              │
│ [________________________]          │
│                                     │
│ Code                                │
│ [____]                              │
│                                     │
│ Description                         │
│ [________________________]          │
│                                     │
│ Display Order                       │
│ [_1_]                               │
│                                     │
│         [Cancel]  [Save]            │
└─────────────────────────────────────┘
```

**Note:** Group Type is not shown in modal since we're already in a specific type's context.

---

### 5.6 Delete Validation (Safest Approach)

**Delete Group:**
- ✅ **Allowed** if group has 0 members
- ❌ **Blocked** if group has members assigned

```
┌─────────────────────────────────────────┐
│  ⚠️ Cannot Delete                  [×]  │
├─────────────────────────────────────────┤
│                                         │
│  This group has 45 members assigned.    │
│                                         │
│  Please unassign all members first      │
│  before deleting this group.            │
│                                         │
│                          [OK]           │
└─────────────────────────────────────────┘
```

**Delete Group Type:**
- ✅ **Allowed** if type has 0 groups
- ❌ **Blocked** if type has groups (even empty groups)

```
┌─────────────────────────────────────────┐
│  ⚠️ Cannot Delete                  [×]  │
├─────────────────────────────────────────┤
│                                         │
│  This group type has 4 groups.          │
│                                         │
│  Please delete all groups first         │
│  before deleting this group type.       │
│                                         │
│                          [OK]           │
└─────────────────────────────────────────┘
```

**Deletion Flow:**
```
To delete a Group Type with members:

1. Unassign all members from each group
   (via member edit page or bulk unassign)
       ↓
2. Delete each group (now allowed - 0 members)
       ↓
3. Delete group type (now allowed - 0 groups)
```

**PHP Validation Logic:**
```php
// Before deleting a group
function can_delete_group($group_id) {
    $count = get_group_member_count($group_id);
    return $count === 0;
}

// Before deleting a group type
function can_delete_group_type($group_type_id) {
    $groups = get_groups($group_type_id, false); // include inactive
    return count($groups) === 0;
}
```

---

## 6. PHP Helper Functions (function.php)

```php
// Get all group types
get_group_types($active_only = true)

// Get groups for a specific group type
get_groups($group_type_id, $active_only = true)

// Get a single group by ID
get_group($group_id)

// Get a single group type by ID
get_group_type($group_type_id)

// Get all groups assigned to a member
get_member_groups($member_id)

// Assign or update a member's group
assign_member_to_group($member_id, $group_type_id, $group_id)

// Remove a member from a group type
remove_member_from_group($member_id, $group_type_id)

// Get all members in a specific group (with pagination)
get_members_by_group($group_id, $page = 1, $per_page = 25, $search = '')

// Get member count for a group
get_group_member_count($group_id)

// Get member counts for all groups in a group type
get_group_member_counts($group_type_id)

// Get total members across all groups in a type
get_group_type_total_members($group_type_id)

// CRUD operations
add_group_type($data)
update_group_type($id, $data)
delete_group_type($id)
add_group($data)
update_group($id, $data)
delete_group($id)
toggle_group_status($id)
toggle_group_type_status($id)
```

---

## 7. Integration with Members Module

These updates are made to existing files in the `member/` folder:

### `member/viewmember.php`
- Display member's group assignments in the profile section
- Show group type name and assigned group (as badge or label)
- "Edit Groups" button → opens modal to change assignments
- Links to group pages in Groups module

### `member/addmember.php`
- Add dropdown for each active group type
- Mark required groups with asterisk
- Validate required groups on submission
- Save group assignments after member is created

### `member/updatemember.php`
- Show current group assignments
- Allow editing via dropdowns
- Save changes to member_groups table

---

## 8. Config Update

Add to `config.php`:

```php
// Group Tables
$tbl_group_types = 'group_types';
$tbl_groups = 'groups';
$tbl_member_groups = 'member_groups';
```

---

## 9. Features Summary

| Feature | Description |
|---------|-------------|
| Dedicated Groups Menu | Separate navigation item for Groups module |
| Group Types Cards | Card-based entry showing all group categories |
| Master-Detail View | Groups sidebar + Members panel in single page |
| Print Support | Print-optimized member lists per group |
| Export Support | Export to Excel/CSV per group |
| Settings Management | All group type/group CRUD in Settings page |
| Member Assignment | Assign members via dropdowns in member forms |
| Unlimited Types | Create any number of grouping categories |
| Unlimited Groups | Each type can have unlimited groups |
| One Group Per Type | Members assigned to one group per type |
| Required/Optional | Groups can be required or optional |
| Active/Inactive | Deactivate without deleting |

---

## 10. Important Notes

### Keep Existing System
- Do **NOT** modify the existing `family.kattalai` field
- Kattalai continues to work as before
- New grouping system is independent/additional

### Migration (Optional)
If desired in future, existing Kattalai data can be migrated to the new system:
1. Create a group type "Kattalai"
2. Import existing label values as groups
3. Migrate member assignments from `family.kattalai` to `member_groups`

---

## 11. Example Usage

### Creating Group Types

```sql
INSERT INTO group_types (name, slug, is_required, display_order) VALUES
('Region', 'region', 1, 1),
('Zone', 'zone', 0, 2),
('Committee', 'committee', 0, 3);
```

### Creating Groups

```sql
-- Regions
INSERT INTO groups (group_type_id, name, code, display_order) VALUES
(1, 'North', 'N', 1),
(1, 'South', 'S', 2),
(1, 'East', 'E', 3),
(1, 'West', 'W', 4);

-- Committees
INSERT INTO groups (group_type_id, name, code, display_order) VALUES
(3, 'Executive', 'EXEC', 1),
(3, 'Finance', 'FIN', 2),
(3, 'Events', 'EVT', 3);
```

### Assigning Members

```sql
-- Assign member 1 to North Region and Executive Committee
INSERT INTO member_groups (member_id, group_type_id, group_id) VALUES
(1, 1, 1),  -- Region: North
(1, 3, 5);  -- Committee: Executive
```

### Querying Members by Group

```sql
-- Get all members in North Region (group_id = 1)
SELECT f.* 
FROM family f
INNER JOIN member_groups mg ON f.id = mg.member_id
WHERE mg.group_id = 1
ORDER BY f.name;

-- Get member count per group for a group type
SELECT g.id, g.name, COUNT(mg.member_id) as member_count
FROM groups g
LEFT JOIN member_groups mg ON g.id = mg.group_id
WHERE g.group_type_id = 1
GROUP BY g.id, g.name
ORDER BY g.display_order;
```

---

## 12. Implementation Checklist

### Database
- [ ] Create `group_types` table
- [ ] Create `groups` table
- [ ] Create `member_groups` table
- [ ] Add table definitions to `config.php`

### Helper Functions
- [ ] Create all helper functions in `function.php`

### Settings Module
- [ ] Add "Member Groups" card to `settings/index.php`
- [ ] Create `settings/groups.php` - Group Types & Groups CRUD
- [ ] Create AJAX handlers in `settings/ajax/`

### Groups Module
- [ ] Create `groups/` folder
- [ ] Create `groups/index.php` - Group Types Cards
- [ ] Create `groups/view.php` - Master-Detail (Groups + Members)
- [ ] Create `groups/print.php` - Print View
- [ ] Create `groups/export.php` - Export CSV/Excel
- [ ] Create AJAX handlers in `groups/ajax/`

### Navigation
- [ ] Add "Groups" menu item to sidebar/header

### Member Module Integration
- [ ] Update `member/viewmember.php` to display groups
- [ ] Update `member/addmember.php` with group dropdowns
- [ ] Update `member/updatemember.php` with group editing

### Testing
- [ ] Test group type CRUD in settings
- [ ] Test group CRUD in settings
- [ ] Test member assignment
- [ ] Test group types cards page
- [ ] Test master-detail view (groups sidebar + members panel)
- [ ] Test print/export
- [ ] Test responsive layout

### Deployment
- [ ] Create SQL migration file
- [ ] Document changes
