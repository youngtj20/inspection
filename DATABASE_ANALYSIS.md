# Vehicle Inspection Database Structure Analysis
## Nigerian States Inspection System

**Database Name:** `timo`  
**Server Version:** MySQL 8.0.36  
**PHP Version:** 7.4.33  
**Generated:** January 11, 2026

---

## 1. SYSTEM OVERVIEW

This database manages vehicle inspection operations across Nigerian states. It tracks:
- Vehicle registration and inspection records
- Technical inspection measurements (brakes, emissions, lighting, etc.)
- Personnel and equipment management
- User access control and audit logging
- Department/facility management

---

## 2. CORE ENTITY GROUPS

### 2.1 Vehicle Management Tables

#### `i_vehicle_base`
**Purpose:** Master vehicle registry  
**Key Fields:**
- `plateno` - License plate number (indexed)
- `vehicletype` - Vehicle classification (4 chars)
- `engineno`, `chassisno` - Engine and chassis identification
- `makeofvehicle`, `model` - Manufacturer details
- `licencetype` - License category (1 char)
- `owner` - Vehicle owner name
- `fueltype`, `headlampsystem`, `drivemethod` - Technical specs
- `axisnumber` - Number of axles
- `netweight`, `grossweight`, `authorizedtocarry`, `personstocarry` - Weight specs
- `registerdate`, `productdate` - Temporal data
- `odmeter` - Odometer reading

**Indexes:** Composite on `(plateno, vehicletype)`

#### `i_vehicle_register`
**Purpose:** Inspection registration records  
**Key Fields:**
- `seriesno` - Unique inspection series number (indexed)
- `inspecttimes` - Inspection sequence number
- `stationno` - Inspection station identifier
- `inspectdate` - Date of inspection (indexed)
- `inspecttype` - Type of inspection (indexed)
- `registertime` - Time of registration
- All vehicle details (denormalized from `i_vehicle_base`)
- `acceptmember` - Inspector who accepted
- `presentor` - Person presenting vehicle
- `invoiceno` - Invoice reference
- `inspectitems` - Items inspected
- `dept_id` - Department/station reference

**Indexes:** 
- `(plateno, vehicletype)`
- `(seriesno, inspecttimes)`
- `inspectdate`
- `inspecttype`
- `dept_id`

---

### 2.2 Inspection Data Tables

#### Brake System Tables
Multiple tables for different axle configurations:
- `i_data_brake_front` - Front axle brakes
- `i_data_brake_rear` - Rear axle brakes
- `i_data_brake_rear02` through `i_data_brake_rear05` - Additional rear axles
- `i_data_brake_rear06` - Alternative rear axle format (InnoDB)
- `i_data_brake_summary` - Aggregate brake test results

**Common Brake Fields:**
- `seriesno`, `inspecttimes` - Link to inspection
- `lftaxleload`, `rgtaxleload`, `axleload` - Axle weight measurements
- `lftbrakeforce`, `rgtbrakeforce` - Brake force (left/right)
- `lfthandbrake`, `rgthandbrake` - Hand brake force
- `lftfrictioneff`, `rgtfrictioneff` - Friction efficiency
- `lftbrakeeff`, `rgtbrakeeff`, `brakeeff` - Brake efficiency
- `lftbrakediff`, `rgtbrakediff`, `brakediff` - Brake differential
- `lfthandbrakeeff`, `rgthandbrakeeff`, `handbrakeeff` - Hand brake efficiency
- `lfthandbrakediff`, `rgthandbrakediff`, `handbrakediff` - Hand brake differential
- Status fields: `sts*` (pass/fail indicators)
- `dept_id` - Department reference

**Brake Summary Fields:**
- `tolbrakeeff` - Total brake efficiency
- `tolhandbrakeeff` - Total hand brake efficiency
- `tolload` - Total load

#### `i_data_gas`
**Purpose:** Emissions/exhaust gas analysis  
**Measurements (Idle & High Speed):**
- HC (Hydrocarbons): `idlhcmax`, `idlhcmin`, `idlhcaverage`, `stshghhc`
- CO (Carbon Monoxide): `idlcomax`, `idlcomin`, `idlcoaverage`, `stshghco`
- Lambda (Air-fuel ratio): `idllambdamax`, `idllambdamin`, `idllambdaaverage`, `stshghlambda`
- CO2 (Carbon Dioxide): `idlco2max`, `idlco2min`, `idlco2average`, `stshghco2`
- O2 (Oxygen): `idlo2max`, `idlo2min`, `idlo2average`, `stshgho2`
- NO (Nitrogen Oxide): `idlnomax`, `idlnomin`, `idlnoaverage`, `stshghno`

