<?php 
ini_set('display_errors','0');
error_reporting(E_ALL);
include "db-connect.php";
session_start();
$name= $_SESSION["name"];
$access= $_SESSION["access"];
$id= $_SESSION["id"];
$email= $_SESSION["email"];
$company_name= $_SESSION["company_name"];
$bid= $_SESSION["bid"];
$gid= $_SESSION["gid"];
$employee_id=$_GET['employee_id'];
$employee_name=$_GET['employee_name'];
//echo $gid;exit;
date_default_timezone_set('Asia/Kolkata');
$current_date = date('Y-m-d H:i:s');

 if($name=='' && $access=='' && $id=='' && $email=='' && $company_name=='' && $bid=='' && $gid==''){
 	  echo "<script>alert('You Dont have Access this Page');location.href='https://incometax.indiafilings.com';</script>";
 	header('Location:https://incometax.indiafilings.com');

} 

$sel_query_resp=pg_fetch_array(pg_query($db,"select custom_direct_benefits,custom_deductions from deduction_benefits where bid ='$bid'"));
	$custom_benefits=$sel_query_resp[0];
	$custom_deductions=$sel_query_resp[1];

 //get custom data
$get_custom_query="SELECT  custom_direct_benefits,custom_deductions FROM deduction_benefits where bid='$bid'";
$query_resp_custom_data=pg_fetch_array(pg_query($db,$get_custom_query));
$custom_benefits_added=$query_resp_custom_data[0];
$custom_deductions_added=$query_resp_custom_data[1];
//echo $custom_deductions_added;exit;


$query1="SELECT  plan FROM company_plan_structure where company_name='$company_name' AND bid='$bid'AND status='active'";
 //echo $query1;exit;
 $selectQuery= pg_query($db,$query1);
 while ($plan_array1=pg_fetch_array($selectQuery)) {
 	$plan_array[]=$plan_array1[0];
 	# code...
 }
$complete_company_plan= json_encode($plan_array);
//echo $complete_company_plan;exit;
//plan updated

if(isset($_POST["component_name_checking"])){
$component_name_checking=trim($_POST["component_name_checking"]);
$current_plan=$_POST["current_plan"];
$type_of_benefits=$_POST["type_of_benefits"];
$current_emp_id=$_POST["current_emp_id"];

//select the data from custom perks and table
$plan_custom_perks_fetch="SELECT custom_direct_benefits->'custom_direct_benefits',custom_deductions->'custom_deductions' from custom_perks_deductions where employee_id='$current_emp_id' AND bid='$bid'";//echo $plan_custom_perks_fetch;echo;
$query_resp_custom=pg_fetch_array(pg_query($db,$plan_custom_perks_fetch));
$direct_Benifits_in_custom=json_decode($query_resp_custom[0],true);
$deduction_Benifits_in_custom=json_decode($query_resp_custom[1],true);

foreach ($direct_Benifits_in_custom as $key1 => $value1) {
	$DirectBenefitsArray[]=trim($direct_Benifits_in_custom[$key1]["ComponentName"]);//echo $DirectBenefitsArray;exit;
}
if(sizeof($deduction_Benifits_in_custom) > 0){
foreach ($deduction_Benifits_in_custom as $key2 => $value2) {
	$DeductionArray[]=$deduction_Benifits_in_custom[$key2]["DeductionCategory"];
}}
//print_r($DirectBenefitsArray);

$plan_creation_table_fetch="SELECT selected_breakup->'Breakup',user_deductions->'Deduction' from company_plan_structure where plan='$current_plan' AND bid='$bid'";
$query_resp=pg_fetch_array(pg_query($db,$plan_creation_table_fetch));
$direct_Benifits_in_plan=json_decode($query_resp[0],true);
$deduction_Benifits_in_plan=json_decode($query_resp[1],true);
//print_r($deduction_Benifits_in_plan);
foreach ($direct_Benifits_in_plan as $key3 => $value3) {
	foreach ($value3 as $key_component => $value) {
		 $DirectBenefitsArray[]=trim($key_component);
	}
	
}
if(sizeof($deduction_Benifits_in_plan) > 0){
foreach ($deduction_Benifits_in_plan as $key4 => $value4) {
	$DeductionArray[]=$deduction_Benifits_in_plan[$key4]["DeductionCategory"];
}
}
$data_present=in_array($component_name_checking,${"$type_of_benefits"});
$data=($data_present=="1") ? "match":"no-match";
die($data);
}

if(isset($_POST["ShowPlan"])){

 //$delete_plan=$_POST["ShowPlan"];
 $given_plan=$_POST["ShowPlan"];
$query1="SELECT selected_breakup->'Breakup',user_deductions->'Deduction',retirement_benefits,medical_insurance,food_subsidy,loan_subsidy,loan_interest,tds_applicability FROM company_plan_structure where company_name='$company_name' AND bid='$bid' AND plan='$given_plan' ";
$selectQuery= pg_query($db,$query1);

$resp_data=pg_fetch_array($selectQuery);
$die_data=$resp_data[0]."~".$resp_data[1]."~".$resp_data[2]."~".$resp_data[3]."~".$resp_data[4]."~".$resp_data[5]."~".$resp_data[6]."~".$resp_data[7];

die($die_data);

 }

//delete record

 if(isset($_POST["delete_emp_id"])){ 
 	die("end");
$delete_emp_id=$_POST["delete_emp_id"]; 
$delete_emp_plan=$_POST["delete_emp_plan"];
$delete_emp_component=$_POST["delete_emp_component"];
$delete_emp_index=$_POST["delete_emp_index"];
$delete_coulumn=$_POST["delete_coulumn"];
$get_ctc_delete_column_temp=explode("_", $delete_coulumn);
$get_ctc_delete_column=$get_ctc_delete_column_temp[1];
//get size of array
$select_query="SELECT $delete_coulumn->'$delete_coulumn' from custom_perks_deductions WHERE bid = '$bid' AND employee_id='$delete_emp_id'";
$query_resp_perks_table=pg_fetch_array(pg_query($db,$select_query));
$array_count=sizeof(json_decode($query_resp_perks_table[0],true));
//echo $array_count;exit;
//custom perks table delete
if($array_count > 1){
$delete_query_custom_table = <<<EOF
	 UPDATE custom_perks_deductions
	SET {$delete_coulumn} = {$delete_coulumn} #- '{{$delete_coulumn},$delete_emp_index}' WHERE bid='$bid'  and employee_id='$delete_emp_id' ; 
EOF;
//	echo $delete_query_custom_table;exit;
$error_reports=pg_query($db,$delete_query_custom_table);
}
if($array_count <= 1){
	$update_table_query="UPDATE custom_perks_deductions SET $delete_coulumn='{}' WHERE bid ='$bid' AND employee_id='$delete_emp_id'";
//echo $update_table_query;exit;
pg_query($db,$update_table_query);
}


//select query from gross table
$select_query_query_table = <<<EOF
	 SELECT salary_breakup,direct_benefits_payable_period,user_deductions from employee_basic_ctc_details WHERE bid = '$bid' AND employee_id='$delete_emp_id' ; 
EOF;
//echo $select_query_query_table;exit;
$query_resp=pg_fetch_array(pg_query($db,$select_query_query_table));
//print_r($query_resp);exit;
$decoded_selected_benefits_data=json_decode($query_resp[0],true);
$existing_ctc_grand_total=$decoded_selected_benefits_data["CTC_grand_total"];
$subtract_value=$decoded_selected_benefits_data[$delete_emp_component];
$decoded_selected_benefits_data["CTC_grand_total"]=$existing_ctc_grand_total-$subtract_value;
 unset($decoded_selected_benefits_data[$delete_emp_component]);
$insert_data_in_gross_table=json_encode($decoded_selected_benefits_data);
//echo $decoded_selected_benefits_data;exit;
print_r($decoded_selected_benefits_data);exit;
$update_gross_query="UPDATE employee_basic_ctc_details SET salary_breakup='$insert_data_in_gross_table' WHERE bid = '$bid'  AND employee_id='$delete_emp_id'";//echo $update_gross_query;exit;
//echo $update_gross_query;exit;
pg_query($db,$update_gross_query);
if($get_ctc_delete_column=="direct"){
//benefits payable
$decoded_selected_benefits_data_payable=json_decode($query_resp[1],true);
unset($decoded_selected_benefits_data_payable[$delete_emp_component]);
$insert_data_in_gross_table=json_encode($decoded_selected_benefits_data_payable);
$update_ctc_query_payable="UPDATE employee_basic_ctc_details SET direct_benefits_payable_period='$insert_data_in_gross_table' WHERE bid = '$bid'  AND employee_id='$delete_emp_id'";
//echo $insert_data_in_gross_table;exit;
pg_query($db,$update_ctc_query_payable);

} 
//echo $get_ctc_delete_column;exit;
if($get_ctc_delete_column=="deductions"){
//deductions data
$get_sizeof_deduction_benefits=sizeof($decoded_deductions_benefits_data_payable);
	$return_deduction_query_inctc=($get_sizeof_deduction_benefits <=0 ) ? "UPDATE employee_basic_ctc_details SET user_deductions='{}' WHERE bid = '$bid'  AND employee_id='$delete_emp_id'" :  " UPDATE custom_perks_deductions
	SET user_deductions= user_deductions #- '{Deduction,$delete_emp_index}' WHERE bid='$bid'  and delete_emp_id='$delete_emp_id'" ;
 
pg_query($db,$return_deduction_query_inctc);

}
die("end");

}


