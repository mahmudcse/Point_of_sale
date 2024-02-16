
--

DELIMITER $$

DROP PROCEDURE IF EXISTS `getsequence`$$
CREATE PROCEDURE `getsequence`(IN `seqName` VARCHAR(255))
BEGIN
   DECLARE curVal INT;
   SET curVal = -1;
 	SELECT currentValue INTO curVal FROM sequences WHERE uniqueCode = seqName;
   IF curVal = -1 THEN
        INSERT INTO sequences (uniqueCode,currentValue) VALUES(seqName, 0);
   END IF;
   UPDATE sequences SET currentValue = currentValue + 1 WHERE uniqueCode = seqName;
   SELECT currentValue FROM sequences WHERE uniqueCode = seqName;
END$$

DROP FUNCTION IF EXISTS getCOGS$$
CREATE FUNCTION `getCOGS`(`pItemId` BIGINT) RETURNS float
BEGIN
  declare vRet FLOAT;
  SET vRet = 0.00;
   SELECT SUM(d.quantity*d.unitPrice) / SUM(d.quantity) INTO vRet
	FROM transaction_detail d
	INNER JOIN transaction t ON (t.componentId = d.transactionId AND t.`type` = 'PURCHASE')
	WHERE d.itemId = pItemId AND d.`type` = 1 AND d.accountId = 12;
  RETURN vRet;
END$$

DROP FUNCTION IF EXISTS getCommission$$
CREATE FUNCTION `getCommission`(`pItemId` BIGINT) RETURNS float
BEGIN
  declare vRet FLOAT;
  SET vRet = 0.00;
   SELECT SUM(d.`type`*d.quantity*d.unitPrice) / SUM(d.`type`*d.quantity) INTO vRet
	FROM transaction_detail d
	WHERE d.itemId = pItemId AND d.accountId = 7;
  RETURN vRet;
END$$

DROP FUNCTION IF EXISTS lastPurchasePrice$$
CREATE FUNCTION `lastPurchasePrice`(`itemId` INT) RETURNS double
BEGIN
	DECLARE v_tdate DATETIME;
	DECLARE v_price DOUBLE;
	SET v_price = 0.0;
	SELECT MAX(tdate) INTO v_tdate 
	FROM transaction_detail d
	INNER JOIN transaction t ON (d.transactionId = t.componentId)
	WHERE t.type = 'PURCHASE' AND d.itemId = itemId;
	SELECT MAX(unitPrice) INTO v_price
	FROM transaction_detail d
	INNER JOIN transaction t ON (d.transactionId = t.componentId)
	WHERE t.type = 'PURCHASE' AND d.itemId = itemId AND t.tdate = v_tdate;
	RETURN v_price;
END$$

DELIMITER ;

DROP TABLE IF EXISTS `account`;
CREATE TABLE IF NOT EXISTS `account` (
  `componentId` bigint(20) NOT NULL AUTO_INCREMENT,
  `uniqueCode` varchar(128) DEFAULT NULL,
  `description` varchar(128) DEFAULT NULL,
  `category1` varchar(128) DEFAULT NULL,
  `category2` varchar(128) DEFAULT NULL,
  `category3` varchar(128) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `version` int(11) DEFAULT '0',
  `createddate` datetime DEFAULT NULL,
  `createdby` bigint(20) DEFAULT NULL,
  `updateddate` datetime DEFAULT NULL,
  `updatedby` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`componentId`),
  KEY `Account_Code_index` (`uniqueCode`)
);


