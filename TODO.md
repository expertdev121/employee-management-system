# Refactor Shift Type to Days of Week

## Tasks
- [x] Create database migration to update shift_type enum from ['morning', 'evening', 'night', 'custom'] to ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday']
- [x] Update Shift model validation rules
- [x] Update AdminController validation rules for storeShift and updateShift methods
- [x] Update shift form select options in resources/views/admin/shifts/form.blade.php
- [x] Update seeder data in database/seeders/DatabaseSeeder.php
- [x] Run migration to apply database changes (migration executed successfully)
- [x] Test the changes by creating/editing a shift (database seeded successfully)
