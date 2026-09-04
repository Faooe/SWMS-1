# Employee Web Performance Fix v60

- Fixed web Employee Performance requests so `from` + `to` are resolved as a month range by the HR Recap service.
- Web Performance endpoint now uses the same `resolveRecapRange`, `attendanceSummary`, `assignmentSummary`, and `chartData` flow used by the mobile HR recap.
- Fixed the previous regression where `from=2026-07&to=2026-09` was interpreted as July only because `period` defaulted to `month`.
- Web chart fetch now sends `period=range` explicitly.
- Existing 1/3-month export buttons remain separate, but now send explicit `period=range&from=...&to=...` queries for compatibility with the new range resolver.
- No database migration required.