INSERT INTO `account` (`componentId`, `uniqueCode`, `description`, `category1`, `category2`, `category3`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES
(1, 'Cash in Hand', 'Cash', 'ASSET', 'CURRENT ASSET', 'CASH', 0, 0, NULL, NULL, NULL, NULL),
(4, 'Receivable', 'Receivable', 'ASSET', 'CURRENT ASSET', '', 0, 0, NULL, NULL, NULL, NULL),
(5, 'Payable', 'Payable', 'LIABILITY', '', '', 0, 0, NULL, NULL, NULL, NULL),
(7, 'Suspension', 'Suspension', NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL),
(8, 'purchase commission', 'purchase commission', 'REVENUE', '', '', 0, 0, NULL, NULL, NULL, NULL),
(9, 'Sale commission', 'Sale commission', 'EXPENSE', 'OPERATING EXPENSE', '', 0, 0, NULL, NULL, NULL, NULL),
(10, 'Cash Advance', 'Cash Advance', 'ASSET', NULL, NULL, 0, 0, NULL, NULL, NULL, NULL),
(12, 'Purchase', 'Purchase', 'EXPENSE', '', '', 0, 0, NULL, NULL, NULL, NULL),
(13, 'Sales', 'Sales', 'REVENUE', '', '', 0, 0, NULL, NULL, NULL, NULL),
(24, 'ENTERTAINMENT', 'ENTERTAINMENT', 'EXPENSE', '', '', 0, 0, NULL, NULL, NULL, NULL),
(25, 'OFFICE RENT', 'SHOW-ROOM RENT', 'EXPENSE', '', '', 0, 0, NULL, NULL, NULL, NULL),
(29, 'MESELINISE EXPENDITURE', 'OTHERS EXPENDITURE', 'EXPENSE', '', '', 0, 0, NULL, NULL, NULL, NULL),
(30, 'PBL', 'PUBALI BANK LIMITED', 'ASSET', 'CURRENT ASSET', 'BANK', 0, 0, NULL, NULL, NULL, NULL),
(31, 'Equity', 'investment', 'LIABILITY', '', '', 0, 0, NULL, NULL, NULL, NULL),
(33, 'transport ', 'transport', 'EXPENSE', '', '', 0, 0, NULL, NULL, NULL, NULL),
(34, 'Personal Expence', 'Equity', 'LIABILITY', 'OTHER LIABILITY', '', 0, 0, NULL, NULL, NULL, NULL),
(41, 'MOBILE BILL', 'FLEXILOAD', 'EXPENSE', '', '', 0, 0, NULL, NULL, NULL, NULL),
(44, 'VAT&TAX', 'LOCAL VAT', 'EXPENSE', '', '', 0, 0, NULL, NULL, NULL, NULL),
(45, 'STORE ROOM RENT', 'RAJNAGAR, R/A, HABIGANJ', 'EXPENSE', '', '', 0, 0, NULL, NULL, NULL, NULL);


DROP TABLE IF EXISTS `challan`;
CREATE TABLE IF NOT EXISTS `challan` (
  `componentId` bigint(20) NOT NULL AUTO_INCREMENT,
  `uniqueCode` varchar(128) DEFAULT NULL,
  `transactionId` int(11) DEFAULT NULL,
  `billTo` varchar(250) DEFAULT NULL,
  `billingAddress` varchar(255) DEFAULT NULL,
  `shipTo` varchar(255) DEFAULT NULL,
  `shippingAddress` varchar(255) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `version` int(11) DEFAULT '0',
  `createddate` datetime DEFAULT NULL,
  `createdby` bigint(20) DEFAULT NULL,
  `updateddate` datetime DEFAULT NULL,
  `updatedby` bigint(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `deliverydate` datetime DEFAULT NULL,
  PRIMARY KEY (`componentId`),
  KEY `Challan_Code_index` (`uniqueCode`)
);

DROP TABLE IF EXISTS `codes`;
CREATE TABLE IF NOT EXISTS `codes` (
  `componentId` int(11) NOT NULL AUTO_INCREMENT,
  `uniqueCode` varchar(255) NOT NULL,
  `key_name` varchar(255) NOT NULL,
  `key_value` varchar(255) NOT NULL,
  PRIMARY KEY (`componentId`)
);


INSERT INTO `codes` (`componentId`, `uniqueCode`, `key_name`, `key_value`) VALUES
(1, 'customer-group', 'customer-group', 'ALL'),
(2, 'company_function', 'company_function', 'All Kinds of Sweater, Knit Dyeing & Washing Chemicals'),
(3, 'head_office_address', 'head_office_address', 'House # 13, Road # 3/F <br> Sector # 9, Uttara, Dhaka-1230'),
(4, 'head_office_phone', 'head_office_phone', '58955919'),
(5, 'head_office_fax', 'head_office_fax', '88-02-58955919'),
(6, 'head_office_email1', 'head_office_email1', 'fcibd.net@gmail.com'),
(7, 'head_office_email2', 'head_office_email2', 'm.rahmantc@yahoo.com'),
(8, 'branch_office_name', 'branch_office_name', 'Ware House'),
(9, 'branch_office_address', 'branch_office_address', 'Vasan Sorok,Bisso Road<br />National University,Gazipur'),
(10, 'tin', 'tin', '075-105-5846'),
(11, 'vat', 'vat', '18011054825'),
(12, 'area', 'area', '180101'),
(13, 'system_name', 'system_name', 'FCI'),
(14, 'system_title', '', 'FCI'),
(15, 'address', '', 'Sherpur, Bangladesh'),
(16, 'phone', '', '01824412272');


DROP TABLE IF EXISTS `customer`;
CREATE TABLE IF NOT EXISTS `customer` (
  `componentId` bigint(20) NOT NULL AUTO_INCREMENT,
  `uniqueCode` varchar(128) DEFAULT NULL,
  `name` char(50) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `city` char(100) NOT NULL,
  `phone` char(20) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `isCustomer` bit(1) DEFAULT b'0',
  `isSupplier` bit(1) DEFAULT b'0',
  `isOwn` bit(1) DEFAULT b'0',
  `status` int(11) NOT NULL DEFAULT '0',
  `version` int(11) DEFAULT '0',
  `createddate` datetime DEFAULT NULL,
  `createdby` bigint(20) DEFAULT NULL,
  `updateddate` datetime DEFAULT NULL,
  `updatedby` bigint(20) DEFAULT NULL,
  `customergroup` int DEFAULT NULL,
  `customertype` int DEFAULT NULL,
  PRIMARY KEY (`componentId`)
);

INSERT INTO `customer` (`componentId`, `uniqueCode`, `name`, `address`, `city`, `phone`, `email`, `isCustomer`, `isSupplier`, `isOwn`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES
(1, 'Company user', 'Company user', 'Default user for company', '', '', NULL, b'0', b'0', b'1', 0, 0, NULL, NULL, NULL, NULL),
(2, 'CustAnonymous5730bef02898c', 'CustAnonymous', 'Customer Anonymous', '', '', '', b'1', b'0', b'0', 0, 0, NULL, NULL, NULL, NULL),
(3, 'SuppAnonymous58c0f16c7a890', 'SuppAnonymous', 'Supplier Anonymous', '', '', NULL, b'0', b'1', b'0', 0, 0, NULL, NULL, NULL, NULL);


DROP TABLE IF EXISTS `functioncode`;
CREATE TABLE IF NOT EXISTS `functioncode` (
  `componentId` bigint(20) NOT NULL AUTO_INCREMENT,
  `uniqueCode` varchar(128) DEFAULT NULL,
  `displayName` varchar(128) DEFAULT NULL,
  `functionGroup` varchar(128) DEFAULT NULL,
  `codeNumber` int(11) DEFAULT NULL,
  `actionUrl` varchar(1024) DEFAULT NULL,
  `isMenu` bit(1) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `version` int(11) DEFAULT '0',
  `createddate` datetime DEFAULT NULL,
  `createdby` bigint(20) DEFAULT NULL,
  `updateddate` datetime DEFAULT NULL,
  `updatedby` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`componentId`)
);



INSERT INTO `functioncode` (`componentId`, `uniqueCode`, `displayName`, `functionGroup`, `codeNumber`, `actionUrl`, `isMenu`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES
(1, 'usersearch', 'Users', 'Config', 1001, 'user/search', b'1', 1, 0, NULL, NULL, NULL, NULL),
(2, 'userget', 'User View', 'Config', 1002, 'user/get', b'0', 1, 0, NULL, NULL, NULL, NULL),
(3, 'useradd', 'Add User', 'Config', 1003, 'user/add', b'0', 1, 0, NULL, NULL, NULL, NULL),
(4, 'usermodify', 'Modify User', 'Config', 1004, 'user/modify', b'0', 1, 0, NULL, NULL, NULL, NULL),
(5, 'userdelete', 'Delete User', 'Config', 1005, 'user/delete', b'0', 1, 0, NULL, NULL, NULL, NULL),
(6, 'rolesearch', 'Roles', 'Config', 1006, 'role/search', b'1', 1, 0, NULL, NULL, NULL, NULL),
(7, 'roleget', 'Role View', 'Config', 1007, 'role/get', b'0', 1, 0, NULL, NULL, NULL, NULL),
(8, 'roleadd', 'Add Role', 'Config', 1008, 'role/add', b'0', 1, 0, NULL, NULL, NULL, NULL),
(9, 'rolemodify', 'Modify Role', 'Config', 1009, 'role/modify', b'0', 1, 0, NULL, NULL, NULL, NULL),
(10, 'roledelete', 'Delete Role', 'Config', 1010, 'role/delete', b'0', 1, 0, NULL, NULL, NULL, NULL),
(11, 'functioncodesearch', 'Functions', 'Config', 1011, 'functioncode/search', b'1', 1, 0, NULL, NULL, NULL, NULL),
(12, 'functioncodeget', 'Function View', 'Config', 1012, 'functioncode/get', b'0', 1, 0, NULL, NULL, NULL, NULL),
(13, 'functioncodeadd', 'Add FunctionCode', 'Config', 1013, 'functioncode/add', b'0', 1, 0, NULL, NULL, NULL, NULL),
(14, 'functioncodemodify', 'Modify FunctionCode', 'Config', 1014, 'functioncode/modify', b'0', 1, 0, NULL, NULL, NULL, NULL),
(15, 'functioncodedelete', 'Delete FunctionCode', 'Config', 1015, 'functioncode/delete', b'0', 1, 0, NULL, NULL, NULL, NULL),
(16, 'roleassignment', 'Role Assignment', 'Config', 1016, 'role/assignment', b'1', 1, 0, NULL, NULL, NULL, NULL),
(17, 'functioncodeassignment', 'Function Assignment', 'Config', 1017, 'functioncode/assignment', b'1', 1, 0, NULL, NULL, NULL, NULL),
(18, 'customersearch', 'Customers', 'Config', 1018, 'customer/search', b'1', 1, 0, NULL, NULL, NULL, NULL),
(19, 'customeradd', 'Add Customer', 'Config', 1019, '/view/user/roleaddmodify.php', b'0', 1, 0, NULL, NULL, NULL, NULL),
(20, 'customermodify', 'Modify Customer', 'Config', 1020, '/view/user/useraddmodify.php', b'0', 1, 0, NULL, NULL, NULL, NULL),
(21, 'customerdelete', 'Delete Customer', 'Config', 1021, '/view/user/useraddmodify.php', b'0', 1, 0, NULL, NULL, NULL, NULL),
(22, 'accountsearch', 'Accounts', 'Accounting', 1022, 'account/search', b'1', 1, 0, NULL, NULL, NULL, NULL),
(23, 'accountadd', 'Add Account', 'Accounting', 1023, 'account/add', b'0', 1, 0, NULL, NULL, NULL, NULL),
(24, 'accountmodify', 'Modify Account', 'Accounting', 1024, 'account/modify', b'0', 1, 0, NULL, NULL, NULL, NULL),
(25, 'accountdelete', 'Delete Account', 'Accounting', 1025, 'account/delete', b'0', 1, 0, NULL, NULL, NULL, NULL),
(26, 'transactionsearch', 'Transactions', 'Accounting', 1026, 'transaction/search', b'1', 1, 0, NULL, NULL, NULL, NULL),
(27, 'transactionadd', 'Add Transaction', 'Accounting', 1027, 'transaction/add', b'0', 1, 0, NULL, NULL, NULL, NULL),
(28, 'transactionmodify', 'Modify Transaction', 'Accounting', 1028, 'transaction/modify.php', b'0', 1, 0, NULL, NULL, NULL, NULL),
(29, 'transactiondelete', 'Delete Transaction', 'Accounting', 1029, 'transaction/transactionaddmodify.php', b'0', 1, 0, NULL, NULL, NULL, NULL),
(30, 'customersearch', 'Customers', 'Accounting', 1018, 'customer/search', b'1', 1, 0, NULL, NULL, NULL, NULL),
(31, 'customeradd', 'Add Customer', 'Accounting', 1019, '/view/user/roleaddmodify.php', b'0', 1, 0, NULL, NULL, NULL, NULL),
(32, 'customermodify', 'Modify Customer', 'Accounting', 1020, '/view/user/useraddmodify.php', b'0', 1, 0, NULL, NULL, NULL, NULL),
(33, 'customerdelete', 'Delete Customer', 'Accounting', 1021, '/view/user/useraddmodify.php', b'0', 1, 0, NULL, NULL, NULL, NULL),
(34, 'suppliersearch', 'Suppliers', 'Accounting', 1018, 'supplier/search', b'1', 1, 0, NULL, NULL, NULL, NULL),
(35, 'supplieradd', 'Add Supplier', 'Accounting', 1019, '/view/user/roleaddmodify.php', b'0', 1, 0, NULL, NULL, NULL, NULL),
(36, 'suppliermodify', 'Modify Supplier', 'Accounting', 1020, '/view/user/useraddmodify.php', b'0', 1, 0, NULL, NULL, NULL, NULL),
(37, 'supplierdelete', 'Delete Supplier', 'Accounting', 1021, '/view/user/useraddmodify.php', b'0', 1, 0, NULL, NULL, NULL, NULL),
(38, 'itemsearch', 'Items', 'Accounting', 1022, 'item/search', b'1', 1, 0, NULL, NULL, NULL, NULL),
(39, 'itemadd', 'Add Item', 'Accounting', 1024, 'item/add', b'0', 1, 0, NULL, NULL, NULL, NULL),
(40, 'itemmodify', 'Modify Item', 'Accounting', 1025, 'item/modify', b'0', 1, 0, NULL, NULL, NULL, NULL),
(41, 'itemdelete', 'Delete Item', 'Accounting', 1026, 'item/delete', b'0', 1, 0, NULL, NULL, NULL, NULL),
(42, 'purchasesearch', 'Search Purchase', 'Purchase', 1027, 'purchase/search', b'1', 1, 0, NULL, NULL, NULL, NULL),
(43, 'purchaseangpurchase', 'Add Purchase', 'Purchase', 1028, 'purchase/add', b'1', 1, 0, NULL, NULL, NULL, NULL),
(44, 'inventorysearch', 'Search Inventory', 'Inventory', 1029, 'inventory/search', b'1', 1, 0, NULL, NULL, NULL, NULL),
(45, 'Add Inventory', 'Inventory', 'Inventory', 1030, 'inventory/add', b'0', 1, 0, NULL, NULL, NULL, NULL),
(46, 'salesearch', 'Search Sale', 'Sale', 1031, 'sale/search', b'1', 1, 0, NULL, NULL, NULL, NULL),
(47, 'saleadd', 'Add Sale', 'Sale', 1032, 'sale/add', b'1', 1, 0, NULL, NULL, NULL, NULL),
(48, 'ledgersearch', 'Ledger', 'Report', 1033, 'ledger/showledger', b'1', 1, 0, NULL, NULL, NULL, NULL),
(49, 'recpay', 'Receipts &amp; Payments', 'Report', 1034, 'ledger/recpay', b'1', 1, 0, NULL, NULL, NULL, NULL),
(50, 'payment', 'Payments', 'Purchase', 1035, 'transaction/payment', b'1', 1, 0, NULL, NULL, NULL, NULL),
(51, 'receive', 'Receives', 'Sale', 1036, 'transaction/receive', b'1', 1, 0, NULL, NULL, NULL, NULL),
(52, 'challansearch', 'Challans', 'Report', 1037, 'challan/search', b'1', 1, 0, NULL, NULL, NULL, NULL),
(53, 'challanmodify', 'Modify Challan', 'Report', 1039, 'challan/modify', b'0', 1, 0, NULL, NULL, NULL, NULL),
(54, 'Profit &amp; Loss', 'Profit &amp; Loss', 'Report', 1040, 'ledger/profitloss', b'1', 1, 0, NULL, NULL, NULL, NULL),
(55, 'unitsearch', 'Units', 'Config', 1041, 'unit/search', b'1', 1, 0, NULL, NULL, NULL, NULL),
(56, 'unitadd', 'Add Unit', 'Config', 1042, 'unit/add', b'0', 1, 0, NULL, NULL, NULL, NULL),
(57, 'unitmodify', 'Modify Unit', 'Config', 1043, 'unit/add', b'0', 1, 0, NULL, NULL, NULL, NULL),
(58, 'unitdelete', 'Delete Unit', 'Config', 1044, 'unit/delete', b'0', 1, 0, NULL, NULL, NULL, NULL),
(59, 'BalanceSheet', 'Balance Sheet', 'Report', 1045, 'ledger/balancesheet', b'1', 0, 0, NULL, NULL, NULL, NULL),
(60, 'Purchase Return', 'Purchase Return', 'Purchase', 1046, 'purchasereturn/add', b'1', 1, 0, NULL, NULL, NULL, NULL),
(61, 'Sale Return', 'Sale Return', 'Sale', 1047, 'salereturn/add', b'1', 1, 0, NULL, NULL, NULL, NULL),
(62, 'purchasestmt', 'Purchase Report', 'Report', 1048, 'ledger/purchase', b'1', 1, 0, NULL, NULL, NULL, NULL),
(63, 'salestmt', 'Sale Report', 'Report', 1049, 'ledger/sale', b'1', 1, 0, NULL, NULL, NULL, NULL),
(64, 'codeelement', 'Code Elements', 'Config', 1050, 'codeelement/search', b'1', 1, 0, NULL, NULL, NULL, NULL),
(65, 'installmentsearch', 'Installment', 'Sale', 1051, 'installment/search', b'0', 0, 0, NULL, NULL, NULL, NULL),
(66, 'installmentpayments', 'Payments', 'Sale', 1052, 'installment/payments', b'0', 0, 0, NULL, NULL, NULL, NULL),
(67, 'purchaseadd', 'Add Purchase', 'Purchase', 1053, 'purchase/add', b'0', 0, 0, NULL, NULL, NULL, NULL),
(68, 'purchasesave', 'Save Purchase', 'Purchase', 1054, 'purchase/save', b'0', 0, 0, NULL, NULL, NULL, NULL),
(69, 'challanadd', 'Add Challan', 'Chalan', 1055, 'challan/add', b'0', 0, 0, NULL, NULL, NULL, NULL),
(71, 'salesave', 'Save Sale', 'Sale', 1056, 'sale/save', b'0', 0, 0, NULL, NULL, NULL, NULL),
(72, 'saleangsale', 'Ang Sale', 'Sale', 1057, 'sale/angsale', b'0', 0, 0, NULL, NULL, NULL, NULL),
(73, 'suppliersave', 'Supplier Save', 'Accounting', 1058, 'supplier/save', b'0', 0, 0, NULL, NULL, NULL, NULL),
(74, 'transactionsave', 'Transaction Save', 'Accounting', 1059, 'transaction/save', b'0', 0, 0, NULL, NULL, NULL, NULL),
(75, 'ledgershowledger', 'Ledger', 'Report', 1060, 'ledger/showledger', b'0', 0, 0, NULL, NULL, NULL, NULL),
(76, 'ledgerpurchase', 'Purchase Report', 'Report', 1061, 'ledger/purchase', b'0', 0, 0, NULL, NULL, NULL, NULL),
(77, 'ledgersale', 'Sale Report', 'Report', 1062, 'ledger/sale', b'0', 0, 0, NULL, NULL, NULL, NULL),
(78, 'ledgerrecpay', 'Receipt & Payment', 'Report', 1063, 'ledger/recpay', b'0', 0, 0, NULL, NULL, NULL, NULL),
(79, 'ledgerprofitloss', 'Profit & Loss', 'Report', 1064, 'ledger/profitloss', b'0', 0, 0, NULL, NULL, NULL, NULL),
(80, 'ledgerbalancesheet', 'Balance Sheet', 'Report', 1065, 'ledger/balancesheet', b'0', 0, 0, NULL, NULL, NULL, NULL),
(81, 'warehousesearch', 'Search Warehouse', 'Warehouse', 1066, 'warehouse/search', NULL, 0, 0, NULL, NULL, NULL, NULL),
(82, 'warehouseadd', 'Add Warehouse', 'Warehouse', 1067, 'warehouse/add', NULL, 0, 0, NULL, NULL, NULL, NULL),
(83, 'warehousesave', 'Save Warehouse', 'Warehouse', 1068, 'warehouse/save', NULL, 0, 0, NULL, NULL, NULL, NULL),
(84, 'warehousedelete', 'Delete Warehouse', 'Warehouse', 1069, 'warehouse/delete', NULL, 0, 0, NULL, NULL, NULL, NULL),
(85, 'warehouseassignment', 'Assign Warehouse', 'Warehouse', 1070, 'warehouse/assignment', NULL, 0, 0, NULL, NULL, NULL, NULL);

DROP TABLE IF EXISTS `functionrole`;
CREATE TABLE IF NOT EXISTS `functionrole` (
  `componentId` bigint(20) NOT NULL AUTO_INCREMENT,
  `functionId` bigint(20) NOT NULL,
  `roleId` bigint(20) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `version` int(11) DEFAULT '0',
  `createddate` datetime DEFAULT NULL,
  `createdby` bigint(20) DEFAULT NULL,
  `updateddate` datetime DEFAULT NULL,
  `updatedby` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`componentId`,`functionId`,`roleId`)
);



INSERT INTO `functionrole` (`componentId`, `functionId`, `roleId`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES
(345, 18, 3, 0, 0, NULL, NULL, NULL, NULL),
(346, 19, 3, 0, 0, NULL, NULL, NULL, NULL),
(347, 20, 3, 0, 0, NULL, NULL, NULL, NULL),
(348, 22, 3, 0, 0, NULL, NULL, NULL, NULL),
(349, 23, 3, 0, 0, NULL, NULL, NULL, NULL),
(350, 26, 3, 0, 0, NULL, NULL, NULL, NULL),
(351, 27, 3, 0, 0, NULL, NULL, NULL, NULL),
(352, 30, 3, 0, 0, NULL, NULL, NULL, NULL),
(353, 31, 3, 0, 0, NULL, NULL, NULL, NULL),
(354, 34, 3, 0, 0, NULL, NULL, NULL, NULL),
(355, 35, 3, 0, 0, NULL, NULL, NULL, NULL),
(356, 38, 3, 0, 0, NULL, NULL, NULL, NULL),
(357, 39, 3, 0, 0, NULL, NULL, NULL, NULL),
(358, 42, 3, 0, 0, NULL, NULL, NULL, NULL),
(359, 43, 3, 0, 0, NULL, NULL, NULL, NULL),
(360, 44, 3, 0, 0, NULL, NULL, NULL, NULL),
(361, 45, 3, 0, 0, NULL, NULL, NULL, NULL),
(362, 46, 3, 0, 0, NULL, NULL, NULL, NULL),
(363, 47, 3, 0, 0, NULL, NULL, NULL, NULL),
(364, 48, 3, 0, 0, NULL, NULL, NULL, NULL),
(365, 50, 3, 0, 0, NULL, NULL, NULL, NULL),
(366, 51, 3, 0, 0, NULL, NULL, NULL, NULL),
(367, 52, 3, 0, 0, NULL, NULL, NULL, NULL),
(368, 53, 3, 0, 0, NULL, NULL, NULL, NULL),
(369, 55, 3, 0, 0, NULL, NULL, NULL, NULL),
(370, 56, 3, 0, 0, NULL, NULL, NULL, NULL),
(457, 49, 2, 0, 0, NULL, NULL, NULL, NULL),
(458, 54, 2, 0, 0, NULL, NULL, NULL, NULL),
(459, 59, 2, 0, 0, NULL, NULL, NULL, NULL),
(702, 1, 8, 0, 0, NULL, NULL, NULL, NULL),
(703, 2, 8, 0, 0, NULL, NULL, NULL, NULL),
(704, 3, 8, 0, 0, NULL, NULL, NULL, NULL),
(705, 4, 8, 0, 0, NULL, NULL, NULL, NULL),
(706, 5, 8, 0, 0, NULL, NULL, NULL, NULL),
(707, 6, 8, 0, 0, NULL, NULL, NULL, NULL),
(708, 7, 8, 0, 0, NULL, NULL, NULL, NULL),
(709, 8, 8, 0, 0, NULL, NULL, NULL, NULL),
(710, 9, 8, 0, 0, NULL, NULL, NULL, NULL),
(711, 10, 8, 0, 0, NULL, NULL, NULL, NULL),
(712, 11, 8, 0, 0, NULL, NULL, NULL, NULL),
(713, 12, 8, 0, 0, NULL, NULL, NULL, NULL),
(714, 13, 8, 0, 0, NULL, NULL, NULL, NULL),
(715, 14, 8, 0, 0, NULL, NULL, NULL, NULL),
(716, 15, 8, 0, 0, NULL, NULL, NULL, NULL),
(717, 16, 8, 0, 0, NULL, NULL, NULL, NULL),
(718, 17, 8, 0, 0, NULL, NULL, NULL, NULL),
(719, 18, 8, 0, 0, NULL, NULL, NULL, NULL),
(720, 19, 8, 0, 0, NULL, NULL, NULL, NULL),
(721, 20, 8, 0, 0, NULL, NULL, NULL, NULL),
(722, 21, 8, 0, 0, NULL, NULL, NULL, NULL),
(723, 22, 8, 0, 0, NULL, NULL, NULL, NULL),
(724, 23, 8, 0, 0, NULL, NULL, NULL, NULL),
(725, 24, 8, 0, 0, NULL, NULL, NULL, NULL),
(726, 25, 8, 0, 0, NULL, NULL, NULL, NULL),
(727, 26, 8, 0, 0, NULL, NULL, NULL, NULL),
(728, 27, 8, 0, 0, NULL, NULL, NULL, NULL),
(729, 28, 8, 0, 0, NULL, NULL, NULL, NULL),
(730, 29, 8, 0, 0, NULL, NULL, NULL, NULL),
(731, 30, 8, 0, 0, NULL, NULL, NULL, NULL),
(732, 31, 8, 0, 0, NULL, NULL, NULL, NULL),
(733, 32, 8, 0, 0, NULL, NULL, NULL, NULL),
(734, 33, 8, 0, 0, NULL, NULL, NULL, NULL),
(735, 34, 8, 0, 0, NULL, NULL, NULL, NULL),
(736, 35, 8, 0, 0, NULL, NULL, NULL, NULL),
(737, 36, 8, 0, 0, NULL, NULL, NULL, NULL),
(738, 37, 8, 0, 0, NULL, NULL, NULL, NULL),
(739, 38, 8, 0, 0, NULL, NULL, NULL, NULL),
(740, 39, 8, 0, 0, NULL, NULL, NULL, NULL),
(741, 40, 8, 0, 0, NULL, NULL, NULL, NULL),
(742, 41, 8, 0, 0, NULL, NULL, NULL, NULL),
(743, 42, 8, 0, 0, NULL, NULL, NULL, NULL),
(744, 43, 8, 0, 0, NULL, NULL, NULL, NULL),
(745, 44, 8, 0, 0, NULL, NULL, NULL, NULL),
(746, 45, 8, 0, 0, NULL, NULL, NULL, NULL),
(747, 46, 8, 0, 0, NULL, NULL, NULL, NULL),
(748, 47, 8, 0, 0, NULL, NULL, NULL, NULL),
(749, 48, 8, 0, 0, NULL, NULL, NULL, NULL),
(750, 55, 8, 0, 0, NULL, NULL, NULL, NULL),
(751, 56, 8, 0, 0, NULL, NULL, NULL, NULL),
(752, 57, 8, 0, 0, NULL, NULL, NULL, NULL),
(753, 58, 8, 0, 0, NULL, NULL, NULL, NULL),
(754, 62, 8, 0, 0, NULL, NULL, NULL, NULL),
(755, 63, 8, 0, 0, NULL, NULL, NULL, NULL),
(756, 49, 8, 0, 0, NULL, NULL, NULL, NULL),
(757, 50, 8, 0, 0, NULL, NULL, NULL, NULL),
(758, 51, 8, 0, 0, NULL, NULL, NULL, NULL),
(759, 52, 8, 0, 0, NULL, NULL, NULL, NULL),
(760, 53, 8, 0, 0, NULL, NULL, NULL, NULL),
(761, 54, 8, 0, 0, NULL, NULL, NULL, NULL),
(762, 59, 8, 0, 0, NULL, NULL, NULL, NULL),
(763, 60, 8, 0, 0, NULL, NULL, NULL, NULL),
(764, 61, 8, 0, 0, NULL, NULL, NULL, NULL),
(765, 64, 8, 0, 0, NULL, NULL, NULL, NULL),
(766, 18, 1, 0, 0, NULL, NULL, NULL, NULL),
(767, 19, 1, 0, 0, NULL, NULL, NULL, NULL),
(768, 20, 1, 0, 0, NULL, NULL, NULL, NULL),
(769, 21, 1, 0, 0, NULL, NULL, NULL, NULL),
(770, 22, 1, 0, 0, NULL, NULL, NULL, NULL),
(771, 23, 1, 0, 0, NULL, NULL, NULL, NULL),
(772, 24, 1, 0, 0, NULL, NULL, NULL, NULL),
(773, 25, 1, 0, 0, NULL, NULL, NULL, NULL),
(774, 30, 1, 0, 0, NULL, NULL, NULL, NULL),
(775, 31, 1, 0, 0, NULL, NULL, NULL, NULL),
(776, 32, 1, 0, 0, NULL, NULL, NULL, NULL),
(777, 33, 1, 0, 0, NULL, NULL, NULL, NULL),
(778, 34, 1, 0, 0, NULL, NULL, NULL, NULL),
(779, 35, 1, 0, 0, NULL, NULL, NULL, NULL),
(780, 36, 1, 0, 0, NULL, NULL, NULL, NULL),
(781, 37, 1, 0, 0, NULL, NULL, NULL, NULL),
(782, 38, 1, 0, 0, NULL, NULL, NULL, NULL),
(783, 39, 1, 0, 0, NULL, NULL, NULL, NULL),
(784, 40, 1, 0, 0, NULL, NULL, NULL, NULL),
(785, 41, 1, 0, 0, NULL, NULL, NULL, NULL),
(786, 42, 1, 0, 0, NULL, NULL, NULL, NULL),
(787, 43, 1, 0, 0, NULL, NULL, NULL, NULL),
(788, 44, 1, 0, 0, NULL, NULL, NULL, NULL),
(789, 45, 1, 0, 0, NULL, NULL, NULL, NULL),
(790, 46, 1, 0, 0, NULL, NULL, NULL, NULL),
(791, 47, 1, 0, 0, NULL, NULL, NULL, NULL),
(792, 48, 1, 0, 0, NULL, NULL, NULL, NULL),
(793, 55, 1, 0, 0, NULL, NULL, NULL, NULL),
(794, 56, 1, 0, 0, NULL, NULL, NULL, NULL),
(795, 57, 1, 0, 0, NULL, NULL, NULL, NULL),
(796, 58, 1, 0, 0, NULL, NULL, NULL, NULL),
(797, 62, 1, 0, 0, NULL, NULL, NULL, NULL),
(798, 63, 1, 0, 0, NULL, NULL, NULL, NULL);



DROP TABLE IF EXISTS `group`;
CREATE TABLE IF NOT EXISTS `group` (
  `componentId` bigint(20) NOT NULL AUTO_INCREMENT,
  `uniqueCode` varchar(100) DEFAULT NULL,
  `description` varchar(100) DEFAULT NULL,
  `parentGroupId` bigint(20) DEFAULT NULL,
  `status` int(11) NOT NULL,
  `version` int(11) DEFAULT '0',
  `createddate` datetime DEFAULT NULL,
  `createdby` bigint(20) DEFAULT NULL,
  `updateddate` datetime DEFAULT NULL,
  `updatedby` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`componentId`)
);

DROP TABLE IF EXISTS `installment`;
CREATE TABLE IF NOT EXISTS `installment` (
  `componentId` BIGINT(20) NOT NULL AUTO_INCREMENT,
  `uniqueCode` VARCHAR(128) NULL DEFAULT NULL,
  `transactionId` BIGINT(20) NULL DEFAULT NULL,
  `installmentNo` INT(11) NOT NULL DEFAULT '0',
  `deadLine` DATE NULL DEFAULT NULL,
  `amount` DOUBLE NOT NULL DEFAULT '0',
  `status` INT(11) NOT NULL DEFAULT '0',
  `version` INT(11) NULL DEFAULT '0',
  `createddate` DATETIME NULL DEFAULT NULL,
  `createdby` BIGINT(20) NULL DEFAULT NULL,
  `updateddate` DATETIME NULL DEFAULT NULL,
  `updatedby` BIGINT(20) NULL DEFAULT NULL,
  PRIMARY KEY (`componentId`)
);

DROP TABLE IF EXISTS `installment_reconcile`;
CREATE TABLE IF NOT EXISTS `installment_reconcile` (
  `componentId` INT(11) NOT NULL AUTO_INCREMENT,
  `installmentId` INT(11) NOT NULL,
  `transactionId` INT(11) NOT NULL,
  `transaction_detail_id` INT(11) NOT NULL,
  `amount` INT(11) NOT NULL,
  `reconcile_date` DATETIME NOT NULL,
  PRIMARY KEY (`componentId`)
);


DROP TABLE IF EXISTS  `company`;  
CREATE TABLE IF NOT EXISTS `company` (
  `componentId` BIGINT(20) NOT NULL AUTO_INCREMENT,
  `uniqueCode` VARCHAR(128) NULL DEFAULT NULL,
  `name` VARCHAR(255) NOT NULL,
  `parentId` BIGINT(20) NULL DEFAULT NULL,
  `address` VARCHAR(1000) NULL DEFAULT NULL,
  `contact` VARCHAR(255) NULL DEFAULT NULL,
  `hierarchyPath` VARCHAR(255) NULL DEFAULT NULL,
  `status` INT(11) NOT NULL DEFAULT '0',
  `version` INT(11) NULL DEFAULT '0',
  `createddate` DATETIME NULL DEFAULT NULL,
  `createdby` BIGINT(20) NULL DEFAULT NULL,
  `updateddate` DATETIME NULL DEFAULT NULL,
  `updatedby` BIGINT(20) NULL DEFAULT NULL,
  PRIMARY KEY (`componentId`)
);

DROP TABLE IF EXISTS `inventory`;
CREATE TABLE IF NOT EXISTS `inventory` (
    `componentId` BIGINT(20) NOT NULL AUTO_INCREMENT,
  `uniqueCode` VARCHAR(128) NULL DEFAULT NULL,
  `description` VARCHAR(128) NULL DEFAULT NULL,
  `tdate` DATETIME NULL DEFAULT NULL,
  `type` VARCHAR(128) NULL DEFAULT NULL,
  `status` VARCHAR(250) NOT NULL DEFAULT '0',
  `createddate` DATETIME NULL DEFAULT NULL,
  `createdby` BIGINT(20) NULL DEFAULT NULL,
  `updateddate` DATETIME NULL DEFAULT NULL,
  `updatedby` BIGINT(20) NULL DEFAULT NULL,
  `warehouseId` BIGINT(20) NULL DEFAULT NULL,
  `refrencewarehouseId` BIGINT(20) NULL DEFAULT NULL,
  `version` INT(11) NOT NULL DEFAULT '0',
  `transactionId` BIGINT(20) NOT NULL,
  PRIMARY KEY (`componentId`)
);

DROP TABLE IF EXISTS `inventorydetail`;
CREATE TABLE IF NOT EXISTS `inventorydetail` (
  `componentId` BIGINT(20) NOT NULL AUTO_INCREMENT,
  `itemId` INT(11) NULL DEFAULT NULL,
  `quantity` FLOAT NULL DEFAULT NULL,
  `type` INT(11) NULL DEFAULT NULL,
  `condition` VARCHAR(128) NULL DEFAULT NULL,
  `inventoryId` BIGINT(20) NULL DEFAULT NULL,
  `warehouseId` BIGINT(20) NULL DEFAULT NULL,
  PRIMARY KEY (`componentId`)
);

DROP TABLE IF EXISTS `item`;
CREATE TABLE IF NOT EXISTS `item` (
  `componentId` bigint(20) NOT NULL AUTO_INCREMENT,
  `uniqueCode` varchar(128) DEFAULT NULL,
  `itemName` char(75) NOT NULL,
  `category1` varchar(128) DEFAULT NULL,
  `category2` varchar(128) DEFAULT NULL,
  `category3` varchar(128) DEFAULT NULL,
  `salePrice` float DEFAULT NULL,
  `purchasePrice` float DEFAULT NULL,
  `minQty` int(11) DEFAULT NULL,
  `unitId` int(11) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `version` int(11) DEFAULT '0',
  `createddate` datetime DEFAULT NULL,
  `createdby` bigint(20) DEFAULT NULL,
  `updateddate` datetime DEFAULT NULL,
  `updatedby` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`componentId`)
);


INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `purchasePrice`, `minQty`, `unitId`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES
(1, 'TAKA', 'TAKA', 'INVENTORY', '', '', 1, 1, 1, 1, 0, 0, NULL, NULL, NULL, NULL);

DROP TABLE IF EXISTS `language`;
CREATE TABLE IF NOT EXISTS `language` (
  `phrase_id` int(11) NOT NULL AUTO_INCREMENT,
  `phrase` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `english` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`phrase_id`)
);


