<?PHP
error_reporting(E_ALL);
header('Content-Type: text/html; charset=utf-8');
if (session_status() == PHP_SESSION_NONE) { session_start(); }
include 'db_config.php';
define('DB_HOST', $dbhost);
define('DB_USER', $dbuser);
define('DB_PASSWORD', $dbpass);
define('DB_NAME', $dbname);
require 'class/dropdowns.class.php';
require 'class/encrypted_password_class.php';
require 'class/functions.class.php';
require 'class/summary.class.php';
$summary = new TheSummary;
$functions = new TheFunctions;
$dropdown = new DropDowns;
$pass = new Password;
/* ################# STORE SETTINGS ################################ */
$dbe = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$qSettings = "SELECT * FROM store_settings WHERE id=1";
$res = mysqli_query($dbe, $qSettings);    
if ($res->num_rows > 0) 
{ 
    while($SETTROW = mysqli_fetch_array($res))  
	{
		define("APP_NAME", $SETTROW['app_name']);
		define("COMPANY", $SETTROW['company']);
		define("APP_LOGO", $SETTROW['app_logo']);
		define("VERSION_TEXT", $SETTROW['version_text']);
		// define("VERSION_NUMBER", $SETTROW['version_number']);
		if(isset( $SETTROW['theme_color']))
		{
			define("THEME_COLOR", $SETTROW['theme_color']);
		} else {
			define("THEME_COLOR", 0);
		}
	}
} else {
	echo "No Data";
}
if(THEME_COLOR == 0) {
	define("THEME", 'form_class_green');
} else if(THEME_COLOR == 1) {
	define("THEME", 'form_class_orange');
} else {
	define("THEME", 'form_class_green');
}

$url_host = '120.28.196.113';
/* ########### READ VERSION ################### */
//////////////////////////////////


function storeSettingsTableChecker($dbe) {
    $sql = "SELECT * FROM store_settings WHERE id='1' AND version_text='Created 2022 - '";
    $result = $dbe->query($sql);
    if ($result->num_rows > 0) {
        return 1;
    } else {
        return 0;
    }
}

if (storeSettingsTableChecker($dbe) === 0) {
    $alterQuery = "UPDATE store_settings SET version_text='Created 2022 - ' WHERE id='1'";
    if ($dbe->query($alterQuery) === TRUE) { } else { }
}

/////////////////////////////////// IDCODE COLUMN in CHARGES TABLE

function checkerForChargesIDCODEClolumn($dbe){
	$tablequeryLocation = "SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'store_charges_data' AND COLUMN_NAME = 'idcode'";
	$result = $dbe->query($tablequeryLocation);
	if ($result->num_rows > 0){
	    return 1;
	} 
	else{
	    return 0;
	}
}


if(checkerForChargesIDCODEClolumn($dbe) === 0){

    $alterQuery = "ALTER TABLE store_charges_data ADD COLUMN idcode VARCHAR(20)";
    if ($dbe->query($alterQuery) === TRUE) {} else {}
}
///////////////////
////////////////// CREATE INVENTORY RECORD DATA TABLE
/*
function inventoryrecordatachecker($dbe){
	$tablequeryLocation = "SELECT * FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_NAME = 'store_inventory_record_data'";
	$result = $dbe->query($tablequeryLocation);
	if ($result->num_rows > 0){
	    return 1;
	} 
	else{
	    return 0;
	}
}

if(inventoryrecordatachecker($dbe) === 0){
	$navigation = "CREATE TABLE `store_inventory_record_data` (`id` int(11) NOT NULL AUTO_INCREMENT,`branch` varchar(30) DEFAULT NULL,`report_date` date DEFAULT NULL,`shift` varchar(20) DEFAULT NULL,`employee_name` varchar(123) DEFAULT '',`supervisor` varchar(123) DEFAULT NULL,`category` varchar(80) DEFAULT NULL,`item_name` varchar(123) DEFAULT '',`item_id` int(11) DEFAULT NULL,`date_created` datetime DEFAULT '0000-00-00 00:00:00',`date_updated` datetime DEFAULT NULL,`updated_by` varchar(50) DEFAULT NULL,`posted` varchar(6) DEFAULT 'No',`status` varchar(6) DEFAULT 'Open',`audit_mode` varchar(1) DEFAULT NULL,PRIMARY KEY (`id`)";
	$result = $dbe->query($navigation);
}
*/
///////////////////////////
///////////////////////// NAVIGATION CHECKING

