CREATE PROCEDURE `et_client_alert_add`(IN `client_id` INT)
BEGIN
UPDATE et_client SET alert_count = alert_count+1 WHERE id = client_id;
END;