# Execute these queries to uninstall the module (used for development)

-- Drop module tables
DROP TABLE IF EXISTS contact_albums;
DROP TABLE IF EXISTS contact_albums_tracks;
DROP TABLE IF EXISTS contact_categories;

-- Remove from backend navigation
DELETE FROM backend_navigation WHERE label = 'contact';
DELETE FROM backend_navigation WHERE url = '%contact%';

-- Remove from groups_rights
DELETE FROM groups_rights_actions WHERE module = 'contact';
DELETE FROM groups_rights_modules WHERE module = 'contact';

-- Remove from locale
DELETE FROM locale WHERE module = 'contact';
DELETE FROM locale WHERE module = 'core' AND name = 'contact%';

-- Remove from modules
DELETE FROM modules WHERE name = 'contact';
DELETE FROM modules_extras WHERE module = 'contact';

-- Remove from Meta
DELETE FROM meta WHERE keywords = '%contact%';
