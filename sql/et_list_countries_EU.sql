UPDATE et_list_countries SET  EU=NULL;
UPDATE et_list_countries SET  EU=1 WHERE   code IN('DE','AT','BE','BG','CY','DK','ES','EE','FI','FR','GR','HU','IE','IT','LV','LT','LU','MT','MC','NL','PL','PT','CZ','RO','GB','SK','SI','SE');
UPDATE et_list_countries SET  sepa=NULL;
UPDATE et_list_countries SET  sepa=1 WHERE code IN('DE','AT','BE','BG','CY','DK','ES','EE','FI','FR','GR','HU','IE','IT','LV','LT','LU','MT','MC','NL','PL','PT','CZ','RO','GB','SK','SI','SE','IS','LI','NO','CH');