DROP TABLE IF EXISTS `logs`;
CREATE TABLE IF NOT EXISTS `logs` (
  `componentId` bigint(20) NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NULL DEFAULT NULL,
  `priorityName` varchar(32) DEFAULT NULL,
  `priorityLevel` int(11) DEFAULT '-1',
  `userName` varchar(128) DEFAULT NULL,
  `userIP` varchar(128) DEFAULT NULL,
  `userHost` varchar(128) DEFAULT NULL,
  `message` text,
  PRIMARY KEY (`componentId`)
);

DROP TABLE IF EXISTS `role`;
CREATE TABLE IF NOT EXISTS `role` (
  `componentId` bigint(20) NOT NULL AUTO_INCREMENT,
  `uniqueCode` varchar(128) DEFAULT NULL,
  `description` varchar(512) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `version` int(11) DEFAULT '0',
  `createddate` datetime DEFAULT NULL,
  `createdby` bigint(20) DEFAULT NULL,
  `updateddate` datetime DEFAULT NULL,
  `updatedby` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`componentId`)
);

INSERT INTO `role` (`componentId`, `uniqueCode`, `description`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES
(1, 'System Admin', 'System Admin Role', 1, 0, NULL, NULL, NULL, NULL),
(3, 'User', 'User Role', 1, 0, NULL, NULL, NULL, NULL),
(8, 'Supar Admin', 'Super Admin Role', 0, 0, NULL, NULL, NULL, NULL);

DROP TABLE IF EXISTS `sequences`;
CREATE TABLE IF NOT EXISTS `sequences` (
  `componentId` bigint(20) NOT NULL AUTO_INCREMENT,
  `uniqueCode` varchar(128) DEFAULT NULL,
  `currentValue` bigint(20) NOT NULL,
  PRIMARY KEY (`componentId`)
);


INSERT INTO `sequences` (`componentId`, `uniqueCode`, `currentValue`) VALUES
(1, 'TEST', 14),
(2, 'JOURNAL', 4086),
(3, 'PURCHASE', 1362),
(4, 'SALES', 4122),
(5, 'PAYMENT', 100),
(6, 'RECEIVE', 495),
(7, 'CHALLAN', 3100),
(8, 'SALE-RETURN', 40),
(9, 'PURCHASE-RETURN', 28);


CREATE TABLE IF NOT EXISTS `settings` (
  `settings_id` int(11) NOT NULL AUTO_INCREMENT,
  `type` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `description` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`settings_id`)
);

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`settings_id`, `type`, `description`) VALUES
(1, 'system_name', 'T.R. Electro Mart'),
(2, 'system_title', 'T.R. Electro Mart'),
(3, 'address', 'Hobigong, Bangladesh'),
(4, 'phone', '01824412272'),
(5, 'paypal_email', 'payment@school.com'),
(6, 'currency', 'usd'),
(7, 'system_email', 'info@netsoft.com.bd'),
(8, 'buyer', ''),
(9, 'purchase_code', '');


