
--
CREATE DATABASE IF NOT EXISTS `spos` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `spos`;

DELIMITER $$
--
-- Procedures
--
DROP PROCEDURE IF EXISTS `getsequence`$$
CREATE PROCEDURE `getsequence`(IN `seqName` VARCHAR(255))
BEGIN
   DECLARE curVal INT;
   SET curVal = -1;
 	SELECT currentValue INTO curVal FROM sequences WHERE uniqueCode = seqName;
 
   IF curVal = -1 THEN
        INSERt INTO sequences (uniqueCode,currentValue) VALUES(seqName, 0);
   END IF;
   
   UPDATE sequences SET currentValue = currentValue + 1 WHERE uniqueCode = seqName;
   
   SELECT currentValue FROM sequences WHERE uniqueCode = seqName;
END$$

--
-- Functions
--
DROP FUNCTION IF EXISTS `lastPurchasePrice`$$
CREATE FUNCTION `lastPurchasePrice`(itemId INT) RETURNS double
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

-- --------------------------------------------------------

--
-- Table structure for table `account`
--

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
)  ;

--
-- Dumping data for table `account`
--

INSERT INTO `account` (`componentId`, `uniqueCode`, `description`, `category1`, `category2`, `category3`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(1, 'Cash in Hand', 'Cash', 'ASSET', 'CURRENT ASSET', 'CASH', 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `account` (`componentId`, `uniqueCode`, `description`, `category1`, `category2`, `category3`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(4, 'Receivable', 'Receivable', 'ASSET', 'RECEIVABLE', '', 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `account` (`componentId`, `uniqueCode`, `description`, `category1`, `category2`, `category3`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(5, 'Payable', 'Payable', 'LIABILITY', '', '', 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `account` (`componentId`, `uniqueCode`, `description`, `category1`, `category2`, `category3`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(12, 'Purchase', 'Purchase', 'EXPENSE', '', '', 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `account` (`componentId`, `uniqueCode`, `description`, `category1`, `category2`, `category3`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(13, 'Sales', 'Sales', 'REVENUE', '', '', 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `account` (`componentId`, `uniqueCode`, `description`, `category1`, `category2`, `category3`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(8, 'purchase commission', 'purchase commission', 'REVENUE', '', '', 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `account` (`componentId`, `uniqueCode`, `description`, `category1`, `category2`, `category3`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(9, 'Sale discount', 'Sale discount', 'EXPENSE', '', '', 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `account` (`componentId`, `uniqueCode`, `description`, `category1`, `category2`, `category3`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(10, 'Cash advance', 'Cash advance', 'ASSET', 'RECEIVABLE', '', 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `account` (`componentId`, `uniqueCode`, `description`, `category1`, `category2`, `category3`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(17, 'NBL', 'NATIONAL BANK', 'ASSET', 'CURRENT ASSET', 'BANK', 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `account` (`componentId`, `uniqueCode`, `description`, `category1`, `category2`, `category3`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(18, 'Brack', 'Brack Bank', 'ASSET', 'CURRENT ASSET', 'BANK', 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `account` (`componentId`, `uniqueCode`, `description`, `category1`, `category2`, `category3`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(23, 'SALARY', 'STUFF SALARY', 'EXPENSE', '', '', 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `account` (`componentId`, `uniqueCode`, `description`, `category1`, `category2`, `category3`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(24, 'ENTERTAINMENT', 'ENTERTAINMENT', 'EXPENSE', '', '', 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `account` (`componentId`, `uniqueCode`, `description`, `category1`, `category2`, `category3`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(25, 'OFFICE RENT', 'SHOW-ROOM RENT', 'EXPENSE', '', '', 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `account` (`componentId`, `uniqueCode`, `description`, `category1`, `category2`, `category3`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(29, 'MESELINISE EXPENDITURE', 'OTHERS EXPENDITURE', 'EXPENSE', '', '', 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `account` (`componentId`, `uniqueCode`, `description`, `category1`, `category2`, `category3`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(30, 'PBL', 'PUBALI BANK LIMITED', 'ASSET', 'CURRENT ASSET', 'BANK', 0, 0, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--


DROP TABLE IF EXISTS `customer`;
CREATE TABLE IF NOT EXISTS `customer` (
  `componentId` bigint(20) NOT NULL AUTO_INCREMENT,
  `uniqueCode` varchar(128) DEFAULT NULL,
  `name` char(50) NOT NULL,
  `description` varchar(512) DEFAULT NULL,
  `city` char(100) NOT NULL,
  `phone` char(20) NOT NULL,
  `isCustomer` BIT(1) DEFAULT 0,
  `isSupplier` BIT(1) DEFAULT 0,
  `isOwn` BIT(1) DEFAULT 0,
  `status` int(11) NOT NULL DEFAULT '0',
  `version` int(11) DEFAULT '0',
  `createddate` datetime DEFAULT NULL,
  `createdby` bigint(20) DEFAULT NULL,
  `updateddate` datetime DEFAULT NULL,
  `updatedby` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`componentId`)
)  ;

--
-- Dumping data for table `customer`
--
INSERT INTO `customer` (`componentId`, `uniqueCode`, `name`, `description`, `city`, `phone`, `isCustomer`,`isSupplier`,`isOwn`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(1, 'Company user', 'Company user', 'Default user for company', '', '', 0,0,1, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `customer` (`componentId`, `uniqueCode`, `name`, `description`, `city`, `phone`, `isCustomer`,`isSupplier`,`isOwn`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(2, 'AnonymousCustomer', 'Anonymous Customer', 'Anonymous Customer', '', '',1,0,0 ,0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `customer` (`componentId`, `uniqueCode`, `name`, `description`, `city`, `phone`, `isCustomer`,`isSupplier`,`isOwn`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(3, 'AnonymousSupplier', 'Anonymous Supplier', 'Anonymous Supplier', '', '',0,1,0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `customer` (`componentId`, `uniqueCode`, `name`, `description`, `city`, `phone`, `isCustomer`,`isSupplier`,`isOwn`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(9, 'Test571741e254f81', 'Test', 'Test 1', 'sdsd', 'sdd', 1,0,0, 0, 0, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `functioncode`
--

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
) ;

--
-- Dumping data for table `functioncode`
--

INSERT INTO `functioncode` (`componentId`, `uniqueCode`, `displayName`, `functionGroup`, `codeNumber`, `actionUrl`, `isMenu`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(1, 'usersearch', 'Users', 'Config', 1001, 'user/search', b'1', 1, 0, NULL, NULL, NULL, NULL);
INSERT INTO `functioncode` (`componentId`, `uniqueCode`, `displayName`, `functionGroup`, `codeNumber`, `actionUrl`, `isMenu`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(2, 'userget', 'User View', 'Config', 1002, 'user/get', b'0', 1, 0, NULL, NULL, NULL, NULL);
INSERT INTO `functioncode` (`componentId`, `uniqueCode`, `displayName`, `functionGroup`, `codeNumber`, `actionUrl`, `isMenu`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(3, 'useradd', 'Add User', 'Config', 1003, 'user/add', b'0', 1, 0, NULL, NULL, NULL, NULL);
INSERT INTO `functioncode` (`componentId`, `uniqueCode`, `displayName`, `functionGroup`, `codeNumber`, `actionUrl`, `isMenu`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(4, 'usermodify', 'Modify User', 'Config', 1004, 'user/modify', b'0', 1, 0, NULL, NULL, NULL, NULL);
INSERT INTO `functioncode` (`componentId`, `uniqueCode`, `displayName`, `functionGroup`, `codeNumber`, `actionUrl`, `isMenu`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(5, 'userdelete', 'Delete User', 'Config', 1005, 'user/delete', b'0', 1, 0, NULL, NULL, NULL, NULL);
INSERT INTO `functioncode` (`componentId`, `uniqueCode`, `displayName`, `functionGroup`, `codeNumber`, `actionUrl`, `isMenu`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(6, 'rolesearch', 'Roles', 'Config', 1006, 'role/search', b'1', 1, 0, NULL, NULL, NULL, NULL);
INSERT INTO `functioncode` (`componentId`, `uniqueCode`, `displayName`, `functionGroup`, `codeNumber`, `actionUrl`, `isMenu`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(7, 'roleget', 'Role View', 'Config', 1007, 'role/get', b'0', 1, 0, NULL, NULL, NULL, NULL);
INSERT INTO `functioncode` (`componentId`, `uniqueCode`, `displayName`, `functionGroup`, `codeNumber`, `actionUrl`, `isMenu`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(8, 'roleadd', 'Add Role', 'Config', 1008, 'role/add', b'0', 1, 0, NULL, NULL, NULL, NULL);
INSERT INTO `functioncode` (`componentId`, `uniqueCode`, `displayName`, `functionGroup`, `codeNumber`, `actionUrl`, `isMenu`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(9, 'rolemodify', 'Modify Role', 'Config', 1009, 'role/modify', b'0', 1, 0, NULL, NULL, NULL, NULL);
INSERT INTO `functioncode` (`componentId`, `uniqueCode`, `displayName`, `functionGroup`, `codeNumber`, `actionUrl`, `isMenu`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(10, 'roledelete', 'Delete Role', 'Config', 1010, 'role/delete', b'0', 1, 0, NULL, NULL, NULL, NULL);
INSERT INTO `functioncode` (`componentId`, `uniqueCode`, `displayName`, `functionGroup`, `codeNumber`, `actionUrl`, `isMenu`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(11, 'functioncodesearch', 'Functions', 'Config', 1011, 'functioncode/search', b'1', 1, 0, NULL, NULL, NULL, NULL);
INSERT INTO `functioncode` (`componentId`, `uniqueCode`, `displayName`, `functionGroup`, `codeNumber`, `actionUrl`, `isMenu`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(12, 'functioncodeget', 'Function View', 'Config', 1012, 'functioncode/get', b'0', 1, 0, NULL, NULL, NULL, NULL);
INSERT INTO `functioncode` (`componentId`, `uniqueCode`, `displayName`, `functionGroup`, `codeNumber`, `actionUrl`, `isMenu`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(13, 'functioncodeadd', 'Add FunctionCode', 'Config', 1013, 'functioncode/add', b'0', 1, 0, NULL, NULL, NULL, NULL);
INSERT INTO `functioncode` (`componentId`, `uniqueCode`, `displayName`, `functionGroup`, `codeNumber`, `actionUrl`, `isMenu`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(14, 'functioncodemodify', 'Modify FunctionCode', 'Config', 1014, 'functioncode/modify', b'0', 1, 0, NULL, NULL, NULL, NULL);
INSERT INTO `functioncode` (`componentId`, `uniqueCode`, `displayName`, `functionGroup`, `codeNumber`, `actionUrl`, `isMenu`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(15, 'functioncodedelete', 'Delete FunctionCode', 'Config', 1015, 'functioncode/delete', b'0', 1, 0, NULL, NULL, NULL, NULL);
INSERT INTO `functioncode` (`componentId`, `uniqueCode`, `displayName`, `functionGroup`, `codeNumber`, `actionUrl`, `isMenu`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(16, 'roleassignment', 'Role Assignment', 'Config', 1016, 'role/assignment', b'1', 1, 0, NULL, NULL, NULL, NULL);
INSERT INTO `functioncode` (`componentId`, `uniqueCode`, `displayName`, `functionGroup`, `codeNumber`, `actionUrl`, `isMenu`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(17, 'functionassignment', 'Function Assignment', 'Config', 1017, 'function/assignment', b'1', 1, 0, NULL, NULL, NULL, NULL);
INSERT INTO `functioncode` (`componentId`, `uniqueCode`, `displayName`, `functionGroup`, `codeNumber`, `actionUrl`, `isMenu`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(18, 'customersearch', 'Customers', 'Config', 1018, 'customer/search', b'1', 1, 0, NULL, NULL, NULL, NULL);
INSERT INTO `functioncode` (`componentId`, `uniqueCode`, `displayName`, `functionGroup`, `codeNumber`, `actionUrl`, `isMenu`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(19, 'customeradd', 'Add Customer', 'Config', 1019, '/view/user/roleaddmodify.php', b'0', 1, 0, NULL, NULL, NULL, NULL);
INSERT INTO `functioncode` (`componentId`, `uniqueCode`, `displayName`, `functionGroup`, `codeNumber`, `actionUrl`, `isMenu`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(20, 'customermodify', 'Modify Customer', 'Config', 1020, '/view/user/useraddmodify.php', b'0', 1, 0, NULL, NULL, NULL, NULL);
INSERT INTO `functioncode` (`componentId`, `uniqueCode`, `displayName`, `functionGroup`, `codeNumber`, `actionUrl`, `isMenu`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(21, 'customerdelete', 'Delete Customer', 'Config', 1021, '/view/user/useraddmodify.php', b'0', 1, 0, NULL, NULL, NULL, NULL);
INSERT INTO `functioncode` (`componentId`, `uniqueCode`, `displayName`, `functionGroup`, `codeNumber`, `actionUrl`, `isMenu`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(22, 'accountsearch', 'Accounts', 'Accounting', 1022, 'account/search', b'1', 1, 0, NULL, NULL, NULL, NULL);
INSERT INTO `functioncode` (`componentId`, `uniqueCode`, `displayName`, `functionGroup`, `codeNumber`, `actionUrl`, `isMenu`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(23, 'accountadd', 'Add Account', 'Accounting', 1023, 'account/add', b'0', 1, 0, NULL, NULL, NULL, NULL);
INSERT INTO `functioncode` (`componentId`, `uniqueCode`, `displayName`, `functionGroup`, `codeNumber`, `actionUrl`, `isMenu`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(24, 'accountmodify', 'Modify Account', 'Accounting', 1024, 'account/modify', b'0', 1, 0, NULL, NULL, NULL, NULL);
INSERT INTO `functioncode` (`componentId`, `uniqueCode`, `displayName`, `functionGroup`, `codeNumber`, `actionUrl`, `isMenu`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(25, 'accountdelete', 'Delete Account', 'Accounting', 1025, 'account/delete', b'0', 1, 0, NULL, NULL, NULL, NULL);
INSERT INTO `functioncode` (`componentId`, `uniqueCode`, `displayName`, `functionGroup`, `codeNumber`, `actionUrl`, `isMenu`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(26, 'transactionsearch', 'Transactions', 'Accounting', 1026, 'transaction/search', b'1', 1, 0, NULL, NULL, NULL, NULL);
INSERT INTO `functioncode` (`componentId`, `uniqueCode`, `displayName`, `functionGroup`, `codeNumber`, `actionUrl`, `isMenu`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(27, 'transactionadd', 'Add Transaction', 'Accounting', 1027, 'transaction/add', b'0', 1, 0, NULL, NULL, NULL, NULL);
INSERT INTO `functioncode` (`componentId`, `uniqueCode`, `displayName`, `functionGroup`, `codeNumber`, `actionUrl`, `isMenu`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(28, 'transactionmodify', 'Modify Transaction', 'Accounting', 1028, 'transaction/modify.php', b'0', 1, 0, NULL, NULL, NULL, NULL);
INSERT INTO `functioncode` (`componentId`, `uniqueCode`, `displayName`, `functionGroup`, `codeNumber`, `actionUrl`, `isMenu`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(29, 'transactiondelete', 'Delete Transaction', 'Accounting', 1029, 'transaction/transactionaddmodify.php', b'0', 1, 0, NULL, NULL, NULL, NULL);
INSERT INTO `functioncode` (`componentId`, `uniqueCode`, `displayName`, `functionGroup`, `codeNumber`, `actionUrl`, `isMenu`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(30, 'customersearch', 'Customers', 'Accounting', 1018, 'customer/search', b'1', 1, 0, NULL, NULL, NULL, NULL);
INSERT INTO `functioncode` (`componentId`, `uniqueCode`, `displayName`, `functionGroup`, `codeNumber`, `actionUrl`, `isMenu`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(31, 'customeradd', 'Add Customer', 'Accounting', 1019, '/view/user/roleaddmodify.php', b'0', 1, 0, NULL, NULL, NULL, NULL);
INSERT INTO `functioncode` (`componentId`, `uniqueCode`, `displayName`, `functionGroup`, `codeNumber`, `actionUrl`, `isMenu`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(32, 'customermodify', 'Modify Customer', 'Accounting', 1020, '/view/user/useraddmodify.php', b'0', 1, 0, NULL, NULL, NULL, NULL);
INSERT INTO `functioncode` (`componentId`, `uniqueCode`, `displayName`, `functionGroup`, `codeNumber`, `actionUrl`, `isMenu`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(33, 'customerdelete', 'Delete Customer', 'Accounting', 1021, '/view/user/useraddmodify.php', b'0', 1, 0, NULL, NULL, NULL, NULL);
INSERT INTO `functioncode` (`componentId`, `uniqueCode`, `displayName`, `functionGroup`, `codeNumber`, `actionUrl`, `isMenu`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(34, 'suppliersearch', 'Suppliers', 'Accounting', 1018, 'supplier/search', b'1', 1, 0, NULL, NULL, NULL, NULL);
INSERT INTO `functioncode` (`componentId`, `uniqueCode`, `displayName`, `functionGroup`, `codeNumber`, `actionUrl`, `isMenu`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(35, 'supplieradd', 'Add Supplier', 'Accounting', 1019, '/view/user/roleaddmodify.php', b'0', 1, 0, NULL, NULL, NULL, NULL);
INSERT INTO `functioncode` (`componentId`, `uniqueCode`, `displayName`, `functionGroup`, `codeNumber`, `actionUrl`, `isMenu`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(36, 'suppliermodify', 'Modify Supplier', 'Accounting', 1020, '/view/user/useraddmodify.php', b'0', 1, 0, NULL, NULL, NULL, NULL);
INSERT INTO `functioncode` (`componentId`, `uniqueCode`, `displayName`, `functionGroup`, `codeNumber`, `actionUrl`, `isMenu`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(37, 'supplierdelete', 'Delete Supplier', 'Accounting', 1021, '/view/user/useraddmodify.php', b'0', 1, 0, NULL, NULL, NULL, NULL);
INSERT INTO `functioncode` (`componentId`, `uniqueCode`, `displayName`, `functionGroup`, `codeNumber`, `actionUrl`, `isMenu`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(38, 'itemsearch', 'Items', 'Accounting', 1022, 'item/search', b'1', 1, 0, NULL, NULL, NULL, NULL);
INSERT INTO `functioncode` (`componentId`, `uniqueCode`, `displayName`, `functionGroup`, `codeNumber`, `actionUrl`, `isMenu`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(39, 'itemadd', 'Add Item', 'Accounting', 1024, 'item/add', b'0', 1, 0, NULL, NULL, NULL, NULL);
INSERT INTO `functioncode` (`componentId`, `uniqueCode`, `displayName`, `functionGroup`, `codeNumber`, `actionUrl`, `isMenu`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(40, 'itemmodify', 'Modify Item', 'Accounting', 1025, 'item/modify', b'0', 1, 0, NULL, NULL, NULL, NULL);
INSERT INTO `functioncode` (`componentId`, `uniqueCode`, `displayName`, `functionGroup`, `codeNumber`, `actionUrl`, `isMenu`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(41, 'itemdelete', 'Delete Item', 'Accounting', 1026, 'item/delete', b'0', 1, 0, NULL, NULL, NULL, NULL);
INSERT INTO `functioncode` (`componentId`, `uniqueCode`, `displayName`, `functionGroup`, `codeNumber`, `actionUrl`, `isMenu`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(42, 'purchasesearch', 'Search Purchase', 'Purchase', 1027, 'purchase/search', b'1', 1, 0, NULL, NULL, NULL, NULL);
INSERT INTO `functioncode` (`componentId`, `uniqueCode`, `displayName`, `functionGroup`, `codeNumber`, `actionUrl`, `isMenu`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(43, 'Add Purchase', 'Add Purchase', 'Purchase', 1028, 'purchase/add', b'1', 1, 0, NULL, NULL, NULL, NULL);
INSERT INTO `functioncode` (`componentId`, `uniqueCode`, `displayName`, `functionGroup`, `codeNumber`, `actionUrl`, `isMenu`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(44, 'inventorysearch', 'Inventory', 'Inventory', 1029, 'inventory/search', b'1', 1, 0, NULL, NULL, NULL, NULL);
INSERT INTO `functioncode` (`componentId`, `uniqueCode`, `displayName`, `functionGroup`, `codeNumber`, `actionUrl`, `isMenu`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(45, 'Add Inventory', 'Inventory', 'Inventory', 1030, 'inventory/add', b'0', 1, 0, NULL, NULL, NULL, NULL);
INSERT INTO `functioncode` (`componentId`, `uniqueCode`, `displayName`, `functionGroup`, `codeNumber`, `actionUrl`, `isMenu`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(46, 'salesearch', 'Search Sale', 'Sale', 1031, 'sale/search', b'1', 1, 0, NULL, NULL, NULL, NULL);
INSERT INTO `functioncode` (`componentId`, `uniqueCode`, `displayName`, `functionGroup`, `codeNumber`, `actionUrl`, `isMenu`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(47, 'Add Sale', 'Add Sale', 'Sale', 1032, 'sale/add', b'1', 1, 0, NULL, NULL, NULL, NULL);
INSERT INTO `functioncode` (`componentId`, `uniqueCode`, `displayName`, `functionGroup`, `codeNumber`, `actionUrl`, `isMenu`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(48, 'ledgersearch', 'Ledger', 'Report', 1033, 'ledger/search', b'1', 1, 0, NULL, NULL, NULL, NULL);
INSERT INTO `functioncode` (`componentId`, `uniqueCode`, `displayName`, `functionGroup`, `codeNumber`, `actionUrl`, `isMenu`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(49, 'recpay', 'Receipts &amp; Payments', 'Report', 1034, 'ledger/recpay', b'1', 1, 0, NULL, NULL, NULL, NULL);
INSERT INTO `functioncode` (`componentId`, `uniqueCode`, `displayName`, `functionGroup`, `codeNumber`, `actionUrl`, `isMenu`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(50, 'payment', 'Payments', 'Accounting', 1035, 'transaction/payment', b'1', 1, 0, NULL, NULL, NULL, NULL);
INSERT INTO `functioncode` (`componentId`, `uniqueCode`, `displayName`, `functionGroup`, `codeNumber`, `actionUrl`, `isMenu`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(51, 'receive', 'Receives', 'Accounting', 1036, 'transaction/receive', b'1', 1, 0, NULL, NULL, NULL, NULL);
INSERT INTO `functioncode` (`componentId`, `uniqueCode`, `displayName`, `functionGroup`, `codeNumber`, `actionUrl`, `isMenu`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(52, 'warehousesearch', 'warehousesearchs', 'warehouse', 1037, 'warehouse/search', b'1', 1, 0, NULL, NULL, NULL, NULL);
INSERT INTO `functioncode` (`componentId`, `uniqueCode`, `displayName`, `functionGroup`, `codeNumber`, `actionUrl`, `isMenu`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(53, 'warehouseadd', 'warehouseadds', 'warehouse', 1038, 'warehouse/index', b'1', 1, 0, NULL, NULL, NULL, NULL);
INSERT INTO `functioncode` (`componentId`, `uniqueCode`, `displayName`, `functionGroup`, `codeNumber`, `actionUrl`, `isMenu`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(54, 'warehousesend', 'warehousesends', 'warehouse', 1039, 'warehouse/newSendWarehouse', b'1', 1, 0, NULL, NULL, NULL, NULL);
INSERT INTO `functioncode` (`componentId`, `uniqueCode`, `displayName`, `functionGroup`, `codeNumber`, `actionUrl`, `isMenu`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(55, 'warehouseshowsend', 'warehouseshowsends', 'warehouse', 1040, 'warehouse/showSendInventory', b'1', 1, 0, NULL, NULL, NULL, NULL);
INSERT INTO `functioncode` (`componentId`, `uniqueCode`, `displayName`, `functionGroup`, `codeNumber`, `actionUrl`, `isMenu`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(56, 'warehousereceived', 'warehousereceiveds', 'warehouse', 1041, 'warehouse/warehousesReceivedProduct', b'1', 1, 0, NULL, NULL, NULL, NULL);
INSERT INTO `functioncode` (`componentId`, `uniqueCode`, `displayName`, `functionGroup`, `codeNumber`, `actionUrl`, `isMenu`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(57, 'warehouseassignment', 'Warehouse Assignment', 'Config', 1042, 'warehouse/assignment', b'1', 1, 0, NULL, NULL, NULL, NULL);


-- --------------------------------------------------------

--
-- Table structure for table `functionrole`
--

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
)  ;

--
-- Dumping data for table `functionrole`
--

INSERT INTO `functionrole` (`componentId`, `functionId`, `roleId`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(1, 1, 1, 1, 1, NULL, NULL, NULL, NULL);
INSERT INTO `functionrole` (`componentId`, `functionId`, `roleId`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(2, 2, 1, 1, 1, NULL, NULL, NULL, NULL);
INSERT INTO `functionrole` (`componentId`, `functionId`, `roleId`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(3, 3, 1, 1, 1, NULL, NULL, NULL, NULL);
INSERT INTO `functionrole` (`componentId`, `functionId`, `roleId`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(4, 4, 1, 1, 1, NULL, NULL, NULL, NULL);
INSERT INTO `functionrole` (`componentId`, `functionId`, `roleId`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(5, 5, 1, 1, 1, NULL, NULL, NULL, NULL);
INSERT INTO `functionrole` (`componentId`, `functionId`, `roleId`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(6, 6, 1, 1, 1, NULL, NULL, NULL, NULL);
INSERT INTO `functionrole` (`componentId`, `functionId`, `roleId`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(7, 7, 1, 1, 1, NULL, NULL, NULL, NULL);
INSERT INTO `functionrole` (`componentId`, `functionId`, `roleId`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(8, 8, 1, 1, 1, NULL, NULL, NULL, NULL);
INSERT INTO `functionrole` (`componentId`, `functionId`, `roleId`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(9, 9, 1, 1, 1, NULL, NULL, NULL, NULL);
INSERT INTO `functionrole` (`componentId`, `functionId`, `roleId`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(10, 10, 1, 1, 1, NULL, NULL, NULL, NULL);
INSERT INTO `functionrole` (`componentId`, `functionId`, `roleId`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(11, 11, 1, 1, 1, NULL, NULL, NULL, NULL);
INSERT INTO `functionrole` (`componentId`, `functionId`, `roleId`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(12, 12, 1, 1, 1, NULL, NULL, NULL, NULL);
INSERT INTO `functionrole` (`componentId`, `functionId`, `roleId`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(13, 13, 1, 1, 1, NULL, NULL, NULL, NULL);
INSERT INTO `functionrole` (`componentId`, `functionId`, `roleId`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(14, 14, 1, 1, 1, NULL, NULL, NULL, NULL);
INSERT INTO `functionrole` (`componentId`, `functionId`, `roleId`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(15, 15, 1, 1, 1, NULL, NULL, NULL, NULL);
INSERT INTO `functionrole` (`componentId`, `functionId`, `roleId`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(16, 16, 1, 1, 1, NULL, NULL, NULL, NULL);
INSERT INTO `functionrole` (`componentId`, `functionId`, `roleId`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(17, 17, 1, 1, 1, NULL, NULL, NULL, NULL);
INSERT INTO `functionrole` (`componentId`, `functionId`, `roleId`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(18, 18, 1, 1, 1, NULL, NULL, NULL, NULL);
INSERT INTO `functionrole` (`componentId`, `functionId`, `roleId`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(19, 19, 1, 1, 1, NULL, NULL, NULL, NULL);
INSERT INTO `functionrole` (`componentId`, `functionId`, `roleId`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(20, 20, 1, 1, 1, NULL, NULL, NULL, NULL);
INSERT INTO `functionrole` (`componentId`, `functionId`, `roleId`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(21, 21, 1, 1, 1, NULL, NULL, NULL, NULL);
INSERT INTO `functionrole` (`componentId`, `functionId`, `roleId`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(22, 22, 1, 1, 1, NULL, NULL, NULL, NULL);
INSERT INTO `functionrole` (`componentId`, `functionId`, `roleId`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(23, 23, 1, 1, 1, NULL, NULL, NULL, NULL);
INSERT INTO `functionrole` (`componentId`, `functionId`, `roleId`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(24, 24, 1, 1, 1, NULL, NULL, NULL, NULL);
INSERT INTO `functionrole` (`componentId`, `functionId`, `roleId`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(25, 25, 1, 1, 1, NULL, NULL, NULL, NULL);
INSERT INTO `functionrole` (`componentId`, `functionId`, `roleId`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(26, 26, 1, 1, 1, NULL, NULL, NULL, NULL);
INSERT INTO `functionrole` (`componentId`, `functionId`, `roleId`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(27, 27, 1, 1, 1, NULL, NULL, NULL, NULL);
INSERT INTO `functionrole` (`componentId`, `functionId`, `roleId`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(28, 28, 1, 1, 1, NULL, NULL, NULL, NULL);
INSERT INTO `functionrole` (`componentId`, `functionId`, `roleId`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(29, 29, 1, 1, 1, NULL, NULL, NULL, NULL);
INSERT INTO `functionrole` (`componentId`, `functionId`, `roleId`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(30, 30, 1, 1, 1, NULL, NULL, NULL, NULL);
INSERT INTO `functionrole` (`componentId`, `functionId`, `roleId`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(31, 31, 1, 1, 1, NULL, NULL, NULL, NULL);
INSERT INTO `functionrole` (`componentId`, `functionId`, `roleId`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(32, 32, 1, 1, 1, NULL, NULL, NULL, NULL);
INSERT INTO `functionrole` (`componentId`, `functionId`, `roleId`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(33, 33, 1, 1, 1, NULL, NULL, NULL, NULL);
INSERT INTO `functionrole` (`componentId`, `functionId`, `roleId`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(34, 34, 1, 1, 1, NULL, NULL, NULL, NULL);
INSERT INTO `functionrole` (`componentId`, `functionId`, `roleId`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(35, 35, 1, 1, 1, NULL, NULL, NULL, NULL);
INSERT INTO `functionrole` (`componentId`, `functionId`, `roleId`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(36, 36, 1, 1, 1, NULL, NULL, NULL, NULL);
INSERT INTO `functionrole` (`componentId`, `functionId`, `roleId`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(37, 37, 1, 1, 1, NULL, NULL, NULL, NULL);
INSERT INTO `functionrole` (`componentId`, `functionId`, `roleId`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(38, 38, 1, 1, 1, NULL, NULL, NULL, NULL);
INSERT INTO `functionrole` (`componentId`, `functionId`, `roleId`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(39, 39, 1, 1, 1, NULL, NULL, NULL, NULL);
INSERT INTO `functionrole` (`componentId`, `functionId`, `roleId`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(40, 40, 1, 1, 1, NULL, NULL, NULL, NULL);
INSERT INTO `functionrole` (`componentId`, `functionId`, `roleId`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(41, 41, 1, 1, 1, NULL, NULL, NULL, NULL);
INSERT INTO `functionrole` (`componentId`, `functionId`, `roleId`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(42, 42, 1, 1, 1, NULL, NULL, NULL, NULL);
INSERT INTO `functionrole` (`componentId`, `functionId`, `roleId`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(43, 43, 1, 1, 1, NULL, NULL, NULL, NULL);
INSERT INTO `functionrole` (`componentId`, `functionId`, `roleId`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(44, 44, 1, 1, 1, NULL, NULL, NULL, NULL);
INSERT INTO `functionrole` (`componentId`, `functionId`, `roleId`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(45, 45, 1, 1, 1, NULL, NULL, NULL, NULL);
INSERT INTO `functionrole` (`componentId`, `functionId`, `roleId`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(46, 46, 1, 1, 1, NULL, NULL, NULL, NULL);
INSERT INTO `functionrole` (`componentId`, `functionId`, `roleId`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(47, 47, 1, 1, 1, NULL, NULL, NULL, NULL);
INSERT INTO `functionrole` (`componentId`, `functionId`, `roleId`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(48, 48, 1, 1, 1, NULL, NULL, NULL, NULL);
INSERT INTO `functionrole` (`componentId`, `functionId`, `roleId`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(49, 49, 1, 1, 1, NULL, NULL, NULL, NULL);
INSERT INTO `functionrole` (`componentId`, `functionId`, `roleId`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(50, 50, 1, 1, 1, NULL, NULL, NULL, NULL);
INSERT INTO `functionrole` (`componentId`, `functionId`, `roleId`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(51, 51, 1, 1, 1, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `group`
--

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
) ;

-- --------------------------------------------------------

--
-- Table structure for table `item`
--

DROP TABLE IF EXISTS `item`;
CREATE TABLE IF NOT EXISTS `item` (
  `componentId` bigint(20) NOT NULL AUTO_INCREMENT,
  `uniqueCode` varchar(128) DEFAULT NULL,
  `itemName` char(75) NOT NULL,
  `category1` varchar(128) DEFAULT NULL,
  `category2` varchar(128) DEFAULT NULL,
  `category3` varchar(128) DEFAULT NULL,
  `salePrice` float DEFAULT NULL,
  `minQty` int(11) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `version` int(11) DEFAULT '0',
  `createddate` datetime DEFAULT NULL,
  `createdby` bigint(20) DEFAULT NULL,
  `updateddate` datetime DEFAULT NULL,
  `updatedby` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`componentId`)
)  ;

--
-- Dumping data for table `item`
--

INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(0, 'Taka', 'Taka', NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(5, 'INVENTORY/LEd tv//WT55S46(55")', 'WT55S46(55")', 'INVENTORY', 'LEd tv', '', 90000, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(6, 'INVENTORY/LEd tv//WH55K370(55")', 'WH55K370(55")', 'INVENTORY', 'LEd tv', '', 89000, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(7, 'INVENTORY/LED tv/Smart/W55E3000AS(55" smart led)', 'W55E3000AS(55" smart led)', 'INVENTORY', 'LED tv', 'Smart', 81900, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(8, 'INVENTORY/LED tv//W55C20(55")', 'W55C20(55")', 'INVENTORY', 'LED tv', '', 86000, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(9, 'INVENTORY/LED tv//WSD55FD(55")', 'WSD55FD(55")', 'INVENTORY', 'LED tv', '', 70900, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(10, 'INVENTORY/LED tv//WT50E30(50")', 'WT50E30(50")', 'INVENTORY', 'LED tv', '', 84800, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(11, 'INVENTORY/LED tv//WC50B5000(50")', 'WC50B5000(50")', 'INVENTORY', 'LED tv', '', 84500, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(12, 'INVENTORY/LED tv//W50E59(50")', 'W50E59(50")', 'INVENTORY', 'LED tv', '', 83500, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(13, 'INVENTORY/LEd tv//WC50D12(50")', 'WC50D12(50")', 'INVENTORY', 'LEd tv', '', 74100, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(14, 'INVENTORY/LED tv//WH50K20(50")', 'WH50K20(50")', 'INVENTORY', 'LED tv', '', 72000, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(15, 'INVENTORY/LED tv//WSD49FD(49")', 'WSD49FD(49")', 'INVENTORY', 'LED tv', '', 57900, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(16, 'INVENTORY/LED tv//WT48S46(48")', 'WT48S46(48")', 'INVENTORY', 'LED tv', '', 70900, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(17, 'INVENTORY/LED tv//W46T3500(46")', 'W46T3500(46")', 'INVENTORY', 'LED tv', '', 69900, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(18, 'INVENTORY/LED tv/Smart/W49E3000AS(49" smart led)', 'W49E3000AS(49" smart led)', 'INVENTORY', 'LED tv', 'Smart', 66900, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(19, 'INVENTORY/LED tv/Smart/W43E3000AS(43" Smart Led)', 'W43E3000AS(43" Smart Led)', 'INVENTORY', 'LED tv', 'Smart', 52900, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(20, 'INVENTORY/LED tv//W43E3000(43")', 'W43E3000(43")', 'INVENTORY', 'LED tv', '', 44500, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(21, 'INVENTORY/LED tv//WSD43FD(43")', 'WSD43FD(43")', 'INVENTORY', 'LED tv', '', 42900, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(22, 'INVENTORY/LED tv//W42E68(42")', 'W42E68(42")', 'INVENTORY', 'LED tv', '', 67900, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(23, 'INVENTORY/LED tv//W42T3500(42")', 'W42T3500(42")', 'INVENTORY', 'LED tv', '', 66500, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(24, 'INVENTORY/LED tv//W42E59(42")', 'W42E59(42")', 'INVENTORY', 'LED tv', '', 62500, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(27, 'INVENTORY/LED tv//WC42B5000(42")', 'WC42B5000(42")', 'INVENTORY', 'LED tv', '', 62000, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(30, 'INVENTORY/LED tv//WC42D12(42")', 'WC42D12(42")', 'INVENTORY', 'LED tv', '', 53900, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(31, 'INVENTORY/LED tv//W40E3000-FHD(40")', 'W40E3000-FHD(40")', 'INVENTORY', 'LED tv', '', 39900, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(32, 'INVENTORY/LED tv//WD406AFH(40")', 'WD406AFH(40")', 'INVENTORY', 'LED tv', '', 35900, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(33, 'INVENTORY/LED tv//WT40S46(40")', 'WT40S46(40")', 'INVENTORY', 'LED tv', '', 46500, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(34, 'INVENTORY/LED tv//WH40K20(40")', 'WH40K20(40")', 'INVENTORY', 'LED tv', '', 43900, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(35, 'INVENTORY/LED tv//WC40D12(40")', 'WC40D12(40")', 'INVENTORY', 'LED tv', '', 40900, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(36, 'INVENTORY/LEd tv//W40E36D(40")', 'W40E36D(40")', 'INVENTORY', 'LEd tv', '', 39900, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(37, 'INVENTORY/LEd tv//WSD40FD(40")', 'WSD40FD(40")', 'INVENTORY', 'LEd tv', '', 33900, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(38, 'INVENTORY/LED tv//W39T3500(39")', 'W39T3500(39")', 'INVENTORY', 'LED tv', '', 51300, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(39, 'INVENTORY/LED tv//WC39B5000(39")', 'WC39B5000(39")', 'INVENTORY', 'LED tv', '', 49500, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(40, 'INVENTORY/LED tv//W32E59(32")', 'W32E59(32")', 'INVENTORY', 'LED tv', '', 41200, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(41, 'INVENTORY/LED tv//WC32B5000(32")', 'WC32B5000(32")', 'INVENTORY', 'LED tv', '', 35500, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(42, 'INVENTORY/LED Tv/INTERNET/W32E510IS', 'W32E510IS', 'INVENTORY', 'LED Tv', 'INTERNET', 35900, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(43, 'INVENTORY/LED Tv/INTERNET/W32B28EX', 'W32B28EX', 'INVENTORY', 'LED Tv', 'INTERNET', 35900, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(44, 'INVENTORY/LED tv//WT32S46(32")', 'WT32S46(32")', 'INVENTORY', 'LED tv', '', 33900, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(45, 'INVENTORY/LED Tv//W32E36/36D(32")', 'W32E36/36D(32")', 'INVENTORY', 'LED Tv', '', 33900, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(46, 'INVENTORY/LED Tv//W32T3500(32")', 'W32T3500(32")', 'INVENTORY', 'LED Tv', '', 32900, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(47, 'INVENTORY/LED Tv//W32T80D(32")', 'W32T80D(32")', 'INVENTORY', 'LED Tv', '', 32100, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(48, 'INVENTORY/LED tv//WC32D12(32")', 'WC32D12(32")', 'INVENTORY', 'LED tv', '', 31900, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(49, 'INVENTORY/LED Tv//WH32K370(32")', 'WH32K370(32")', 'INVENTORY', 'LED Tv', '', 31800, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(50, 'INVENTORY/LED tv//W32E510(32")', 'W32E510(32")', 'INVENTORY', 'LED tv', '', 31990, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(51, 'INVENTORY/LED tv//WE326DH-S(32")', 'WE326DH-S(32")', 'INVENTORY', 'LED tv', '', 29990, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(52, 'INVENTORY/LED Tv//W32E3000(32")', 'W32E3000(32")', 'INVENTORY', 'LED Tv', '', 26700, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(53, 'INVENTORY/LED tv//W32B28D(32")', 'W32B28D(32")', 'INVENTORY', 'LED tv', '', 30500, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(54, 'INVENTORY/LED tv//WD326JX(32")', 'WD326JX(32")', 'INVENTORY', 'LED tv', '', 23900, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(55, 'INVENTORY/LED tv//WT32HD(32")', 'WT32HD(32")', 'INVENTORY', 'LED tv', '', 21600, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(56, 'INVENTORY/LED Tv//WT32D27(32")', 'WT32D27(32")', 'INVENTORY', 'LED Tv', '', 24500, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(57, 'INVENTORY/LED tv//WD325AH(32")', 'WD325AH(32")', 'INVENTORY', 'LED tv', '', 24900, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(58, 'INVENTORY/LED tv//WT32E42(32")', 'WT32E42(32")', 'INVENTORY', 'LED tv', '', 27500, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(59, 'INVENTORY/LED Tv//WSD32HD(32")', 'WSD32HD(32")', 'INVENTORY', 'LED Tv', '', 22950, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(60, 'INVENTORY/LED Tv//WE326AH(32")', 'WE326AH(32")', 'INVENTORY', 'LED Tv', '', 21900, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(61, 'INVENTORY/LED Tv//WD32SR(32")', 'WD32SR(32")', 'INVENTORY', 'LED Tv', '', 20900, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(62, 'INVENTORY/LED Tv//W28T3510(28")', 'W28T3510(28")', 'INVENTORY', 'LED Tv', '', 29700, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(63, 'INVENTORY/LED Tv//W28C20(28")', 'W28C20(28")', 'INVENTORY', 'LED Tv', '', 27900, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(64, 'INVENTORY/LED Tv//W28E36D(28")', 'W28E36D(28")', 'INVENTORY', 'LED Tv', '', 26800, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(65, 'INVENTORY/LEd tv//WT28D27(28")', 'WT28D27(28")', 'INVENTORY', 'LEd tv', '', 19300, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(66, 'INVENTORY/LED tv//WT28HD(28")', 'WT28HD(28")', 'INVENTORY', 'LED tv', '', 19350, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(67, 'INVENTORY/LED Tv//WD285AH(28")', 'WD285AH(28")', 'INVENTORY', 'LED Tv', '', 20900, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(68, 'INVENTORY/LED Tv//WT28E42(28")', 'WT28E42(28")', 'INVENTORY', 'LED Tv', '', 24500, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(69, 'INVENTORY/LED Tv//W24T3540(24")', 'W24T3540(24")', 'INVENTORY', 'LED Tv', '', 23900, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(70, 'INVENTORY/LED Tv//WC24B1000(24")', 'WC24B1000(24")', 'INVENTORY', 'LED Tv', '', 23700, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(71, 'INVENTORY/LED Tv//W24TH03(24")', 'W24TH03(24")', 'INVENTORY', 'LED Tv', '', 23100, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(72, 'INVENTORY/LED Tv//WC24D12(24")', 'WC24D12(24")', 'INVENTORY', 'LED Tv', '', 23000, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(73, 'INVENTORY/LED Tv//W24E990(24")', 'W24E990(24")', 'INVENTORY', 'LED Tv', '', 22900, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(74, 'INVENTORY/LED Tv//WT24B26(24")', 'WT24B26(24")', 'INVENTORY', 'LED Tv', '', 21500, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(75, 'INVENTORY/LED tv//W24E66B(24")', 'W24E66B(24")', 'INVENTORY', 'LED tv', '', 19990, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(76, 'INVENTORY/LED tv//WCT2404(24")', 'WCT2404(24")', 'INVENTORY', 'LED tv', '', 15500, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(77, 'INVENTORY/LED tv//WCT2404X(24")', 'WCT2404X(24")', 'INVENTORY', 'LED tv', '', 15900, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(78, 'INVENTORY/LED tv//WCT2404C(24")', 'WCT2404C(24")', 'INVENTORY', 'LED tv', '', 15700, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(79, 'INVENTORY/LED tv//WCT2404K(24")', 'WCT2404K(24")', 'INVENTORY', 'LED tv', '', 13900, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(80, 'INVENTORY/LED tv//WCT2404B(24")LIMITED MODEL', 'WCT2404B(24")LIMITED MODEL', 'INVENTORY', 'LED tv', '', 13900, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(81, 'INVENTORY/LED tv//WH24D30(24")', 'WH24D30(24")', 'INVENTORY', 'LED tv', '', 19800, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(82, 'INVENTORY/LED tv//W23T80(23")', 'W23T80(23")', 'INVENTORY', 'LED tv', '', 21800, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(83, 'INVENTORY/LED tv//WT23E42(23")', 'WT23E42(23")', 'INVENTORY', 'LED tv', '', 18700, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(84, 'INVENTORY/LED tv//W22TH03(22")', 'W22TH03(22")', 'INVENTORY', 'LED tv', '', 20500, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(85, 'INVENTORY/LED tv//WC22D12(22")', 'WC22D12(22")', 'INVENTORY', 'LED tv', '', 19800, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(86, 'INVENTORY/LED tv//W19E990(19")', 'W19E990(19")', 'INVENTORY', 'LED tv', '', 15600, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(87, 'INVENTORY/LED tv//W19C86(19")', 'W19C86(19")', 'INVENTORY', 'LED tv', '', 15500, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(88, 'INVENTORY/LED tv//WT19T3540(19")', 'WT19T3540(19")', 'INVENTORY', 'LED tv', '', 15200, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(89, 'INVENTORY/LED tv//W19TH03(19")', 'W19TH03(19")', 'INVENTORY', 'LED tv', '', 15100, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(90, 'INVENTORY/LED tv//W19E66B(19")', 'W19E66B(19")', 'INVENTORY', 'LED tv', '', 14900, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(91, 'INVENTORY/LED tv//WC19D12(19")', 'WC19D12(19")', 'INVENTORY', 'LED tv', '', 14900, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(92, 'INVENTORY/LED tv//WT19E42(19")', 'WT19E42(19")', 'INVENTORY', 'LED tv', '', 13950, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(93, 'INVENTORY/LED tv//WCT1904(19")', 'WCT1904(19")', 'INVENTORY', 'LED tv', '', 12500, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(94, 'INVENTORY/LED tv//WCT1904X(19")', 'WCT1904X(19")', 'INVENTORY', 'LED tv', '', 12950, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(95, 'INVENTORY/LED tv//WCT1904C(19")', 'WCT1904C(19")', 'INVENTORY', 'LED tv', '', 12800, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(96, 'INVENTORY/LED tv//WCT1904K(19")', 'WCT1904K(19")', 'INVENTORY', 'LED tv', '', 11600, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(97, 'INVENTORY/LED tv//WCT1904B(19")LIMITED MODEL', 'WCT1904B(19")LIMITED MODEL', 'INVENTORY', 'LED tv', '', 10900, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(98, 'INVENTORY/LED tv//WC16E1(16")', 'WC16E1(16")', 'INVENTORY', 'LED tv', '', 12200, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(99, 'INVENTORY/LED tv//W16TH03(16")', 'W16TH03(16")', 'INVENTORY', 'LED tv', '', 11600, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(100, 'INVENTORY/LED tv//WT16D(16")', 'WT16D(16")', 'INVENTORY', 'LED tv', '', 11200, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(101, 'INVENTORY/LED tv//W14E57(14")', 'W14E57(14")', 'INVENTORY', 'LED tv', '', 9900, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(102, 'INVENTORY/LED TV REMOTE//SMART REMOTE (FIXED)', 'SMART REMOTE (FIXED)', 'INVENTORY', 'LED TV REMOTE', '', 3000, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(103, 'INVENTORY/FREEZER//FC-1B3(DEEP)', 'FC-1B3(DEEP)', 'INVENTORY', 'FREEZER', '', 19100, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(104, 'INVENTORY/FREEZER//FC-1D5(DEEP)', 'FC-1D5(DEEP)', 'INVENTORY', 'FREEZER', '', 20200, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(105, 'INVENTORY/FREEZER//FC-2T5(DEEP)', 'FC-2T5(DEEP)', 'INVENTORY', 'FREEZER', '', 22950, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(106, 'INVENTORY/FREEZER//FC-2T5FH(DEEP)', 'FC-2T5FH(DEEP)', 'INVENTORY', 'FREEZER', '', 23500, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(107, 'INVENTORY/FREEZER//WVF-1R2(DEEP)', 'WVF-1R2(DEEP)', 'INVENTORY', 'FREEZER', '', 25900, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(108, 'INVENTORY/FREEZER//FC-2E5(DEEP)', 'FC-2E5(DEEP)', 'INVENTORY', 'FREEZER', '', 26300, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(109, 'INVENTORY/FREEZER//FC-3JO(DEEP)', 'FC-3JO(DEEP)', 'INVENTORY', 'FREEZER', '', 28100, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(110, 'INVENTORY/FREEZER//FC-3JOD(DEEP)', 'FC-3JOD(DEEP)', 'INVENTORY', 'FREEZER', '', 28590, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(111, 'INVENTORY/REFRIGERATOR//W500-1B6', 'W500-1B6', 'INVENTORY', 'REFRIGERATOR', '', 17200, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(112, 'INVENTORY/REFRIGERATOR//W500-4DMBC', 'W500-4DMBC', 'INVENTORY', 'REFRIGERATOR', '', 20450, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(113, 'INVENTORY/REFRIGERATOR//W500-4DMB', 'W500-4DMB', 'INVENTORY', 'REFRIGERATOR', '', 20250, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(114, 'INVENTORY/REFRIGERATOR//W500-1D0C', 'W500-1D0C', 'INVENTORY', 'REFRIGERATOR', '', 20350, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(115, 'INVENTORY/REFRIGERATOR//W500-1D0', 'W500-1D0', 'INVENTORY', 'REFRIGERATOR', '', 19990, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(116, 'INVENTORY/REFRIGERATOR//W500-4DRD', 'W500-4DRD', 'INVENTORY', 'REFRIGERATOR', '', 20050, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(117, 'INVENTORY/REFRIGERATOR//D-1F0', 'D-1F0', 'INVENTORY', 'REFRIGERATOR', '', 20900, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(118, 'INVENTORY/REFRIGERATOR//W500-AF3(RD)', 'W500-AF3(RD)', 'INVENTORY', 'REFRIGERATOR', '', 22500, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(119, 'INVENTORY/REFRIGERATOR//W500-AF3', 'W500-AF3', 'INVENTORY', 'REFRIGERATOR', '', 22400, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(120, 'INVENTORY/REFRIGERATOR//WFD-1F3-0201-RXXX', 'WFD-1F3-0201-RXXX', 'INVENTORY', 'REFRIGERATOR', '', 22400, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(121, 'INVENTORY/REFRIGERATOR//WS-1F5', 'WS-1F5', 'INVENTORY', 'REFRIGERATOR', '', 16500, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(122, 'INVENTORY/REFRIGERATOR//W2D-A90', 'W2D-A90', 'INVENTORY', 'REFRIGERATOR', '', 21950, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(123, 'INVENTORY/REFRIGERATOR//W2D-1H5', 'W2D-1H5', 'INVENTORY', 'REFRIGERATOR', '', 24600, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(124, 'INVENTORY/REFRIGERATOR//W2D-2X1', 'W2D-2X1', 'INVENTORY', 'REFRIGERATOR', '', 25600, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(125, 'INVENTORY/REFRIGERATOR//WFF-2A3', 'WFF-2A3', 'INVENTORY', 'REFRIGERATOR', '', 23100, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(126, 'INVENTORY/REFRIGERATOR//WFA-2A3-0103-RXXX-RP', 'WFA-2A3-0103-RXXX-RP', 'INVENTORY', 'REFRIGERATOR', '', 23100, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(127, 'INVENTORY/REFRIGERATOR//WFA-2A3-0301-RXXX-RP', 'WFA-2A3-0301-RXXX-RP', 'INVENTORY', 'REFRIGERATOR', '', 23100, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(128, 'INVENTORY/REFRIGERATOR//WFA-2A3-0201-CDBX-XX', 'WFA-2A3-0201-CDBX-XX', 'INVENTORY', 'REFRIGERATOR', '', 22950, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(129, 'INVENTORY/REFRIGERATOR//WFA-2A3-0103-CDLX-XX', 'WFA-2A3-0103-CDLX-XX', 'INVENTORY', 'REFRIGERATOR', '', 22950, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(130, 'INVENTORY/REFRIGERATOR//WFF-2A3-CD', 'WFF-2A3-CD', 'INVENTORY', 'REFRIGERATOR', '', 22950, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(131, 'INVENTORY/REFRIGERATOR//WFF-2A3-CD-BOTH', 'WFF-2A3-CD-BOTH', 'INVENTORY', 'REFRIGERATOR', '', 22950, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(132, 'INVENTORY/REFRIGERATOR//WFA-2A3-0201-RXXX-CP', 'WFA-2A3-0201-RXXX-CP', 'INVENTORY', 'REFRIGERATOR', '', 22950, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(133, 'INVENTORY/REFRIGERATOR//WFA-2A3-0201-RXXX-CP', 'WFA-2A3-0201-RXXX-CP', 'INVENTORY', 'REFRIGERATOR', '', 22950, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(134, 'INVENTORY/REFRIGERATOR//WFA-2A3-0201-RXXX-XX', 'WFA-2A3-0201-RXXX-XX', 'INVENTORY', 'REFRIGERATOR', '', 22950, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(135, 'INVENTORY/REFRIGERATOR//WFA-2A3-0301-CDBX-XX', 'WFA-2A3-0301-CDBX-XX', 'INVENTORY', 'REFRIGERATOR', '', 22950, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(136, 'INVENTORY/REFRIGERATOR//WFA-2A3-0301-CDLX-XX', 'WFA-2A3-0301-CDLX-XX', 'INVENTORY', 'REFRIGERATOR', '', 22950, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(137, 'INVENTORY/REFRIGERATOR//WCO-M12(GLASS DOOR)', 'WCO-M12(GLASS DOOR)', 'INVENTORY', 'REFRIGERATOR', '', 27900, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(138, 'INVENTORY/REFRIGERATOR//W2D-2B0', 'W2D-2B0', 'INVENTORY', 'REFRIGERATOR', '', 23200, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(139, 'INVENTORY/REFRIGERATOR//W2D-2B0-CD', 'W2D-2B0-CD', 'INVENTORY', 'REFRIGERATOR', '', 23300, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(140, 'INVENTORY/REFRIGERATOR//W2D-2B0-CD BOTH', 'W2D-2B0-CD BOTH', 'INVENTORY', 'REFRIGERATOR', '', 23400, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(141, 'INVENTORY/REFRIGERATOR//W2D-2B6', 'W2D-2B6', 'INVENTORY', 'REFRIGERATOR', '', 27950, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(142, 'INVENTORY/REFRIGERATOR//W2D-2B6-CD', 'W2D-2B6-CD', 'INVENTORY', 'REFRIGERATOR', '', 27850, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(143, 'INVENTORY/REFRIGERATOR//W2D-2D4', 'W2D-2D4', 'INVENTORY', 'REFRIGERATOR', '', 23950, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(144, 'INVENTORY/REFRIGERATOR//W2D-2D4-CD', 'W2D-2D4-CD', 'INVENTORY', 'REFRIGERATOR', '', 23950, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(145, 'INVENTORY/REFRIGERATOR//W2D-2D4-CD-BOTH', 'W2D-2D4-CD-BOTH', 'INVENTORY', 'REFRIGERATOR', '', 24500, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(146, 'INVENTORY/REFRIGERATOR//WFA-2D4-0301-CDBX-XX', 'WFA-2D4-0301-CDBX-XX', 'INVENTORY', 'REFRIGERATOR', '', 24200, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(147, 'INVENTORY/REFRIGERATOR//WFA-2D4-0301-CDLX-XX', 'WFA-2D4-0301-CDLX-XX', 'INVENTORY', 'REFRIGERATOR', '', 24200, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(148, 'INVENTORY/REFRIGERATOR//W2D-2E4', 'W2D-2E4', 'INVENTORY', 'REFRIGERATOR', '', 28200, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(149, 'INVENTORY/REFRIGERATOR//WBVC-2F0', 'WBVC-2F0', 'INVENTORY', 'REFRIGERATOR', '', 37700, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(150, 'INVENTORY/REFRIGERATOR//W585-2H2', 'W585-2H2', 'INVENTORY', 'REFRIGERATOR', '', 25600, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(151, 'INVENTORY/REFRIGERATOR//WFE-2H2-0101-(RXXX)', 'WFE-2H2-0101-(RXXX)', 'INVENTORY', 'REFRIGERATOR', '', 25600, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(152, 'INVENTORY/REFRIGERATOR//WFE-2H2-0101-(CRXX)', 'WFE-2H2-0101-(CRXX)', 'INVENTORY', 'REFRIGERATOR', '', 25600, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(153, 'INVENTORY/REFRIGERATOR//W2D-2GC', 'W2D-2GC', 'INVENTORY', 'REFRIGERATOR', '', 33000, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(154, 'INVENTORY/REFRIGERATOR//W585-2N5', 'W585-2N5', 'INVENTORY', 'REFRIGERATOR', '', 26300, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(155, 'INVENTORY/REFRIGERATOR//W2D-3A7N', 'W2D-3A7N', 'INVENTORY', 'REFRIGERATOR', '', 28950, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(156, 'INVENTORY/REFRIGERATOR//WFC-3A7-0201-NXXX', 'WFC-3A7-0201-NXXX', 'INVENTORY', 'REFRIGERATOR', '', 28600, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(157, 'INVENTORY/REFRIGERATOR//WFC-3A7-0101-RXXX', 'WFC-3A7-0101-RXXX', 'INVENTORY', 'REFRIGERATOR', '', 27650, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(158, 'INVENTORY/REFRIGERATOR//W2D-3A7', 'W2D-3A7', 'INVENTORY', 'REFRIGERATOR', '', 27650, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(159, 'INVENTORY/REFRIGERATOR//WFC-3A7-0201-RXXX', 'WFC-3A7-0201-RXXX', 'INVENTORY', 'REFRIGERATOR', '', 27200, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(160, 'INVENTORY/REFRIGERATOR//W585-3BO', 'W585-3BO', 'INVENTORY', 'REFRIGERATOR', '', 29800, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(161, 'INVENTORY/REFRIGERATOR//W585-3BOCR', 'W585-3BOCR', 'INVENTORY', 'REFRIGERATOR', '', 30200, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(162, 'INVENTORY/REFRIGERATOR//W2D-3D8', 'W2D-3D8', 'INVENTORY', 'REFRIGERATOR', '', 30300, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(163, 'INVENTORY/REFRIGERATOR//W2D-3F5', 'W2D-3F5', 'INVENTORY', 'REFRIGERATOR', '', 34300, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(164, 'INVENTORY/REFRIGERATOR//WFB-2A8-0101', 'WFB-2A8-0101', 'INVENTORY', 'REFRIGERATOR', '', 25900, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(165, 'INVENTORY/REFRIGERATOR//NW-2AO(FIXED)', 'NW-2AO(FIXED)', 'INVENTORY', 'REFRIGERATOR', '', 18000, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(166, 'INVENTORY/REFRIGERATOR//NW-2G6(FIXED)', 'NW-2G6(FIXED)', 'INVENTORY', 'REFRIGERATOR', '', 20000, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(167, 'INVENTORY/REFRIGERATOR//WNH-3H6-0101(386L)', 'WNH-3H6-0101(386L)', 'INVENTORY', 'REFRIGERATOR', '', 40900, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(168, 'INVENTORY/REFRIGERATOR//WT700-4CO(430L)', 'WT700-4CO(430L)', 'INVENTORY', 'REFRIGERATOR', '', 49900, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(169, 'INVENTORY/REFRIGERATOR//NW670-316', 'NW670-316', 'INVENTORY', 'REFRIGERATOR', '', 39990, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(170, 'INVENTORY/REFRIGERATOR//WT730-5A2(512)', 'WT730-5A2(512)', 'INVENTORY', 'REFRIGERATOR', '', 52500, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(171, 'INVENTORY/REFRIGERATOR//WT730-5B6(526)', 'WT730-5B6(526)', 'INVENTORY', 'REFRIGERATOR', '', 58900, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(172, 'INVENTORY/REFRIGERATOR//WNJ-5E5-0101(555L)', 'WNJ-5E5-0101(555L)', 'INVENTORY', 'REFRIGERATOR', '', 54900, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(173, 'INVENTORY/REFRIGERATOR//WNL-5G5-0101', 'WNL-5G5-0101', 'INVENTORY', 'REFRIGERATOR', '', 56900, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(174, 'INVENTORY/REFRIGERATOR//WSS-4H5', 'WSS-4H5', 'INVENTORY', 'REFRIGERATOR', '', 69900, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(175, 'INVENTORY/REFRIGERATOR//WNJ-5H5-0101(585L)', 'WNJ-5H5-0101(585L)', 'INVENTORY', 'REFRIGERATOR', '', 57900, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(176, 'INVENTORY///ANY MODEL CD LEFT (FOR DISTRIBUTOR)', 'ANY MODEL CD LEFT (FOR DISTRIBUTOR)', 'INVENTORY', '', '', 0, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(177, 'INVENTORY///ANY MODEL CD BOTTH (FOR PLAZA)', 'ANY MODEL CD BOTTH (FOR PLAZA)', 'INVENTORY', '', '', 0, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(178, 'INVENTORY/MOTORCYCLE//XPLORE-140/XPLORE-140+', 'XPLORE-140/XPLORE-140+', 'INVENTORY', 'MOTORCYCLE', '', 125000, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(179, 'INVENTORY/MOTORCYCLE//FUSION-NX-125(DIGITAL)', 'FUSION-NX-125(DIGITAL)', 'INVENTORY', 'MOTORCYCLE', '', 110000, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(180, 'INVENTORY/MOTORCYCLE//XPLORE-125', 'XPLORE-125', 'INVENTORY', 'MOTORCYCLE', '', 99200, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(181, 'INVENTORY/MOTORCYCLE//FUSION-EX-125(DIGITAL)', 'FUSION-EX-125(DIGITAL)', 'INVENTORY', 'MOTORCYCLE', '', 97000, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(182, 'INVENTORY/MOTORCYCLE//FUSION-EX-110(DIGITAL)', 'FUSION-EX-110(DIGITAL)', 'INVENTORY', 'MOTORCYCLE', '', 100000, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(183, 'INVENTORY/MOTORCYCLE//RANGER-100', 'RANGER-100', 'INVENTORY', 'MOTORCYCLE', '', 105000, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(184, 'INVENTORY/MOTORCYCLE//CRUIZE(DIGITAL)', 'CRUIZE(DIGITAL)', 'INVENTORY', 'MOTORCYCLE', '', 85000, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(185, 'INVENTORY/MOTORCYCLE//STLEX ALOY+', 'STLEX ALOY+', 'INVENTORY', 'MOTORCYCLE', '', 85000, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(186, 'INVENTORY/MOTORCYCLE//LEO ALOY+', 'LEO ALOY+', 'INVENTORY', 'MOTORCYCLE', '', 80000, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(187, 'INVENTORY/MOTORCYCLE//STYLEX-ALOY(NEW)', 'STYLEX-ALOY(NEW)', 'INVENTORY', 'MOTORCYCLE', '', 72500, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(188, 'INVENTORY/MOTORCYCLE//LEO-ALOY+', 'LEO-ALOY+', 'INVENTORY', 'MOTORCYCLE', '', 69500, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(189, 'INVENTORY/MOTORCYCLE//LEO-SPOKE', 'LEO-SPOKE', 'INVENTORY', 'MOTORCYCLE', '', 64000, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(190, 'INVENTORY///VOICE ALARM', 'VOICE ALARM', 'INVENTORY', '', '', 1250, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(191, 'INVENTORY///HELMET', 'HELMET', 'INVENTORY', '', '', 1500, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(192, 'INVENTORY/MICROWAVE-OVEN//WG30ESLR', 'WG30ESLR', 'INVENTORY', 'MICROWAVE-OVEN', '', 14900, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(193, 'INVENTORY/MICROWAVE-OVEN//WG23-CGD', 'WG23-CGD', 'INVENTORY', 'MICROWAVE-OVEN', '', 11900, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(194, 'INVENTORY/MICROWAVE-OVEN//WG20-GL', 'WG20-GL', 'INVENTORY', 'MICROWAVE-OVEN', '', 9900, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(195, 'INVENTORY/MICROWAVE-OVEN//WG17AL-DI', 'WG17AL-DI', 'INVENTORY', 'MICROWAVE-OVEN', '', 8600, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(196, 'INVENTORY/CRT TV//WF21U10A', 'WF21U10A', 'INVENTORY', 'CRT TV', '', 14100, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(197, 'INVENTORY/CRT TV//WF21T10A', 'WF21T10A', 'INVENTORY', 'CRT TV', '', 13900, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(198, 'INVENTORY/CRT TV//WF21T1OB', 'WF21T1OB', 'INVENTORY', 'CRT TV', '', 13900, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(199, 'INVENTORY/CRT TV//WT2106', 'WT2106', 'INVENTORY', 'CRT TV', '', 12000, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(200, 'INVENTORY/CRT TV//WT2105', 'WT2105', 'INVENTORY', 'CRT TV', '', 11900, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(201, 'INVENTORY/CRT TV//WT21US', 'WT21US', 'INVENTORY', 'CRT TV', '', 11750, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(202, 'INVENTORY/CRT TV//WPF21U1/WPF21U2', 'WPF21U1/WPF21U2', 'INVENTORY', 'CRT TV', '', 11750, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(203, 'INVENTORY/CRT TV//WT2148', 'WT2148', 'INVENTORY', 'CRT TV', '', 11650, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(204, 'INVENTORY/CRT TV//WT2168', 'WT2168', 'INVENTORY', 'CRT TV', '', 11650, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(205, 'INVENTORY/CRT TV//WT2138', 'WT2138', 'INVENTORY', 'CRT TV', '', 11550, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(206, 'INVENTORY/CRT TV//WT2128/WF2106A', 'WT2128/WF2106A', 'INVENTORY', 'CRT TV', '', 11450, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(207, 'INVENTORY/CRT TV//WT2196', 'WT2196', 'INVENTORY', 'CRT TV', '', 11400, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(208, 'INVENTORY/CRT TV//WT2178', 'WT2178', 'INVENTORY', 'CRT TV', '', 11350, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(209, 'INVENTORY/CRT TV//WT2188/WT2186', 'WT2188/WT2186', 'INVENTORY', 'CRT TV', '', 11350, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(210, 'INVENTORY/CRT TV//WDF21R/WDF21S', 'WDF21R/WDF21S', 'INVENTORY', 'CRT TV', '', 11300, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(212, 'INVENTORY/CRT TV//WT2188A', 'WT2188A', 'INVENTORY', 'CRT TV', '', 11300, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(213, 'INVENTORY/CRT TV//WPF21T1/WPF21T2', 'WPF21T1/WPF21T2', 'INVENTORY', 'CRT TV', '', 10950, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(214, 'INVENTORY/CRT TV//W1438', 'W1438', 'INVENTORY', 'CRT TV', '', 9075, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(215, 'INVENTORY/CRT TV//W14A3', 'W14A3', 'INVENTORY', 'CRT TV', '', 8975, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(216, 'INVENTORY/CRT TV//W14A2', 'W14A2', 'INVENTORY', 'CRT TV', '', 8950, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(217, 'INVENTORY/CRT TV//W1406A', 'W1406A', 'INVENTORY', 'CRT TV', '', 8925, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(218, 'INVENTORY/CRT TV//W1408A/W1408', 'W1408A/W1408', 'INVENTORY', 'CRT TV', '', 8875, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(219, 'INVENTORY/CRT TV//WF1403', 'WF1403', 'INVENTORY', 'CRT TV', '', 8700, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(220, 'INVENTORY/CRT TV//W1488/W1496', 'W1488/W1496', 'INVENTORY', 'CRT TV', '', 8600, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(221, 'INVENTORY/CRT TV//W1406/W1458', 'W1406/W1458', 'INVENTORY', 'CRT TV', '', 8600, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(222, 'INVENTORY/CRT TV//W1428', 'W1428', 'INVENTORY', 'CRT TV', '', 8500, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(223, 'INVENTORY/CRT TV//W1448/W1486', 'W1448/W1486', 'INVENTORY', 'CRT TV', '', 8500, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(224, 'INVENTORY/AIR-CONDITIONER//W-25GW(.75TON)', 'W-25GW(.75TON)', 'INVENTORY', 'AIR-CONDITIONER', '', 31500, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(225, 'INVENTORY/AIR-CONDITIONER//W-35GW(1TON)', 'W-35GW(1TON)', 'INVENTORY', 'AIR-CONDITIONER', '', 34000, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(226, 'INVENTORY/AIR-CONDITIONER//W-50GW(1.5TON)', 'W-50GW(1.5TON)', 'INVENTORY', 'AIR-CONDITIONER', '', 43900, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(227, 'INVENTORY/AIR-CONDITIONER//W-70GW(2TON)', 'W-70GW(2TON)', 'INVENTORY', 'AIR-CONDITIONER', '', 54600, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(228, 'INVENTORY/AIR-CONDITIONER//W-35GWH(1TON)', 'W-35GWH(1TON)', 'INVENTORY', 'AIR-CONDITIONER', '', 36000, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(229, 'INVENTORY/AIR-CONDITIONER//W50-GWH(1TON)', 'W50-GWH(1TON)', 'INVENTORY', 'AIR-CONDITIONER', '', 46200, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(230, 'INVENTORY/AIR-CONDITIONER//W-70GWH(2TON)', 'W-70GWH(2TON)', 'INVENTORY', 'AIR-CONDITIONER', '', 57600, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(231, 'INVENTORY/GENERATOR//ZET-1000', 'ZET-1000', 'INVENTORY', 'GENERATOR', '', 16500, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(232, 'INVENTORY/GENERATOR//ZOOM-1200', 'ZOOM-1200', 'INVENTORY', 'GENERATOR', '', 17000, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(233, 'INVENTORY/GENERATOR//SPIRIT-1350', 'SPIRIT-1350', 'INVENTORY', 'GENERATOR', '', 17500, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(234, 'INVENTORY/GENERATOR//POWER PLUS-1500-DM', 'POWER PLUS-1500-DM', 'INVENTORY', 'GENERATOR', '', 22000, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(235, 'INVENTORY/GENERATOR//POWER-PLUS-1500E-DM', 'POWER-PLUS-1500E-DM', 'INVENTORY', 'GENERATOR', '', 30000, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(236, 'INVENTORY/GENERATOR//EXCEL-2200-DM', 'EXCEL-2200-DM', 'INVENTORY', 'GENERATOR', '', 25000, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(237, 'INVENTORY/GENERATOR//EXCEL-2200E-DM', 'EXCEL-2200E-DM', 'INVENTORY', 'GENERATOR', '', 32200, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(238, 'INVENTORY/GENERATOR//POWER MAX-3100', 'POWER MAX-3100', 'INVENTORY', 'GENERATOR', '', 34500, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(239, 'INVENTORY/GENERATOR//POWER MAX-3100E', 'POWER MAX-3100E', 'INVENTORY', 'GENERATOR', '', 44000, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(240, 'INVENTORY/GENERATOR//POWER MAX-3600', 'POWER MAX-3600', 'INVENTORY', 'GENERATOR', '', 36900, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(241, 'INVENTORY/GENERATOR//POWER MAX-3600E', 'POWER MAX-3600E', 'INVENTORY', 'GENERATOR', '', 46000, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(242, 'INVENTORY/GENERATOR//SPARKS-4500', 'SPARKS-4500', 'INVENTORY', 'GENERATOR', '', 39000, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(243, 'INVENTORY/GENERATOR//SPARKS-4500E', 'SPARKS-4500E', 'INVENTORY', 'GENERATOR', '', 49000, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(244, 'INVENTORY/GENERATOR//SILENT KATRINA-5000E-DIESEL', 'SILENT KATRINA-5000E-DIESEL', 'INVENTORY', 'GENERATOR', '', 94000, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(245, 'INVENTORY/GENERATOR//SILENT KATRINA-5500E-DIESEL', 'SILENT KATRINA-5500E-DIESEL', 'INVENTORY', 'GENERATOR', '', 98000, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(246, 'INVENTORY/GENERATOR//SUPERIA-6000', 'SUPERIA-6000', 'INVENTORY', 'GENERATOR', '', 49000, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(247, 'INVENTORY/GENERATOR//SUPERIA-6000E', 'SUPERIA-6000E', 'INVENTORY', 'GENERATOR', '', 59000, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(248, 'INVENTORY/GENERATOR//POWER CRAFT-7000E', 'POWER CRAFT-7000E', 'INVENTORY', 'GENERATOR', '', 68000, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(249, 'INVENTORY/GENERATOR//POWER CRAFT-8000E', 'POWER CRAFT-8000E', 'INVENTORY', 'GENERATOR', '', 69000, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(250, 'INVENTORY/WASHING MACHINE//WWM-M80', 'WWM-M80', 'INVENTORY', 'WASHING MACHINE', '', 29900, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(251, 'INVENTORY/WASHING MACHINE//WWM-S70', 'WWM-S70', 'INVENTORY', 'WASHING MACHINE', '', 28000, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(252, 'INVENTORY/WASHING MACHINE//WMG60-S302G', 'WMG60-S302G', 'INVENTORY', 'WASHING MACHINE', '', 25000, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(253, 'INVENTORY/WASHING MACHINE//WWM-M60', 'WWM-M60', 'INVENTORY', 'WASHING MACHINE', '', 25000, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(254, 'INVENTORY/IPS/WITHOUT BATTERY/WIP-400SH', 'WIP-400SH', 'INVENTORY', 'IPS', 'WITHOUT BATTERY', 11200, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(255, 'INVENTORY/IPS/WITHOUT BATTERY/WIP-600SH', 'WIP-600SH', 'INVENTORY', 'IPS', 'WITHOUT BATTERY', 13800, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(256, 'INVENTORY/IPS/WITHOUT BATTERY/WIP-800SH', 'WIP-800SH', 'INVENTORY', 'IPS', 'WITHOUT BATTERY', 18000, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(257, 'INVENTORY/IPS/WITHOUT BATTERY/WIP-400SS', 'WIP-400SS', 'INVENTORY', 'IPS', 'WITHOUT BATTERY', 11500, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(258, 'INVENTORY/IPS/WITHOUT BATTERY/WIP-600SS', 'WIP-600SS', 'INVENTORY', 'IPS', 'WITHOUT BATTERY', 14000, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(259, 'INVENTORY/IPS/WITHOUT BATTERY/WIP-800SS', 'WIP-800SS', 'INVENTORY', 'IPS', 'WITHOUT BATTERY', 18300, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(260, 'INVENTORY/AIR-COOLER//WRA-S99', 'WRA-S99', 'INVENTORY', 'AIR-COOLER', '', 12900, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(261, 'INVENTORY/AIR-COOLER//WEA-S100', 'WEA-S100', 'INVENTORY', 'AIR-COOLER', '', 12500, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(262, 'INVENTORY/AIR-COOLER//WRA-S77', 'WRA-S77', 'INVENTORY', 'AIR-COOLER', '', 11500, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(263, 'INVENTORY/AIR-COOLER//WRA-1181', 'WRA-1181', 'INVENTORY', 'AIR-COOLER', '', 7900, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(264, 'INVENTORY/AIR-COOLER//WRA-S66(MINI)', 'WRA-S66(MINI)', 'INVENTORY', 'AIR-COOLER', '', 3790, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(265, 'INVENTORY/WATER-DISPENSER//WWD-QC01', 'WWD-QC01', 'INVENTORY', 'WATER-DISPENSER', '', 10500, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(266, 'INVENTORY/WATER-DISPENSER//WWD-QE01', 'WWD-QE01', 'INVENTORY', 'WATER-DISPENSER', '', 6200, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(267, 'INVENTORY/WATER-DISPENSER//WWD-Q001', 'WWD-Q001', 'INVENTORY', 'WATER-DISPENSER', '', 4750, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(268, 'INVENTORY/WATER-DISPENSER//WWD-Q002', 'WWD-Q002', 'INVENTORY', 'WATER-DISPENSER', '', 5950, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(269, 'INVENTORY/RECHARGEBLE LAMP/PORTABLE/WRL-L11', 'WRL-L11', 'INVENTORY', 'RECHARGEBLE LAMP', 'PORTABLE', 1100, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(270, 'INVENTORY/RECHARGEBLE LAMP/PORTABLE/WRL-L22', 'WRL-L22', 'INVENTORY', 'RECHARGEBLE LAMP', 'PORTABLE', 1250, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(271, 'INVENTORY/RECHARGEBLE LAMP/PORTABLE]/WRL-T33', 'WRL-T33', 'INVENTORY', 'RECHARGEBLE LAMP', 'PORTABLE]', 1350, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(272, 'INVENTORY/RECHARGEBLE LAMP/PORTABLE/WRL-L77(TWO COLOUR)', 'WRL-L77(TWO COLOUR)', 'INVENTORY', 'RECHARGEBLE LAMP', 'PORTABLE', 1350, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(273, 'INVENTORY/RECHARGEBLE LAMP/PORTABLE/WRL-L66(TWO COLOUR)', 'WRL-L66(TWO COLOUR)', 'INVENTORY', 'RECHARGEBLE LAMP', 'PORTABLE', 750, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(274, 'INVENTORY/TORCH/9"LENGTH/WRL-T20-TORCH', 'WRL-T20-TORCH', 'INVENTORY', 'TORCH', '9"LENGTH', 240, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(275, 'INVENTORY/RECHARGEBLE LAMP/PORTABLE/WRL-L55(TWO COLOUR)', 'WRL-L55(TWO COLOUR)', 'INVENTORY', 'RECHARGEBLE LAMP', 'PORTABLE', 900, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(276, 'INVENTORY/RECHARGEBLE LAMP/PORTABLE/WRL-L44(TWO COLOUR)', 'WRL-L44(TWO COLOUR)', 'INVENTORY', 'RECHARGEBLE LAMP', 'PORTABLE', 800, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(277, 'INVENTORY/AIR-FRYER//WAF-CR01', 'WAF-CR01', 'INVENTORY', 'AIR-FRYER', '', 7500, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(278, 'INVENTORY/FOOD PROCESSOR//WFP-GS503', 'WFP-GS503', 'INVENTORY', 'FOOD PROCESSOR', '', 4400, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(279, 'INVENTORY/GAS-STOVE(NG&LPG)//WGS-AT150(LPG)', 'WGS-AT150(LPG)', 'INVENTORY', 'GAS-STOVE(NG&LPG)', '', 1990, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(280, 'INVENTORY/GAS-STOVE(NG&LPG)//WGS-AT250', 'WGS-AT250', 'INVENTORY', 'GAS-STOVE(NG&LPG)', '', 4590, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(281, 'INVENTORY/GAS-STOVE(NG&LPG)//WGS-AT222', 'WGS-AT222', 'INVENTORY', 'GAS-STOVE(NG&LPG)', '', 3500, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(282, 'INVENTORY/GAS-STOVE(NG&LPG)//WGS-AST233', 'WGS-AST233', 'INVENTORY', 'GAS-STOVE(NG&LPG)', '', 3490, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(283, 'INVENTORY/GAS-STOVE(NG&LPG)//WGS-AT211', 'WGS-AT211', 'INVENTORY', 'GAS-STOVE(NG&LPG)', '', 3090, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(284, 'INVENTORY/GAS-STOVE(NG&LPG)//WGS-AT100', 'WGS-AT100', 'INVENTORY', 'GAS-STOVE(NG&LPG)', '', 1790, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(285, 'INVENTORY/DVD//DVD-602', 'DVD-602', 'INVENTORY', 'DVD', '', 4100, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(286, 'INVENTORY/DVD//WT-363', 'WT-363', 'INVENTORY', 'DVD', '', 4000, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(287, 'INVENTORY/DVD//WD-835', 'WD-835', 'INVENTORY', 'DVD', '', 3900, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(288, 'INVENTORY/DVD//WT-365', 'WT-365', 'INVENTORY', 'DVD', '', 3600, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(289, 'INVENTORY/DVD//WT-308', 'WT-308', 'INVENTORY', 'DVD', '', 3600, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(290, 'INVENTORY/HAIR-DRYER//WHD-P02', 'WHD-P02', 'INVENTORY', 'HAIR-DRYER', '', 750, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(291, 'INVENTORY/HAIR-DRYER//WHD-PO4', 'WHD-PO4', 'INVENTORY', 'HAIR-DRYER', '', 850, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(292, 'INVENTORY/HAIR-DRYER//WHS-PO3', 'WHS-PO3', 'INVENTORY', 'HAIR-DRYER', '', 1000, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(293, 'INVENTORY/HAIR-DRYER//WZ-HD105', 'WZ-HD105', 'INVENTORY', 'HAIR-DRYER', '', 1300, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(294, 'INVENTORY/HAIR-DRYER//WHDS-PO1', 'WHDS-PO1', 'INVENTORY', 'HAIR-DRYER', '', 1600, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(295, 'INVENTORY/LED TUBE LIGHT//WLED-T8TUBE-60-FMR-8W', 'WLED-T8TUBE-60-FMR-8W', 'INVENTORY', 'LED TUBE LIGHT', '', 470, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(296, 'INVENTORY/LED TUBE LIGHT//WLED-T8-TUBE-60-FMR-10W', 'WLED-T8-TUBE-60-FMR-10W', 'INVENTORY', 'LED TUBE LIGHT', '', 485, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(297, 'INVENTORY/LED TUBE LIGHT//WLED-T8-TUBE-120FMR-16', 'WLED-T8-TUBE-120FMR-16', 'INVENTORY', 'LED TUBE LIGHT', '', 750, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(298, 'INVENTORY/LED TUBE LIGHT//WLED-T8-TUBE-120-FMR-18W', 'WLED-T8-TUBE-120-FMR-18W', 'INVENTORY', 'LED TUBE LIGHT', '', 775, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(299, 'INVENTORY/LED TUBE LIGHT//WLED-T8-TUBE-120-FMR-18W', 'WLED-T8-TUBE-120-FMR-18W', 'INVENTORY', 'LED TUBE LIGHT', '', 775, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(300, 'INVENTORY/LED PANEL LIGHT//WLED-PL1F1-PR12W', 'WLED-PL1F1-PR12W', 'INVENTORY', 'LED PANEL LIGHT', '', 1650, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(301, 'INVENTORY/LED PANEL LIGHT//WLED-PL1F1-PR24W', 'WLED-PL1F1-PR24W', 'INVENTORY', 'LED PANEL LIGHT', '', 2100, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(302, 'INVENTORY/LED PANEL LIGHT//WLED-PL1F1-PR36W', 'WLED-PL1F1-PR36W', 'INVENTORY', 'LED PANEL LIGHT', '', 3950, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(303, 'INVENTORY/LED PANEL LIGHT//WLED-PL1F1-PR48W', 'WLED-PL1F1-PR48W', 'INVENTORY', 'LED PANEL LIGHT', '', 4500, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(304, 'INVENTORY/VACUUM FLASK//WVF-JP10', 'WVF-JP10', 'INVENTORY', 'VACUUM FLASK', '', 1080, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(305, 'INVENTORY/VACUUM FLASK//WVF-JP13', 'WVF-JP13', 'INVENTORY', 'VACUUM FLASK', '', 1180, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(306, 'INVENTORY/VACUUM FLASK//WVF-JP16', 'WVF-JP16', 'INVENTORY', 'VACUUM FLASK', '', 1380, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(307, 'INVENTORY/VACUUM FLASK//WVF-GP16', 'WVF-GP16', 'INVENTORY', 'VACUUM FLASK', '', 1380, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(308, 'INVENTORY/VACUUM FLASK//WVF-SXP20(2.0ltr)', 'WVF-SXP20(2.0ltr)', 'INVENTORY', 'VACUUM FLASK', '', 1200, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(309, 'INVENTORY/VACUUM FLASK//WVF-SXP15(1.5ltr)', 'WVF-SXP15(1.5ltr)', 'INVENTORY', 'VACUUM FLASK', '', 1100, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(310, 'INVENTORY/VACUUM FLASK//WVF-SXP10(1.0ltr)', 'WVF-SXP10(1.0ltr)', 'INVENTORY', 'VACUUM FLASK', '', 950, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(311, 'INVENTORY/MULTI CURRY COOKER//WCC-FH05', 'WCC-FH05', 'INVENTORY', 'MULTI CURRY COOKER', '', 2600, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(312, 'INVENTORY/MULTI CURRY COOKER//WCC-WK50(1.0l)', 'WCC-WK50(1.0l)', 'INVENTORY', 'MULTI CURRY COOKER', '', 2300, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(313, 'INVENTORY/MULTI CURRY COOKER//WCC-WK30(3.0L)', 'WCC-WK30(3.0L)', 'INVENTORY', 'MULTI CURRY COOKER', '', 1900, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(314, 'INVENTORY/METALLIC BLACK C/S3&V8SERISE/S32MCPMB-2M COVER PLATE-S3', 'S32MCPMB-2M COVER PLATE-S3', 'INVENTORY', 'METALLIC BLACK C', 'S3&V8SERISE', 69, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(315, 'INVENTORY/METALLIC BLACK C/S3&V8SERISE/V82MCPMB-2M COVER PLATE-V8', 'V82MCPMB-2M COVER PLATE-V8', 'INVENTORY', 'METALLIC BLACK C', 'S3&V8SERISE', 85, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(316, 'INVENTORY/METALLIC BLACK C/S3&V8SERISE/S33MCPMB-3M COVER PLATE-S3', 'S33MCPMB-3M COVER PLATE-S3', 'INVENTORY', 'METALLIC BLACK C', 'S3&V8SERISE', 85, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(317, 'INVENTORY/METALLIC BLACK C/S3&V8SERISE/V83MCPMB-3M COVER PLATE-V8', 'V83MCPMB-3M COVER PLATE-V8', 'INVENTORY', 'METALLIC BLACK C', 'S3&V8SERISE', 95, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(318, 'INVENTORY/METALLIC BLACK C/S3&V8SERISE/S34MCPMB-4M COVER PLATE-S3', 'S34MCPMB-4M COVER PLATE-S3', 'INVENTORY', 'METALLIC BLACK C', 'S3&V8SERISE', 95, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(319, 'INVENTORY/METALLIC BLACK C/S3&V8SERISE/V84MCPMB-4M COVER PLATE-V8', 'V84MCPMB-4M COVER PLATE-V8', 'INVENTORY', 'METALLIC BLACK C', 'S3&V8SERISE', 115, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(320, 'INVENTORY/METALLIC BLACK C/S3&V8SERISE/SVSSRMB16.1-SINGLE SWITCH MODULE(1 WAY)', 'SVSSRMB16.1-SINGLE SWITCH MODULE(1 WAY)', 'INVENTORY', 'METALLIC BLACK C', 'S3&V8SERISE', 69, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(321, 'INVENTORY/METALLIC BLACK C/S3&V8SERISE/SVDSRMB16.1-DOUBLE SWITCH MODULE(1 WAY)', 'SVDSRMB16.1-DOUBLE SWITCH MODULE(1 WAY)', 'INVENTORY', 'METALLIC BLACK C', 'S3&V8SERISE', 89, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(322, 'INVENTORY/METALLIC BLACK C/S3&V8SERISE/SV3PUMB-10.3 PIN SOCKET MODULE(UNIVERSAL)', 'SV3PUMB-10.3 PIN SOCKET MODULE(UNIVERSAL)', 'INVENTORY', 'METALLIC BLACK C', 'S3&V8SERISE', 99, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(323, 'INVENTORY/METALLIC BLACK C/S3&V8SERISE/SVTVSMB-TV SOCKET MODULE', 'SVTVSMB-TV SOCKET MODULE', 'INVENTORY', 'METALLIC BLACK C', 'S3&V8SERISE', 80, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(324, 'INVENTORY/METALLIC BLACK C/S3&V8SERISE/SVTSMB3-TELEPHONE SOCKET MODULE', 'SVTSMB3-TELEPHONE SOCKET MODULE', 'INVENTORY', 'METALLIC BLACK C', 'S3&V8SERISE', 99, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(325, 'INVENTORY/METALLIC BLACK C/S3&V8SERISE/SVDSMB6--DATA SOCKET MODULE', 'SVDSMB6--DATA SOCKET MODULE', 'INVENTORY', 'METALLIC BLACK C', 'S3&V8SERISE', 165, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(326, 'INVENTORY/METALLIC BLACK C/S3&V8SERISE/SVBMMB-BLANK MODULE', 'SVBMMB-BLANK MODULE', 'INVENTORY', 'METALLIC BLACK C', 'S3&V8SERISE', 0, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(327, 'INVENTORY/METALLIC BLACK C/S3&V8SERISE/S3CBSRMB16.1-CALLING BELL SWITCH(S3)', 'S3CBSRMB16.1-CALLING BELL SWITCH(S3)', 'INVENTORY', 'METALLIC BLACK C', 'S3&V8SERISE', 125, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(328, 'INVENTORY/METALLIC BLACK C/S3&V8SERISE/V8CBSRMB16.1-CALLING BELL SWITCH(V8)', 'V8CBSRMB16.1-CALLING BELL SWITCH(V8)', 'INVENTORY', 'METALLIC BLACK C', 'S3&V8SERISE', 139, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(329, 'INVENTORY/METALLIC BLACK C/S3&V8SERISE/S31GSRMB16.1-1 GANG SWITCH(S3)', 'S31GSRMB16.1-1 GANG SWITCH(S3)', 'INVENTORY', 'METALLIC BLACK C', 'S3&V8SERISE', 130, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(330, 'INVENTORY/METALLIC BLACK C/S3&V8SERISE/V81GSRMB16.1-1 GANG SWITCHA(V8)', 'V81GSRMB16.1-1 GANG SWITCHA(V8)', 'INVENTORY', 'METALLIC BLACK C', 'S3&V8SERISE', 140, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(331, 'INVENTORY/ELECTRIC SWITCH/10 PCS CARTOON/WGS-01 GANG', 'WGS-01 GANG', 'INVENTORY', 'ELECTRIC SWITCH', '10 PCS CARTOON', 85, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(332, 'INVENTORY/ELECTRIC SWITCH/10 PCS CARTOON/WGS-02 GANG', 'WGS-02 GANG', 'INVENTORY', 'ELECTRIC SWITCH', '10 PCS CARTOON', 140, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(333, 'INVENTORY/ELECTRIC SWITCH/10 PCS CARTOON/WGS-03 GANG', 'WGS-03 GANG', 'INVENTORY', 'ELECTRIC SWITCH', '10 PCS CARTOON', 185, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(334, 'INVENTORY/ELECTRIC SWITCH/10 PCS CARTOON/WGS-04 GANG', 'WGS-04 GANG', 'INVENTORY', 'ELECTRIC SWITCH', '10 PCS CARTOON', 245, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(335, 'INVENTORY/ELECTRIC SWITCH/10 PCS CARTOON/WGS-05 GANG', 'WGS-05 GANG', 'INVENTORY', 'ELECTRIC SWITCH', '10 PCS CARTOON', 290, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(336, 'INVENTORY/ELECTRIC SWITCH/10 PCS CARTOON/W3PS-01 PIN SOCKET', 'W3PS-01 PIN SOCKET', 'INVENTORY', 'ELECTRIC SWITCH', '10 PCS CARTOON', 210, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(337, 'INVENTORY/ELECTRIC SWITCH/S3&V8SERISE/WU3P-01(USB)', 'WU3P-01(USB)', 'INVENTORY', 'ELECTRIC SWITCH', 'S3&V8SERISE', 445, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(338, 'INVENTORY/ELECTRIC SWITCH/10 PCS CARTOON/WCB-01(CALLING BELL)', 'WCB-01(CALLING BELL)', 'INVENTORY', 'ELECTRIC SWITCH', '10 PCS CARTOON', 90, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(339, 'INVENTORY/ELECTRIC SWITCH/10 PCS CARTOON/WRCS-01(REMOTE CON. SWITCH)', 'WRCS-01(REMOTE CON. SWITCH)', 'INVENTORY', 'ELECTRIC SWITCH', '10 PCS CARTOON', 650, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(340, 'INVENTORY/ELECTRIC SWITCH/10 PCS CARTOON/S3CBSWWR16.1-CALLING BELL-S3', 'S3CBSWWR16.1-CALLING BELL-S3', 'INVENTORY', 'ELECTRIC SWITCH', '10 PCS CARTOON', 105, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(341, 'INVENTORY/ELECTRIC SWITCH/10 PCS CARTOON/V8CBSWWR16.1-CALLING BELL-V8', 'V8CBSWWR16.1-CALLING BELL-V8', 'INVENTORY', 'ELECTRIC SWITCH', '10 PCS CARTOON', 119, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(342, 'INVENTORY/ELECTRIC SWITCH/10 PCS CARTOON/S32MCPWW-2M COVER PLATE-S3', 'S32MCPWW-2M COVER PLATE-S3', 'INVENTORY', 'ELECTRIC SWITCH', '10 PCS CARTOON', 55, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(343, 'INVENTORY/ELECTRIC SWITCH/10 PCS CARTOON/V82MCPWW-2M COVER PLATE-V8', 'V82MCPWW-2M COVER PLATE-V8', 'INVENTORY', 'ELECTRIC SWITCH', '10 PCS CARTOON', 69, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(344, 'INVENTORY/ELECTRIC SWITCH/10 PCS CARTOON/S33MCPWW-3M COVER LATE-S3', 'S33MCPWW-3M COVER LATE-S3', 'INVENTORY', 'ELECTRIC SWITCH', '10 PCS CARTOON', 69, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(345, 'INVENTORY/ELECTRIC SWITCH/10 PCS CARTOON/V83MCPWW-3M COVER PLATE-V8', 'V83MCPWW-3M COVER PLATE-V8', 'INVENTORY', 'ELECTRIC SWITCH', '10 PCS CARTOON', 83, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(346, 'INVENTORY/ELECTRIC SWITCH/10 PCS CARTOON/S34MCPWW-4M COVER PLATE-S3', 'S34MCPWW-4M COVER PLATE-S3', 'INVENTORY', 'ELECTRIC SWITCH', '10 PCS CARTOON', 79, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(347, 'INVENTORY/ELECTRIC SWITCH/10 PCS CARTOON/V84MCPDWW-4M COVER PLATE-V8', 'V84MCPDWW-4M COVER PLATE-V8', 'INVENTORY', 'ELECTRIC SWITCH', '10 PCS CARTOON', 94, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(348, 'INVENTORY/ELECTRIC SWITCH/10 PCS CARTOON/SVSSWWR16.1-SINGLE SWITCH', 'SVSSWWR16.1-SINGLE SWITCH', 'INVENTORY', 'ELECTRIC SWITCH', '10 PCS CARTOON', 59, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(349, 'INVENTORY/ELECTRIC SWITCH/10 PCS CARTOON/SVDSWWR16.1-DOUBLE SWITCH', 'SVDSWWR16.1-DOUBLE SWITCH', 'INVENTORY', 'ELECTRIC SWITCH', '10 PCS CARTOON', 75, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(350, 'INVENTORY/ELECTRIC SWITCH/10 PCS CARTOON/SV3PUWW10-3 PIN SOCKET', 'SV3PUWW10-3 PIN SOCKET', 'INVENTORY', 'ELECTRIC SWITCH', '10 PCS CARTOON', 85, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(351, 'INVENTORY/ELECTRIC SWITCH/10 PCS CARTOON/SVTVSW-TV SOCKET', 'SVTVSW-TV SOCKET', 'INVENTORY', 'ELECTRIC SWITCH', '10 PCS CARTOON', 69, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(352, 'INVENTORY/ELECTRIC SWITCH/10 PCS CARTOON/SVTSWW--TELEPHONE SOCKET', 'SVTSWW--TELEPHONE SOCKET', 'INVENTORY', 'ELECTRIC SWITCH', '10 PCS CARTOON', 89, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(353, 'INVENTORY/ELECTRIC SWITCH/10 PCS CARTOON/SVDSWW6-DATA SOCKET', 'SVDSWW6-DATA SOCKET', 'INVENTORY', 'ELECTRIC SWITCH', '10 PCS CARTOON', 145, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(354, 'INVENTORY/ELECTRIC SWITCH/10 PCS CARTOON/SVBMW-BLANK', 'SVBMW-BLANK', 'INVENTORY', 'ELECTRIC SWITCH', '10 PCS CARTOON', 20, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(355, 'INVENTORY/ELECTRIC SWITCH/10 PCS CARTOON/S31GSWW16.1-1 GANG SWITCH S3', 'S31GSWW16.1-1 GANG SWITCH S3', 'INVENTORY', 'ELECTRIC SWITCH', '10 PCS CARTOON', 105, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(356, 'INVENTORY/ELECTRIC SWITCH/10 PCS CARTOON/V81GSWWR16.1-1 GANG SWITCH-V8', 'V81GSWWR16.1-1 GANG SWITCH-V8', 'INVENTORY', 'ELECTRIC SWITCH', '10 PCS CARTOON', 119, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(357, 'INVENTORY/RICE-COOKER//WRC-C322(3.2L-DOUBLE POT SS+AL', 'WRC-C322(3.2L-DOUBLE POT SS+AL', 'INVENTORY', 'RICE-COOKER', '', 3300, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(358, 'INVENTORY/RICE-COOKER//WR-HS50-2.2LT', 'WR-HS50-2.2LT', 'INVENTORY', 'RICE-COOKER', '', 3300, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(359, 'INVENTORY/RICE-COOKER//WR-SS25-2.5LT', 'WR-SS25-2.5LT', 'INVENTORY', 'RICE-COOKER', '', 3150, 0, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `item` (`componentId`, `uniqueCode`, `itemName`, `category1`, `category2`, `category3`, `salePrice`, `minQty`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(360, 'INVENTORY/RICE-COOKER//WR-HS40-1.8LT', 'WR-HS40-1.8LT', 'INVENTORY', 'RICE-COOKER', '', 2990, 0, 0, 0, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `language`
--

DROP TABLE IF EXISTS `language`;
CREATE TABLE IF NOT EXISTS `language` (
  `phrase_id` int(11) NOT NULL AUTO_INCREMENT,
  `phrase` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `english` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`phrase_id`)
)  ;

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

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
)  ;

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

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
) ;

--
-- Dumping data for table `role`
--

INSERT INTO `role` (`componentId`, `uniqueCode`, `description`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(1, 'System Admin', 'System Admin Role', 1, 0, NULL, NULL, NULL, NULL);
INSERT INTO `role` (`componentId`, `uniqueCode`, `description`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(2, 'Group Admin', 'System Admin Role', 1, 0, NULL, NULL, NULL, NULL);
INSERT INTO `role` (`componentId`, `uniqueCode`, `description`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(3, 'User', 'User Role', 1, 0, NULL, NULL, NULL, NULL);
INSERT INTO `role` (`componentId`, `uniqueCode`, `description`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(5, 'role 2 1111', 'Role two 1111', 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `role` (`componentId`, `uniqueCode`, `description`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(6, 'role 3xx', 'Role Threexx', 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `role` (`componentId`, `uniqueCode`, `description`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(7, 'New role 1', 'role new', 0, 0, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `sequences`
--

DROP TABLE IF EXISTS `sequences`;
CREATE TABLE IF NOT EXISTS `sequences` (
  `componentId` bigint(20) NOT NULL AUTO_INCREMENT,
  `uniqueCode` varchar(128) DEFAULT NULL,
  `currentValue` bigint(20) NOT NULL,
  PRIMARY KEY (`componentId`)
) ;

--
-- Dumping data for table `sequences`
--

INSERT INTO `sequences` (`componentId`, `uniqueCode`, `currentValue`) VALUES(1, 'TEST', 14);
INSERT INTO `sequences` (`componentId`, `uniqueCode`, `currentValue`) VALUES(2, 'JOURNAL', 136);
INSERT INTO `sequences` (`componentId`, `uniqueCode`, `currentValue`) VALUES(3, 'PURCHASE', 103);
INSERT INTO `sequences` (`componentId`, `uniqueCode`, `currentValue`) VALUES(4, 'SALES', 31);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
CREATE TABLE IF NOT EXISTS `settings` (
  `settings_id` int(11) NOT NULL AUTO_INCREMENT,
  `type` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `description` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`settings_id`)
) ;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`settings_id`, `type`, `description`) VALUES(1, 'system_name', 'T.R. Electro Mart');
INSERT INTO `settings` (`settings_id`, `type`, `description`) VALUES(2, 'system_title', 'T.R. Electro Mart');
INSERT INTO `settings` (`settings_id`, `type`, `description`) VALUES(3, 'address', 'Hobigong, Bangladesh');
INSERT INTO `settings` (`settings_id`, `type`, `description`) VALUES(4, 'phone', '01824412272');
INSERT INTO `settings` (`settings_id`, `type`, `description`) VALUES(5, 'paypal_email', 'payment@school.com');
INSERT INTO `settings` (`settings_id`, `type`, `description`) VALUES(6, 'currency', 'usd');
INSERT INTO `settings` (`settings_id`, `type`, `description`) VALUES(7, 'system_email', 'info@netsoft.com.bd');
INSERT INTO `settings` (`settings_id`, `type`, `description`) VALUES(8, 'buyer', '');
INSERT INTO `settings` (`settings_id`, `type`, `description`) VALUES(9, 'purchase_code', '');

-- --------------------------------------------------------

--
-- Table structure for table `supplier`
--

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
)  ;

--
-- Dumping data for table `supplier`
--

INSERT INTO `supplier` (`componentId`, `uniqueCode`, `supplierName`, `description`, `city`, `phone`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(8, 'WALTON HI-TECH INDUSTRIES LTD572074281d7f9', 'WALTON HI-TECH INDUSTRIES LTD', 'WALTON HI-TECH INDUSTRIES LTD', 'CHANDRA, KALIAKOIR, GAZIPUR', '01678028150', 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `supplier` (`componentId`, `uniqueCode`, `supplierName`, `description`, `city`, `phone`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(9, 'WALTON-MICROTECH-CORPORATION LTD572083b3ae0a5', 'WALTON-MICROTECH-CORPORATION LTD', 'WALTON- MICROTECH-CORPORATION LTD', 'CHANDRA, KALIAKOIR, GAZIPUR', '01678028150', 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `supplier` (`componentId`, `uniqueCode`, `supplierName`, `description`, `city`, `phone`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(10, 'DREAM-PARK-INTERNATIONAL572083a775ca0', 'DREAM-PARK-INTERNATIONAL', 'DREAM-PARK-INTERNATIONAL', 'CHANDRA, KALIAKOIR, GAZIPUR', '01678028150', 0, 0, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `transaction`
--

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

--
-- Dumping data for table `transaction`
--

INSERT INTO `transaction` (`componentId`, `uniqueCode`, `description`, `tdate`, `month`, `year`, `type`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(24, 'PURCHASE-96', 'PUR', '2016-04-27 00:00:00', NULL, NULL, 'PURCHASE', 0, 0, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `transaction_detail`
--

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
  PRIMARY KEY (`componentId`)
);

--
-- Dumping data for table `transaction_detail`
--

INSERT INTO `transaction_detail` (`componentId`, `transactionId`, `userId`, `itemId`, `accountId`, `type`, `quantity`, `unitPrice`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(1, 2, 7, 1, 1, 1, 100, 1, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `transaction_detail` (`componentId`, `transactionId`, `userId`, `itemId`, `accountId`, `type`, `quantity`, `unitPrice`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(46, 24, 9, 276, 12, 1, 1, 100, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `transaction_detail` (`componentId`, `transactionId`, `userId`, `itemId`, `accountId`, `type`, `quantity`, `unitPrice`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(47, 24, 9, 277, 12, 1, 1, 150, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `transaction_detail` (`componentId`, `transactionId`, `userId`, `itemId`, `accountId`, `type`, `quantity`, `unitPrice`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(48, 24, 9, 278, 12, 1, 1, 250, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `transaction_detail` (`componentId`, `transactionId`, `userId`, `itemId`, `accountId`, `type`, `quantity`, `unitPrice`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(49, 24, 1, 1, 1, -1, 100, 1, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `transaction_detail` (`componentId`, `transactionId`, `userId`, `itemId`, `accountId`, `type`, `quantity`, `unitPrice`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(50, 24, 1, 1, 2, -1, 100, 1, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `transaction_detail` (`componentId`, `transactionId`, `userId`, `itemId`, `accountId`, `type`, `quantity`, `unitPrice`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(51, 24, 1, 1, 3, -1, 200, 1, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `transaction_detail` (`componentId`, `transactionId`, `userId`, `itemId`, `accountId`, `type`, `quantity`, `unitPrice`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(52, 24, 9, 0, 3, -1, 200, 1, 0, 0, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

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

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`componentId`, `uniqueCode`, `firstName`, `lastName`, `password`, `email`, `groupId`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(1, 'admin', 'admin', 'admin', '21232f297a57a5a743894a0e4a801fc3', 'admin@i2gether.com', NULL, 1, 0, NULL, NULL, NULL, NULL);
INSERT INTO `user` (`componentId`, `uniqueCode`, `firstName`, `lastName`, `password`, `email`, `groupId`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(2, 'Sharif', 'Sharif', 'useer', '123', 'abc@def.hhh', NULL, 0, 0, NULL, NULL, NULL, NULL);
INSERT INTO `user` (`componentId`, `uniqueCode`, `firstName`, `lastName`, `password`, `email`, `groupId`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(4, '', '', '', '', '', NULL, 0, 0, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `userrole`
--

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

--
-- Dumping data for table `userrole`
--

INSERT INTO `userrole` (`componentId`, `userId`, `roleId`, `status`, `version`, `createddate`, `createdby`, `updateddate`, `updatedby`) VALUES(1, 1, 1, 1, 1, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------
--
-- Table structure for table `warehouse`
--
DROP TABLE IF EXISTS `warehouse`;
CREATE TABLE IF NOT EXISTS `warehouse` (
  `componentId` bigint(20) NOT NULL,
  `uniqueCode` varchar(128) DEFAULT NULL,
  `address` varchar(200) DEFAULT NULL,
  `type` int(11) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `version` int(11) DEFAULT '0',
  `createddate` datetime DEFAULT NULL,
  `createdby` bigint(20) DEFAULT NULL,
  `updateddate` datetime DEFAULT NULL,
  `updatedby` bigint(20) DEFAULT NULL,
   PRIMARY KEY (`componentId`)
) ;



-------------------------------------------------------------
--
---- Table structure for table `userwarehouse`
-- 
DROP TABLE IF EXISTS `userwarehouse`;
CREATE TABLE IF NOT EXISTS `userwarehouse` (
  `componentId` bigint(20) NOT NULL,
  `userId` bigint(20) NOT NULL,
  `warehouseId` bigint(20) NOT NULL,
  `status` int(11) DEFAULT NULL,
  `version` int(11) DEFAULT NULL,
  `createddate` datetime DEFAULT NULL,
  `createdby` bigint(20) DEFAULT NULL,
  `updateddate` datetime DEFAULT NULL,
  `updatedby` bigint(20) DEFAULT NULL,
   PRIMARY KEY (`componentId`,`userId`,`warehouseId`)
) ;





-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--
DROP TABLE IF EXISTS `inventory`;
CREATE TABLE IF NOT EXISTS `inventory` (
  `componentId` bigint(20) NOT NULL,
  `uniqueCode` varchar(128) DEFAULT NULL,
  `description` varchar(128) DEFAULT NULL,
  `tdate` datetime DEFAULT NULL,
  `type` varchar(128) DEFAULT NULL,
  `status` varchar(250) NOT NULL DEFAULT '0',
  `createddate` datetime DEFAULT NULL,
  `createdby` bigint(20) DEFAULT NULL,
  `updateddate` datetime DEFAULT NULL,
  `updatedby` bigint(20) DEFAULT NULL,
  `warehouseId` bigint(20) NOT NULL,
  `refrencewarehouseId` bigint(20) NOT NULL,
  `version` int(11) NOT NULL DEFAULT '0',
  `transactionId` bigint(20) NOT NULL,
   PRIMARY KEY (`componentId`)
) ;




-- --------------------------------------------------------

--
-- Table structure for table `inventorydetail`
--
DROP TABLE IF EXISTS `inventorydetail`;
CREATE TABLE IF NOT EXISTS  `inventorydetail` (
  `componentId` bigint(20) NOT NULL,
  `itemId` int(11) DEFAULT NULL,
  `quantity` float DEFAULT NULL,
  `type` int(11) DEFAULT NULL,
  `condition` varchar(128) DEFAULT NULL,
  `inventoryId` bigint(20) NOT NULL,
  `warehouseId` bigint(20) NOT NULL,
   PRIMARY KEY (`componentId`)
  
) ;



---------------------------------------------------------------------

--
-- Structure for view `inventoryView`
--
DROP VIEW IF EXISTS `inventoryView`;

CREATE VIEW `inventoryView` AS 
SELECT i.componentId AS itemId,i.uniqueCode AS itemName,(d.type * d.quantity) AS quantity,t.tdate AS tdate,
	t.uniqueCode AS voucher,u.uniqueCode as unit, i.minQty AS minQty,i.salePrice AS salePrice,d.transactionId AS transactionId,
	d.unitPrice AS unitPrice,(d.unitPrice * d.quantity) AS amount,d.quantity AS absquantity 
FROM item i 
LEFT JOIN transaction_detail d on(d.itemId = i.componentId) 
LEFT JOIN transaction t on(d.transactionId = t.componentId) 
LEFT JOIN unit u ON (i.unitId = u.componentId)
WHERE (i.category1 = 'INVENTORY') and (d.accountId <> 8) and (d.accountId <> 9)

-- --------------------------------------------------------

--
-- Structure for view `vfunctiongroups`
--
DROP VIEW IF EXISTS `vfunctiongroups`;

CREATE VIEW `vfunctiongroups` AS select `f`.`functionGroup` AS `functionGroup`,min(`f`.`componentId`) AS `functionid`,count(0) AS `rownum`,`u`.`userId` AS `userid` from ((`functioncode` `f` join `functionrole` `r` on((`f`.`componentId` = `r`.`functionId`))) join `userrole` `u` on((`r`.`roleId` = `u`.`roleId`))) group by `u`.`userId`,`f`.`functionGroup`;

-- --------------------------------------------------------

--
-- Structure for view `vTransactions`
--
DROP VIEW IF EXISTS `vTransactions`;

CREATE VIEW `vTransactions` AS select `t`.`componentId` AS `componentId`,`t`.`description` AS `description`,`t`.`uniqueCode` AS `uniqueCode`,`t`.`tdate` AS `tdate`,`t`.`type` AS `type`,sum((`d`.`quantity` * `d`.`unitPrice`)) AS `amount` from (`transaction` `t` join `transaction_detail` `d` on(((`t`.`componentId` = `d`.`transactionId`) and (`d`.`type` = 1)))) group by `t`.`componentId`;

-- --------------------------------------------------------

--
-- Structure for view `vuserfunctions`
--
DROP VIEW IF EXISTS `vuserfunctions`;

CREATE VIEW `vuserfunctions` AS select `f`.`componentId` AS `componentId`,`f`.`uniqueCode` AS `uniqueCode`,`f`.`displayName` AS `displayName`,`f`.`functionGroup` AS `functionGroup`,`f`.`codeNumber` AS `codeNumber`,`f`.`actionUrl` AS `actionUrl`,`f`.`isMenu` AS `isMenu`,`f`.`status` AS `status`,`f`.`version` AS `version`,`f`.`createddate` AS `createddate`,`f`.`createdby` AS `createdby`,`f`.`updateddate` AS `updateddate`,`f`.`updatedby` AS `updatedby`,`u`.`userId` AS `userid` from ((`functioncode` `f` join `functionrole` `r` on((`f`.`componentId` = `r`.`functionId`))) join `userrole` `u` on((`r`.`roleId` = `u`.`roleId`)));

DROP VIEW IF EXISTS vFrequentItem;
CREATE VIEW vFrequentItem AS
SELECT i.*, count(d.componentId) 
FROM item i
LEFT JOIN transaction_detail d ON (d.itemId = i.componentId)
LEFT JOIN transaction t ON (d.transactionId = t.componentId)
WHERE category1 = 'INVENTORY'
GROUP BY i.componentId
ORDER BY count(d.componentId) DESC;
----------------------------------------------------------------





---------------------------------------------------------------

drop table if exists `Challan`;
create table `Challan` (
	`componentId` bigint not null auto_increment,
	`uniqueCode` varchar(128),
	`transactionId` int,
	`customerId` int,
	`address` varchar(128),

	`status` int not null default '0',
	`version` int default 0,
	`createddate` datetime default null,
	`createdby` bigint default null,
	`updateddate` datetime default null,
	`updatedby` bigint default null,
	primary key (`componentId`)
) ENGINE = InnoDB; #here componentId is automatically clustered index in MySQL 5.0
CREATE INDEX `Challan_Code_index` ON `Challan`(`uniqueCode`);


INSERT INTO `FunctionCode`(`uniqueCode`, `displayName`,functionGroup, `codeNumber`,`actionUrl`, `isMenu`,`status`,`version`) 
 SELECT 'challansearch','Challans','Report', MAX(`codeNumber`)+1,'challan/search',1,1,0 
 FROM `FunctionCode`;

INSERT INTO `FunctionCode`(`uniqueCode`, `displayName`,functionGroup,  `codeNumber`,`actionUrl`, `isMenu`,`status`,`version`) 
 SELECT 'challanadd','Add Challan','Report',MAX(`codeNumber`)+1,'challan/add',0,1,0 
 FROM `FunctionCode`;
INSERT INTO `FunctionCode`(`uniqueCode`, `displayName`, functionGroup, `codeNumber`,`actionUrl`, `isMenu`,`status`,`version`) 
 SELECT 'challanmodify','Modify Challan','Report',MAX(`codeNumber`)+1,'challan/modify',0,1,0 
 FROM `FunctionCode`;
INSERT INTO `FunctionCode`(`uniqueCode`, `displayName`,functionGroup,  `codeNumber`,`actionUrl`, `isMenu`,`status`,`version`) 
 SELECT 'challandelete','Delete Challan','Report',MAX(`codeNumber`)+1,'challan/delete',0,1,0 
 FROM `FunctionCode`;

 INSERT INTO `FunctionCode`(`uniqueCode`, `displayName`,functionGroup,  `codeNumber`,`actionUrl`, `isMenu`,`status`,`version`) 
 SELECT 'Profit &amp; Loss','Profit &amp; Loss','Report',MAX(`codeNumber`)+1,'ledger/profitloss',1,1,0 
 FROM `FunctionCode`;
 
ALTER VIEW paymentDueView AS
SELECT t.componentId transactionId, t.uniqueCode, t.tdate, 
SUM(CASE WHEN (d.type = -1 AND t.type = 'SALES') OR (d.type = 1 AND t.type = 'PURCHASE') THEN quantity*unitPrice ELSE 0 END) total, 
SUM( CASE WHEN a.category3 = 'BANK' OR a.category3 = 'CASH' THEN quantity*unitPrice ELSE 0 END) AS paid,
	SUM(	CASE WHEN a.componentId = 4 OR a.componentId = 5 THEN quantity*unitPrice ELSE 0 END) AS due,
	SUM(	CASE WHEN a.componentId = 9 THEN quantity*unitPrice ELSE 0 END) AS discount
FROM transaction_detail d
INNER JOIN transaction t ON (t.componentId = d.transactionId)
INNER JOIN account a ON (a.componentId = d.accountId)
WHERE (t.type = 'PURCHASE' OR t.type = 'SALES') 
GROUP BY d.transactionId;

ALTER TABLE `customer`
	ADD COLUMN `email` VARCHAR(100) NULL AFTER `phone`;
	
ALTER TABLE `challan`
	ADD COLUMN `email` VARCHAR(100) NULL;
	
	
DROP FUNCTION IF EXISTS getCOGS;

DELIMITER $$

CREATE FUNCTION getCOGS (pItemId BIGINT)
RETURNS FLOAT DETERMINISTIC
BEGIN
  declare vRet FLOAT;
  SET vRet = 0.00;
   
   SELECT SUM(d.quantity*d.unitPrice) / SUM(d.quantity) INTO vRet
	FROM transaction_detail d
	INNER JOIN transaction t ON (t.componentId = d.transactionId AND t.`type` = 'PURCHASE')
	WHERE d.itemId = pItemId AND d.`type` = 1 AND d.accountId = 12;

  RETURN vRet;
END$$

DELIMITER ;



ALTER TABLE `item`
	ADD COLUMN `unitId` INT(11) NULL DEFAULT NULL AFTER `minQty`;
	

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
  `componentId` bigint(20) NOT NULL AUTO_INCREMENT,
  `uniqueCode` varchar(128) DEFAULT NULL,
  `description` varchar(128) DEFAULT NULL,
  `tdate` datetime DEFAULT NULL,
  `type` varchar(128) DEFAULT NULL,
  `status` varchar(250) NOT NULL DEFAULT '0',
  `createddate` datetime DEFAULT NULL,
  `createdby` bigint(20) DEFAULT NULL,
  `updateddate` datetime DEFAULT NULL,
  `updatedby` bigint(20) DEFAULT NULL,
  `warehouseId` bigint(20) NOT NULL,
  `refrencewarehouseId` bigint(20) NOT NULL,
  `version` int(11) NOT NULL DEFAULT '0',
  `transactionId` bigint(20) NOT NULL,
  PRIMARY KEY (`componentId`)
) ;

DROP TABLE IF EXISTS `inventorydetail`;
CREATE TABLE IF NOT EXISTS `inventorydetail` (
  `componentId` bigint(20) NOT NULL AUTO_INCREMENT,
  `itemId` int(11) DEFAULT NULL,
  `quantity` float DEFAULT NULL,
  `type` int(11) DEFAULT NULL,
  `condition` varchar(128) DEFAULT NULL,
  `inventoryId` bigint(20) NOT NULL,
  `warehouseId` bigint(20) NOT NULL,
  PRIMARY KEY (`componentId`)
);

DROP TABLE IF EXISTS `userwarehouse`;
CREATE TABLE IF NOT EXISTS `userwarehouse` (
  `componentId` bigint(20) NOT NULL,
  `userId` bigint(20) NOT NULL,
  `warehouseId` bigint(20) NOT NULL,
  `status` int(11) DEFAULT NULL,
  `version` int(11) DEFAULT NULL,
  `createddate` datetime DEFAULT NULL,
  `createdby` bigint(20) DEFAULT NULL,
  `updateddate` datetime DEFAULT NULL,
  `updatedby` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`componentId`,`userId`,`warehouseId`)
);


DROP TABLE IF EXISTS `warehouse`;
CREATE TABLE IF NOT EXISTS `warehouse` (
  `componentId` bigint(20) NOT NULL AUTO_INCREMENT,
  `uniqueCode` varchar(128) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `address` varchar(1000) DEFAULT NULL,
  `contact` varchar(255) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `version` int(11) DEFAULT '0',
  `createddate` datetime DEFAULT NULL,
  `createdby` bigint(20) DEFAULT NULL,
  `updateddate` datetime DEFAULT NULL,
  `updatedby` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`componentId`)
);


INSERT INTO `FunctionCode`(`uniqueCode`, `displayName`,functionGroup, `codeNumber`,`actionUrl`, `isMenu`,`status`,`version`) 
 SELECT 'warehousesearch','Warehouses','Warehouse', MAX(`codeNumber`)+1,'warehouse/search',1,1,0 
 FROM `FunctionCode`;

INSERT INTO `FunctionCode`(`uniqueCode`, `displayName`,functionGroup,  `codeNumber`,`actionUrl`, `isMenu`,`status`,`version`) 
 SELECT 'warehouseadd','Add Warehouse','Warehouse',MAX(`codeNumber`)+1,'warehouse/add',1,1,0 
 FROM `FunctionCode`;
 
INSERT INTO `FunctionCode`(`uniqueCode`, `displayName`, functionGroup, `codeNumber`,`actionUrl`, `isMenu`,`status`,`version`) 
 SELECT 'warehousemodify','Modify Warehouse','Warehouse',MAX(`codeNumber`)+1,'warehouse/modify',0,1,0 
 FROM `FunctionCode`;
 
INSERT INTO `FunctionCode`(`uniqueCode`, `displayName`,functionGroup,  `codeNumber`,`actionUrl`, `isMenu`,`status`,`version`) 
 SELECT 'warehousedelete','Warehouse Delete','Warehouse',MAX(`codeNumber`)+1,'warehouse/delete',0,1,0 
 FROM `FunctionCode`;
 
 INSERT INTO `FunctionCode`(`uniqueCode`, `displayName`,functionGroup,  `codeNumber`,`actionUrl`, `isMenu`,`status`,`version`) 
 SELECT 'warehousesend','Send Inventory','Warehouse',MAX(`codeNumber`)+1,'warehouse/send',1,1,0 
 FROM `FunctionCode`;

INSERT INTO `FunctionCode`(`uniqueCode`, `displayName`,functionGroup,  `codeNumber`,`actionUrl`, `isMenu`,`status`,`version`) 
 SELECT 'warehousetransfer','Warehouse Transfer','Warehouse',MAX(`codeNumber`)+1,'warehouse/transfer',1,1,0 
 FROM `FunctionCode`;


DROP TABLE IF EXISTS `installment`;
CREATE TABLE IF NOT EXISTS `installment` (
  `componentId` bigint(20) NOT NULL AUTO_INCREMENT,
  `uniqueCode` varchar(128) DEFAULT NULL,
  `transactionId` bigint(20) DEFAULT NULL,
  `installmentNo` int(11) NOT NULL DEFAULT '0',
  `amount` double NOT NULL DEFAULT '0',
  `status` int(11) NOT NULL DEFAULT '0',
  `version` int(11) DEFAULT '0',
  `createddate` datetime DEFAULT NULL,
  `createdby` bigint(20) DEFAULT NULL,
  `updateddate` datetime DEFAULT NULL,
  `updatedby` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`componentId`)
);

INSERT INTO `FunctionCode`(`uniqueCode`, `displayName`,functionGroup, `codeNumber`,`actionUrl`, `isMenu`,`status`,`version`) 
 SELECT 'installmentsearch','Installments','Accounting', MAX(`codeNumber`)+1,'installment/search',1,1,0 
 FROM `FunctionCode`;