#### `i_data_headlamp_left` & `i_data_headlamp_right`
**Purpose:** Headlight alignment and intensity testing  
**Fields:**
- `height` - Lamp height
- `lightintensity` - Light output measurement
- `offsetlrfar`, `offsetlrnear` - Left-right offset (far/near)
- `offsetudfar`, `offsetudnear` - Up-down offset (far/near)
- Status fields for each measurement

#### `i_data_suspension_front` & `i_data_suspension_rear`
**Purpose:** Suspension system testing  
**Fields:**
- `lftweight`, `rgtweight` - Axle weights
- `lftsuspension`, `rgtsuspension` - Suspension force
- `suspensiondiff` - Suspension differential
- `suspensioneff` - Suspension efficiency
- Status fields

#### `i_data_smoke`
**Purpose:** Smoke/opacity emissions testing  
**Fields:**
- N-series (Smoke opacity): `n1`, `n2`, `n3`, `n4`, `naverage`, `stsn`
- K-series (Alternative measurement): `k1`, `k2`, `k3`, `k4`, `kaverage`, `stsk`

#### `i_data_speedometer`
**Purpose:** Speedometer accuracy testing  
**Fields:**
- `speed` - Measured speed
- `stsspeed` - Pass/fail status
- Multiple ID field variants (camelCase and snake_case)

#### `i_data_sideslip`
**Purpose:** Vehicle sideslip measurement  
**Fields:**
- `slide` - Sideslip measurement
- `stsslide` - Pass/fail status

#### `i_data_pit` & `i_data_visual`
**Purpose:** Defect recording (pit inspection and visual inspection)  
**Fields:**
- `defectcode` - Standardized defect code
- `category` - Defect category
- `description` - Detailed defect description (800 chars)

---

### 2.3 Facility Management Tables

#### `f_equipment_files`
**Purpose:** Equipment inventory and certification  
**Fields:**
- `name`, `type` - Equipment identification
- `manufacturer`, `model` - Equipment details
- `producerCountry`, `productDate` - Origin information
- `certificationDate` - Equipment certification date
- `dept_id` - Department owning equipment
- Timestamps: `createDate`, `updateDate`

**Note:** Contains duplicate fields with different naming conventions (camelCase vs snake_case)

#### `f_personnel_files`
**Purpose:** Staff/personnel records  
**Fields:**
- `name`, `email`, `phone` - Contact information
- `age`, `gender` - Demographics
- `education`, `jobTitle` - Professional details
- `dept_id` - Department assignment
- Timestamps: `createDate`, `updateDate`

---

### 2.4 System Administration Tables

#### `sys_user`
**Purpose:** User accounts and authentication  
**Fields:**
- `username`, `nickname` - User identification
- `password` (64 chars), `salt` - Authentication
- `dept_id` - Department assignment
- `picture` - User avatar
- `sex` (1=male, 2=female) - Gender
- `email`, `phone` - Contact
- `status` (1=normal, 2=frozen, 3=deleted)
- Timestamps: `create_date`, `update_date`

#### `sys_role`
**Purpose:** Role definitions  
**Fields:**
- `title` - Role name (Chinese)
- `name` - Role identifier
- `status` - Role status
- Audit fields: `create_by`, `update_by`, timestamps

#### `sys_user_role`
**Purpose:** User-role mapping (many-to-many)  
**Primary Key:** `(user_id, role_id)`

#### `sys_role_menu`
**Purpose:** Role-menu permissions (many-to-many)  
**Primary Key:** `(role_id, menu_id)`

#### `sys_menu`
**Purpose:** Application menu structure  
**Fields:**
- `title` - Menu name
- `pid`, `pids` - Parent menu (hierarchical)
- `url` - Navigation URL
- `perms` - Permission identifier
- `icon` - Menu icon
- `type` (1=main, 2=submenu, 3=non-menu)
- `sort` - Display order

#### `sys_dept`
**Purpose:** Department/station management  
**Fields:**
- `title` - Department name
- `pid`, `pids` - Parent department (hierarchical)
- `deptno` - Department code
- `address`, `state` - Location
- `area` - Service area
- `contactnumber`, `contacts` - Contact info
- `employees` - Staff count
- `status` (1=normal, 2=frozen, 3=deleted)
- Audit fields

#### `sys_dict`
**Purpose:** System configuration/lookup values  
**Fields:**
- `title` - Dictionary name
- `name` - Dictionary key
- `type` - Dictionary type
- `value` - Dictionary values (text)
- `status` - Active/inactive