DROP TABLE IF EXISTS `supplier`;
CREATE TABLE IF NOT EXISTS `supplier` (
  `componentId` bigint(20) NOT NULL AUTO_INCREMENT,
  `uniqueCode` varchar(128) DEFAULT NULL,
  `supplierName` char(50) NOT NULL,
  `description` varchar(512) DEFAULT NULL,
  `city` char(100) NOT NULL,
  `phone` char(20) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `version` int(11) DEFAULT '0',
  `createddate` datetime DEFAULT NULL,
  `createdby` bigint(20) DEFAULT NULL,
  `updateddate` datetime DEFAULT NULL,
  `updatedby` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`componentId`)
);


DROP TABLE IF EXISTS `transaction`;
CREATE TABLE IF NOT EXISTS `transaction` (
  `componentId` bigint(20) NOT NULL AUTO_INCREMENT,
  `uniqueCode` varchar(128) DEFAULT NULL,
  `description` varchar(128) DEFAULT NULL,
  `tdate` datetime DEFAULT NULL,
  `month` int(11) DEFAULT NULL,
  `year` int(11) DEFAULT NULL,
  `type` varchar(128) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `version` int(11) DEFAULT '0',
  `createddate` datetime DEFAULT NULL,
  `createdby` bigint(20) DEFAULT NULL,
  `updateddate` datetime DEFAULT NULL,
  `updatedby` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`componentId`),
  KEY `Transaction_Code_index` (`uniqueCode`)
);

