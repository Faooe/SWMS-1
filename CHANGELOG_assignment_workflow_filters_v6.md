# Assignment workflow filters v6

- Added employee Needs Revision filter.
- Employee Assigned includes Assigned/Accepted/In Progress pivot states.
- Added Company workflow pseudo-filters Pending Review and Needs Revision.
- Company In Progress remains a separate operational filter.
- Company Completed only matches approved work with no pending/revision review.

- Added Company Assignment date filter using schedule overlap (start_datetime <= date <= end_datetime).
