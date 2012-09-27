CREATE TRIGGER `et_client_alert_insert`
AFTER INSERT ON `et_client_alert` FOR EACH ROW
BEGIN
CALL et_client_alert_add(NEW.client_id);
END;