DROP TABLE IF EXISTS `transaction_detail`;
CREATE TABLE IF NOT EXISTS `transaction_detail` (
  `componentId` bigint(20) NOT NULL AUTO_INCREMENT,
  `transactionId` int(11) DEFAULT NULL,
  `userId` int(11) DEFAULT NULL,
  `itemId` int(11) DEFAULT NULL,
  `accountId` int(11) DEFAULT NULL,
  `type` int(11) DEFAULT NULL,
  `quantity` float DEFAULT NULL,
  `unitPrice` float DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `version` int(11) DEFAULT '0',
  `createddate` datetime DEFAULT NULL,
  `createdby` bigint(20) DEFAULT NULL,
  `updateddate` datetime DEFAULT NULL,
  `updatedby` bigint(20) DEFAULT NULL,
  `description` varchar(1000) DEFAULT NULL,
  PRIMARY KEY (`componentId`),
  KEY `td_itm_idx` (`itemId`),
  KEY `td_acct_idx` (`accountId`)
);

DROP TABLE IF EXISTS `unit`;
CREATE TABLE IF NOT EXISTS `unit` (
  `componentId` bigint(20) NOT NULL AUTO_INCREMENT,
  `uniqueCode` varchar(128) DEFAULT NULL,
  `description` varchar(128) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `version` int(11) DEFAULT '0',
  `createddate` datetime DEFAULT NULL,
  `createdby` bigint(20) DEFAULT NULL,
  `updateddate` datetime DEFAULT NULL,
  `updatedby` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`componentId`)
);

