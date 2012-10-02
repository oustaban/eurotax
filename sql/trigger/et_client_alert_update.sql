DROP TRIGGER IF EXISTS `et_client_alert_update`;
CREATE TRIGGER `et_client_alert_update`
AFTER UPDATE ON `et_client_alert` FOR EACH ROW
BEGIN
CALL et_client_alert_add_del(OLD.client_id);
END;