if(isset($_POST["get_emp_id"])){
$get_emp_id=$_POST["get_emp_id"];
$get_emp_index=$_POST["get_emp_index"];
$get_coulumn=$_POST["get_coulumn"];

$select_query_in_custom_table="SELECT $get_coulumn->'$get_coulumn'->$get_emp_index from custom_perks_deductions where bid='$bid' AND employee_id='$get_emp_id'";
$query_resp_data=pg_fetch_array(pg_query($db,$select_query_in_custom_table));
//echo $select_query_in_custom_table;exit;
die($query_resp_data[0]);

}







//benefits




if(isset($_POST['name'])){
	$insert_data=$_POST['name'];
	$sel_query=pg_query($db,"select custom_direct_benefits,custom_deductions,onetime_benefits,onetime_deduction from deduction_benefits where bid ='$bid'");
	$row=pg_fetch_assoc($sel_query);
	$custom_direct_benefits=$row['custom_direct_benefits'];
	$custom_deductions=$row['custom_deductions'];
	$onetime_benefits=$row['onetime_benefits'];
	$onetime_deduction=$row['onetime_deduction'];
	 $data1=json_decode($custom_direct_benefits,true);
	$data2=json_decode($custom_deductions,true);
	$data3=json_decode($onetime_benefits,true);
	$data4=json_decode($onetime_deduction,true);
	
	
	foreach($data1 as $dat1){$name1[]=$dat1['name'];}
	foreach($data2 as $dat2){$name1[]=$dat2['name'];}
	foreach($data3 as $dat3){$name1[]=$dat3['name'];}
	foreach($data4 as $dat4){$name1[]=$dat4['name'];}
	
	
	$datas=in_array($insert_data,$name1);if($datas==''){$datas=0;}
	
	
	 // print_r ($datas);exit;
	// $sat=array_combine($data1,$data2);
	die($datas);
}




//die($resp_data[0]);  1->2= 0->1
if(isset($_POST['Customized'])){
	$array=$_POST['Customized'];  
	$array=array_values($array);
 $length=sizeof($array);
  //print_r($array);exit;
  
  
  
 
 
 for($x=0; $x<$length; $x++){
	  $select=$array[$x]['select'];  
	  $insert_dat=$array[$x]['name'];  
	  
	 /*$name=$array[$x]['name'];
	   // $data1=$_POST; */ 
	$sel_query=pg_query($db,"select custom_direct_benefits,custom_deductions,onetime_benefits,onetime_deduction from deduction_benefits where bid ='$bid'");
	$row=pg_fetch_assoc($sel_query);
	$count=pg_num_rows($sel_query);
	$custom_direct_benefits=$row['custom_direct_benefits'];
	$custom_deductions=$row['custom_deductions'];
	$onetime_benefits=$row['onetime_benefits'];
	$onetime_deduction=$row['onetime_deduction'];
	 $data1=json_decode($custom_direct_benefits,true);
	$data2=json_decode($custom_deductions,true);
	$data3=json_decode($onetime_benefits,true);
	$data4=json_decode($onetime_deduction,true);
	 
	
	foreach($data1 as $dat1){$name1[]=$dat1['name'];}
	foreach($data2 as $dat2){$name1[]=$dat2['name'];}
	foreach($data3 as $dat3){$name1[]=$dat3['name'];}
	foreach($data4 as $dat4){$name1[]=$dat4['name'];}
	
	
	$datas=in_array($insert_dat,$name1);if($datas==''){$datas=0;}//echo $datas; exit;  
	// echo in_array($insert_dat,$name1);exit;
	if($insert_dat){
	if($datas==0){
	 
	if($select=="Benefits"){
		unset($array[$x]["select"]);
		$data=json_encode($array[$x]);
		
			if($count==0){ 
				$insert = pg_query($db,"insert into deduction_benefits(bid,custom_direct_benefits,custom_deductions,created_by,created_date,status,onetime_benefits,onetime_deduction)values('$bid','[$data]','[]','$email','$current_date','0','[]','[]')");
					if($insert){$data="insert";}else{$data="not insert";}
			}else{
				if($custom_direct_benefits=='[]'){
					$update=pg_query($db,"UPDATE public.deduction_benefits SET custom_direct_benefits='[$data]' where bid='$bid'");
						if($update){$data="insert";}else{$data="not insert";}
				}else{
					$insert_data= str_replace(']',",$data]","$custom_direct_benefits");
						$update=pg_query($db,"UPDATE public.deduction_benefits SET custom_direct_benefits='$insert_data' where bid='$bid'");
							if($update){$data="insert";}else{$data="not insert";}
				}
			}
	}
	
		/* One Time Benefits */
		
		
	if($select=="OneTimeBenefits"){
		unset($array[$x]["select"]);
		$data=json_encode($array[$x]);
		
			if($count==0){
				$insert = pg_query($db,"insert into deduction_benefits(bid,custom_direct_benefits,custom_deductions,created_by,created_date,status,onetime_benefits,onetime_deduction)values('$bid','[]','[]','$email','$current_date','0','[$data]','[]')");
					if($insert){$data="insert";}else{$data="not insert";}
			}else{
				if($onetime_benefits=='[]'){
					$update=pg_query($db,"UPDATE public.deduction_benefits SET onetime_benefits='[$data]' where bid='$bid'");
						if($update){$data="insert";}else{$data="not insert";}
				}else{
					$insert_data= str_replace(']',",$data]","$onetime_benefits");
						$update=pg_query($db,"UPDATE public.deduction_benefits SET onetime_benefits='$insert_data' where bid='$bid'");
							if($update){$data="insert";}else{$data="not insert";}
				}
			}
	}
	
	
	 /* For Deduction */
	 
	 if($select=="Deduction"){
		unset($array[$x]["select"]);
		$data=json_encode($array[$x]);
		
			if($count==0){
				$insert = pg_query($db,"insert into deduction_benefits(bid,custom_direct_benefits,custom_deductions,created_by,created_date,status,onetime_benefits,onetime_deduction)values('$bid','[]','[$data]','$email','$current_date','0','[]','[]')");
					if($insert){$data="insert";}else{$data="not insert";}
			}else{
				if($custom_deductions=='[]'){
					$update=pg_query($db,"UPDATE public.deduction_benefits SET custom_deductions='[$data]' where bid='$bid'");
						if($update){$data="insert";}else{$data="not insert";}
				}else{
					$insert_data= str_replace(']',",$data]","$custom_deductions");
						$update=pg_query($db,"UPDATE public.deduction_benefits SET custom_deductions='$insert_data' where bid='$bid'");
							if($update){$data="insert";}else{$data="not insert";}
				}
			}
	}
	
	
		/* One Time Deduction */
		
		
	if($select=="OneTimeDeduction"){
		unset($array[$x]["select"]);
		$data=json_encode($array[$x]);
		
			if($count==0){
				$insert = pg_query($db,"insert into deduction_benefits(bid,custom_direct_benefits,custom_deductions,created_by,created_date,status,onetime_benefits,onetime_deduction)values('$bid','[]','[]','$email','$current_date','0','[]','[$data]')");
					if($insert){$data="insert";}else{$data="not insert";}
			}else{
				if($onetime_deduction=='[]'){
					$update=pg_query($db,"UPDATE public.deduction_benefits SET onetime_deduction='[$data]' where bid='$bid'");
						if($update){$data="insert";}else{$data="not insert";}
				}else{
					$insert_data= str_replace(']',",$data]","$onetime_deduction");
						$update=pg_query($db,"UPDATE public.deduction_benefits SET onetime_deduction='$insert_data' where bid='$bid'");
							if($update){$data="insert";}else{$data="not insert";}
				}
			}
	}
	
	
	}else{$data="not insert.";}}else{$data="not insert.";}
	
 }	
die($data);

}



