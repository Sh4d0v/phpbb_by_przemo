-- Run once after backing up the database. Replace phpbb_ with the table prefix from config.php.
ALTER TABLE `phpbb_users`
  DROP COLUMN `user_msnm`;

DELETE FROM `phpbb_config`
WHERE `config_name` = 'cmsn';
