<?php
require '../init.php';
$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$selectedbranch = $_SESSION['appstore_branch'];
$defaultDate = $_SESSION['session_date'];
$TheFunctions = new TheFunctions;

if(isset($_POST['dateselectfrom']) && isset($_POST['dateselectto']))
{
	$dateselectfrom = $_POST['dateselectfrom'];
	$dateselectto = $_POST['dateselectto'];
	$q = "WHERE branch='$selectedbranch' AND report_date BETWEEN '$dateselectfrom' AND '$dateselectto'";
	if(isset($_POST['search'])){
		$search = $_POST['search'];
		$q .= " AND item_name LIKE '%$search%'";
	}
}
else
{
	$dateselectfrom = $defaultDate;
	$dateselectto = $defaultDate;
	$q = "WHERE branch='$selectedbranch' AND report_date='$defaultDate'";
}


?>
<style>
#tgas,#beg,#fgtsin,#tin,#tout,#del { position:relative; }
.title-box1,.title-box2,.title-box3,.title-box4,.title-box5,.title-box6  {
	position:absolute;display:none;font-size:12px;padding:5px 10px 5px 10px;
	border:#aeaeae;background:#f9f5e8;color:#232323;border-radius:5px; 
	border:1px solid orange;z-index:999;
}
.dc-notifs {
	padding:2px 10px 2px 10px;
	border:1px solid orange;
	background:#f9f5e8;
	font-weight:normal;
	font-style:italic;
	font-size:12px;
	margin-left:100px;
	border-radius:5px
}
</style>
<?php 
?>
<table id="upper" style="width: 100%" class="table table-hover table-striped table-bordered">
	<tr>
		<th style="width:50px;text-align:center">#</th>
		<th>ITEMS</th>
		<th>BEG</th>
		<th>STK.IN</th>
		<th>F.DOUGH</th>
		<th>TX-IN</th>
		<th>TX-OUT</th>
		<th>CHARGES</th>
		<th>SNACKS</th>
		<th>B.O</th>
		<th>DAMAGE</th>
		<th>COMPLI.</th>
		<th>ACTL. COUNT</th>
		<th>SOLD</th>
		<th>SRP</th>
		<th>AMOUNT</th>
		
	</tr>
