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
//echo $gid.$bid;exit;
date_default_timezone_set('Asia/Kolkata');
$current_date = date('Y-m-d H:i:s');

 if($name=='' && $access=='' && $id=='' && $email=='' && $company_name=='' && $bid=='' && $gid==''){
 	  echo "<script>alert('You Dont have Access this Page');location.href='https://incometax.indiafilings.com';</script>";
 	header('Location:https://incometax.indiafilings.com');

} 
 
$sel_query_resp=pg_fetch_array(pg_query($db,"select custom_direct_benefits,custom_deductions from deduction_benefits where bid ='$bid'"));
	$custom_benefits=$sel_query_resp[0];
	$custom_deductions=$sel_query_resp[1];
	//echo $custom_benefits;exit;
//echo $custom_benefits;exit;

if(isset($_POST['name'])){
	$insert_data=$_POST['name'];
	$sel_query=pg_query($db,"select custom_direct_benefits,custom_deductions from deduction_benefits where bid ='$bid'");
	$row=pg_fetch_assoc($sel_query);
	$custom_direct_benefits=$row['custom_direct_benefits'];
	$custom_deductions=$row['custom_deductions'];
	$data1=json_decode($custom_direct_benefits,true);
	$data2=json_decode($custom_deductions,true);
	$size_data1=sizeof($data1);
	$size_data2=sizeof($data2);
	// for($x=0; $x<$size_data1; $x++){$name1[]=$data1[$x];}
	
	foreach($data1 as $dat1){$name1[]=$dat1['name'];}
	foreach($data2 as $dat2){$name[]=$dat2['name'];}
	
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
	  $data_name=$array[$x]['name'];  
	  // $insert_dat str_replace(' ','-',$data_name)
	     $emp_insert_name1= preg_replace('/[^A-Za-z\-]/', '', $data_name);
			 echo $emp_insert_name1;//exit;
	 /*$name=$array[$x]['name'];
	    $data1=$_POST; */ 
	$sel_query=pg_query($db,"select custom_direct_benefits,custom_deductions from deduction_benefits where bid ='$bid'");
	$row=pg_fetch_assoc($sel_query);
	$custom_direct_benefits=$row['custom_direct_benefits'];
   $custom_deductions=$row['custom_deductions'];
	 $data1=json_decode($custom_direct_benefits,true);
	$data2=json_decode($custom_deductions,true);
	$size_data1=sizeof($data1);
	$size_data2=sizeof($data2);
	// for($x=0; $x<$size_data1; $x++){$name1[]=$data1[$x];}
	
	foreach($data1 as $dat1){$name1[]=$dat1['name'];}
	foreach($data2 as $dat2){$name1[]=$dat2['name'];}
	
	
	$datas=in_array($insert_dat,$name1);if($datas=='0'){$datas=0;} 
	//echo in_array($insert_dat,$name1);exit;
	if($datas==0){
	
  $sel=pg_query($db,"select custom_direct_benefits,custom_deductions from deduction_benefits where bid='$bid'");
	 $count=pg_num_rows($sel);
	 $row=pg_fetch_assoc($sel);
	 $custom_direct_benefits=$row['custom_direct_benefits'];
	 $custom_deductions=$row['custom_deductions'];
	 
	 
	 // for benefits
	  if($select=="Benefits"){
		unset($array[$x]["select"]);
		$data=json_encode($array[$x]);
		if($count==0){
			
				$insert = pg_query($db,"insert into deduction_benefits(bid,custom_direct_benefits,custom_deductions,created_by,created_date,status)values('$bid','[$data]','[]','$email','$current_date','')");
				
					if($insert){$data="insert";}else{$data="not insert";}
			}else{
				if($custom_direct_benefits=='[]'){
					$update=pg_query($db,"UPDATE deduction_benefits SET custom_direct_benefits='[$data]' where bid='$bid'");
						if($update){$data="insert";}else{$data="not insert";}
				}else{
					$insert_data= str_replace(']',",$data]","$custom_direct_benefits");
						$update=pg_query($db,"UPDATE deduction_benefits SET custom_direct_benefits='$insert_data' where bid='$bid' ");
							if($update){$data="insert";}else{$data="not insert";}
				}
			}
	}
		
	 //   For Deduction  

if($select=="Deduction"){
		unset($array[$x]["select"]);
		$data=json_encode($array[$x]);
		
			if($count==0){
				$insert = pg_query($db,"insert into deduction_benefits(bid,custom_direct_benefits,custom_deductions,created_by,created_date,status)values('$bid','[]','[$data]','$email','$current_date','')");
				
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
	}else{$data="not insert.";}  
	
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
	<div class="modal fade show" id="show_plan_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" >
							<div class="modal-dialog modal-lg" role="document">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title" id="exampleModalLabel">customize benefits creation</h5>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">×</span>
										</button>
									</div>
									<div class="modal-body">
										<form>
											
												<div class="row">

   	<div class="col-lg-12">	

   		<table class="table table-bordered m-table m-table--border-brand ">
<thead class="thead1"><tr><th></th><th colspan="3" style="color:white;text-align: center;"><center>DIRECT BENEFITS</center></th><th></th><th></th></tr></thead>
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
</tr></thead>
<tbody class="direct_benefits_row" id=""></tbody></table>


												
								</div>	
<div class="col-lg-12">
<table class="table table-bordered m-table m-table--border-brand ">
<thead class="thead1"><tr><th></th><th colspan="2" style="color:white;text-align: center;"><center>INDIRECT BENEFITS </center></th></tr></thead>
<thead class="indirect_benifits_thead" style="display: none;"><tr><th>Salary Component</th>
<th>Amount</th><th></th></tr></thead>
<tbody class="indirect_benefits"><tr><th></th><th colspan="2">No Data found for this Plan</th></tr></tbody></table>	
</div>
<div class="col-lg-12"> 
<table class="table table-bordered m-table m-table--border-brand myTable2">
<thead class="thead1 "><tr><th></th><th align="center" colspan="4" style="color:white"><center>DEDUCTIONS BENEFITS </center></th></tr></thead>
<thead class="deduction_benifits_thead">
<th>
Dedudction Component
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
<tbody class="deduction_row" id=""></tbody></table>
</div>
<div class="col-lg-12">
<table class="table table-bordered m-table m-table--border-brand ">
<thead class="thead1 "><tr><th></th><th align="center" colspan="4" style="color:white"><center>DEDUCTIONS BENEFITS </center></th></tr></thead>
<thead class="deduction_benifits_thead" >
<tr><th>
Dedudction Component
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
</tr></thead>
<tbody class="deduction_row"></tbody></table> 
</div>		



							</div>	
											
										</form>
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
										<!-- <button type="button" class="btn btn-primary">Send message</button> -->
									</div>
								</div>
							</div>
						</div>
<!--benefits-->
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
				</select>
	</td> 
				<td><input type="text" class="form-control m-input component_name check_componenets_name"  name="Customized[0][name]" onkeyup="this.value=this.value.toUpperCase();" required></td>
				<td>
				<button type="button" class="btn m-btn--pill m-btn--air  btn-sm  btn-success add1"  style='margin-left: 4%;'>
		Add Record
	</button> 
	</td>
										
		<td></td>
	
			<!-- <button type="button" class="btn m-btn--pill m-btn--air  btn-sm  btn-success add1" <?php //if($i==0){echo '';}else{echo 'style="display:none"';}?>  style='margin-left: 4%;'>
				Add Record
			</button> -->
			<!--<button type="button" class="btn m-btn--pill m-btn--air  btn-sm  btn-danger Delete1"  style='margin-left: 10%;'>
				Delete
			</button>-->
		
	</tr>
	 
		</tbody>
</table>	

<table id="custom1" class="table table-bordered m-table m-table--border-brand"></table>
<script>
var custom_deductions='<?php echo $custom_deductions?>';
 var  custom_benefits='<?php echo $custom_benefits?>';
 var parsed_benefits=JSON.parse(custom_benefits);
 var parsed_deductions=JSON.parse(custom_deductions);
 for(var i=0;i<parsed_benefits.length;i=i+1)
 {
	 var temp= parsed_benefits[i].name;
	 
	$("#custom1").append('<tr><td><select class="form-control m-input " name="Customized['+i+'][select]"><option value="Benefits">Benefits</option></select></td><td><input type="text" class="form-control m-input" value="'+temp+'" readonly > </td></tr>');
	
	//$("#custom1").append('<tr><td><select class="form-control m-input " name="Customized['+index+'][select]"><option value="">Select</option><option value="Benefits">Benefits</option><option value="Deduction">Deduction</option></select></td><td><input type="text" class="form-control m-input dash_value component_name'+index+' check_componenets_name" name="Customized['+index+'][name]"<span>'temp'</span>onkeyup="this.value=this.value.toUpperCase();" ></td></tr>');
 
         
	 //alert(parsed_benefits[i].name);
 } 
 for(var i=0;i<parsed_deductions.length;i=i+1)
 {
	 var temp= parsed_deductions[i].name;
	 
	$("#custom1").append('<tr><td><select class="form-control m-input " name="Customized['+i+'][select]"><option value"Deduction">Deduction</option></select></td><td><input type="text" class="form-control m-input" value="'+temp+'" readonly > </td></tr>');
	
	//$("#custom1").append('<tr><td><select class="form-control m-input " name="Customized['+index+'][select]"><option value="">Select</option><option value="Benefits">Benefits</option><option value="Deduction">Deduction</option></select></td><td><input type="text" class="form-control m-input dash_value component_name'+index+' check_componenets_name" name="Customized['+index+'][name]"<span>'temp'</span>onkeyup="this.value=this.value.toUpperCase();" ></td></tr>');
 
         
	// alert(parsed_deductions[i].name);
 }
</script>
								
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
											<div class="form-group"><span class="m-menu__link-badge blink_me" style="float: right; display: none">
                                        <span class="m-badge m-badge--brand m-badge--wide" style="margin:0 !important">
                                            New value replace the existing value
                                        </span>
                                    </span>
												<label for="recipient-name" class="form-control-label">Component Name:</label>
												
												<input type="text" class="form-control ComponentName"  name="ComponentName"  onkeyup='this.value = this.value.toUpperCase();'>
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
<option value='PercofBasicNoLimt'>% of Basic-No Limit</option>
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
											        		<select class='form-control m-input given_benifits' id='exampleSelect1' name='Month'>
<option value=''>Select</option>
<option value='custom_direct_benefits'>Direct Benefits</option>
<!-- <option value='custom_indirect_benefits'>In-Direct Benefits</option> -->
<option value='custom_deductions'>Deductions</option>
</select>
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
										<div class="col-lg-6 m--margin-bottom-10-tablet-and-mobile">
											<br>
											<div class="col-xl-12 order-1 order-xl-2 m--align-right">
											
											
											<button type="button" class="btn btn-focus benefits_deduct" data-toggle="modal" data-target="#benefits_deduction"  style="margin-right: -113px;margin-top: -115px;">Add</button>
												      </div>	     
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
        		<select class='form-control m-input given_benifits' id='exampleSelect1' name='Month'>
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
 
 
 //initial data append if data present
	 

 
  var index=1; 
      $(".add1").on("click ", function() {

	 
        $("#table_bp").append('<tr><td><select class="form-control m-input " name="Customized['+index+'][select]"><option value="">Select</option><option value="Benefits">Benefits</option><option value="Deduction">Deduction</option></select></td><td><input type="text" class="form-control m-input dash_value '+index+' check_componenets_name component_name" name="Customized['+index+'][name]"onkeyup="this.value=this.value.toUpperCase();" required></td></tr>');
 
         

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
		success: function(data){
			if(data==1)
			{toastr.error("Component Name already Exists.");
			}
			else{toastr.success("New Component Name.");
			}
		}
	})
	
	
	
	
	
});



 $(document).on("click",'.benefits_deduct',function(e){ 
 $("#benefits_deduction").modal("show");
	$(".component_name").val('');
	//alert(e);
 });
 $(document).on("click",'.modal_submit',function(e){ 
 
 // $(".modal_submit").hide();
		var data1=$("#employee_details").serialize();
	 //alert(data1);
	$.ajax({
  type: "POST",
  data: data1,
  success: function(data) {
		//alert(1);
    if(data=="insert")
  {
	  $("#benefits_deduction").modal("hide");
	 
 		  
	  //alert(1);
  toastr.success("Information insert");
//alert(e);  
  }
  else
  {
	  toastr.error("Information not Update"); 
	   $("#benefits_deduction").modal("hide");
  }
    
  }
  })
  
  }); 
	

 
    </script>
  </body>	
</html>
