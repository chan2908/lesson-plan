<?php
ini_set('display_errors','0');
error_reporting(E_ALL);
include "db-connect.php";
//$bid=7894;
  session_start();
//print_r($_SESSION);exit;
$name= $_SESSION["name"];

$access= $_SESSION["access"];
$id= $_SESSION["id"];
$email= $_SESSION["email"];
$company_name= $_SESSION["company_name"];
$gid= $_SESSION["gid"];
$bid= $_SESSION["bid"];
//echo $gid; exit;
if($_SESSION["name"]=='' && $_SESSION["access"]=='' && $_SESSION["id"]=='' && $_SESSION["email"]=='' && $_SESSION["company_name"]=='' && $_SESSION["bid"]=='' && $_SESSION["gid"]==''){header('Location:https://incometax.indiafilings.com/tds_filing/salary/session/session.php');} 

$data=$_GET["month"];
$data1=explode("-",$data);
$month1=$data1[0];
$year1=$data1[1];
// echo $month1;exit;



//$sel_deduction = pg_query($db, "select employee_id,employee_name,user_deduction,salary_calculated_until from employee_monthly_salary_detail where bid='$bid'");
//echo $id;exit;
$sel_sql="select distinct a.employee_id,a.employee_name,a.basic,a.plan,a.stage,c.gross_total,c.income_tax,c.investments_so_far,c.salary_payout,c.tds_per_month,c.current_month,c.year,
c.deduction->>'SECURITY-DEPOSIT' as deductionamount,c.created_date,c.created_by,c.deduction
from employee_overall_gross_detail a 
INNER  JOIN employee_monthly_salary_detail c on a.employee_id=c.employee_id where a.bid='$bid' and c.bid='$bid' and a.stage='current'";


 if($month1 && $year1){$sel_sql="select distinct a.employee_id,a.employee_name,a.basic,a.plan,c.gross_total,c.income_tax,c.investments_so_far,c.salary_payout,c.tds_per_month,c.current_month,c.year,
c.deduction->>'SECURITY-DEPOSIT' as deductionamount,c.created_date,c.created_by,c.deduction
from employee_overall_gross_detail a 
INNER  JOIN employee_monthly_salary_detail c on a.employee_id=c.employee_id where a.bid='$bid' and c.bid='$bid' and c.current_month='$month1' and c.year='$year1'";} 

//echo $sel_sql;exit;
$res1=pg_query($db,$sel_sql);
  while($result=pg_fetch_assoc($res1)){
$required_data[]=$result;
//echo $required_data;exit;
}
$trimmed = json_encode($required_data,true); 


if(isset($_POST['get_emp_record']))
{
	//echo "qq";exit;
$get_emp_record = $_POST['get_emp_record'];
$current_month = $_POST['current_month'];
$year = $_POST['year'];
/* get employee details */
$curl = curl_init();
curl_setopt_array($curl, array(
  CURLOPT_URL => "https://xjtqy5zi7e.execute-api.ap-south-1.amazonaws.com/production/relationship-cosmosdb",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => "{\r\n  \"op_type\": \"select_eid\",\r\n  \"bid\":  ".$bid." ,\r\n  \"eid\":".$get_emp_record."\r\n}",
  CURLOPT_HTTPHEADER => array(
    "cache-control: no-cache",
    "content-type: application/json",
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);
curl_close($curl);

 $response1 = json_decode($response,true);
 
 //echo $response; exit;
/* get reporting to mail id */
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://xjtqy5zi7e.execute-api.ap-south-1.amazonaws.com/production/relationship-cosmosdb",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => "{\r\n  \"op_type\": \"select_eid\",\r\n  \"bid\":  ".$bid." ,\r\n  \"eid\":".$response1['employeemaster']['reporting_to']."\r\n}",
  CURLOPT_HTTPHEADER => array(
    "cache-control: no-cache",
    "content-type: application/json",
  ),
));

$response2 = curl_exec($curl);

$err = curl_error($curl);
curl_close($curl);
 

 $response3 = json_decode($response2,true);
 
 $emp_master = '{"designation": "'.$response1['employeemaster']['designation'].'"},{"department": "'.$response1['employeemaster']['department'].'"},{"reporting_to": "'.$response3['employeemaster']['officeemail'].'"}';
 $result_emp_data_array[] = $emp_master;
//echo $emp_master;exit;

$sel_sqlb="select deduction from employee_monthly_salary_detail where employee_id='$get_emp_record'";
 //echo $sel_sqlb;exit;
$result= pg_query($db,$sel_sqlb);

$res= pg_fetch_array($result);
//echo $res;exit;
$adv=$res[0];

$arr=json_decode($adv,true);

$arr_size=sizeof($adv,true);

if($arr_size>0){
 $deductionamount = 0;
 foreach($arr as $item) {
	
  $deductionamount1 +=$item;}} 
//echo $deductionamount1;exit;







  
 $sel_empdata = pg_query($db, "select distinct eog.basic,eog.stage,eog.gross_salary_breakup,emsd.deduction,emsd.retirement_benefits from employee_overall_gross_detail as eog inner join employee_monthly_salary_detail as emsd on emsd.employee_id=eog.employee_id and eog.month= emsd.current_month where eog.employee_id='$get_emp_record' and emsd.current_month='$current_month' and emsd.year='$year'"); 
  
 /* $sel_empdata = pg_query($db, "select distinct eog.basic,eog.stage,eog.gross_salary_breakup,emsd.user_deduction,emg.investment_value  from employee_overall_gross_detail as eog inner join employee_monthly_salary_detail as emsd on emsd.employee_id=eog.employee_id and eog.month= emsd.current_month and eog.year=emsd.year inner join investments as emg on eog.employee_id=emg.employee_id and eog.year=emg.year where eog.employee_id='$get_emp_record' and emsd.current_month='$current_month' and emsd.year='$year'");
	 */
  /* echo "select distinct eog.basic,eog.stage,eog.gross_salary_breakup,emsd.user_deduction,emsd.deduction from employee_overall_gross_detail as eog inner join employee_monthly_salary_detail as emsd on emsd.employee_id=eog.employee_id and eog.month= emsd.current_month where eog.employee_id='$get_emp_record' and emsd.current_month='$current_month' and emsd.year='$year'"; exit;  */
	while($result_emp_data = pg_fetch_assoc($sel_empdata))
	{
		$result_emp_data_array[] = '{"basic": "'.$result_emp_data['basic'].'"}';
		$result_emp_data_array[] = $result_emp_data['gross_salary_breakup'];
		
		$result_emp_data_array[] = $result_emp_data['retirement_benefits'];
		$result_emp_data_array[] = $result_emp_data['deduction'];
		$result_emp_data_array[] = $result_emp_data['investment_value'];
			
	}
	$result_emp_data_array1 =  stripslashes((json_encode($result_emp_data_array,true)));
	$result_emp_data_array2 = str_replace('["','[',$result_emp_data_array1);
	$result_emp_data_array3 = str_replace('"]',']',$result_emp_data_array2);
	$result_emp_data_array4 = str_replace('}","{','},{',$result_emp_data_array3);
	$result_emp_data_array5 = str_replace(',{}','',$result_emp_data_array4);
	$result_emp_data_array6 = str_replace('",null','',$result_emp_data_array5);
	$result_emp_data_array7 = str_replace(',"',',',$result_emp_data_array6);
	 $result_emp_data_array8 = str_replace('}","{','},{',$result_emp_data_array7);
	 $result_emp_data_array9 = str_replace('[]",','',$result_emp_data_array8);
	 $result_emp_data_array10 = str_replace('",[]','',$result_emp_data_array9);
	 $result_emp_data_array11 = str_replace('",[',',',$result_emp_data_array10);
	 $result_emp_data_array12 = str_replace(']]',']',$result_emp_data_array11);
	
	 $result_emp_data_array13 = str_replace('}"','}',$result_emp_data_array12);
	//echo $result_emp_data_array6;exit;  
	
	die($result_emp_data_array13);

	}
	
if(isset($_POST["get_empl_record"])){

$eid=$_POST["get_empl_record"];


	
$gross_salary_breakup=$_POST["gross_salary_breakup"];

$emp_id = $_POST['get_empl_record'];
$basic = $_POST['basic'];
unset($_POST['get_empl_record']);	
unset($_POST['basic']);	
$decoded_post_data = json_encode($_POST,true);
//echo $decoded_post_data;exit;

	 $update_query="update employee_overall_gross_detail set gross_salary_breakup='$decoded_post_data',mode_of_calculation='manual',calculated_by='$name' where employee_id='$eid'"; 
	//echo $update_query;exit;
	$exe_query=pg_query($db,$update_query);
	
	if($exe_query)
		
	{

		$data = "update";
		
	}
	else
	{
		$data = "error";
	}
	die($data);
} 	







	//echo $result_emp_data_array5; exit;
?>


<!DOCTYPE html>
<html lang="en" >
	<head>
		<meta charset="utf-8" />
		<title>
			View Monthly Pay
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
		<link href="assets/vendors/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
<link rel="shortcut icon" href="favicon.ico" />
<style>
.form-control
{
background-color: #e9ecef;
}

 
</style>		
	</head>
	<?php include "HR-left-header-1.php"; ?>	
	<?php //include "HR-left-header-1.php"; ?>	
		<div class="m-grid__item m-grid__item--fluid m-grid m-grid--ver-desktop m-grid--desktop m-body">
	
			<button class="m-aside-left-close  m-aside-left-close--skin-dark " id="m_aside_left_close_btn">
				<i class="la la-close"></i>
			</button>
			<?php include "left-menu.php"; ?>
			<!-- END: Left Aside -->
			<div class="m-grid__item m-grid__item--fluid m-wrapper">
<div class="m-content">
	
<div class="row">

   	
		<!--begin::Portlet-->
		<div class="m-portlet m-portlet--tab" id="">
			
		<!-- <div class="m-portlet__body new_div">
        <div class="m-content" style="margin-top:-35px;"> -->
	
						
						<div class="m-portlet__head">
								<div class="m-portlet__head-caption">
									<div class="m-portlet__head-title">
										<h3 class="m-portlet__head-text" style="color:#34bfa3;">
											Monthly Pay
									</div>
								</div>
								
								<div class="col-md-4">
													<div class="m-form__group m-form__group--inline">
												
													
														
														<div class="m-form__label">
															<label class="m-label m-label--single">Month:</label>
														</div>
														<div class="m-form__control">
															<select class="form-control m-input select_class" id="m_form_type">
															<option value="">All</option>
															<?php
															
															
												 $query2 = "select distinct eog.month,emsd.current_month from employee_overall_gross_detail as eog inner join employee_monthly_salary_detail as emsd on 
												 emsd.current_month=eog.month order by emsd.current_month asc ";
												 
												/*  echo "select distinct eog.month,emsd.current_month from employee_overall_gross_detail as eog inner join employee_monthly_salary_detail as emsd on 
												 emsd.current_month=eog.month order by asc "; exit; */
												 $result2 = pg_query($db, $query2);

												while($res=pg_fetch_array($result2)){
												 /* echo "<option value='" . $res['month'] ."'>ALL</option>"; */
																	
												echo "<option value='" . $res['month'] . "'>" . $res['month'] . "</option>";
																}
														?>
																															
																
																	
															</select>
															 
														</div>
														</div>
															</div>
														<div class="col-md-4">
														<div class="m-form__label">
															<label class="m-label m-label--single">Year:</label>
														</div>
														<div class="m-form__control">
															<select class="form-control m-input " id="m_form_status">
															<option value="">All</option>
																<?php
																$select="select distinct eog.year,emsd.year from employee_overall_gross_detail as eog inner join employee_monthly_salary_detail as emsd on emsd.year=eog.year";
																
																$result = pg_query($db, $select);

                                                                while($res1=pg_fetch_array($result)){
																/* echo "<option value='" . $res1['year'] ."'>ALL</option>"; */
	
																echo "<option value='" . $res1['year'] . "'>" . $res1['year'] . "</option>";
																}
																	?>
															</select>
															 
														</div>
														
													</div>
													
													
													<div class="d-md-none m--margin-bottom-10"></div>
											
												
												<div class="col-md-2" style="margin-top:10px;">
													<a href="#" data-export="export"  id="export" class="btn  m-btn--air btn-success m-btn" data-toggle="modal" data-target="#it_record">
													<script src="jquery.tabletoCSV.js"></script>

													<span class="excel">GENERATE EXCEL</span> &nbsp <i class="fa fa-download"></i>
													</a>
												</div>
								
								
							</div>
							 
							<?php
						$selectQuery= pg_query($db,"select distinct a.employee_id,a.employee_name,a.basic,a.plan,a.stage,c.gross_total,c.income_tax,c.investments_so_far,c.salary_payout,c.tds_per_month,c.current_month,c.year,c.created_date,c.created_by,c.deduction,c.retirement_benefits->>'Employee_ESI' as employee_esi,c.retirement_benefits->>'Employee_PF' as employee_pf,b.respective_emp_data from employee_overall_gross_detail a INNER  JOIN employee_monthly_salary_detail c on a.employee_id=c.employee_id inner join employee_monthly_attendance_details b on b.employee_id=c.employee_id where a.bid='$bid' and c.bid='$bid' and b.bid='$bid' and a.stage='current'  ");
						
						/*  echo "select distinct a.employee_id,a.employee_name,a.basic,a.plan,a.stage,c.gross_total,c.income_tax,c.investments_so_far,c.salary_payout,c.tds_per_month,c.current_month,c.year,c.created_date,c.created_by,c.deduction,c.retirement_benefits
from employee_overall_gross_detail a 
INNER  JOIN employee_monthly_salary_detail c on a.employee_id=c.employee_id where a.employee_id='116' and a.bid='$bid' and c.bid='$bid' and a.stage='current' ";exit;  */
						
						 while($count_benefits= pg_fetch_array($selectQuery)){
							 //echo $count_benefits;exit;
							 //$deduction=$count_benefits['deduction'];
							 //print_r ($deduction); exit;	
							 $deduction=json_decode($count_benefits['deduction'],true);
								foreach ($deduction as $key => $value)
								{
									$deduction_array[] = $key;
								}
						
							 $deduction_length=sizeof([$deduction]);
							 $retirement_benefits=json_decode($count_benefits['retirement_benefits'],true);
							 foreach($retirement_benefits as $key2=>$value2)
							 {
								 $retirement_benefits_array[] = $key2;
							 }
						 
							 $employee_id=$count_benefits['employee_id'];
							 $employee_name=$count_benefits['employee_name'];
							 $basic=$count_benefits['basic'];
							 $gross_total=$count_benefits['gross_total'];
							 $salary_payout=$count_benefits['salary_payout'];
							 $investments_so_far=$count_benefits['investments_so_far'];
						 }
							
						$deduction_unique_array = array_unique($deduction_array);
						$retirement_benefits_unique_array = array_unique($retirement_benefits_array);
						
							 ?>
						
					
							
							<div class="m-portlet__body">
                            <table class="table m-table" style="width:100%;overflow-x: scroll;">
									<thead style="background-color:#F4F3F8;">									
				 
										<tr>
										<th>EID  </th>
										<th>ENAME </th>
										<th>BASIC  </th>
										<th>Total Earnings</th>
										<th>Net Pay</th>
									
                              
									
	
							 <?php
							 foreach($deduction_unique_array as $key3)
							 {
							 ?>		<th> <?php echo $key3;?></th> 			     													
							             								
								  
									
							   <?php } ?>	
								<th> PF</th>  
								<th> ESI</th>  
                               <th> Investment </th>
                               <th> TDS </th>
                               <th> Worked Days </th>
                               <th> Holidays Present </th>
                               <th> leave </th>
                               <th> Casual leave </th>
                               <th> Earned leave </th>
                               <th> Month </th>
                               <th> Year </th>
                               <th> Computed By</th>
                               <th> Salary Computation</th>
								</tr>
									
								</thead>
								
								<tbody>
					<?php
						$selectQuery= pg_query($db,"select distinct a.employee_id,a.employee_name,a.basic,a.plan,a.stage,c.gross_total,c.income_tax,c.investments_so_far,c.salary_payout,c.tds_per_month,c.current_month,c.year,c.created_date,c.created_by,c.deduction,c.retirement_benefits->>'Employee_ESI' as employee_esi,c.retirement_benefits->>'Employee_PF' as employee_pf,b.respective_emp_data->>'worked_days' as worked_days, b.respective_emp_data->>'holiday_present' as holiday_present,b.respective_emp_data->>'leave' as leave, b.respective_emp_data->>'cl' as cl,b.respective_emp_data->>'el' as el from employee_overall_gross_detail a INNER JOIN employee_monthly_salary_detail c on a.employee_id=c.employee_id inner join employee_monthly_attendance_details b on b.employee_id=c.employee_id where a.bid='10653721' and c.bid='10653721' and b.bid='10653721' and a.stage='current' ");
						
		                   
					        while($count_benefits= pg_fetch_array($selectQuery)){ 
							 
														
						    $deduction=json_decode($count_benefits['deduction'],true);
						
							 $deduction_length=sizeof([$deduction]);
							 $retirement_benefits=json_decode($count_benefits['retirement_benefits'],true);
						 
							 $employee_id=$count_benefits['employee_id'];
							 $employee_name=$count_benefits['employee_name'];
							 $basic=$count_benefits['basic'];
							 $gross_total=$count_benefits['gross_total'];
							 $salary_payout=$count_benefits['salary_payout'];
							 $investments_so_far=$count_benefits['investments_so_far'];
							 $employee_pf=$count_benefits['employee_pf'];
							 $employee_esi=$count_benefits['employee_esi'];
							 $tds_per_month=$count_benefits['tds_per_month'];
							 $worked_days=$count_benefits['worked_days'];
							 $holiday_present=$count_benefits['holiday_present'];
							 $leave=$count_benefits['leave'];
							 $cl=$count_benefits['cl'];
							 $el=$count_benefits['el'];
							 $current_month=$count_benefits['current_month'];
							 $year=$count_benefits['year'];
							 $created_by=$count_benefits['created_by'];
							 $created_date=$count_benefits['created_date'];										
						
							 ?>
							
								
								<tr>
								<td> <?php  echo $employee_id; ?></td>
								<td> <?php  echo $employee_name; ?></td>
								
								<td> <?php  echo $basic; ?></td>
							
								<td> <?php  echo $gross_total; ?></td>
								<td> <?php  echo $salary_payout	; ?></td>
								
								
								<?php 
								
								 foreach($deduction as $key2 => $value){
									 							 
								?> 	
								<td> <?php  echo $value; ?></td>
                                 						
								<?php } ?>
								
                               	
							   																 								
							    
                                   
								
								<td> <?php  echo $employee_pf; ?></td>		
								<td> <?php  echo $employee_esi; ?></td>
								<td> <?php  echo $investments_so_far; ?></td>
								<td> <?php  echo $tds_per_month; ?></td>
								<td> <?php  echo $worked_days; ?></td>
								<td> <?php  echo $holiday_present; ?></td>
								<td> <?php  echo $leave; ?></td>
								<td> <?php  echo $cl; ?></td>
								<td> <?php  echo $el; ?></td>
								<td> <?php  echo $current_month; ?></td>
								<td> <?php  echo $year; ?></td>
								<td> <?php  echo $created_by; ?></td>
								<td> <?php  echo $created_date; ?></td>
																					
								<?php } ?>
								
									
								</tr>	
								</tbody>
							
								
								</table>
							</div>
						
						

						
					</div>
 
     
  
		
		
		</div>	
		<!--end::Portlet--> 
	</div>
	
<!-- </div>
</div> -->
</div>
</div>