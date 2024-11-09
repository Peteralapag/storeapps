<?PHP
include '../init.php';
$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);	
include '../db_config_main.php';
$mainconn = new mysqli(CON_HOST, CON_USER, CON_PASSWORD, CON_NAME);
$storebranch = $functions->AppBranch();
$transdate = $functions->GetSession('branchdate');
$shift = $functions->GetSession('shift');
$time_stamp = date("Y-m-d H:i:s");
if(isset($_POST['modulename']))
{
	$mode = $_POST['modulename'];
} else {
	print_r('
		<script>
			app_alert("Warning"," The Mode you are trying to pass does not exist","warning","Ok","","no");
		</script>
	');
	exit();
}
if($mode == 'production')
{
	if(isset($_SESSION['appstore_month'])) { $months = $_SESSION['appstore_month']; } else { $months = date("F"); }
	$monthnum = $functions->GetMonthNumber($months);
	$q ="branch='$storebranch' AND month='$monthnum'";
}
else if($mode == 'transfer' || $mode == 'rm_transfer' || $mode == 'supplies_transfer' || $mode == 'scrapinventory_transfer' || $mode == 'boinventory_transfer')
{
	$q = "report_date='$transdate' AND shift='$shift' AND posted='Posted'";
}
else if($mode == 'dum')
{
	$q = "branch='$storebranch' AND report_date='$transdate' AND shift='$shift'";
}
else {
	$q = "branch='$storebranch' AND report_date='$transdate' AND shift='$shift' AND posted='Posted'";
}
$table = "store_".$mode."_data";
$module_name = strtoupper($mode);
/* #################################################################################### */
$query ="SELECT * FROM $table WHERE $q";
$result = mysqli_query($db, $query);  
$x=mysqli_num_rows($result);
if($result->num_rows > 0)
{
	$y=0;
	while($ROW = mysqli_fetch_array($result))  
	{
		$y++;
		$rowid = $ROW['id'];
		if($table == "store_cashcount_data" || $table == "store_discount_data" || $table == "store_gcash_data"){
		}
		else{
			$item_id = $ROW['item_id'];
		}
		
		if($mode == 'fgts')
		{
			$summary->SendFGTSToServer($storebranch,$transdate,$shift,$time_stamp,$table,$rowid,$db,$mainconn);
		}
		else if($mode == 'transfer')
		{
			if($ROW['branch'] == $storebranch){
				$recipient = "branch='$storebranch'"; 
			} 
			else if($ROW['transfer_to'] == $storebranch){ 
				$recipient = "transfer_to='$storebranch'"; 
			}
			echo $summary->SendTransferToServer($recipient,$transdate,$shift,$time_stamp,$table,$rowid,$db,$mainconn);
		}
		else if($mode == 'charges')
		{
			echo $summary->SendChargesToServer($storebranch,$transdate,$shift,$time_stamp,$table,$rowid,$db,$mainconn);
		}
		else if($mode == 'snacks')
		{
			echo $summary->SendSnacksToServer($storebranch,$transdate,$shift,$time_stamp,$table,$rowid,$db,$mainconn);
		}
		else if($mode == 'badorder')
		{
			echo $summary->SendBOToServer($storebranch,$transdate,$shift,$time_stamp,$table,$rowid,$db,$mainconn);
		}
		else if($mode == 'damage')
		{
			echo $summary->SendDamageToServer($storebranch,$transdate,$shift,$time_stamp,$table,$rowid,$db,$mainconn);
		}		
		else if($mode == 'complimentary')
		{
			echo $summary->SendComplimentaryToServer($storebranch,$transdate,$shift,$time_stamp,$table,$rowid,$db,$mainconn);
		}
		else if($mode == 'receiving')
		{
			echo $summary->SendReceivingToServer($storebranch,$transdate,$shift,$time_stamp,$table,$rowid,$db,$mainconn);
		}
		else if($mode == 'cashcount')
		{
			echo $summary->SendCashCountToServer($storebranch,$transdate,$shift,$time_stamp,$table,$rowid,$db,$mainconn);
		}
		else if($mode == 'frozendough')
		{
			echo $summary->SendFrozenDoughToServer($storebranch,$transdate,$shift,$time_stamp,$table,$rowid,$db,$mainconn);
		}
		else if($mode == 'pcount')
		{
			echo $summary->SendPCountToServer($storebranch,$transdate,$shift,$time_stamp,$table,$rowid,$db,$mainconn);
		}
		else if($mode == 'discount')
		{
			echo $summary->SendDiscountToServer($storebranch,$transdate,$shift,$time_stamp,$table,$rowid,$db,$mainconn);
		}
		else if($mode == 'gcash')
		{
			echo $summary->SendGcashToServer($storebranch,$transdate,$shift,$time_stamp,$table,$rowid,$db,$mainconn);
		}
		else if($mode == 'grab')
		{
			echo $summary->SendGrabToServer($storebranch,$transdate,$shift,$time_stamp,$table,$rowid,$db,$mainconn);
		}
		else if($mode == 'pakati')
		{
			echo $summary->SendPakatiToServer($storebranch,$transdate,$shift,$time_stamp,$table,$rowid,$db,$mainconn);
		}
		else if($mode == 'production')
		{
			echo $summary->SendProductionToServer($storebranch,$monthnum,$table,$rowid,$db,$mainconn);
		}
		else if($mode == 'summary')
		{
			echo $summary->SendSummaryToServer($storebranch,$transdate,$shift,$time_stamp,$table,$rowid,$db,$mainconn);
		}
		else if($mode == 'rm_receiving')
		{
			echo $summary->SendRmReceivingToServer($storebranch,$transdate,$shift,$time_stamp,$table,$rowid,$db,$mainconn);
		}
		else if($mode == 'rm_transfer')
		{
			if($ROW['branch'] == $storebranch) { $recipient = "branch='$storebranch'"; }
			else if($ROW['transfer_to'] == $storebranch) { $recipient = "transfer_to='$storebranch'"; }
			echo $summary->SendRmTransferToServer($recipient,$transdate,$shift,$time_stamp,$table,$rowid,$db,$mainconn);
		}
		else if($mode == 'rm_badorder')
		{
			echo $summary->SendRmBOToServer($storebranch,$transdate,$shift,$time_stamp,$table,$rowid,$db,$mainconn);
		}
		else if($mode == 'rm_pcount')
		{
			echo $summary->SendRmPCountToServer($storebranch,$transdate,$shift,$time_stamp,$table,$rowid,$db,$mainconn);
		}
		else if($mode == 'dum')
		{
			echo $summary->SendDumToServer($storebranch,$transdate,$shift,$time_stamp,$table,$rowid,$db,$mainconn);
		}
		else if($mode == 'rm_summary')
		{
			echo $summary->SendRmSummaryToServer($storebranch,$transdate,$shift,$time_stamp,$table,$rowid,$db,$mainconn);
		}
		/////////////////// SUPLIES ///////////////
		else if($mode == 'supplies_receiving')
		{
			echo $summary->SendSuppliesReceivingToServer($storebranch,$transdate,$shift,$time_stamp,$table,$rowid,$db,$mainconn);
		}
		else if($mode == 'supplies_transfer')
		{
			if($ROW['branch'] == $storebranch) { $recipient = "branch='$storebranch'"; }
			else if($ROW['transfer_to'] == $storebranch) { $recipient = "transfer_to='$storebranch'"; }
			echo $summary->SendSuppliesTransferToServer($recipient,$transdate,$shift,$time_stamp,$table,$rowid,$db,$mainconn);
		}
		else if($mode == 'supplies_badorder')
		{
			echo $summary->SendRmBOToServer($storebranch,$transdate,$shift,$time_stamp,$table,$rowid,$db,$mainconn);
		}
		else if($mode == 'supplies_pcount')
		{
			echo $summary->SendSuppliesPCountToServer($storebranch,$transdate,$shift,$time_stamp,$table,$rowid,$db,$mainconn);
		}
		else if($mode == 'supplies_summary')
		{
			echo $summary->SendSuppliesSummaryToServer($storebranch,$transdate,$shift,$time_stamp,$table,$rowid,$db,$mainconn);
		}
		/////////////////// SCRAP INVENTORY ///////////////
		else if($mode == 'scrapinventory_receiving')
		{
			echo $summary->SendScrapInventoryReceivingToServer($storebranch,$transdate,$shift,$time_stamp,$table,$rowid,$db,$mainconn);
		}
		else if($mode == 'scrapinventory_transfer')
		{
			if($ROW['branch'] == $storebranch) { $recipient = "branch='$storebranch'"; }
			else if($ROW['transfer_to'] == $storebranch) { $recipient = "transfer_to='$storebranch'"; }
			echo $summary->SendScrapInventoryTransferToServer($recipient,$transdate,$shift,$time_stamp,$table,$rowid,$db,$mainconn);
		}
		else if($mode == 'scrapinventory_badorder')
		{
			echo $summary->SendScrapInventoryBOToServer($storebranch,$transdate,$shift,$time_stamp,$table,$rowid,$db,$mainconn);
		}
		else if($mode == 'scrapinventory_pcount')
		{
			echo $summary->SendScrapInventoryPCountToServer($storebranch,$transdate,$shift,$time_stamp,$table,$rowid,$db,$mainconn);
		}
		else if($mode == 'scrapinventory_summary')
		{
			echo $summary->SendScrapInventorySummaryToServer($storebranch,$transdate,$shift,$time_stamp,$table,$rowid,$db,$mainconn);
		}
		/////////////////// BO INVENTORY ///////////////
		else if($mode == 'boinventory_receiving')
		{
			echo $summary->SendBoInventoryReceivingToServer($storebranch,$transdate,$shift,$time_stamp,$table,$rowid,$db,$mainconn);
		}
		else if($mode == 'boinventory_transfer')
		{
			if($ROW['branch'] == $storebranch) { $recipient = "branch='$storebranch'"; }
			else if($ROW['transfer_to'] == $storebranch) { $recipient = "transfer_to='$storebranch'"; }
			echo $summary->SendBoInventoryTransferToServer($recipient,$transdate,$shift,$time_stamp,$table,$rowid,$db,$mainconn);
		}
		else if($mode == 'boinventory_badorder')
		{
			echo $summary->SendBoInventoryBOToServer($storebranch,$transdate,$shift,$time_stamp,$table,$rowid,$db,$mainconn);
		}
		else if($mode == 'boinventory_pcount')
		{
			echo $summary->SendBoInventoryPCountToServer($storebranch,$transdate,$shift,$time_stamp,$table,$rowid,$db,$mainconn);
		}
		else if($mode == 'boinventory_summary')
		{
			echo $summary->SendBoInventorySummaryToServer($storebranch,$transdate,$shift,$time_stamp,$table,$rowid,$db,$mainconn);
		}

		
		/* ################################# ENDING SCENE ############################# */
		if($x == $y)
		{
			mysqli_close($db);
			mysqli_close($mainconn);
			
			$page = $_SESSION['session_sidebar'];
			if($page == 'boinventory'){
				$page = 'boinventory_submitserver';
			}
			else if($page == 'scrapinventory'){
				$page = 'scrapinventory_submitserver';
			}
			else if($page == 'supplies'){
				$page = 'supplies_submitserver';
			}
			else if($page == 'rawmats'){
				$page = 'rm_submitserver';
			}
			else if($page == 'fgts'){
				$page = 'submitserver';
			}
			else{
				$page = '';
			}
			$cmd = '';
			$cmd .= '
				<script>
					console.log("'.$page.'");
					var check = "<i class=fa fa-bell icon-color-green></i>";
					var aydi = "'.$mode.'";
					$("#" + aydi).html("'.$module_name.' is successfuly Submitted to the Server" + check);
					set_function("Submit To Server","'.$page.'");	
				</script>
			';
			print_r($cmd);
		}
	}
} else {
	$cmd = '';
	$cmd .= '
		<script>
			var aydi = "'.$mode.'";
			$("#" + aydi).html("'.$module_name.' is not posted or no Data &times");
			$("#" + aydi).addClass("icon-color-red");
		</script>
	';
	print_r($cmd);
}
?>
