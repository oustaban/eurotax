DROP TRIGGER IF EXISTS `et_client_alter_delete`;
CREATE TRIGGER `et_client_alter_delete`
AFTER DELETE ON `et_client_alert` FOR EACH ROW
BEGIN
CALL et_client_alert_add_del(OLD.client_id);
END;