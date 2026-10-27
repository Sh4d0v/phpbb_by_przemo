-- Remove legacy Gadu-Gadu data from an existing installation.
-- Back up the database first and replace phpbb_ with the configured table prefix.
ALTER TABLE `phpbb_users`
  DROP COLUMN `user_aim`,
  DROP COLUMN `user_viewaim`,
  DROP COLUMN `user_notify_gg`;

DELETE FROM `phpbb_config`
WHERE `config_name` IN (
  'cgg', 'numer_gg', 'haslo_gg', 'require_aim', 'notify_gg',
  'admin_notify_gg', 'admin_notify_reply', 'admin_notify_message'
);