#### `sys_file`
**Purpose:** File upload tracking  
**Fields:**
- `name`, `path` - File identification
- `mime` - MIME type
- `size` - File size
- `md5`, `sha1` - File checksums
- `create_by` - Uploader
- `create_date` - Upload timestamp

#### `sys_action_log`
**Purpose:** Audit trail for all operations  
**Fields:**
- `name` - Log name
- `type` - Log type
- `ipaddr` - IP address of operator
- `clazz`, `method` - Java class and method
- `model` - Database table affected
- `record_id` - Record ID affected
- `message` - Log message (text)
- `oper_name`, `oper_by` - Operator information
- `create_date` - Timestamp

#### `hibernate_sequence`
**Purpose:** Hibernate ORM sequence generator  
**Fields:**
- `next_val` - Next sequence value

---

## 3. DATA RELATIONSHIPS

### Foreign Key Relationships

```
sys_user (1) ──→ (many) sys_dept
sys_user (1) ──→ (many) sys_action_log
sys_user (1) ──→ (many) sys_menu (create_by)
sys_user (1) ──→ (many) sys_menu (update_by)
sys_user (1) ──→ (many) sys_role (create_by)
sys_user (1) ──→ (many) sys_role (update_by)
sys_user (1) ──→ (many) sys_dict (create_by)
sys_user (1) ──→ (many) sys_dict (update_by)
sys_user (1) ──→ (many) sys_file (create_by)
sys_user (1) ──→ (many) sys_dept (create_by)
sys_user (1) ──→ (many) sys_dept (update_by)

sys_dept (1) ──→ (many) sys_user
sys_dept (1) ──→ (many) f_equipment_files
sys_dept (1) ──→ (many) f_personnel_files
sys_dept (1) ──→ (many) i_vehicle_register
sys_dept (1) ──→ (many) i_data_base
sys_dept (1) ──→ (many) i_data_brake_* (all brake tables)
sys_dept (1) ──→ (many) i_data_gas
sys_dept (1) ──→ (many) i_data_headlamp_*
sys_dept (1) ──→ (many) i_data_suspension_*
sys_dept (1) ──→ (many) i_data_smoke
sys_dept (1) ──→ (many) i_data_speedometer
sys_dept (1) ──→ (many) i_data_pit
sys_dept (1) ──→ (many) i_data_visual
sys_dept (1) ──→ (many) i_data_sideslip

sys_role (1) ──→ (many) sys_role_menu
sys_menu (1) ──→ (many) sys_role_menu

sys_user (many) ──→ (many) sys_role (via sys_user_role)
sys_role (many) ──→ (many) sys_menu (via sys_role_menu)
```

### Inspection Data Hierarchy

```
i_vehicle_register (1) ──→ (many) i_data_base
                          ├──→ (many) i_data_brake_front
                          ├──→ (many) i_data_brake_rear*
                          ├──→ (many) i_data_gas
                          ├──→ (many) i_data_headlamp_left/right
                          ├──→ (many) i_data_suspension_front/rear
                          ├──→ (many) i_data_smoke
                          ├──→ (many) i_data_speedometer
                          ├──→ (many) i_data_pit
                          ├──→ (many) i_data_visual
                          └──→ (many) i_data_sideslip
```

---

## 4. KEY DESIGN PATTERNS

### 4.1 Naming Convention Issues
The database exhibits **inconsistent naming conventions**:
- **CamelCase:** `createDate`, `updateDate`, `plateno`, `vehicletype`
- **snake_case:** `create_date`, `update_date`, `plate_no`, `vehicle_type`

**Impact:** Many tables have duplicate columns with different naming styles (e.g., `createDate` and `create_date`).

### 4.2 Inspection Series Tracking
- `seriesno` - Unique inspection identifier
- `inspecttimes` - Inspection sequence (1st, 2nd, 3rd inspection, etc.)
- Composite index on `(seriesno, inspecttimes)` for efficient lookups

### 4.3 Status Fields
- Binary status indicators: `sts*` fields (typically "P" for pass, "F" for fail)
- Examples: `stsbrakeeff`, `stshghhc`, `stsspeed`

### 4.4 Measurement Patterns
- **Min/Max/Average:** `idlhcmin`, `idlhcmax`, `idlhcaverage`
- **Left/Right:** `lftbrakeforce`, `rgtbrakeforce`
- **Far/Near:** `offsetlrfar`, `offsetlrnear`

### 4.5 Department Hierarchy
- `sys_dept` supports hierarchical structure via `pid` (parent ID) and `pids` (all parent IDs)
- Enables multi-level organization (State → Region → Station)

