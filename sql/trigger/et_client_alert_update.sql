CREATE TRIGGER `et_client_alert_update`
AFTER UPDATE ON `et_client_alert` FOR EACH ROW
BEGIN
CALL et_client_alert_add(NEW.client_id);
CALL et_client_alert_del(OLD.client_id);
END;