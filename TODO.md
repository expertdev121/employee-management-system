# TODO: Prevent Inactive Employees from Logging In

## Steps to Complete

- [x] Override the `login` method in `LoginController.php` to check user status after authentication attempt.
- [x] If user is authenticated but status is 'inactive', log them out and redirect back with an error message.
- [x] Update `login.blade.php` to display the error message above the form when present.
- [ ] Test the functionality by attempting to log in with an inactive user account.