function navigationPcountActiveChecker($dbe){

	$sql = "SELECT * FROM store_navigation WHERE category_id='100' AND menu_name='Physical Count' AND page_name='pcount' AND display_icon='fa-solid fa-tally' AND sorting='13' AND active=0";
	$result = $dbe->query($sql);
	if ($result->num_rows > 0) {
		return 1;
	} 
	else
	{	  
		return 0; 
	}
}

if(navigationPcountActiveChecker($dbe) === 0){
	
	$navigation1 = "CREATE TABLE `store_inventory_record_data` (`id` int(11) NOT NULL AUTO_INCREMENT,`branch` varchar(30) DEFAULT NULL,`report_date` date DEFAULT NULL,`shift` varchar(20) DEFAULT NULL,`employee_name` varchar(123) DEFAULT '',`supervisor` varchar(123) DEFAULT NULL,`category` varchar(80) DEFAULT NULL,`item_name` varchar(123) DEFAULT '',`item_id` int(11) DEFAULT NULL,`date_created` datetime DEFAULT '0000-00-00 00:00:00',`date_updated` datetime DEFAULT NULL,`updated_by` varchar(50) DEFAULT NULL,`posted` varchar(6) DEFAULT 'No',`status` varchar(6) DEFAULT 'Open',`audit_mode` varchar(1) DEFAULT NULL,PRIMARY KEY (`id`))";
	$result = $dbe->query($navigation1);
	
    $valuesToInsert = [
		['1', '1', '100', 'Dashboard', 'dashboard', 'glyphicon glyphicon-home', 'icon-color-orange', '', '1'],
		['3', '2', '100', 'Finish Goods Transfer', 'fgts', 'fa-solid fa-utensils', 'text-primary', '', '1'],
		['5', '3', '100', 'Transfer In/Out', 'transfer', 'fa-solid fa-right-left', 'text-primary', '', '1'],
		['6', '4', '100', 'Charges', 'charges', 'fa-solid fa-file-invoice-dollar', 'text-primary', '', '0'],
		['7', '5', '100', 'Snacks', 'snacks', 'fa-solid fa-popcorn', 'text-primary', '', '0'],
		['8', '6', '100', 'Bad Order', 'badorder', 'fa-solid fa-send-back', 'text-primary', '', '0'],
		['9', '7', '100', 'Damage', 'damage', 'fa-solid fa-wine-glass-crack', 'text-primary', '', '0'],
		['10', '8', '100', 'Complimentary', 'complimentary', 'fa-sharp fa-solid fa-burger-soda', 'text-primary', '', '0'],
		['11', '9', '100', 'Request', 'request', 'fa-solid fa-paper-plane-top', 'text-primary', '', '0'],
		['12', '10', '100', 'Receiving Report', 'receiving', 'fa-solid fa-inbox-in', 'text-primary', '', '1'],
		['13', '11', '100', 'Cash Count', 'cashcount', 'fa-solid fa-treasure-chest', 'text-primary', '', '1'],
		['14', '15', '1100', 'Summary Report', 'summary', 'fa-solid fa-file-spreadsheet', 'text-danger', '', '1'],
		['15', '22', '1100', 'Submit To Server', 'submitserver', 'fa-solid fa-cloud', 'text-primary', '', '1'],
		['16', '1', '102', 'Receiving Report', 'rm_receiving', 'fa-solid fa-inbox-in', 'text-primary', '', '1'],
		['17', '2', '102', 'Transfer In/Out', 'rm_transfer', 'fa-solid fa-right-left', 'text-primary', '', '1'],
		['18', '4', '102', 'Phsysical Count', 'rm_pcount', 'fa-solid fa-tally', 'text-primary', '', '1'],
		['19', '1', '103', 'Summary Report', 'rm_summary', 'fa-solid fa-file-spreadsheet', 'text-danger', '', '1'],
		['20', '3', '103', 'Submit To Server', 'rm_submitserver', 'fa-solid fa-cloud', 'text-primary', '', '1'],
		['21', '0', '102', 'Dashboard', 'dashboard', 'glyphicon glyphicon-home', 'icon-color-orange', '', '1'],
		['22', '0', '104', 'Dashboard', 'dashboard', 'glyphicon glyphicon-home', 'icon-color-orange', '', '1'],
		['23', '16', '1100', 'Production Report', 'production', 'fa-solid fa-hammer', 'text-primary', '', '1'],
		['24', '0', '103', 'Daily Usage Report', 'dum', 'fa-solid fa-fill-drip', 'text-primary', '', '1'],
		['26', '3', '102', 'Bad Order', 'rm_badorder', 'fa-solid fa-send-back', 'text-primary', '', '1'],
		['27', '13', '103', 'Production Report', 'production', 'fa-book', 'text-primary', '', '0'],
		['28', '12', '100', 'Frozen Dough', 'frozendough', 'fa-solid fa-icicles', 'text-primary', '', '0'],
		['30', '13', '100', 'Physical Count', 'pcount', 'fa-solid fa-tally', 'text-primary', '', '0'],
		['31', '14', '100', 'Store Discount', 'discount', 'fa-solid fa-tags', 'text-primary', '', '1'],
		['33', '17', '1100', 'Inventory Report', 'inventory', 'fa-solid fa-file-spreadsheet', 'text-primary', null, '1'],
		['36', '1', '103', 'Inventory Report', 'rm_inventory', 'fa-solid fa-file-spreadsheet', 'text-primary', null, '1'],
		['38', '0', '103', 'Production Report', 'production', 'fa-solid fa-hammer', 'text-primary', null, '1'],
		['39', '1', '104', 'Receiving Report', 'supplies_receiving', 'fa-solid fa-inbox-in', 'text-primary', null, '1'],
		['40', '2', '104', 'Transfer In/Out', 'supplies_transfer', 'fa-solid fa-right-left', 'text-primary', null, '1'],
		['41', '3', '104', 'Bad Order', 'supplies_badorder', 'fa-solid fa-send-back', 'text-primary', null, '1'],
		['42', '4', '104', 'Physical Count', 'supplies_pcount', 'fa-solid fa-tally', 'text-primary', null, '1'],
		['43', '5', '105', 'Summary Report', 'supplies_summary', 'fa-solid fa-file-spreadsheet', 'text-danger', null, '1'],
		['44', '1', '106', 'Attachment Files', 'documents', 'fa-solid fa-paperclip', 'text-primary', null, '1'],
		['45', '0', '106', 'Dashboard', 'dashboard', 'glyphicon glyphicon-home', 'icon-color-orange', null, '1'],
		['46', '18', '1100', 'Sales Breakdown', 'sales_breakdown', 'fa-solid fa-file-spreadsheet', 'text-primary', null, '1'],
		['47', '7', '105', 'Submit To Server', 'supplies_submitserver', 'fa-solid fa-cloud', 'text-primary', null, '1'],
		['48', '6', '105', 'Inventory Report', 'supplies_inventory', 'fa-solid fa-file-spreadsheet', 'text-primary', null, '1'],
		['49', '0', '108', 'Dashboard', 'dashboard', 'glyphicon glyphicon-home', 'icon-color-orange', null, '1'],
		['50', '1', '108', 'Receiving Report', 'scrapinventory_receiving', 'fa-solid fa-inbox-in', 'text-primary', null, '1'],
		['51', '2', '108', 'Transfer In/Out', 'scrapinventory_transfer', 'fa-solid fa-right-left', 'text-primary', null, '1'],
		['52', '3', '108', 'Disposal', 'scrapinventory_badorder', 'fa-solid fa-send-back', 'text-primary', null, '1'],
		['53', '4', '108', 'Physical Count', 'scrapinventory_pcount', 'fa-solid fa-tally', 'text-primary', null, '1'],
		['54', '5', '109', 'Summary Report', 'scrapinventory_summary', 'fa-solid fa-file-spreadsheet', 'text-danger', null, '1'],
		['55', '6', '109', 'Inventory Report', 'scrapinventory_inventory', 'fa-solid fa-file-spreadsheet', 'text-primary', null, '1'],
		['56', '7', '109', 'Submit To Server', 'scrapinventory_submitserver', 'fa-solid fa-cloud', 'text-primary', null, '1'],
		['57', '0', '110', 'Dashboard', 'dashboard', 'glyphicon glyphicon-home', 'icon-color-orange', null, '1'],
		['58', '1', '110', 'Receiving Report', 'boinventory_receiving', 'fa-solid fa-inbox-in', 'text-primary', null, '1'],
		['59', '2', '110', 'Transfer In/Out', 'boinventory_transfer', 'fa-solid fa-right-left', 'text-primary', null, '1'],
		['60', '3', '110', 'Disposal', 'boinventory_badorder', 'fa-solid fa-send-back', 'text-primary', null, '1'],
		['61', '4', '110', 'Physical Count', 'boinventory_pcount', 'fa-solid fa-tally', 'text-primary', null, '1'],
		['62', '5', '111', 'Summary Report', 'boinventory_summary', 'fa-solid fa-file-spreadsheet', 'text-danger', null, '1'],
		['63', '6', '111', 'Inventory Report', 'boinventory_inventory', 'fa-solid fa-file-spreadsheet', 'text-primary', null, '1'],
		['64', '7', '111', 'Submit To Server', 'boinventory_submitserver', 'fa-solid fa-cloud', 'text-primary', null, '1'],
		['65', '15', '100', 'Gcash Sales', 'gcash', 'fa-solid fa-treasure-chest', 'text-primary', null, '1'],
		['66', '19', '111111100', 'Sales Inv. Vs Cash Count', 'salesinv_cashcount', 'fa-solid fa-file-spreadsheet', 'text-primary', null, '1'],
		['67', '16', '100', 'Grab Sales', 'grab', 'fa-solid fa-motorcycle', 'text-primary', null, '1'],
		['68', '17', '100', 'PAKATI', 'pakati', 'fa-solid fa-treasure-chest', 'text-primary', null, '1'],
		['69', '20', '1100', 'Sales Breakdown2', 'sales_breakdown2', 'fa-solid fa-file-spreadsheet', 'text-primary', null, '1'],
		['70', '21', '1100', 'BO Breakdown', 'bo_breakdown', 'fa-solid fa-file-spreadsheet', 'text-primary', null, '1'],
		['71', '0', '200', 'Build Assembly', 'build_assembly', 'fa-solid fa-circle-bolt', 'text-primary', null, '1'],
		['72', '1', '200', 'Bakes`s Guide', 'bakers_guide', 'fa-solid fa-book', 'text-primary', null, '1'],
		['73', '2', '201', 'Submit To Server', 'ba_submitserver', 'fa-solid fa-cloud', 'text-primary', null, '1'],
		['74', '18', '100', 'Inventory Record', 'inventory_record', 'fa-solid fa-book', 'text-primary', null, '1']
	];
	
	dropNavigationTable($dbe);
	createNavigationTable($dbe);
	insertNavigationData($dbe,$valuesToInsert);
}

 