### 4.6 Role-Based Access Control (RBAC)
- Users assigned to roles via `sys_user_role`
- Roles assigned to menus via `sys_role_menu`
- Enables granular permission management

---

## 5. INSPECTION WORKFLOW

### Typical Inspection Process

```
1. Vehicle Registration
   └─ i_vehicle_register (create inspection record)
   └─ i_vehicle_base (lookup/update vehicle master)

2. Visual Inspection
   └─ i_data_visual (record defects)
   └─ i_data_pit (pit inspection defects)

3. Technical Testing
   ├─ Brake Testing
   │  ├─ i_data_brake_front
   │  ├─ i_data_brake_rear*
   │  └─ i_data_brake_summary
   ├─ Emissions Testing
   │  └─ i_data_gas
   ├─ Lighting Testing
   │  ├─ i_data_headlamp_left
   │  └─ i_data_headlamp_right
   ├─ Suspension Testing
   │  ├─ i_data_suspension_front
   │  └─ i_data_suspension_rear
   ├─ Smoke Testing
   │  └─ i_data_smoke
   ├─ Speedometer Testing
   │  └─ i_data_speedometer
   └─ Sideslip Testing
      └─ i_data_sideslip

4. Inspection Conclusion
   └─ i_data_base (conclusion field)
```

---

## 6. INDEXING STRATEGY

### Primary Indexes
- All tables have PRIMARY KEY on `id`
- Composite indexes on frequently queried combinations

### Performance Indexes
| Table | Index | Purpose |
|-------|-------|---------|
| `i_data_base` | `(plateno, vehicletype)` | Vehicle lookup |
| `i_data_base` | `(seriesno, inspecttimes)` | Inspection lookup |
| `i_data_base` | `inspectdate` | Date range queries |
| `i_vehicle_register` | `(plateno, vehicletype)` | Vehicle lookup |
| `i_vehicle_register` | `(seriesno, inspecttimes)` | Inspection lookup |
| `i_vehicle_register` | `inspectdate` | Date filtering |
| `i_vehicle_register` | `inspecttype` | Inspection type filtering |
| Brake tables | `(seriesno, inspecttimes)` | Inspection data lookup |
| All inspection data | `dept_id` | Department filtering |

### Foreign Key Indexes
- All `dept_id` columns indexed for JOIN operations
- User reference columns indexed in audit tables

---

## 7. STORAGE ENGINES

- **InnoDB:** System tables, newer inspection data tables
  - Supports transactions and foreign keys
  - Used for: `sys_*`, `f_*`, `i_data_brake_rear06`, `i_data_speedometer`

- **MyISAM:** Legacy inspection data tables
  - No transaction support
  - Used for: Most `i_data_*` tables, `i_vehicle_*` tables

**Recommendation:** Migrate all MyISAM tables to InnoDB for consistency and transaction support.

---

## 8. DATA TYPES & CONSTRAINTS

### Common Data Types
- **bigint:** Primary keys, foreign keys, large numbers
- **int:** Counters, small numbers
- **varchar(255):** Standard text fields
- **varchar(30):** Codes (plate numbers, series numbers)
- **varchar(2):** Status flags
- **double:** Weight measurements
- **datetime:** Timestamps
- **text:** Long descriptions, messages

### Character Set
- **utf8mb3:** All tables (supports Chinese characters for system labels)
- **Collation:** `utf8mb3_general_ci` (case-insensitive)

---

## 9. AUDIT & LOGGING

### Audit Trail
- `sys_action_log` tracks all operations
- Fields: operator, IP address, timestamp, affected table/record, message
- Enables compliance and troubleshooting

### Timestamps
- `create_date` / `createDate` - Record creation
- `update_date` / `updateDate` - Last modification
- `inspectdate` - Inspection date
- `registerdate` - Vehicle registration date

---

## 10. IDENTIFIED ISSUES & RECOMMENDATIONS

### Critical Issues

1. **Duplicate Columns with Different Naming**
   - Problem: Tables have both camelCase and snake_case versions
   - Example: `createDate` and `create_date` in same table
   - Impact: Data inconsistency, confusion, wasted storage
   - **Fix:** Standardize on snake_case, migrate data, drop old columns

2. **Mixed Storage Engines**
   - Problem: MyISAM and InnoDB mixed
   - Impact: No transaction support for inspection data
   - **Fix:** Migrate all to InnoDB

3. **Denormalization in i_vehicle_register**
   - Problem: Duplicates all vehicle data from `i_vehicle_base`
   - Impact: Data redundancy, update anomalies
   - **Fix:** Use foreign key reference instead