<?php
	$breadsAmount =0; 
	$query ="SELECT * FROM store_summary_data $q GROUP BY item_id"; 	
	$result = mysqli_query($db, $query);  
	if($result->num_rows > 0)
	{

	$i=0;
	$total=0;
	$amounttotal=0;
	$breadsAmount =0;
	$total=0;$transfer_out=0;$should_be=0;$sold=0;$discnt=0;
	while($ROW = mysqli_fetch_array($result))  
	{
		$total=0;
		$breadsAmount =0;
		$total=0;$transfer_out=0;$should_be=0;$sold;
	
		$i++;
		$item_id = $ROW['item_id'];	
		$item_name = $ROW['item_name'];	
		$category = $ROW['category'];
		
		$beg = $TheFunctions->inventoryValue($selectedbranch,$item_id,$dateselectfrom,$dateselectto,'beginning',$db);
		$stock = $TheFunctions->inventoryValue($selectedbranch,$item_id,$dateselectfrom,$dateselectto,'stock_in',$db);
		$frozendough = $TheFunctions->inventoryValue($selectedbranch,$item_id,$dateselectfrom,$dateselectto,'frozendough',$db);
		$transfer_in = $TheFunctions->inventoryValue($selectedbranch,$item_id,$dateselectfrom,$dateselectto,'t_in',$db);
		$transfer_out = $TheFunctions->inventoryValue($selectedbranch,$item_id,$dateselectfrom,$dateselectto,'t_out',$db);
		
		$charges = $TheFunctions->inventoryValue($selectedbranch,$item_id,$dateselectfrom,$dateselectto,'charges',$db);
		$snacks = $TheFunctions->inventoryValue($selectedbranch,$item_id,$dateselectfrom,$dateselectto,'snacks',$db);
		$bad_order = $TheFunctions->inventoryValue($selectedbranch,$item_id,$dateselectfrom,$dateselectto,'bo',$db);
		$damaged = $TheFunctions->inventoryValue($selectedbranch,$item_id,$dateselectfrom,$dateselectto,'damaged',$db);
		$complimentary = $TheFunctions->inventoryValue($selectedbranch,$item_id,$dateselectfrom,$dateselectto,'complimentary',$db);
		
		$actual_count = $TheFunctions->inventoryValue($selectedbranch,$item_id,$dateselectfrom,$dateselectto,'actual_count',$db);
		$sold = $TheFunctions->inventoryValue($selectedbranch,$item_id,$dateselectfrom,$dateselectto,'sold',$db);
		$unit_price = $TheFunctions->getItemPriceDateSelectTo($dateselectto,$item_id,$db);
					
		$amount =  $TheFunctions->inventoryValue($selectedbranch,$item_id,$dateselectfrom,$dateselectto,'amount',$db);
		$amounttotal += $amount;
?>
	<tr>		
		<td style="text-align:center;"><?php echo $i; ?></td>	
		<td style="text-align:left;white-space:nowrap"><?php echo $item_name?></td>	
		<td><?php echo $beg?></td>
		<td><?php echo $stock?></td>
		<td><?php echo $frozendough?></td>
		<td><?php echo $transfer_in?></td>
		<td><?php echo $transfer_out?></td>
		<td><?php echo $charges?></td>
		<td><?php echo $snacks?></td>
		<td><?php echo $bad_order?></td>
		<td><?php echo $damaged?></td>
		<td><?php echo $complimentary?></td>
		<td><?php echo $actual_count?></td>
		<td><?php echo $sold?></td>
		<td><?php echo $unit_price?></td>
		<td style="text-align:right"><?php echo number_format($amount,2)?></td>
		
	</tr>
<?php
	}
	
	$breads = $TheFunctions->getInventoryBreakdown('BREADS',$selectedbranch,$dateselectfrom,$dateselectto,$db) - $TheFunctions->GetInventoryDiscountType('BREADS',$selectedbranch,$dateselectfrom,$dateselectto,$db);
	$cakes = $TheFunctions->getInventoryBreakdown('CAKES',$selectedbranch,$dateselectfrom,$dateselectto,$db) - $TheFunctions->GetInventoryDiscountType('CAKES',$selectedbranch,$dateselectfrom,$dateselectto,$db);
	$specials = $TheFunctions->getInventoryBreakdown('SPECIALS',$selectedbranch,$dateselectfrom,$dateselectto,$db) - $TheFunctions->GetInventoryDiscountType('SPECIALS',$selectedbranch,$dateselectfrom,$dateselectto,$db);
	$beverages = $TheFunctions->getInventoryBreakdown('BEVERAGES',$selectedbranch,$dateselectfrom,$dateselectto,$db) - $TheFunctions->GetInventoryDiscountType('BEVERAGES',$selectedbranch,$dateselectfrom,$dateselectto,$db);
	$bottledwater = $TheFunctions->getInventoryBreakdown('BOTTLED WATER',$selectedbranch,$dateselectfrom,$dateselectto,$db) - $TheFunctions->GetInventoryDiscountType('BOTTLED WATER',$selectedbranch,$dateselectfrom,$dateselectto,$db);
	$icecream = $TheFunctions->getInventoryBreakdown('ICE CREAM',$selectedbranch,$dateselectfrom,$dateselectto,$db) - $TheFunctions->GetInventoryDiscountType('ICE CREAM',$selectedbranch,$dateselectfrom,$dateselectto,$db);
	$merchandiseothers = $TheFunctions->getInventoryBreakdown('MERCHANDISE OTHERS',$selectedbranch,$dateselectfrom,$dateselectto,$db) - $TheFunctions->GetInventoryDiscountType('MERCHANDISE OTHERS',$selectedbranch,$dateselectfrom,$dateselectto,$db);
	$coffee = $TheFunctions->getInventoryBreakdown('COFFEE',$selectedbranch,$dateselectfrom,$dateselectto,$db) - $TheFunctions->GetInventoryDiscountType('COFFEE',$selectedbranch,$dateselectfrom,$dateselectto,$db);
	$milktea = $TheFunctions->getInventoryBreakdown('MILK TEA',$selectedbranch,$dateselectfrom,$dateselectto,$db) - $TheFunctions->GetInventoryDiscountType('MILK TEA',$selectedbranch,$dateselectfrom,$dateselectto,$db);
	$breakdowntotal = ($breads + $cakes + $specials + $beverages + $bottledwater + $icecream + $merchandiseothers + $coffee + $milktea);
?>
	<tr class="td-total">
		<td colspan="15" style="text-align:left;font-weight:bold">SALES SUMMARY :</td>
		<td style="text-align:right"><?php echo number_format($amounttotal,2)?></td>
	</tr>
	<tr class="td-total">
		<td colspan="15" style="text-align:left;font-weight:bold">
			TOTAL DISCOUNT <?php if($TheFunctions->GetInventoryDiscount($selectedbranch,$dateselectfrom,$dateselectto,$db) == 0) { echo '<span class="dc-notifs">Discount not posted</span>'; } ?>
		</td>
		<td style="text-align:right"><?php echo number_format($TheFunctions->GetInventoryDiscount($selectedbranch,$dateselectfrom,$dateselectto,$db),2)?></td>
	</tr>
	<tr class="td-total">
		<td colspan="15" style="text-align:left;font-weight:bold">GRAND TOTAL</td>
		<td style="text-align:right"><?php $grand_total = ($amounttotal - $TheFunctions->GetInventoryDiscount($selectedbranch,$dateselectfrom,$dateselectto,$db)); echo number_format($grand_total,2); ?></td>
	</tr>
	<tr class="td-total">
		<td colspan="15" style="text-align:left;font-weight:bold">TOTAL CASH COUNT</td>
		<td style="text-align:right"><?php echo number_format($TheFunctions->getInventoryCashCountTotal($selectedbranch,$dateselectfrom,$dateselectto,$db),2)?></td>
	</tr>
	<tr class="td-total">
		<td colspan="15" style="text-align:left;font-weight:bold">CASH VARIANCE</td>
		<td style="text-align:right"><?php $variance = $TheFunctions->getInventoryCashCountTotal($selectedbranch,$dateselectfrom,$dateselectto,$db) - $grand_total; echo number_format($variance,2)?></td>
	</tr>

	<tr>
		<td colspan="15">LESS:</td>
		</tr>
		<tr>
			<td colspan="15">SENIOR DISCOUNT -<i style="opacity: 0.5; position:"> Breads, Specials & Cakes</i></td>
			<td style="text-align:right"><?php echo $TheFunctions->GetIventoryDiscountType('SENIOR DISCOUNT',$selectedbranch,$dateselectfrom,$dateselectto,$db)?></td>
		</tr>
		<tr>
			<td colspan="15">ICE CREAM DISCOUNT -<i style="opacity: 0.5; position:"> Ice Cream</i></td>
			<td style="text-align:right"><?php echo $TheFunctions->GetIventoryDiscountType('ICE CREAM DISCOUNT',$selectedbranch,$dateselectfrom,$dateselectto,$db)?></td>
		</tr>
		<tr>
			<td colspan="15">CAKE DISCOUNT -<i style="opacity: 0.5; position:"> Cake</i></td>
			<td style="text-align:right"><?php echo $TheFunctions->GetIventoryDiscountType('CAKE DISCOUNT',$selectedbranch,$dateselectfrom,$dateselectto,$db)?></td>
		</tr>

	
		<tr><th></th>
			<th>BREADS<span class="label label-danger"><?php echo $TheFunctions->GetInventoryDiscountType('BREADS',$selectedbranch,$dateselectfrom,$dateselectto,$db)?></th>
			<th>CAKES<span class="label label-danger"><?php echo $TheFunctions->GetInventoryDiscountType('CAKES',$selectedbranch,$dateselectfrom,$dateselectto,$db)?></span></th>
			<th>SPECIALS<span class="label label-danger"><?php echo $TheFunctions->GetInventoryDiscountType('SPECIALS',$selectedbranch,$dateselectfrom,$dateselectto,$db)?></span></th>
			<th>BEVERAGES<span class="label label-danger"><?php echo $TheFunctions->GetInventoryDiscountType('BEVERAGES',$selectedbranch,$dateselectfrom,$dateselectto,$db)?></span></th>
			<th>BOTTLED WATER<span class="label label-danger"><?php echo $TheFunctions->GetInventoryDiscountType('BOTTTLED WATER',$selectedbranch,$dateselectfrom,$dateselectto,$db)?></span></th>
			<th>ICE CREAM<span class="label label-danger"><?php echo $TheFunctions->GetInventoryDiscountType('ICE CREAM',$selectedbranch,$dateselectfrom,$dateselectto,$db)?></span></th>
			<th>MERCHANDISE OTHERS<span class="label label-danger"><?php echo $TheFunctions->GetInventoryDiscountType('MERCHANDISE OTHERS',$selectedbranch,$dateselectfrom,$dateselectto,$db)?></span></th>
			<th>COFFEE<span class="label label-danger"><?php echo $TheFunctions->GetInventoryDiscountType('COFFEE',$selectedbranch,$dateselectfrom,$dateselectto,$db)?></span></th>
			<th>MILK TEA<span class="label label-danger"><?php echo $TheFunctions->GetInventoryDiscountType('MILK TEA',$selectedbranch,$dateselectfrom,$dateselectto,$db)?></span></th>			
			<th colspan="4">TOTAL BREAKDOWN</th>
		</tr>
		<tr>
			<td><i class="fa fa-long-arrow-right" aria-hidden="true"></i></td>
			<td class="al-right pad-right"><?php echo number_format($breads,2); ?></td>
			<td class="al-right pad-right"><?php echo number_format($cakes,2); ?></td>
			<td class="al-right pad-right"><?php echo number_format($specials,2); ?></td>
			<td class="al-right pad-right"><?php echo number_format($beverages,2); ?></td>
			<td class="al-right pad-right"><?php echo number_format($bottledwater,2); ?></td>
			<td class="al-right pad-right"><?php echo number_format($icecream,2); ?></td>
			<td class="al-right pad-right"><?php echo number_format($merchandiseothers,2); ?></td>
			<td class="al-right pad-right"><?php echo number_format($coffee,2); ?></td>
			<td class="al-right pad-right"><?php echo number_format($milktea,2); ?></td>
			<td colspan="3" class="al-right pad-right"><?php echo number_format($breakdowntotal,2); ?></td>
		</tr>

	
	
<?php 
}
else{
	?>
		<tr>
			<td colspan="13" style="text-align:center"><i class="fa fa-bell fa-danger"></i>&nbsp;No results Found</td>
		</tr>
	<?php
}
?>
</table>
<div id="inventoryData"></div>