?>
<!DOCTYPE html>
<html lang="en" >
	<head>
		<meta charset="utf-8" />
		<title>
			Individual Salary 
		</title>
		<meta name="description" content="Latest updates and statistic charts">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<!--begin::Web font -->
		<script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.16/webfont.js"></script>
		<script>
			WebFont.load({
				google: {"families":["Poppins:300,400,500,600,700","Roboto:300,400,500,600,700"]},
				active: function() {
						sessionStorage.fonts = true;
				}
			});
		</script>	
		<style type="text/css">
			.form-control[readonly] {
      background-color: #f4f5f8;
	      font-size: 115%;
    }
    .thead1{
					    background: #716aca;
				}
				.table th, .table td
{
border-top:none !important;
}
.table-bordered th, .table-bordered td
{
border:none !important;
}
.selection{
	display: none;
}
.blink_me {
                animation: blinker 0.9s linear infinite;
            }
             @keyframes blinker {  
                50% { opacity: 0; }
                60% { opacity: 1; }
            }
.m-link {
    
    font-size: 11px !important;
}
		</style>
<link rel="shortcut icon" href="favicon.ico" />		
	</head>
	<?php include "HR-left-header-1.php"; ?>	
		<div class="m-grid__item m-grid__item--fluid m-grid m-grid--ver-desktop m-grid--desktop m-body">
	
			<button class="m-aside-left-close  m-aside-left-close--skin-dark " id="m_aside_left_close_btn">
				<i class="la la-close"></i>
			</button>
			<?php include "left-menu.php"; ?>

			<!-- END: Left Aside -->
			<div class="m-grid__item m-grid__item--fluid m-wrapper">
			<div class="m-content">
<div class="modal fade show" id="benefits_deduction" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" data-backdrop="static">
							<div class="modal-dialog modal-lg" role="document">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title " style="color:#34bfa3;"id="exampleModalLabel">Add The Benefits And Deduction For Your Organisation</h5>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">×</span>
										</button>
									</div>
									<div class="modal-body">
										<form id="employee_details">
											
											<table class="table table-bordered m-table m-table--border-brand m-table--head-bg-brand bus_pro" id="table_bp" style="width=0%;margin-left:0%;margin-right:0%">
		<thead>													
			<tr>
				<th>Type Of The Component</th>
				<th>Component Name</th>
				<th colspan=2>Action</th>
			</tr>
		</thead>	
		<tbody id="appentdata">
			 
			<tr>
			<td>						 
		<select class="form-control m-input " name="Customized[0][select]" >
					<option value="">Select</option>
					<option value="Benefits">Benefits</option>
					<option value="Deduction">Deduction</option>	
					<option value="OneTimeBenefits">OneTime Benefits</option>
					<option value="OneTimeDeduction">OneTime Deduction</option>	
				</select>
	</td> 
				<td><input type="text" class="form-control m-input component_name check_componenets_name"  name="Customized[0][name]" onkeyup="this.value=this.value.toUpperCase();"></td>
				<td>
				<button type="button" class="btn m-btn--pill m-btn--air  btn-sm  btn-success add1"  style='margin-left: 4%;'>
		Add Record
	</button> 
	</td>
										
		</tr>
	 
		</tbody>
</table>	
<br>

<table class="table table-bordered m-table m-table--border-brand m-table--head-bg-brand" >
<thead>													
			<tr>  
				<th>Benefit Name</th>
				<th>Deduction Name</th>
				<th>Onetime Benefits </th>
				<th>Onetime Deduction</th>
				 
			</tr>
			<?php 
			$sel=pg_query($db,"select custom_direct_benefits,custom_deductions ,onetime_benefits,onetime_deduction from deduction_benefits where bid='$bid' ");
	 $row=pg_fetch_assoc($sel);
	 //echo "select custom_direct_benefits,custom_deductions ,onetime_benefits,onetime_deduction from deduction_benefits where bid='$bid' "; exit; 
	 
	 $custom_direct_benefits_1=json_decode($row['custom_direct_benefits'],true);
	// print_r ($custom_direct_benefits_1) ; exit;
	 $custom_deductions_1=json_decode($row['custom_deductions'],true);
	 $onetime_benefits_1=json_decode($row['onetime_benefits'],true);
	 $onetime_deduction_1=json_decode($row['onetime_deduction'],true);
	 
			?>
			
			<?php
			 foreach($custom_direct_benefits_1 as $key1 => $value){
				// print_r ($custom_direct_benefits_1); //exit;
				 ?>
		
				
			<tr>  
				<td><?php echo $custom_direct_benefits_1[$key1]['name'];?></td> 
				<td><?php echo $custom_deductions_1[$key1]['name'];?></td> 
				<td><?php echo $onetime_benefits_1[$key1]['name'];?></td> 
				<td><?php echo $onetime_deduction_1[$key1]['name'];?></td> 
				
				
				
				
	 </tr><?php }?>
			
		</thead>	
</table>				






<script>
/*var custom_deductions='<?php echo $custom_deductions?>';
 var custom_benefits='<?php echo $custom_benefits?>';
 var parsed_benefits=JSON.parse(custom_benefits);
 var parsed_deductions=JSON.parse(custom_deductions);
 for(var i=0;i<parsed_benefits.length;i=i+1)
 {
	 var temp= parsed_benefits[i].name;
	 
	$("#custom1").append('<tr><td><select class="form-control m-input " name="Customized['+i+'][select]"><option value="Benefits">Benefits</option></select></td><td><input type="text" class="form-control m-input"   name="" value="'+temp+'" readonly > </td></tr>');
	
	//$("#custom1").append('<tr><td><select class="form-control m-input " name="Customized['+index+'][select]"><option value="">Select</option><option value="Benefits">Benefits</option><option value="Deduction">Deduction</option></select></td><td><input type="text" class="form-control m-input dash_value component_name'+index+' check_componenets_name" name="Customized['+index+'][name]"<span>'temp'</span>onkeyup="this.value=this.value.toUpperCase();" ></td></tr>');
 
         
	 //alert(parsed_benefits[i].name);
 } 
 for(var i=0;i<parsed_deductions.length;i=i+1)
 {
	 var temp= parsed_deductions[i].name;
	 
	$("#custom1").append('<tr><td><select class="form-control m-input " name="Customized['+i+'][select]"><option value"Deduction">Deduction</option></select></td><td><input type="text" class="form-control m-input"   name="" value="'+temp+'" readonly > </td></tr>');
	
	//$("#custom1").append('<tr><td><select class="form-control m-input " name="Customized['+index+'][select]"><option value="">Select</option><option value="Benefits">Benefits</option><option value="Deduction">Deduction</option></select></td><td><input type="text" class="form-control m-input dash_value component_name'+index+' check_componenets_name" name="Customized['+index+'][name]"<span>'temp'</span>onkeyup="this.value=this.value.toUpperCase();" ></td></tr>');
 
         
	 //alert(parsed_benefits[i].name);
 }*/
</script>
<!--  -->	
								
									</div>
									
									<div class="modal-footer">
										<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
										<button type="button"  class="btn btn-primary modal_submit">save</button>
									</div></form>
								</div>
							</div>
						</div>
<div class="modal fade show" id="rename_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
							<div class="modal-dialog modal-lg" role="document">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title" id="exampleModalLabel">customize benefits creation</h5>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">×</span>
										</button>
									</div>
									<div class="modal-body">
										<form id="customized_plan">
										<input type="hidden" name="direct_benifits_identification" id="direct_benifits_identification">
											<div class="form-group"><span class="m-menu__link-badge blink_me" style="float: right; display: none">
                                        <span class="m-badge m-badge--brand m-badge--wide" style="margin:0 !important">
                                            New value replace the existing value
                                        </span>
                                    </span>
												<label for="recipient-name" class="form-control-label">Component Name:</label>
												<select class="form-control m-input m-input--square ComponentName" >
															<option value="">Select category</option>
														</select>
												<!-- <input type="text" class="form-control ComponentName"  name="ComponentName"  onkeyup='this.value = this.value.toUpperCase();'> -->
											</div>
											<div class="form-group"><label for="recipient-name" class="form-control-label payable_category_label">Payable</label>
												<select class="form-control m-input m-input--square payable_category" >
															<option value="">Select category</option>
															<option value="Monthly" selected="">Monthly</option>
															<option value="Quarterly">Quarterly</option>
															<option value="HalfYearly">Half Yearly</option>
															<option value="Annually">Annually</option>
														</select>

											</div>