4. **Multiple Brake Tables**
   - Problem: Separate tables for each rear axle configuration
   - Impact: Complex queries, maintenance burden
   - **Fix:** Single table with axle_position column

### Performance Issues

1. **Missing Indexes**
   - Add index on `i_data_base.dept_id` for filtering
   - Add index on `sys_user.dept_id` for department queries

2. **Inefficient Queries**
   - Composite indexes on `(seriesno, inspecttimes)` should be primary lookup
   - Consider covering indexes for common queries

### Data Quality Issues

1. **Nullable Foreign Keys**
   - Many `dept_id` fields are nullable
   - Should enforce NOT NULL for referential integrity

2. **Status Field Inconsistency**
   - Status values not standardized (P/F vs 1/2/3)
   - Create lookup table for standardization

---

## 11. SAMPLE QUERIES

### Find all inspections for a vehicle
```sql
SELECT * FROM i_vehicle_register 
WHERE plateno = 'ABC123' 
ORDER BY inspectdate DESC;
```

### Get brake test results for an inspection
```sql
SELECT 
  b.seriesno,
  b.inspecttimes,
  b.brakeeff,
  b.stsbrakeeff,
  bs.tolbrakeeff,
  bs.stsbrakeeff as summary_status
FROM i_data_brake_front b
LEFT JOIN i_data_brake_summary bs 
  ON b.seriesno = bs.seriesno 
  AND b.inspecttimes = bs.inspecttimes
WHERE b.seriesno = 'SERIES001';
```

### Get emissions test results
```sql
SELECT 
  seriesno,
  inspecttimes,
  idlhcaverage,
  hghhcaverage,
  idlcoaverage,
  hghcoaverage,
  stsidlhc,
  stshghhc,
  stsidlco,
  stshghco
FROM i_data_gas
WHERE seriesno = 'SERIES001';
```

### Get all defects for an inspection
```sql
SELECT 
  'Visual' as inspection_type,
  defectcode,
  category,
  description
FROM i_data_visual
WHERE seriesno = 'SERIES001'
UNION ALL
SELECT 
  'Pit' as inspection_type,
  defectcode,
  category,
  description
FROM i_data_pit
WHERE seriesno = 'SERIES001';
```

### Get inspection statistics by department
```sql
SELECT 
  d.title as department,
  COUNT(DISTINCT vr.seriesno) as total_inspections,
  COUNT(DISTINCT vr.plateno) as unique_vehicles,
  SUM(CASE WHEN vr.testresult = 'P' THEN 1 ELSE 0 END) as passed,
  SUM(CASE WHEN vr.testresult = 'F' THEN 1 ELSE 0 END) as failed
FROM i_vehicle_register vr
JOIN sys_dept d ON vr.dept_id = d.id
GROUP BY d.id, d.title;
```

---

## 12. SECURITY CONSIDERATIONS

1. **Password Storage**
   - Uses salt + hash (64 chars)
   - Ensure strong hashing algorithm (SHA-256 or bcrypt)

2. **Access Control**
   - RBAC implemented via roles and menus
   - Audit logging tracks all operations

3. **Data Sensitivity**
   - Vehicle owner information (PII)
   - Inspector credentials
   - Implement row-level security for department isolation

4. **Recommendations**
   - Encrypt sensitive fields (owner names, phone numbers)
   - Implement API authentication tokens
   - Add data masking for PII in logs
   - Regular security audits of audit logs

---

## 13. SCALABILITY CONSIDERATIONS

### Current Limitations
- Single database instance
- No sharding strategy
- All inspection data in one database

### Recommendations for Growth
1. **Partitioning**
   - Partition inspection tables by date (monthly/yearly)
   - Partition by department for multi-tenant isolation

2. **Archiving**
   - Move old inspection records to archive tables
   - Implement data retention policies

3. **Replication**
   - Set up read replicas for reporting
   - Implement master-slave replication

4. **Caching**
   - Cache vehicle master data
   - Cache department hierarchy
   - Cache system configuration (sys_dict)

---

## 14. SUMMARY

This database implements a comprehensive vehicle inspection management system for Nigerian states with:

✅ **Strengths:**
- Detailed technical measurement capture
- Hierarchical department structure
- Audit trail and logging
- Role-based access control
- Support for multiple inspection types

⚠️ **Weaknesses:**
- Inconsistent naming conventions
- Mixed storage engines
- Denormalization issues
- Multiple similar tables instead of flexible design
- Data quality constraints

🔧 **Priority Improvements:**
1. Standardize naming conventions
2. Migrate to InnoDB
3. Normalize vehicle data
4. Consolidate brake tables
5. Add comprehensive constraints
6. Implement data validation rules
