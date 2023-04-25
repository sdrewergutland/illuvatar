SET FOREIGN_KEY_CHECKS=0;START TRANSACTION;
DELETE FROM `doctrine_migration_versions`;
DELETE FROM `example`;
DELETE FROM `shared_messenger_messages`;
COMMIT;
