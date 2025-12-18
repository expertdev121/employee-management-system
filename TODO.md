# Refactoring Plan: Separate Clients from Users

## Steps to Complete

1. **Create Client Model**: New model separate from User, with fields like name, email, phone, department, hourly_rate, etc.
2. **Create Migration**: New clients table, move client data from users table.
3. **Update EmployeeShift Model**: Change employee_id to polymorphic relationship (can belong to User or Client).
4. **Update AttendanceLog Model**: Similar polymorphic relationship.
5. **Update PayrollReport Model**: Remove client payroll functionality.
6. **Update User Model**: Remove client role, methods, and relationships.
7. **Update AdminController**: Modify client methods to use Client model, remove client attendance/payroll.
8. **Remove ClientController**: No longer needed.
9. **Update Routes**: Remove client auth routes, update admin routes for clients.
10. **Update Views**: Remove client views, update admin client views to show data only.
11. **Update Seeders**: Create clients in new table.

## Progress Tracking
- [x] Step 1: Create Client Model
- [x] Step 2: Create Migration
- [x] Step 3: Update EmployeeShift Model
- [x] Step 4: Update AttendanceLog Model
- [x] Step 5: Update PayrollReport Model
- [x] Step 6: Update User Model
- [x] Step 7: Update AdminController
- [x] Step 8: Remove ClientController
- [x] Step 9: Update Routes
- [x] Step 10: Update Views
- [x] Step 11: Update Seeders
