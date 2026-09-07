# v72 - Role 2 Attendance Detail Map & UI/UX

- Restores the inline GPS map using Leaflet + OpenStreetMap raster tiles (no WebGL dependency).
- Shows employee check-in marker, office/assignment reference point, allowed-radius circle, and distance line when reference coordinates exist.
- Adds graceful in-page fallback if Leaflet/tile initialization fails; external Google Maps/OpenStreetMap links remain available.
- Makes GPS Validation and Attendance Photos full-width sections for clearer desktop hierarchy.
- Removes duplicated Allowed Radius / Employee Distance fields from Attendance Information (they live in GPS Validation).
- Uses the attendance record office before current employment office in detail presentation.
- Normalizes Attendance Type labels to Office / Assignment.
- No migration and no attendance business-logic changes.