<div class="form-group select_payable_row" style="display: none">
	<label for="recipient-name" class="form-control-label">Select payable Month:</label>
<select class="form-control m-input given_month_payable" id="exampleSelect1" >
<option value="">Select</option>
<option value="APR">APRIL</option>
<option value="MAY">MAY</option>
<option value="JUN">JUNE</option>
<option value="JUL">JULY</option>
<option value="AUG">AUGUST</option>
<option value="SEP">SEPTEMBER</option>
<option value="OCT">OCTOBER</option>
<option value="NOV">NOVEMBER</option>
<option value="DEC">DECEMBER</option>
<option value="JAN">JANUARY</option>
<option value="FEB">FEBRUARY</option>
<option value="MAR">MARCH</option>
</select>	
</div>
 <div class="form-group" >
	
<label for="recipient-name" class="form-control-label payable_category_month_temp_row" style="display: none">Payable Month:</label>
<input type="text" class="form-control payable_category_month_temp" id="recipient-name" style="display: none">
<select class="form-control m-select2 payable_category_month" name="PayableMonth[]" id="m_select2_3"  multiple="multiple" style="display: none">
											
												<optgroup="Payable Months">
												<option value="NA">Select</option>
													<option value="APR">APRIL</option>
<option value="MAY">MAY</option>
<option value="JUN" >JUNE</option>
<option value="JUL" >JULY</option>
<option value="AUG">AUGUST</option>
<option value="SEP">SEPTEMBER</option>
<option value="OCT">OCTOBER</option>
<option value="NOV">NOVEMBER</option>
<option value="DEC">DECEMBER</option>
<option value="JAN">JANUARY</option>
<option value="FEB">FEBRUARY</option>
<option value="MAR">MARCH</option>
												</optgroup>
												
											</select> 
</div> 
											<div class="form-group">Select option
												<select class='form-control m-input selected_type' id='exampleSelect1' >
<option value=''>Select</option>
<option value='FixedAmount'>FixedAmount</option>
<option value='PercofBasicNoLimit'>% of Basic-No Limit</option>
<option value='PercofBasicCapped'>% of Basic-Capped</option>
</select>
											</div>
											<div class="form-group basic_or_perc_value" style="display: none">
												<label for="recipient-name" class="form-control-label Basic_or_fixed"></label>
												<input type="text" class="form-control fixed_percentage" id="recipient-name" >
											</div>
											<div class="form-group basic_of_cap_value" style="display: none">
												<label for="recipient-name" class="form-control-label">Capped Amount</label>
												<input type="text" class="form-control CappedAmount" id="recipient-name" >
											</div>
											<div class="form-group basic_of_excess_movedto" style="display: none">
												<label for="recipient-name" class="form-control-label">Excess Moved to:</label>
												<select class="form-control m-input ExcessMovedto">
<option value=''>Select</option>
<option value='HouseRentAllowance'>HouseRent Allowance</option>
<option value='DearnessAllowance'>Dearness Allowance</option>
<option value='ConveyanceAllowance'>Conveyance Allowance</option>
<option value='FoodAllowance'>Food Allowance</option>
<option value='MedicalAllowance'>Medical Allowance</option>
<option value='SpecialAllowance'>Special Allowance</option>

</select>
											</div>
											<input type="hidden" name="update_index" class="update_index">
										
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
										<button type="button" class="btn btn-info saveupdates">Update Details</button>
									</div>
								</div>
							</div>
						</div>


	
	
<div class="row">

   	<div class="col-lg-12">		<div class="m-portlet m-portlet--tab" id="">
		<div class="m-portlet__head">


<div class="m-portlet__head-tools">
<label class="col-form-label"><h4 class="m-portlet__head-text ">

Customize Salary 
</h4></label> &nbsp;
</div>


</div>
		<div class="m-portlet__body">
			<div class="row m--margin-bottom-20">
										<div class="col-lg-4 m--margin-bottom-10-tablet-and-mobile">
											<label>Employee-ID:</label>
											<input type="text" class="form-control m-input EmployeeId" placeholder="E.g: 4590" data-col-index="0"  name="EmployeeId" required="">
										</div>
										<div class="col-lg-4 m--margin-bottom-10-tablet-and-mobile">
											<label>Employee-Name:</label>
											<input type="text" class="form-control m-input EmployeeName" placeholder="E.g: XXXYYY" data-col-index="1" name="EmployeeName" value="" readonly="" required="">
										</div>
										<input type="hidden" class="Basic"  name="Basic">
<input type="hidden" class="BenefitsToBe"  required="" name="BenefitsToBe">
								
										<div class="col-lg-4 m--margin-bottom-10-tablet-and-mobile">
											<label>Plan:</label>
											<input type="text" class="form-control m-input Plan" placeholder="ABCD" data-col-index="4" name="Plan" readonly="" required="">
										</div>
									</div>
									</form>
		<div class="row m--margin-bottom-20">
										<div class="col-lg-4 m--margin-bottom-10-tablet-and-mobile">
											<label>Benefits/Deduction</label>
											        		<select class='form-control m-input given_benefits' id='exampleSelect1' name='Month[]'>
<option value=''>Select</option>
<option value='custom_direct_benefits'>Direct Benefits</option>
<!-- <option value='custom_indirect_benefits'>In-Direct Benefits</option> -->
<option value='custom_deductions'>Deductions</option>
</select>




<a href="#" class="m-link benefits_deduct" data-toggle="modal" data-target="#benefits_deduction">Click to set Benefits/Deductions for your company</a>
<!-- <span class="m-form__help">We'll never share your email with anyone else.</span> -->
<!-- <label>Click to set Benefits/Deductions for your company</label> -->
										</div>
										<div class="col-lg-6 m--margin-bottom-10-tablet-and-mobile">
											<br>
											<div class="col-xl-4 order-1 order-xl-2 m--align-right">
											<a href="#" class="btn btn-accent m-btn m-btn--custom m-btn--icon m-btn--pill m-btn--air show_modal">
														<span>
															<i class="la la-plus"></i>
															<span>Add Details</span>
														</span>
													</a>
											<div class="m-separator m-separator--dashed d-xl-none"></div>
										</div>
										</div>
			
									</div>
<!-- 

			   <div class="row">        	<div class="row align-items-center">
										<div class="col-xl-8 order-2 order-xl-1">
											<div class="form-group m-form__group row align-items-center">
												<div class="col-lg-12">
													 <div class="m-input-icon m-input-icon--left"> 
														Select Category:
        		<select class='form-control m-input given_benefits' id='exampleSelect1' name='Month'>
<option value=''>Select</option>
<option value='custom_direct_benefits'>Direct Benefits</option>
<option value='custom_indirect_benefits'>In-Direct Benefits</option>
<option value='custom_deductions'>Deductions</option>
</select>
											 </div>
												</div>
											</div>
										</div>
										<div class="col-xl-4 order-1 order-xl-2 m--align-right">
											<a href="#" class="btn btn-accent m-btn m-btn--custom m-btn--icon m-btn--pill m-btn--air show_modal">
														<span>
															<i class="la la-plus"></i>
															<span>Add Event</span>
														</span>
													</a>
											<div class="m-separator m-separator--dashed d-xl-none"></div>
										</div>
									</div> 


								</div> -->
								<div class="row">
										<div class="col-lg-6 " >
<table class="table table-bordered m-table m-table--border-brand table1" style="display: none">
<thead class="thead1"><tr><th></th><th colspan="4" style="color:white;text-align: center;"><center>CUSTOM DIRECT BENEFITS</center></th><th></th><th></th></tr></thead>
<thead class="direct_benefits_thead">
<tr><th>
Salary Components
</th>
<th>
Type
</th>
<th>
Value
</th>
<th>
Cap Value
</th>
<th>
Excess Moved to
</th>
<th>
Pay Period
</th>
<th>
Actions
</th>
</tr></thead>
<tbody class="direct_benefits_row" id="direct_benefits_row"></tbody></table>

											 </div>
	<div class="col-lg-6 ">
		<table class="table table-bordered m-table m-table--border-brand table2 myTable2" style="display: none">
