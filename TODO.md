# TODO List - Employee Shift Management System

## Completed Tasks
- [x] Create migration to add `max_shifts_per_week` and `max_shifts_per_day` columns to users table
- [x] Update User model to include new fields in fillable array
- [x] Add boot method to User model to set default shift limits based on role
- [x] Update AdminController storeEmployee and storeClient methods to set appropriate defaults
- [x] Update ClientController acceptShift method to use user-specific weekly limits
- [x] Update EmployeeController acceptShift method to use user-specific daily limits
- [x] Run migration to add new columns to database
- [x] Update existing users with default shift limits

## Pending Tasks
- [ ] Test shift acceptance for both employee and client roles to ensure limits work correctly
- [ ] Verify that new users are created with correct default limits
- [ ] Check that the system properly enforces the new configurable limits

## Notes
- Employees: max_shifts_per_day = 4 (default)
- Clients: max_shifts_per_week = 4 (default)
- Limits are now configurable per user instead of hardcoded
- Migration has been run and existing users updated with defaults