INSERT INTO `unit` (`componentId`, `uniqueCode`, `description`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES
(1, 'Pcs', 'Piece', 0, 0, NULL, NULL, NULL, NULL),
(2, 'Li', 'Litter', 0, 0, NULL, NULL, NULL, NULL),
(3, 'Kg', 'Kilogram', 0, 0, NULL, NULL, NULL, NULL),
(4, 'Ibs', 'Pound', 0, 0, NULL, NULL, NULL, NULL);

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `componentId` bigint(20) NOT NULL AUTO_INCREMENT,
  `uniqueCode` varchar(50) DEFAULT NULL,
  `firstName` varchar(50) DEFAULT NULL,
  `lastName` varchar(50) DEFAULT NULL,
  `password` varchar(1024) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `groupId` bigint(20) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `version` int(11) DEFAULT '0',
  `createddate` datetime DEFAULT NULL,
  `createdby` bigint(20) DEFAULT NULL,
  `updateddate` datetime DEFAULT NULL,
  `updatedby` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`componentId`)
);

INSERT INTO `user` (`componentId`, `uniqueCode`, `firstName`, `lastName`, `password`, `email`, `groupId`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES
(21, 'Azad', 'Azad', 'Miah', '5e4df207b638972dff832c5ff1e26f25', '', NULL, 0, 0, NULL, NULL, NULL, NULL),
(22, 'sadmin', 'sadmin', '', 'c5edac1b8c1d58bad90a246d8f08f53b', '', NULL, 0, 0, NULL, NULL, NULL, NULL);


DROP TABLE IF EXISTS `usercompany`;
CREATE TABLE IF NOT EXISTS `usercompany` (
  `componentId` bigint(20) NOT NULL AUTO_INCREMENT,
  `userId` bigint(20) NOT NULL,
  `companyId` bigint(20) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `version` int(11) DEFAULT '0',
  `createddate` datetime DEFAULT NULL,
  `createdby` bigint(20) DEFAULT NULL,
  `updateddate` datetime DEFAULT NULL,
  `updatedby` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`componentId`)
);