<thead class="thead1 "><th align="center" colspan="5" style="color:white"><center>CUSTOM DEDUCTIONS</center></th></thead>
<thead class="deduction_benifits_thead">
<th>
Deduction Component
</th>
<th>
Type
</th>
<th>
Value
</th>
<th>
Cap Amount
</th>
<th>
Actions
</th>
</thead>
<tbody class="deduction_row" id="deduction_append_data"></tbody></table>
	</div>
								</div>


</div></div>

        
        	</div>
												
		</div>
 

		</div> 
  
		</div>
		
		
	

</div>
</div>
	</div></div>	</div><?php //include "footer.php"; ?>
<script src="assets/demo/default/custom/crud/forms/widgets/bootstrap-datepicker.js" type="text/javascript"></script> 
<script src="assets/demo/default/custom/crud/forms/widgets/select2.js" type="text/javascript"></script>   
<script src="assets/demo/default/custom/crud/forms/widgets/bootstrap-datepicker.js" type="text/javascript"></script>    
<script src="assets/demo/default/custom/components/base/sweetalert2.js" type="text/javascript"></script>
<script>

//benefits deduction
	var index=1; 
      $(".add1").on("click ", function() {

	 
               $("#table_bp").append('<tr><td><select class="form-control m-input " name="Customized['+index+'][select]"><option value="">Select</option><option value="Benefits">Benefits</option><option value="Deduction">Deduction</option><option value="OneTimeBenefits">OneTime Benefits </option><option value="OneTimeDeduction">OneTime Deduction</option></select></td><td><input type="text" class="form-control m-input dash_value component_name'+index+' check_componenets_name" name="Customized['+index+'][name]"onkeyup="this.value=this.value.toUpperCase();" ></td></tr>');
 
         

 index++;
	 
		
      });
	  
      // deleting the row  
      /* $("#table_bp").on('click', '.Delete1', function() {
        $(this).closest('tr').remove();
      });
  */
var benefits = [];

$(document).on("click",'.select_sector',function(){
   var count=($(this).length);
    var delete_table1_index=document.getElementsByClassName("select_sector");
    //console.log(delete_table1_index);
 for(var table1_index=0;table1_index<delete_table1_index.length;table1_index++){ 
 	var current_selected=(delete_table1_index[table1_index].value);
 	var name=current_selected+"["+table1_index+"]";
 	console.log(name);
 	if(current_selected==""){
 		benefits.push("1");

 	}
 	delete_table1_index[table1_index].name=""+name+""; 
 	//$(".category").prop("name",""+name+"");

 }
   

});


$(".component_name").keyup(function(){
	var str=$(".component_name").val();
	  var res = str.replace(" ", "-");
	$(".component_name").val(res);
});

$(".component_name").change(function(){
	var str=$(".component_name").val();
	  var res = str.replace(" ", "-");
	$(".component_name").val(res);
	
	
	$.ajax({
		type: "POST",
		data: {name:res},
		success: function(datas){
			if(datas==1){toastr.error("Component Name already Exists.");}else{toastr.success("New Component Name.");}
		}
	})
	
	
	
});



 $(document).on("click",'.modal_submit',function(e){ 
 // $(".modal_submit").hide();
		var data1=$("#employee_details").serialize();
	 // alert(data1);
	$.ajax({
  type: "POST",
  data: data1,
  success: function(data) {
    if(data=="insert")
  {
	  // $("#benefits_deduction").modal("hide");
	 
 		  
	  //alert(1);
  toastr.success("Information insert");  
  }
  else
  {
	  toastr.error("Information not Update"); 
	   // $("#benefits_deduction").modal("hide");
  }
    
  }
  })
  
  }); 
</script>
    <script>
function ComputeMonths(given_month,period){
	//console.log("period"+period);
var ref=["JAN","FEB","MAR","APR","MAY","JUN","JUL","AUG","SEP","OCT","NOV","DEC"];
var a=parseInt(ref.indexOf(given_month));
var staticindex=0;
var new_mnth=[given_month];
var def_period=(period==3) ? 4 :2;
//console.log(def_period);
for(var i=0;i< (def_period)-1;i++){
 var data_index=a+period+staticindex;
 //console.log(data_index);
 if(data_index > 11){
 var index=data_index-12;
 new_mnth.push(ref[index]);
 }
 if(data_index <= 11){
 new_mnth.push(ref[data_index]);
 }
 

 staticindex += period;
}
return new_mnth;
}
$(function(){
toastr.options = {
"preventDuplicates": true,
"preventOpenDuplicates": true
};
   $(".ComponentName").keypress(function(e) {
       if(e.which==32) {
         toastr.error("Spaces are dont't allowed...")
          return false;
        }
      });
      $(".fixed_percentage").keypress(function(e) {
       if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
         toastr.error("Numbers Only");
          return false;
        }
      });
      var a=["NA"];
 	//console.log(a);
 $('.payable_category_month,.payable_category_month_temp').val(a);
});

$(document).on("click change",".given_month_payable,.payable_category",function(){
var given_month_payable=$(".given_month_payable").val();
    
   var payable_category=$(".payable_category").val();
  /* if(payable_category || given_month_payable){*/
   	
 if(payable_category=="Quarterly"){
 	$(".select_payable_row").show();
 	var computed_month=ComputeMonths(given_month_payable,3);
$('.payable_category_month,.payable_category_month_temp').val(computed_month);
 }
 if(payable_category=="HalfYearly"){
 	$(".select_payable_row").show();
 	var computed_month=ComputeMonths(given_month_payable,6);
$('.payable_category_month,.payable_category_month_temp').val(computed_month);
 }
  if(payable_category =="Annually"){
  	$(".select_payable_row").show();
 $('.payable_category_month,.payable_category_month_temp').val(given_month_payable);
 }  
 if(payable_category =="Monthly"){
 	//$(".select_payable_row").hide();
 	var a=["NA"];
 	//console.log(a);
 $('.payable_category_month,.payable_category_month_temp').val(a);
 }    
  
/*}*/
});
$(document).on("change",".given_benefits",function(){
var choosed_benefits=$(this).val();
$(".payable_category_month ").removeAttr("name");
//console.log(choosed_benefits);
if(choosed_benefits=="custom_deductions"){
$("#direct_benifits_identification").val('');	
  var custom_deductions_added='<?php echo $custom_deductions_added;?>';
  var parsed_custom_deductions_data=JSON.parse(custom_deductions_added);
  $(".ComponentName").empty();
  $(".ComponentName").append('<option value="">select category</option>');
 for (var custom_deduction_index_fetch =0;custom_deduction_index_fetch< parsed_custom_deductions_data.length; custom_deduction_index_fetch++) {
 	var option_value=parsed_custom_deductions_data[custom_deduction_index_fetch]["name"];
 	$(".ComponentName").append('<option value='+option_value+'>'+option_value+'</option>');
 }
	$(".payable_category").empty();
	$(".payable_category_month ").removeAttr("name");
	$(".basic_of_excess_movedto").hide();
	$(".payable_category").prop("required",true);
	$(".ComponentName").prop("name","DeductionCategory");
	$(".payable_category").prop("name","Payable");
$(".payable_category_label").html("Re-Payable to employee at the time of");
$(".payable_category").append("<option value='NonRepayable'>Non-Repayable</option>");
$(".payable_category").append("<option value='Retirement'>Retirement</option>");
$(".payable_category").append("<option value='TrainingPeriodEnd'>Training PeriodEnd</option>");
$(".payable_category").append("<option value='EmployeeRelieving'>Employee Relieving</option>");
$(".payable_category").append("<option value='EmployeeDemise'>Employee's Demise</option>");
//document.getelementsbyclassname('payable_category_label').innerHTML = 'Hi, ghgh';
}
if(choosed_benefits=="custom_direct_benefits"){
$("#direct_benifits_identification").val('1');	
	var custom_benefits_added='<?php echo $custom_benefits_added;?>';
	 var parsed_benefits_data=JSON.parse(custom_benefits_added);
	 $(".ComponentName").empty();
	 $(".ComponentName").append('<option value="">select category</option>');
 for (var custom_benefits_index_fetch =0;custom_benefits_index_fetch< parsed_benefits_data.length; custom_benefits_index_fetch++) {
 	var option_value=parsed_benefits_data[custom_benefits_index_fetch]["name"];
 	$(".ComponentName").append('<option value='+option_value+'>'+option_value+'</option>');
 }
$(".payable_category_month ").prop("name","PayableMonth[]");
$(".payable_category").removeAttr("name"); 
$(".payable_category_label").html("Payable");
$(".ComponentName").prop("name","ComponentName");
$(".payable_category").val("Monthly");
//$(".basic_of_excess_movedto").show();
$(".payable_category").prop("required",true);
$(".payable_category").empty();
$(".payable_category").append("<option value='Monthly'>Monthly</option>");
$(".payable_category").append("<option value='Quarterly'>Quarterly</option>");
$(".payable_category").append("<option value='HalfYearly'>Half Yearly</option>");
$(".payable_category").append("<option value='Annually'>Annuallys</option>");
//document.getelementsbyclassname('payable_category_label').innerHTML = 'Hi,two';
}

});

