# TODO: Implement Cron Job for Missing Shifts

## Steps to Complete
- [x] Create the Artisan command `CheckMissingShifts` in `app/Console/Commands/CheckMissingShifts.php`
- [x] Schedule the command in `app/Console/Kernel.php` to run periodically (e.g., hourly)
- [x] Test the command manually
- [x] Verify webhook sending and handle potential errors
- [ ] Adjust scheduling frequency if needed
