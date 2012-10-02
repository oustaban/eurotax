DROP PROCEDURE IF EXISTS `et_client_alert_add_del`;
CREATE PROCEDURE `et_client_alert_add_del`(IN `client_id` INT)
BEGIN
UPDATE et_client SET alert_count = (SELECT count(et_ca.client_id) FROM et_client_alert et_ca WHERE et_ca.client_id = client_id), blocked_count = (SELECT SUM(et_ca.is_blocked) FROM et_client_alert et_ca WHERE et_ca.client_id = client_id) WHERE id = client_id;
END;