$(document).on("click change",".selected_type",function(){
	var selected_vaue=$(this).val();
	//console.log(selected_vaue);
	var selected_benefits=$(".given_benefits").val();

    
	if(selected_vaue=="FixedAmount"){
		$(".basic_or_perc_value").show();
		$(".basic_of_excess_movedto").hide();
		$(".Basic_or_fixed").html(selected_vaue);
		$(".fixed_percentage").prop("name","FixedAmount");
        $(".CappedAmount").removeAttr("name");
        $(".fixed_percentage").prop("required",true);
        $(".CappedAmount").prop("required",false);

	}
if(selected_vaue=="PercofBasicNoLimit"){
	    $(".CappedAmount").removeAttr("name");
	    $(".CappedAmount").prop("required",false);
        $(".basic_of_cap_value").hide();
	    $(".basic_or_perc_value").show();
		$(".Basic_or_fixed").html("% of Basic");
		$(".fixed_percentage").prop("name","PercOfBasic");
		$(".fixed_percentage").prop("required",true);
        
		
		
	}
	if(selected_vaue=="PercofBasicCapped"){
		$(".basic_or_perc_value,.basic_of_cap_value").show();
		$(".Basic_or_fixed").html("% of Basic");
		$(".fixed_percentage").prop("name","PercOfBasic");
		$(".CappedAmount").prop("name","CappedAmount");
		$(".fixed_percentage,.CappedAmount").prop("required",true);
		(selected_benefits=="custom_direct_benefits") ? $(".basic_of_excess_movedto").show() : $(".basic_of_excess_movedto").hide();
		
	}
 $(".ExcessMovedto").change(function() {
         var ExcessMovedto=$(this).val();
          if(ExcessMovedto==""){
          	 $(".ExcessMovedto").removeAttr("name");
          }
          if(ExcessMovedto){
          	$(".ExcessMovedto").prop("name","ExcessMovedto");
          }
      });

});


$(document).on("click",".show_modal",function(){
	var benefits_select=$(".given_benefits").val();
	var plan_name=$(".Plan").val();
	$(".update_index").val("");
	var benefits_length=benefits_select.length;
	//document.getElementById("customized_plan").reset();
	/*if(plan_name==""){
		alert();
	}*/
	
if(benefits_length=="0" && plan_name==""){
	//console.log("fdd");
	toastr.options = {
"preventDuplicates": true,
"preventOpenDuplicates": true
};
toastr.error("Please Select The Type Of Benefits..");
$("#rename_modal").modal("hide");
}
if(benefits_length !="0" && plan_name){
	$(".BenefitsToBe").val(benefits_select);
	$(".ComponentName,.fixed_percentage,.CappedAmount,.selected_type").val("");
	$(".basic_or_perc_value,.basic_of_cap_value").hide();
$("#rename_modal").modal("show");

}
if(benefits_length !="0" && plan_name==""){
 swal("Not Done...!", "Please Set Your Salary plan...", "warning");  
}
if(benefits_length =="0" && plan_name){
 //toastr.error("Please Select The Type Of Benefits..");
  swal( "Please Select whether you want to add Benefit or a Deduction...");  

}
});







$(document).on("click",".saveupdates",function(){
if (!$('#customized_plan').valid()) return false; 
var employee_plan=$(".Plan").val();
var employee_id=$(".EmployeeId").val();

	//var existing_plan=$("").data("current-plan");
    $.ajax({
        type: "POST",
        url:"customized-benifits-creation-test1.php",
        data: $("#customized_plan").serialize(),
			success: function(data) { 
				$("#rename_modal").modal("hide");
				if(data){
					var split_data_response=data.split("~");
					toastr.success("successfully inserted...");
						$(".deduction_row,.direct_benefits_row").empty();
					if(split_data_response[0]=="null"){ 
                      $(".deduction_row").append('<tr><td></td><td></td><th colspan="3">No data found for this employee</th></tr>');
					}
					if(split_data_response[1]=="null"){ 
						$(".direct_benefits_thead").hide();
                      $(".direct_benefits_row").append('<tr><td></td><th colspan="6">No data found for this employee</th></tr>');
					}
					if(split_data_response[0] !="null"){
						var parsed_data_deduction=JSON.parse(split_data_response[0]);
                       for (var deduction_index_insert =0; deduction_index_insert<parsed_data_deduction.length;deduction_index_insert++) {
                       	  var Payable=parsed_data_deduction[deduction_index_insert]["Payable"];
             	var DeductionCategory=parsed_data_deduction[deduction_index_insert]["DeductionCategory"];
             	var FixedAmount=parsed_data_deduction[deduction_index_insert]["FixedAmount"];
             	var PercOfBasic_Deduction=parsed_data_deduction[deduction_index_insert]["PercOfBasic"]; 
             	var CappedAmount_Deduction=parsed_data_deduction[deduction_index_insert]["CappedAmount"];
             	
if(FixedAmount > 0){
 var deduction_type="Fixed Amount";
}
if(PercOfBasic_Deduction < 100){
 var PercOfBasic_Deduction="% of Basic-No Limit";
}
if(PercOfBasic_Deduction < 100 && CappedAmount_Deduction > 0){
var deduction_type="% of Basic-Capped";
}
var deduction_value=(FixedAmount > 0) ? "₹"+FixedAmount : PercOfBasic_Deduction+"%";
var deduction_capamount=(CappedAmount_Deduction > 0) ? CappedAmount_Deduction : "NA";

             	$(".deduction_row").append("<tr><td>"+DeductionCategory+"</td><td>"+deduction_type+"</td><td>"+deduction_value+"</td><td>"+deduction_capamount+"</td><td><a class='m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill update_record' title='Edit details' data-update="+employee_id+"~"+deduction_index_insert+"~custom_deductions > <i class='la la-edit'></i></a><a href='#' class='m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill delete_benefits_records'  title='Delete' data-delete-index="+deduction_index_insert+" data-delete="+employee_id+"~"+employee_plan+"~"+DeductionCategory+"~"+deduction_index_insert+"~custom_deductions><i class='la la-trash'> </i></a></td></tr></tr>");
                       }
					}
//direct benefits
if(split_data_response[1] !="null"){
	$(".deduction_benifits_thead").show();
	var direct_benefits=JSON.parse(split_data_response[1]);
     for (var benefits_index1 =0;benefits_index1 < direct_benefits.length;benefits_index1++) {

              	  var FixedAmount=direct_benefits[benefits_index1]["FixedAmount"];
              	  var PayableMonth=direct_benefits[benefits_index1]["PayableMonth"];
              	  var ComponentName=direct_benefits[benefits_index1]["ComponentName"];
              	  var PercOfBasic=direct_benefits[benefits_index1]["PercOfBasic"];
              	  var ExcessMovedto=direct_benefits[benefits_index1]["ExcessMovedto"];
              	  var CappedAmount=direct_benefits[benefits_index1]["CappedAmount"];

if(FixedAmount > 0 && PercOfBasic <=0){
 var type="Fixed Amount";
}
if(PercOfBasic > 0 && FixedAmount <=0 ){
 var type="% of Basic-No Limit";
}
if(PercOfBasic < 100 && CappedAmount > 0){

 var type="% of Basic-Capped";
}
var value=(FixedAmount > 0) ? FixedAmount : PercOfBasic+"%";
var CappedAmount=(CappedAmount > 0) ? CappedAmount : "NA";
var ExcessMovedto=(ExcessMovedto) ? ExcessMovedto : "NA";
var payable=(PayableMonth[0]=="NA") ? "Monthly" : PayableMonth;


$(".direct_benefits_row").append("<tr><td>"+ComponentName+"</td><td>"+type+"</td><td>"+value+"</td><td>"+CappedAmount+"</td><td>"+ExcessMovedto+"</td><td>"+payable+"</td><td><a class='m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill update_record' title='Edit details' data-update="+employee_id+"~"+benefits_index1+"~custom_direct_benefits > <i class='la la-edit'></i></a><a href='#' class='m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill delete_benefits_records'  title='Delete' data-delete-index="+benefits_index1+" data-delete="+employee_id+"~"+employee_plan+"~"+ComponentName+"~"+benefits_index1+"~custom_direct_benefits><i class='la la-trash'> </i></a></td></tr>");
              }
          }}
}
});
});


