# Client Table Separation Tasks

## Database Changes
- [x] Update clients migration to include all form fields except password
- [x] Create client_shifts migration for shift assignments (no status field)
- [x] Run migrations

## Model Updates
- [x] Update Client model to use clients table and add relationships
- [x] Create ClientShift model

## Controller Updates
- [x] Update AdminController client methods to use Client model instead of User

## View Updates
- [x] Update client form view to remove password fields for clients
- [x] Update client views (index, show) to work with Client model

## Route Updates
- [ ] Update routes if needed

## Testing
- [ ] Test client CRUD operations
- [ ] Update any related functionality
