CREATE TABLE `wp_terms` (  `term_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,  `name` varchar(200) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',  `slug` varchar(200) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',  `term_group` bigint(10) NOT NULL DEFAULT '0',  PRIMARY KEY (`term_id`),  KEY `slug` (`slug`(191)),  KEY `name` (`name`(191))) ENGINE=MyISAM AUTO_INCREMENT=183 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40000 ALTER TABLE `wp_terms` DISABLE KEYS */;
SET FOREIGN_KEY_CHECKS = 0;
SET UNIQUE_CHECKS = 0;
INSERT INTO `wp_terms` VALUES('99', 'Soft Maple', 'soft-maple', '0');
INSERT INTO `wp_terms` VALUES('98', 'Hard Maple', 'hard-maple', '0');
INSERT INTO `wp_terms` VALUES('97', 'Cabinet Shops', 'cabinet-shops', '0');
INSERT INTO `wp_terms` VALUES('95', 'Turkey Calls', 'turkey-calls', '0');
INSERT INTO `wp_terms` VALUES('109', 'Craftsman', 'craftsman', '0');
INSERT INTO `wp_terms` VALUES('100', 'Glue-Up', 'glue-up', '0');
INSERT INTO `wp_terms` VALUES('101', 'Basswood', 'basswood', '0');
INSERT INTO `wp_terms` VALUES('102', 'Carving', 'carving', '0');
INSERT INTO `wp_terms` VALUES('103', 'Walnut', 'walnut', '0');
INSERT INTO `wp_terms` VALUES('104', 'Boxelder', 'boxelder', '0');
INSERT INTO `wp_terms` VALUES('105', 'Hickory', 'hickory', '0');
INSERT INTO `wp_terms` VALUES('110', 'Basket Weavers', 'basket-weavers', '0');
INSERT INTO `wp_terms` VALUES('111', 'Hobbyist', 'hobbyist', '0');
INSERT INTO `wp_terms` VALUES('112', 'Turner', 'turner', '0');
INSERT INTO `wp_terms` VALUES('113', 'Builders', 'builders', '0');
INSERT INTO `wp_terms` VALUES('114', 'Furniture', 'furniture', '0');
INSERT INTO `wp_terms` VALUES('115', 'Lumber', 'lumber', '0');
INSERT INTO `wp_terms` VALUES('116', 'Sharpen My Gouge', 'sharpen-my-gouge', '0');
INSERT INTO `wp_terms` VALUES('117', 'Sawmill, No Kiln', 'sawmill', '0');
INSERT INTO `wp_terms` VALUES('118', 'Farmer/Homesteader', 'farmer-homesteader', '0');
INSERT INTO `wp_terms` VALUES('119', 'Sawdust', 'sawdust', '0');
INSERT INTO `wp_terms` VALUES('120', 'Government', 'government', '0');
INSERT INTO `wp_terms` VALUES('121', 'Loggers, All', 'logger', '0');
INSERT INTO `wp_terms` VALUES('122', 'Kiln Work', 'kiln-work', '0');
INSERT INTO `wp_terms` VALUES('123', 'Flooring', 'flooring', '0');
INSERT INTO `wp_terms` VALUES('124', 'Forrester', 'forrester', '0');
INSERT INTO `wp_terms` VALUES('125', 'Bowyer', 'bowyer', '0');
INSERT INTO `wp_terms` VALUES('126', 'Luthier', 'luthier', '0');
INSERT INTO `wp_terms` VALUES('127', 'Community Outreach', 'community-outreach', '0');
INSERT INTO `wp_terms` VALUES('128', 'Veteran', 'veteran', '0');
INSERT INTO `wp_terms` VALUES('129', 'Contractors', 'contractors', '0');
INSERT INTO `wp_terms` VALUES('130', 'Live Edge', 'live-edge', '0');
INSERT INTO `wp_terms` VALUES('131', 'Cookies', 'cookies', '0');
INSERT INTO `wp_terms` VALUES('132', 'Wholesale Buyers', 'wholesale-buyers', '0');
INSERT INTO `wp_terms` VALUES('133', 'Riverwood', 'riverwood', '0');
INSERT INTO `wp_terms` VALUES('134', 'eBay', 'ebay', '0');
INSERT INTO `wp_terms` VALUES('135', 'Sycamore QS', 'sycamore-qs', '0');
INSERT INTO `wp_terms` VALUES('136', 'Riverwood QS', 'riverwood-qs', '0');
INSERT INTO `wp_terms` VALUES('137', 'BA', 'ba', '0');
INSERT INTO `wp_terms` VALUES('138', 'White Oak QS', 'white-oak-qs', '0');
INSERT INTO `wp_terms` VALUES('139', 'Bark Pocket Hickory', 'bark-pocket-hickory', '0');
INSERT INTO `wp_terms` VALUES('140', 'Cherry', 'cherry', '0');
INSERT INTO `wp_terms` VALUES('142', 'ALL CUSTOMERS', 'allcustomers', '0');
INSERT INTO `wp_terms` VALUES('143', 'Manufactured Product', 'manufactured-product', '0');
INSERT INTO `wp_terms` VALUES('144', 'UNSUBSCRIBED TO EMAILS', 'unsubscribed-to-emails', '0');
INSERT INTO `wp_terms` VALUES('145', 'Unsubscribed', 'unsubscribed', '0');
INSERT INTO `wp_terms` VALUES('146', 'Woodworker, Small Shop', 'woodworker-small-shop', '0');
INSERT INTO `wp_terms` VALUES('147', 'Sawmill, Kiln', 'sawmill-kiln', '0');
INSERT INTO `wp_terms` VALUES('148', 'Sawmill, Hobbyist', 'sawmill-hobbyist', '0');
INSERT INTO `wp_terms` VALUES('149', 'Loggers, Cedar', 'loggers-cedar', '0');
INSERT INTO `wp_terms` VALUES('150', 'Red Cedar', 'red-cedar', '0');
INSERT INTO `wp_terms` VALUES('151', 'Custom Tabletop', 'custom-tabletop', '0');
INSERT INTO `wp_terms` VALUES('152', 'Spalted', 'spalted', '0');
INSERT INTO `wp_terms` VALUES('153', 'Figured', 'figured', '0');
INSERT INTO `wp_terms` VALUES('154', 'Sawmill', 'sawmill-2', '0');
INSERT INTO `wp_terms` VALUES('155', 'KY Coffeetree', 'ky-coffeetree', '0');
INSERT INTO `wp_terms` VALUES('156', 'Knife-Makers', 'knife-makers', '0');
INSERT INTO `wp_terms` VALUES('157', 'Pen Turners', 'pen-turners', '0');
INSERT INTO `wp_terms` VALUES('158', 'Knife Makers Supply', 'knife-makers-supply', '0');
INSERT INTO `wp_terms` VALUES('159', 'Tomahawks', 'tomahawks', '0');
INSERT INTO `wp_terms` VALUES('160', 'Etsy', 'etsy', '0');
INSERT INTO `wp_terms` VALUES('161', 'Ebay Customers', 'ebay-customers', '0');
INSERT INTO `wp_terms` VALUES('162', 'Homeowner', 'homeowner', '0');
INSERT INTO `wp_terms` VALUES('163', 'Cedar', 'cedar', '0');
INSERT INTO `wp_terms` VALUES('164', 'Metal work', 'metal-work', '0');
INSERT INTO `wp_terms` VALUES('165', 'Pine Fir Spruce', 'pine-fir-spruce', '0');
INSERT INTO `wp_terms` VALUES('166', 'T&amp;G', 'tg', '0');
INSERT INTO `wp_terms` VALUES('167', 'Shiplap', 'shiplap', '0');
INSERT INTO `wp_terms` VALUES('168', 'Building Supply', 'building-supply', '0');
INSERT INTO `wp_terms` VALUES('169', 'Red Oak', 'red-oak', '0');
INSERT INTO `wp_terms` VALUES('170', 'Sharpener', 'sharpener', '0');
INSERT INTO `wp_terms` VALUES('171', 'Trucking Freight', 'trucking-freight', '0');
INSERT INTO `wp_terms` VALUES('172', 'Door Sellers', 'door-sellers', '0');
INSERT INTO `wp_terms` VALUES('173', 'Mill Work', 'mill-work', '0');
INSERT INTO `wp_terms` VALUES('174', 'Wood Exporter', 'wood-exporter', '0');
INSERT INTO `wp_terms` VALUES('175', 'Employees', 'employees', '0');
INSERT INTO `wp_terms` VALUES('176', 'Hauler', 'hauler', '0');
INSERT INTO `wp_terms` VALUES('177', 'Transport/ pickup/delivery', 'transport-pickup-delivery', '0');
INSERT INTO `wp_terms` VALUES('178', 'Suppliers', 'suppliers', '0');
INSERT INTO `wp_terms` VALUES('179', 'Website', 'website', '0');
INSERT INTO `wp_terms` VALUES('180', 'Designers', 'designers', '0');
INSERT INTO `wp_terms` VALUES('181', 'Gun stock makers', 'gun-stock-makers', '0');
INSERT INTO `wp_terms` VALUES('182', 'Dimensional', 'dimensional', '0');
/*!40000 ALTER TABLE `wp_terms` ENABLE KEYS */;
SET FOREIGN_KEY_CHECKS = 1;
SET UNIQUE_CHECKS = 1;