//to check existing plan
$(document).on("focusout keyup",".ComponentName",function(e){ 
if(e.which==13 || e.type=="focusout"){  
var current_component_name=$(this).val();
 var current_name=$(this).prop("name");
 var current_plan=$(".Plan").val();
 var current_emp_id=$(".EmployeeId").val();
 var type_of_benefits=(current_name=="DeductionCategory") ? "DeductionArray" : "DirectBenefitsArray"


 $.ajax({
        type: "POST",
       // url:"get-revised-data.php",
        data: {component_name_checking:current_component_name,current_plan:current_plan,type_of_benefits:type_of_benefits,current_emp_id:current_emp_id},
			success: function(data) { 
				(data=="match") ? $(".blink_me").show() : $(".blink_me").hide() ;
                   
			} 
		});
}

});


//get plan name and emplyee name
$(document).on("keyup focusout",".EmployeeId",function(e){ 
if(e.which==13 || e.type=="focusout"){    
	//if (!$('#plan_correction').valid()) return false; 
	toastr.options = {
"preventDuplicates": true, 
"preventOpenDuplicates": true
};
	var employee_id=$(this).val();
	var employee_plan=$(".Plan").val();
    $.ajax({
        type: "POST",
        url:"get-revised-data.php",
        data: {given_employee_id:employee_id},
			success: function(data) {
			var split_data=data.split("~"); 
			$(".table1,.table2").show();
          // console.log(data);
           var exception_benefits_array = ["FoodSubsidy", "LoanSubsidy","CTC_grand_total","PF","SuperAnnuation","Gratuity","MedicalInsurance","ESI","CTC_Per_Month"];
		var indirect_benefits_array=["FoodSubsidy","MedicalInsurance","LoanSubsidy"];
		var gross_benefits_array=["CTC_grand_total","CTC_Per_Month"];
     (split_data[1]=="") ? $(".Basic").val("") : $(".Basic").val(split_data[1]);
     if(split_data[0].length =="1"){
     	$(".EmployeeName,.Basic").val("");
     	toastr.error("Invalid Employee id");
     	//swal("Not Done...!", "Invalid Employee id...", "warning");  
     	$(".table1,.table2").hide();
     }if(split_data[0].length > 1){$(".EmployeeName").val(split_data[0]); }
				(split_data[2]=="") ? $(".Plan").val("") : $(".Plan").val(split_data[2]);
if(split_data[4]){
	//console.log(split_data[4]);
             var direct_benefits=JSON.parse(split_data[4]);
             $(".direct_benefits_row").empty();
              $(".direct_benefits_thead").show();
              for (var index1 =0;index1 < direct_benefits.length;index1++) {

              	  var FixedAmount=direct_benefits[index1]["FixedAmount"];
              	  var PayableMonth=JSON.parse(direct_benefits[index1]["PayableMonth"]);
              	  var ComponentName=direct_benefits[index1]["ComponentName"];
              	  var PercOfBasic=direct_benefits[index1]["PercOfBasic"];
              	  var ExcessMovedto=direct_benefits[index1]["ExcessMovedto"];
              	  var CappedAmount=direct_benefits[index1]["CappedAmount"];

if(FixedAmount > 0){
 var type="Fixed Amount";
}
if(PercOfBasic < 100){
 var type="% of Basic-No Limit";
}
if(PercOfBasic < 100 && CappedAmount > 0){

 var type="% of Basic-Capped";
}
var value=(FixedAmount > 0) ? FixedAmount : PercOfBasic+"%";
var CappedAmount=(CappedAmount > 0) ? CappedAmount : "NA";
var ExcessMovedto=(ExcessMovedto) ? ExcessMovedto : "NA";
var payable=(PayableMonth[0]=="NA") ? "Monthly" : PayableMonth;


$(".direct_benefits_row").append("<tr><td>"+ComponentName+"</td><td>"+type+"</td><td>"+value+"</td><td>"+CappedAmount+"</td><td>"+ExcessMovedto+"</td><td>"+payable+"</td><td><a class='m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill update_record' data-update-index="+index2+" title='Edit details' data-update="+employee_id+"~custom_direct_benefits > <i class='la la-edit'></i></a><a href='#' class='m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill delete_benefits_records'  title='Delete' data-delete-index="+index1+" data-delete="+employee_id+"~"+employee_plan+"~"+ComponentName+"~"+index1+"~custom_direct_benefits><i class='la la-trash'> </i></a></td></tr>");
              }

				}

				if(split_data[4]==""){
                  $(".direct_benefits_thead").hide();
                  $(".direct_benefits_row").empty();
                  $(".direct_benefits_row").append("<tr><td></td><th colspan='6'>No data found for this employee</th></tr>");
               }
				
				if(split_data[5]){
					$(".deduction_benifits_thead").show();
					$(".deduction_row").empty();
					//console.log(split_data[5]);
             var parsed_data_deduction=JSON.parse(split_data[5]);
             for (var index2=0; index2<parsed_data_deduction.length; index2++) {
             	var Payable=parsed_data_deduction[index2]["Payable"];
             	var DeductionCategory=parsed_data_deduction[index2]["DeductionCategory"];
             	var FixedAmount_deduction=parsed_data_deduction[index2]["FixedAmount"];
             	var PercOfBasic_Deduction_fetch=parsed_data_deduction[index2]["PercOfBasic"]; 
             	var CappedAmount_Deduction=(parsed_data_deduction[index2]["CappedAmount"] > 0) ? parsed_data_deduction[index2]["CappedAmount"]  : -1;
             //	console.log(CappedAmount_Deduction);
if(FixedAmount_deduction > 0 ){
 var deduction_type="Fixed Amount";
}
if(PercOfBasic_Deduction_fetch > 0 && CappedAmount_Deduction < 0){
 var deduction_type="% of Basic-No Limit";
}
if(PercOfBasic_Deduction_fetch < 100 && CappedAmount_Deduction > 0){
var deduction_type="% of Basic-Capped";
}
  //console.log(PercOfBasic_Deduction_fetch);
var deduction_value=(FixedAmount_deduction > 0) ? "₹"+FixedAmount_deduction : PercOfBasic_Deduction_fetch+"%";
var deduction_capamount=(CappedAmount_Deduction > 0) ? CappedAmount_Deduction : "NA";


             	$(".deduction_row").append("<tr><td>"+DeductionCategory+"</td><td>"+deduction_type+"</td><td>"+deduction_value+"</td><td>"+deduction_capamount+"</td><td><a class='m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill update_record' data-update-index="+index2+
             	  " title='Edit details' data-update="+employee_id+"~custom_deductions ><i class='la la-edit'></i></a><a href='#' class='m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill delete_benefits_records'  title='Delete' data-delete-index="+index2+" data-delete="+employee_id+"~"+employee_plan+"~"+DeductionCategory+"~"+index2+"~custom_deductions><i class='la la-trash'> </i></a></td></tr></tr>");
             }
             
               }
               if(split_data[5]==""){
                  $(".deduction_benifits_thead").hide();
                   $(".deduction_row").empty();
                  $(".deduction_row").append("<tr><td></td><td></td><th colspan='3'>No data found for this employee</th></tr>");
               }


 }
});
}
});

 
 //update record