function dropNavigationTable($dbe){
	$navigation = "DROP TABLE IF EXISTS store_navigation;";
	$result = $dbe->query($navigation);
}

function createNavigationTable($dbe){
	$navigation = "CREATE TABLE `store_navigation`(`id` bigint(20) NOT NULL AUTO_INCREMENT,`sorting` int(6) DEFAULT '0',`category_id` int(20) DEFAULT NULL,`menu_name` varchar(100) DEFAULT NULL,`page_name` varchar(100) DEFAULT NULL,`display_icon` varchar(100) DEFAULT NULL,`icon_color` varchar(100) DEFAULT NULL,`table_columns` longtext,`active` int(1) DEFAULT '1',PRIMARY KEY (`id`))";
	$result = $dbe->query($navigation);
}

function insertNavigationData($dbe,$values){
    $insertQuery = "INSERT INTO `store_navigation` VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $dbe->prepare($insertQuery);
    
    if (!$stmt) {
        echo "Error preparing statement: " . $dbe->error . "<br>";
        return;
    }
    
    foreach ($values as $params) {
        $stmt->bind_param('siisssssi', ...$params);
        $result = $stmt->execute();
        
        if (!$result) {
            echo "Error inserting data: " . $stmt->error . "<br>";
        }
    }
    $stmt->close();
}
/*
function createInventoryRecordTable($dbe){
	$navigation = "CREATE TABLE `store_inventory_record_data` (`id` int(11) NOT NULL AUTO_INCREMENT,`branch` varchar(30) DEFAULT NULL,`report_date` date DEFAULT NULL,`shift` varchar(20) DEFAULT NULL,`employee_name` varchar(123) DEFAULT '',`supervisor` varchar(123) DEFAULT NULL,`category` varchar(80) DEFAULT NULL,`item_name` varchar(123) DEFAULT '',`item_id` int(11) DEFAULT NULL,`date_created` datetime DEFAULT '0000-00-00 00:00:00',`date_updated` datetime DEFAULT NULL,`updated_by` varchar(50) DEFAULT NULL,`posted` varchar(6) DEFAULT 'No',`status` varchar(6) DEFAULT 'Open',`audit_mode` varchar(1) DEFAULT NULL,PRIMARY KEY (`id`)";
	$result = $dbe->query($navigation);
}
*/