DROP TABLE IF EXISTS `usercompany`;
CREATE TABLE IF NOT EXISTS `usercompany` (
  `componentId` bigint(20) NOT NULL AUTO_INCREMENT,
  `userId` bigint(20) NOT NULL,
  `companyId` bigint(20) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `version` int(11) DEFAULT '0',
  `createddate` datetime DEFAULT NULL,
  `createdby` bigint(20) DEFAULT NULL,
  `updateddate` datetime DEFAULT NULL,
  `updatedby` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`componentId`)
);


DROP TABLE IF EXISTS `userrole`;
CREATE TABLE IF NOT EXISTS `userrole` (
  `componentId` bigint(20) NOT NULL AUTO_INCREMENT,
  `userId` bigint(20) NOT NULL,
  `roleId` bigint(20) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `version` int(11) DEFAULT '0',
  `createddate` datetime DEFAULT NULL,
  `createdby` bigint(20) DEFAULT NULL,
  `updateddate` datetime DEFAULT NULL,
  `updatedby` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`componentId`,`userId`,`roleId`)
);


INSERT INTO `userrole` (`componentId`, `userId`, `roleId`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES
(1, 1, 1, 1, 1, NULL, NULL, NULL, NULL),
(2, 2, 3, 0, 0, NULL, NULL, NULL, NULL),
(3, 9, 8, 0, 0, NULL, NULL, NULL, NULL),
(4, 12, 3, 0, 0, NULL, NULL, NULL, NULL),
(5, 13, 1, 0, 0, NULL, NULL, NULL, NULL),
(6, 14, 2, 0, 0, NULL, NULL, NULL, NULL),
(7, 15, 2, 0, 0, NULL, NULL, NULL, NULL),
(8, 16, 1, 0, 0, NULL, NULL, NULL, NULL),
(9, 18, 1, 0, 0, NULL, NULL, NULL, NULL),
(10, 21, 8, 0, 0, NULL, NULL, NULL, NULL),
(11, 19, 1, 0, 0, NULL, NULL, NULL, NULL),
(12, 20, 1, 0, 0, NULL, NULL, NULL, NULL),
(13, 22, 8, 0, 0, NULL, NULL, NULL, NULL);


DROP TABLE IF EXISTS `userwarehouse`;
CREATE TABLE IF NOT EXISTS `userwarehouse` (
  `componentId` BIGINT(20) NOT NULL AUTO_INCREMENT,
  `userId` BIGINT(20) NOT NULL,
  `warehouseId` BIGINT(20) NOT NULL,
  `status` INT(11) NULL DEFAULT NULL,
  `version` INT(11) NULL DEFAULT NULL,
  `createddate` DATETIME NULL DEFAULT NULL,
  `createdby` BIGINT(20) NULL DEFAULT NULL,
  `updateddate` DATETIME NULL DEFAULT NULL,
  `updatedby` BIGINT(20) NULL DEFAULT NULL,
  PRIMARY KEY (`componentId`, `userId`, `warehouseId`)
);


DROP TABLE IF EXISTS `workerinfo`;
CREATE TABLE IF NOT EXISTS `workerinfo` (
  `WorkerId` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `email` varchar(20) NOT NULL,
  `phone` int(11) NOT NULL,
  `address1` varchar(20) NOT NULL,
  `address2` varchar(20) NOT NULL,
  `userName` varchar(20) NOT NULL,
  `password` varchar(20) NOT NULL,
  PRIMARY KEY (`WorkerId`)
);

DROP TABLE IF EXISTS `warehouse`;
CREATE TABLE IF NOT EXISTS `warehouse` (
  `componentId` BIGINT(20) NOT NULL AUTO_INCREMENT,
  `uniqueCode` VARCHAR(128) NULL DEFAULT NULL,
  `name` VARCHAR(255) NOT NULL,
  `address` VARCHAR(1000) NULL DEFAULT NULL,
  `contact` VARCHAR(255) NULL DEFAULT NULL,
  `status` INT(11) NOT NULL DEFAULT '0',
  `version` INT(11) NULL DEFAULT '0',
  `createddate` DATETIME NULL DEFAULT NULL,
  `createdby` BIGINT(20) NULL DEFAULT NULL,
  `updateddate` DATETIME NULL DEFAULT NULL,
  `updatedby` BIGINT(20) NULL DEFAULT NULL,
  PRIMARY KEY (`componentId`)
);


CREATE OR REPLACE VIEW `vInventorySummery` AS 
SELECT `i`.`componentId` AS `componentId`,
`i`.`itemName` AS `itemName`,
`i`.`category2` AS `category2`,
`i`.`category3` AS `category3`,
`i`.`salePrice` AS `salePrice`,
`i`.`minQty` AS `minQty`,
SUM(`p`.`quantity` * `p`.`type`) AS `pquantity`,
SUM(`s`.`quantity`) AS `squantity`,
SUM(`p`.`unitPrice` * `p`.`quantity`) AS `pprice`,
SUM(`s`.`unitPrice` * `s`.`quantity`) AS `sprice` 
FROM `item` `i` 
  LEFT JOIN `transaction_detail` `p` ON(`p`.`accountId` = 12 
    AND `p`.`itemId` = `i`.`componentId`) 
LEFT JOIN `transaction_detail` `s` ON( `s`.`accountId` = 13 
  AND `s`.`itemId` = `i`.`componentId`) 
WHERE (`i`.`category1` = 'INVENTORY' 
  AND ((`p`.`quantity` IS NOT NULL) OR (`s`.`quantity` IS NOT NULL))) 
GROUP BY `i`.`itemName`,`i`.`category2`,`i`.`category3`,
`i`.`salePrice`,`i`.`minQty`,`i`.`salePrice`;


CREATE OR REPLACE VIEW `vItem` AS 
SELECT `i`.`componentId` AS `componentId`,
`i`.`uniqueCode` AS `uniqueCode`,
`i`.`itemName` AS `itemName`,
`i`.`category1` AS `category1`,
`i`.`category2` AS `category2`,
`i`.`category3` AS `category3`,
`i`.`salePrice` AS `saleprice`,
`i`.`minQty` AS `minQty`,
`u`.`uniqueCode` AS `unit` 
FROM `item` `i` 
  LEFT JOIN `unit` `u` ON `i`.`unitId` = `u`.`componentId`;



CREATE OR REPLACE VIEW `vtransactiondetails` AS 
SELECT `t`.`tdate` AS `tdate`,
`t`.`uniqueCode` AS `uniqueCode`,
`t`.`type` AS `ttype`,
`a`.`category3` AS `category3`,
`t`.`componentId` AS `componentId`,
`c`.`name` AS `customerName`,
`d`.`userId` AS `userId`,
`a`.`uniqueCode` AS `accountName`,
`d`.`accountId` AS `accountId`,
`i`.`itemName` AS `itemName`,
`d`.`itemId` AS `itemId`,
`d`.`quantity` AS `quantity`,
`d`.`unitPrice` AS `unitPrice`,
`d`.`type` AS `type`,
`t`.`createdby` AS `createdby`,
concat(`u`.`firstName`,' ',`u`.`lastName`) AS `salesperson` 
FROM `transaction` `t` 
  JOIN `transaction_detail` `d` ON `t`.`componentId` = `d`.`transactionId` 
JOIN `customer` `c` ON `c`.`componentId` = `d`.`userId` 
JOIN `account` `a` ON `d`.`accountId` = `a`.`componentId` 
JOIN `item` `i` ON `d`.`itemId` = `i`.`componentId` 
LEFT JOIN `user` `u` ON `t`.`createdby` = `u`.`componentId`;


CREATE OR REPLACE VIEW `vTransactions` AS 
SELECT `t`.`componentId` AS `componentId`,
`t`.`description` AS `description`,
`t`.`uniqueCode` AS `uniqueCode`,
`t`.`tdate` AS `tdate`,
`t`.`type` AS `type`,
SUM(`d`.`quantity` * `d`.`unitPrice`) AS `amount` 
FROM `transaction` `t` 
  INNER JOIN `transaction_detail` `d` ON (`t`.`componentId` = `d`.`transactionId` 
    AND `d`.`type` = -1) 
GROUP BY `t`.`componentId`;


CREATE OR REPLACE VIEW `vuserfunctions` AS 
SELECT `f`.`componentId` AS `componentId`,
`f`.`uniqueCode` AS `uniqueCode`,
`f`.`displayName` AS `displayName`,
`f`.`functionGroup` AS `functionGroup`,
`f`.`codeNumber` AS `codeNumber`,
`f`.`actionUrl` AS `actionUrl`,
`f`.`isMenu` AS `isMenu`,
`f`.`status` AS `status`,
`f`.`version` AS `version`,
`f`.`createddate` AS `createddate`,
`f`.`createdby` AS `createdby`,
`f`.`updateddate` AS `updateddate`,
`f`.`updatedby` AS `updatedby`,
`u`.`userId` AS `userid` 
FROM (`functioncode` `f` 
  INNER JOIN `functionrole` `r` ON `f`.`componentId` = `r`.`functionId` 
INNER JOIN `userrole` `u` ON `r`.`roleId` = `u`.`roleId`);

CREATE OR REPLACE VIEW `inventoryView` AS 
SELECT `i`.`componentId` AS `itemId`,
`i`.`uniqueCode` AS `itemName`,
`i`.`category1` AS `category1`,
`i`.`category2` AS `category2`,
`i`.`category3` AS `category3`,
(`d`.`type` * `d`.`quantity`) AS `quantity`,
`t`.`tdate` AS `tdate`,
`t`.`uniqueCode` AS `voucher`,
`u`.`uniqueCode` AS `unit`,
`i`.`minQty` AS `minQty`,
`i`.`salePrice` AS `salePrice`,
`d`.`transactionId` AS `transactionId`,
`d`.`unitPrice` AS `unitPrice`,
(`d`.`unitPrice` * `d`.`quantity`) AS `amount`,
`d`.`quantity` AS `absquantity`,
`d`.`description` AS `description` 
FROM `item` `i` 
  LEFT JOIN `transaction_detail` `d` ON `d`.`itemId` = `i`.`componentId` 
LEFT JOIN `transaction` `t` ON `d`.`transactionId` = `t`.`componentId` 
LEFT JOIN `unit` `u` ON `i`.`unitId` = `u`.`componentId`
WHERE (`i`.`category1` = 'INVENTORY' AND 
  (`d`.`accountId` = 12 OR `d`.`accountId` = 13));


CREATE OR REPLACE VIEW `paymentDueView` AS 
SELECT `t`.`componentId` AS `transactionId`,
`t`.`uniqueCode` AS `uniqueCode`,
`t`.`tdate` AS `tdate`,
SUM((CASE WHEN (((`d`.`type` = -(1)) 
  AND (`t`.`type` = 'SALES') 
  AND (`d`.`accountId` <> 7)) 
OR (`d`.`type` = 1 
  AND `t`.`type` = 'PURCHASE')) 
THEN (`d`.`quantity` * `d`.`unitPrice`) ELSE 0 END)) AS `total`,
SUM(CASE WHEN (`a`.`category3` = 'BANK' 
  OR `a`.`category3` = 'CASH') THEN 
(`d`.`quantity` * `d`.`unitPrice`) ELSE 0 end) AS `paid`,
SUM(CASE WHEN (`a`.`componentId` = 4 OR `a`.`componentId` = 5) 
  THEN (`d`.`quantity` * `d`.`unitPrice`) ELSE 0 END) AS `due`,
SUM(CASE WHEN `a`.`componentId` = 9 
  THEN (`d`.`quantity` * `d`.`unitPrice`) ELSE 0 END) AS `discount` 
FROM `transaction_detail` `d` 
  JOIN `transaction` `t` ON `t`.`componentId` = `d`.`transactionId` 
JOIN `account` `a` ON `a`.`componentId` = `d`.`accountId` 
WHERE `t`.`type` = 'PURCHASE' OR `t`.`type` = 'SALES'
GROUP BY `d`.`transactionId`;

CREATE OR REPLACE VIEW `vFrequentItem` AS 
SELECT `i`.`componentId` AS `componentId`,
`i`.`uniqueCode` AS `uniqueCode`,
`i`.`itemName` AS `itemName`,
`i`.`category1` AS `category1`,
`i`.`category2` AS `category2`,
`i`.`category3` AS `category3`,
`i`.`salePrice` AS `salePrice`,
`i`.`minQty` AS `minQty`,
`u`.`uniqueCode` AS `unit`,
`i`.`status` AS `status`,
`i`.`version` AS `version`,
`i`.`createddate` AS `createddate`,
`i`.`createdby` AS `createdby`,
`i`.`updateddate` AS `updateddate`,
`i`.`updatedby` AS `updatedby`,
COUNT(`d`.`componentId`) AS `count(d.componentId)` 
FROM `item` `i` 
  LEFT JOIN `unit` `u` ON `i`.`unitId` = `u`.`componentId` 
LEFT JOIN `transaction_detail` `d` ON `d`.`itemId` = `i`.`componentId` 
LEFT JOIN `transaction` `t` ON `d`.`transactionId` = `t`.`componentId` 
WHERE `i`.`category1` = 'INVENTory' 
GROUP BY `i`.`componentId` 
ORDER BY COUNT(`d`.`componentId`) DESC;


CREATE OR REPLACE VIEW `vfunctiongroups` AS 
SELECT `f`.`functionGroup` AS `functionGroup`,
MIN(`f`.`componentId`) AS `functionid`,
COUNT(0) AS `rownum`,`u`.`userId` AS `userid` 
FROM `functioncode` `f` 
  JOIN `functionrole` `r` ON `f`.`componentId` = `r`.`functionId` 
JOIN `userrole` `u` ON `r`.`roleId` = `u`.`roleId` 
GROUP BY `u`.`userId`,`f`.`functionGroup`;


ALTER VIEW vInventorySummery AS
SELECT i.componentId AS componentId, i.itemName AS itemName,
 i.category2 AS category2, i.category3 AS category3,
 i.salePrice AS salePrice, i.minQty AS minQty,
 SUM(CASE WHEN d.accountId = 12 THEN d.quantity * d.type ELSE 0 END) AS pquantity,
 SUM(CASE WHEN d.accountId = 13 THEN d.quantity * d.type*-1 ELSE 0 END) AS squantity,
 SUM(CASE WHEN d.accountId = 12 THEN d.unitPrice * d.quantity ELSE 0 END) AS pprice,
 SUM(CASE WHEN d.accountId = 13 THEN d.unitPrice * d.quantity ELSE 0 END) AS sprice
FROM item i 
INNER JOIN transaction_detail d ON((d.accountId = 12 OR d.accountId = 13) AND d.itemId = i.componentId) 
WHERE i.category1 = 'INVENTORY'
GROUP BY i.itemName,i.category2,i.category3, i.salePrice,i.minQty,i.salePrice;


ALTER TABLE account ADD INDEX (category1);
ALTER TABLE account ADD INDEX (category2);

ALTER TABLE transaction_detail ADD INDEX (transactionId);
ALTER TABLE transaction_detail ADD INDEX (accountId);
ALTER TABLE transaction_detail ADD INDEX (itemId);
ALTER TABLE transaction_detail ADD INDEX (type);
ALTER TABLE transaction_detail ADD INDEX (quantity);

ALTER TABLE item ADD INDEX (itemName);
ALTER TABLE item ADD INDEX (unitId);
ALTER TABLE item ADD INDEX (category1);
ALTER TABLE item ADD INDEX (itemName);
ALTER TABLE item ADD INDEX (category2);
ALTER TABLE item ADD INDEX (category3);
ALTER TABLE item ADD INDEX (salePrice);
ALTER TABLE item ADD INDEX (minQty);

ALTER TABLE transaction ADD INDEX (createdby);
ALTER TABLE transaction ADD INDEX (type);
ALTER TABLE transaction ADD INDEX (tdate);

ALTER TABLE functioncode ADD INDEX (functionGroup);

ALTER TABLE sequences ADD INDEX (uniqueCode);