$(document).on("click",".update_record",function(){
	var delete_data=$(this).data("update").split("~");
	var get_emp_id=delete_data[0];
	var get_coulumn=delete_data[1];
	var get_emp_index=$(this).data("update-index");

	//console.log(get_emp_index);
	//var split_column=get_coulumn.split("_");
	$(".update_index").val(get_emp_index);
    $.ajax({
         type: "POST",
         data:{get_emp_id:get_emp_id,get_emp_index:get_emp_index,get_coulumn:get_coulumn},
			success: function(data) { 
 if(data){
 	//console.log(data);
 	//$("h5").text("Update customize "+split_column[1]+" ");
 	$(".BenefitsToBe").val(get_coulumn);
 	//$("h5").addClass("m-font--success");
 	var parsed_data=JSON.parse(data);
 	$("#rename_modal").modal("show");
 	if(get_coulumn=="custom_direct_benefits"){
var custom_benefits_added='<?php echo $custom_benefits_added;?>';
	 var parsed_benefits_data=JSON.parse(custom_benefits_added);
	 $(".ComponentName").empty();
	 $(".ComponentName").append('<option value="">select category</option>');
 for (var custom_benefits_index_fetch =0;custom_benefits_index_fetch< parsed_benefits_data.length; custom_benefits_index_fetch++) {
 	var option_value=parsed_benefits_data[custom_benefits_index_fetch]["name"];
 	$(".ComponentName").append('<option value='+option_value+'>'+option_value+'</option>');
 }
 		$(".payable_category_month ").prop("name","PayableMonth[]");
$(".payable_category").removeAttr("name"); 
$(".payable_category_label").html("Payable");
$(".ComponentName").prop("name","ComponentName");
$(".payable_category").val("Monthly");
//$(".basic_of_excess_movedto").show();
$(".payable_category").prop("required",true);
$(".payable_category").empty();
$(".payable_category").append("<option value='Monthly'>Monthly</option>");
$(".payable_category").append("<option value='Quarterly'>Quarterly</option>");
$(".payable_category").append("<option value='HalfYearly'>Half Yearly</option>");
$(".payable_category").append("<option value='Annually'>Annuallys</option>");

        var Payable=parsed_data["Payable"];
 		var FixedAmount_benefits=parsed_data["FixedAmount"];
 		var PayableMonth=parsed_data["PayableMonth"];
 		var PayableMonth_length=parsed_data["PayableMonth"].length;
 		var ComponentName=parsed_data["ComponentName"];
 		$(".ComponentName").val(ComponentName);
 		$(".payable_category").val(Payable);
 	if(FixedAmount_benefits > 0){
    	$(".selected_type").val("FixedAmount");
    	$(".Basic_or_fixed").text("FixedAmount");
    	$(".basic_or_perc_value").show();
    	$(".fixed_percentage").prop("name","FixedAmount");
         $(".fixed_percentage").val(FixedAmount_benefits);
    }

  



 	}
 	//console.log(get_coulumn);
 	if(get_coulumn=="custom_deductions"){
 		 var custom_deductions_added='<?php echo $custom_deductions_added;?>';
  var parsed_custom_deductions_data=JSON.parse(custom_deductions_added);
  $(".ComponentName").empty();
  $(".ComponentName").append('<option value="">select category</option>');
 for (var custom_deduction_index_fetch =0;custom_deduction_index_fetch< parsed_custom_deductions_data.length; custom_deduction_index_fetch++) {
 	var option_value=parsed_custom_deductions_data[custom_deduction_index_fetch]["name"];
 	$(".ComponentName").append('<option value='+option_value+'>'+option_value+'</option>');
 }
 			$(".payable_category").empty();
	$(".payable_category_month ").removeAttr("name");
	$(".basic_of_excess_movedto").hide();
	$(".payable_category").prop("required",true);
	$(".ComponentName").prop("name","DeductionCategory");
	$(".payable_category").prop("name","Payable");
$(".payable_category_label").html("Re-Payable to employee at the time of");
$(".payable_category").append("<option value='NonRepayable'>Non-Repayable</option>");
$(".payable_category").append("<option value='Retirement'>Retirement</option>");
$(".payable_category").append("<option value='TrainingPeriodEnd'>Training PeriodEnd</option>");
$(".payable_category").append("<option value='EmployeeRelieving'>Employee Relieving</option>");
$(".payable_category").append("<option value='EmployeeDemise'>Employee's Demise</option>");

 		var Payable=parsed_data["Payable"];
 		var FixedAmount_deduction=(parsed_data["FixedAmount"]) ? parsed_data["FixedAmount"] : -1;
 		var DeductionCategory=parsed_data["DeductionCategory"];
 		var PercOfBasic_deduction=(parsed_data["PercOfBasic"]) ? parsed_data["PercOfBasic"] : -1;
 		var CappedAmount_deduction=parsed_data["CappedAmount"];
         //show values in correspoding table
 		$(".payable_category").val(Payable);
 		$(".ComponentName").val(DeductionCategory);
 		  console.log(FixedAmount_deduction);
 		   	console.log(PercOfBasic_deduction);
 //select box 
       if(FixedAmount_deduction > 0 && PercOfBasic_deduction <=0){
    
       	$(".basic_or_perc_value").show();
       	$(".Basic_or_fixed").html("Fixed Amount");
$(".selected_type").val("FixedAmount");
$(".fixed_percentage").val(FixedAmount_deduction);
$(".fixed_percentage").prop("name","FixedAmount");
}
if(PercOfBasic_deduction > 0 && FixedAmount_deduction <= 0){
	$(".Basic_or_fixed").html("% Of Basic");
	$(".basic_or_perc_value").show();
$(".fixed_percentage").prop("name","PercOfBasic");	
 $(".selected_type").val("PercofBasicNoLimit");
 $(".fixed_percentage").val(PercOfBasic_deduction);
}
if(PercOfBasic_deduction < 100 && CappedAmount_deduction > 0){
	$(".basic_of_cap_value").show();

$(".fixed_percentage").prop("name","PercOfBasic");
$(".CappedAmount").prop("name","CappedAmount");	
 $(".selected_type").val("PercofBasicCapped");
 $(".CappedAmount").val(CappedAmount_deduction);
 $(".fixed_percentage").val(PercOfBasic_deduction);
}
}
 	
 }
			 } 
			})

			}); 



//delete record
$(document).on("click",".delete_benefits_records",function(){
	var delete_data=$(this).data("delete").split("~");
	var delete_emp_id=delete_data[0];
	var delete_emp_plan=$(".Plan").val();
	var delete_emp_component=delete_data[2];
	var delete_emp_index=$(this).data("delete-index");
	var delete_coulumn=delete_data[4];
    $.ajax({
         type: "POST",
         data:{delete_emp_id:delete_emp_id,delete_emp_plan:delete_emp_plan,delete_emp_component:delete_emp_component,delete_emp_index:delete_emp_index,delete_coulumn:delete_coulumn},
			success: function(data) {  
				if(data=="end"){
					if(delete_coulumn=="custom_direct_benefits"){
                        document.getElementById("direct_benefits_row").deleteRow(delete_emp_index);
                       	 toastr.error("Deleted successfully....");
                       	var benefits_class_data_delete=document.getElementById('deduction_append_data').getElementsByClassName("delete_benefits_records");
   var benefits_class_data_update=document.getElementById('deduction_append_data').getElementsByClassName("update_record");
   //row count of benefits
   var direct_benefits_row_length= $('.table1 .direct_benefits_row tr').length;
                       for(var table1_index=0;table1_index < direct_benefits_row_length;table1_index++){
                       benefits_class_data_delete[table1_index].setAttribute("data-delete-index",table1_index);
                        benefits_class_data_update[table1_index].setAttribute("data-update-index",table1_index);
                       }
                       }
                       if(delete_coulumn=="custom_deductions"){
                       document.getElementById("deduction_append_data").deleteRow(delete_emp_index);
                        toastr.error("Deleted successfully....");
                       var deduction_row_delete=document.getElementsByClassName("delete_benefits_records");
   var deduction_class_data_delete=document.getElementById('deduction_append_data').getElementsByClassName("delete_benefits_records");
   var deduction_class_data_update=document.getElementById('deduction_append_data').getElementsByClassName("update_record");
  // row count of deductions
                      var deduction_row_length= $('.table2 .deduction_row tr').length;
                      for(var table2_index=0;table2_index< deduction_row_length;table2_index++){
                       
                        deduction_class_data_delete[table2_index].setAttribute("data-delete-index",table2_index);
                        deduction_class_data_update[table2_index].setAttribute("data-update-index",table2_index);
                       }
                       }
                       }
                       else{
                       	    toastr.info("Not Done......");
                       }
     }

			 });


});





  



    </script>
  </body>	
</html>
