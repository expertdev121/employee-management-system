# TODO: Add 'client' role with weekly shift limit

## Steps to Complete

- [x] Complete ClientController.php: Add missing methods (acceptShift, rejectShift, showShift, profile, payroll) with weekly limit of 4 shifts per week in acceptShift
- [x] Copy employee views to client views: Create resources/views/client/ directory and copy all employee views
- [x] Add client routes in web.php: Add client routes similar to employee routes, update / and /home redirects to handle 'client' role
- [x] Modify acceptShift in EmployeeController.php: Add daily limit of 4 shifts per day
- [x] Modify acceptShift in ClientController.php: Add weekly limit of 4 shifts per week
- [ ] Test shift acceptance for both roles to ensure limits work
- [ ] Verify redirects work for client login
