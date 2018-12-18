<?php
include "db-connect.php"; 
ini_set('display_errors','0');
//error_reporting(E_ALL);
session_start();
//print_r($_SESSION);exit;
$name= $_SESSION["name"];
$access= $_SESSION["access"];
$id= $_SESSION["id"];
$email= $_SESSION["email"];
$company_name= $_SESSION["company_name"];
$bid= $_SESSION["bid"];
$gid= $_SESSION["gid"];

if($name=='' || $access=='' || $id=='' || $email=='' || $company_name=='' || $bid=='' || $gid==''){header('Location:https://incometax.indiafilings.com/tds_filing/salary/session/session.php');}  


$data=$_GET["month"];
$data1=explode("-",$data);
$month1=$data1[0];
$year1=$data1[1];
 
 
 
$sel_sql="select distinct on (a.employee_id) a.employee_id ,a.employee_name,a.basic,a.salary_breakup,
a.salary_breakup->>'CTC_Per_Month' as CTC_Per_Month ,c.gross_salary_breakup,c.gross_salary_breakup->>'GrossTotal'as total, 
d.deduction ,d.tds_per_month ,d.retirement_benefits,f.respective_emp_data, d.salary_payout,
f.payable_days as payabledays from employee_basic_ctc_details a inner join employee_overall_gross_detail c on 
a.employee_id=c.employee_id AND a.stage=c.stage inner join employee_monthly_salary_detail d on 
c.employee_id=d.employee_id inner join employee_monthly_attendance_detail f on f.gid=d.gid where a.bid='$bid'";
//echo $sel_sql;exit;
$res1=pg_query($db,$sel_sql);
	
//echo $res1;exit;
while($count_benefits=pg_fetch_assoc($res1)){
 

$employee_id = $count_benefits['employee_id'];
$employee_name = $count_benefits['employee_name'];
// echo  $employee_name;exit;
 
//ctc 
$salary_breakup=json_decode($count_benefits['salary_breakup'],true); 
$gross_salary_breakup=json_decode($count_benefits['gross_salary_breakup'],true); 
//echo $gross_salary_breakup;exit;
$total = $count_benefits['total'];
$retirement_benefits=json_decode($count_benefits['retirement_benefits'],true); 
$respective_emp_data=json_decode($count_benefits['respective_emp_data'],true); 
$deduction=json_decode($count_benefits['deduction'],true); 
$salary_payout = $count_benefits['salary_payout'];
$payabledays = $count_benefits['payabledays'];
//echo $payabledays;exit;
	}
	
	
/* $selectQuery= pg_query($db,"select custom_direct_benefits, custom_deductions from deduction_benefits where bid='$bid'");
     
						 while($count_benefits1= pg_fetch_assoc($selectQuery)){
							 
							  
							 $custom_direct_benefits[]=json_decode($count_benefits1['custom_direct_benefits'],true); 
							
							
							 $custom_deductions=json_decode($count_benefits1['custom_deductions'],true); 
							 	 */
							 						 
                             
// print_r($custom_deductions );exit;           
											 
							// $name_component=array_merge($custom_deductions,$custom_direct_benefits);

							?>
	
	
	
<!DOCTYPE html>
<html lang="en" >
	<head>
		<meta charset="utf-8" />
		<title>
			overall details
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
		<link href="assets/vendors/custom/datatables/dataTables.bootstrap.css" rel="stylesheet" type="text/css" />
		<link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
<link rel="shortcut icon" href="favicon.ico" />
<style>

table,
thead,
tr,
tbody,
td,
th {
    text-align: center;
}
body {margin:2em;}
table {margin:0 auto;}
div.bg-success {
	width: 90%;
	height: 46px;
	padding: 5px 10px 5px 32px;
	border-radius: 10px;
	margin: 0px auto 10px auto;
}
.bt-text {margin-left:16px;}

</style>		
	<!--</head>-->
<!--	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Export Html to Excel</title>
    <script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
    <script type="text/javascript">
        var tableToExcel = (function () {
            var uri = 'data:application/vnd.ms-excel;base64,'
                , template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]</head><body><table>{table}</table></body></html>'
                , base64 = function (s) { return window.btoa(unescape(encodeURIComponent(s))) }
                , format = function (s, c) { return s.replace(/{(\w+)}/g, function (m, p) { return c[p]; }) }
            return function (table, name) {
                if (!table.nodeType) table = document.getElementById(table)
                var ctx = { worksheet: name || 'Worksheet', table: table.innerHTML }
                var blob = new Blob([format(template, ctx)]);
                var blobURL = window.URL.createObjectURL(blob);

                if (ifIE()) {
                    csvData = table.innerHTML;
                    if (window.navigator.msSaveBlob) {
                        var blob = new Blob([format(template, ctx)], {
                            type: "text/html"
                        });
                        navigator.msSaveBlob(blob, '' + name + '.xls');
                    }
                }
                else
                window.location.href = uri + base64(format(template, ctx))
            }
        })()

        function ifIE() {
            var isIE11 = navigator.userAgent.indexOf(".NET CLR") > -1;
            var isIE11orLess = isIE11 || navigator.appVersion.indexOf("MSIE") != -1;
            return isIE11orLess;
        }
    </script>
</head>-->
	<?php include "HR-left-header-1.php"; ?>	
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
										                       	Overall Details
									</div>
								</div>
	
							
								<div class="col-md-3" style="padding-right: 455px;margin-top: -14px;">
													<div class="m-form__group m-form__group--inline">
												
													
														
														<div class="m-form__label">
															<label class="m-label m-label--single">Month:</label>
														</div>
														<div class="m-form__control">
															<select class="form-control m-input select_class" id="m_form_type">
															<option value="">All</option>
															<?php
															
															
												 $query2 = " select distinct a.month,b.current_month from employee_overall_gross_detail as a inner join employee_monthly_salary_detail as b on 
												 b.current_month=a.month order by b.current_month asc";
												
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
															
													<div class="col-md-3" style="padding-right: 455px;margin-top: -14px;">
														<div class="m-form__label">
															<label>Year:</label>
														</div>
														<div class="m-form__control">
															<select class="form-control m-input " id="m_form_status">
															<option value="">All</option>
																<?php
																$select="select distinct a.year,b.year from employee_overall_gross_detail as a inner join employee_monthly_salary_detail as b on b.year=a.year";
																
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
							
												<!--<div class="col-md-2" style="margin-top:20px;">
													<a href="#" data-export="export"  id="export" class="btn  m-btn--air btn-success m-btn" data-toggle="modal" data-target="#it_record">
													<script src="jquery.tabletoCSV.js"></script>

													<span class="excel">GENERATE EXCEL</span> &nbsp <i class="fa fa-download"></i>
													</a>
												</div>-->
								<!--<input type="button" onclick="tableToExcel('testTable', 'Export HTML Table to Excel')" value="Export to Excel" />-->
							          	</div>
								
							
							
							<div class="m-portlet__body" style='overflow:auto;'>
               <table class="table table-striped- table-bordered table-hover table-checkable" id="example" style="width:100%;overflow-x: scroll;">
							 
									<thead style="background-color:#F4F3F8;">	
									<tr>
									   <th colspan="2"></th>
								    <th colspan="8" style="text-align: center">CTC</th>
										<th colspan="7">Gross Earnings</th>
										<th colspan="5" style="text-align: center" >Deduction</th>
										<th colspan="">ATTENDANCE</th>
										<th colspan="9" >Total</th>
										</tr>
										
									<tr>
                    <th>Employee ID</th>
									  <th>Employee Name</th>
									  <th>Basic</th>
									

							<?php
							
							unset($salary_breakup['CTC_grand_total']);
							unset($salary_breakup['Gratuity']);
							unset($salary_breakup['SuperAnnuation']);
							unset($salary_breakup['FoodSubsidy']);
							unset($salary_breakup['LoanSubsidy']);
							unset($salary_breakup['CTC_Per_Month']);
							unset($salary_breakup['MedicalInsurance']);
							unset($salary_breakup['GradePay']);
							unset($salary_breakup['ESI_total']);
							
							foreach($salary_breakup as $key1 => $value){ 
						
					//	print_r ($salary_breakup);
						//print_r($salary_breakup);exit;
										?>
											
										<th>
										
										<?php echo $key1;?>
										
									</th>
								 <?php } ?>
						
								 
								 
						<th> Total </th>
						
						<?php
						
						  unset($gross_salary_breakup['GrossTotal']);
						  unset($gross_salary_breakup['Employer_ESI']);
							unset($gross_salary_breakup['Employee_ESI']);
						  unset($gross_salary_breakup['Employer_PF']);
					    unset($gross_salary_breakup['Gratuity_Monthly']);
							unset($gross_salary_breakup['Gratuity_Cumulative']);
							unset($gross_salary_breakup['SuperAnnuation_monthly']);
						  unset($gross_salary_breakup['Employee_PF']);
						  unset($gross_salary_breakup['GradePay']); 
										
						 foreach($gross_salary_breakup as $key2 => $value11){ 
						
							//print_r($gross_salary_breakup);exit;
							//print_r($key2);exit;
							//print_r($value11);exit;
							//echo $gross_salary_breakup;exit;
							//echo $key2;exit;
							//echo $value11;exit;
							//print_r ($key2);
							
										?>
										<th>
										<?php echo $key2;?>
									
									</th>
									
									
									<?php } ?>
							<th> Total </th>
						<?php
							
						     foreach($custom_deductions as $key => $value){ 
											// echo $custom_deductions[$key]['name'];  
											 echo "<th>".($custom_deductions[$key]['name'])."</th>";  
											 } ?> 
						
						
						<th> TDS </th>
						
						
						<?php
						
						
  
						//	unset($retirement_benefits['Employee_ESI']);
						 unset($retirement_benefits['Employer_ESI']);
							unset($retirement_benefits['PT']);
							unset($retirement_benefits['Employer_PF']);	
							unset($retirement_benefits['Gratuity_Monthly']);
							unset($retirement_benefits['VPF']);
							unset($retirement_benefits['SuperAnnuation_monthly']);
							unset($retirement_benefits['Gratuity_Cumulative']);
							
							
							
							
						     foreach($retirement_benefits as $key4=> $value13){ 
							//print_r($retirements_benefits);exit;
										?>
										<th>
										<?php echo $key4;?>
										
									</th>
									<?php } ?>
					
						
					<?php
							
	unset($respective_emp_data['worked_hrs']);
	unset($respective_emp_data['force']);
	unset($respective_emp_data['designation']);
	unset($respective_emp_data['earily']);
	unset($respective_emp_data['Onduty']);
	unset($respective_emp_data['employee_id']);
	unset($respective_emp_data['nightshift_allowance']);
	unset($respective_emp_data['date_from']);
	unset($respective_emp_data['holiday_compoff']);
	unset($respective_emp_data['late']);
	unset($respective_emp_data['gid']);
	unset($respective_emp_data['paidleave']);
	unset($respective_emp_data['department']);
	unset($respective_emp_data['holiday']);
	unset($respective_emp_data['email']);
	unset($respective_emp_data['working_day']);
	unset($respective_emp_data['permission']);
	unset($respective_emp_data['date_to']);
	unset($respective_emp_data['payable_day']);
	unset($respective_emp_data['doj']);
	unset($respective_emp_data['absent']);
	unset($respective_emp_data['force_day']);
	unset($respective_emp_data['name']);
	unset($respective_emp_data['ontime']);
	unset($respective_emp_data['sl']);
							
						     foreach($respective_emp_data as $key6=> $value15){ 
							//print_r($key6);exit;
										?>
										<th>
										
										<?php echo $key6;?>
										
									</th>
									
									
									<?php } ?>
							<th>TakeHOME</th>
							<th>payable_days</th>
						
								</tr>	  
			</thead>
			<tbody>		
  		
			<?php
				
$selectQuery= pg_query("select distinct on (a.employee_id) a.employee_id ,a.employee_name,a.basic,a.salary_breakup,
a.salary_breakup->>'CTC_Per_Month' as CTC_Per_Month ,c.gross_salary_breakup,c.gross_salary_breakup->>'GrossTotal'as total, 
d.deduction ,d.tds_per_month ,d.retirement_benefits,f.respective_emp_data, d.salary_payout,
f.payable_days as payabledays from employee_basic_ctc_details a inner join employee_overall_gross_detail c on 
a.employee_id=c.employee_id AND a.stage=c.stage inner join employee_monthly_salary_detail d on 
c.employee_id=d.employee_id inner join employee_monthly_attendance_detail f on f.gid=d.gid where a.bid='$bid'"); 
//echo $selectQuery;exit;
 /* echo "select distinct on (a.employee_id) a.employee_id ,a.employee_name,a.basic,a.salary_breakup,
a.salary_breakup->>'CTC_Per_Month' as CTC_Per_Month ,c.gross_salary_breakup,c.gross_salary_breakup->>'GrossTotal'as total, 
d.deduction ,d.tds_per_month ,d.retirement_benefits,f.respective_emp_data, d.salary_payout,
f.payable_days as payabledays from employee_basic_ctc_details a inner join employee_overall_gross_detail c on 
a.employee_id=c.employee_id AND a.stage=c.stage inner join employee_monthly_salary_detail d on 
c.employee_id=d.employee_id inner join employee_monthly_attendance_detail f on f.gid=d.gid where a.bid='$bid'";exit;   */
	 
while($count_benefits=pg_fetch_assoc($selectQuery)){
//print_r(  $count_benefits);exit
$employee_id = $count_benefits['employee_id'];
$employee_name = $count_benefits['employee_name'];
$basic = $count_benefits['basic'];
$ctc_per_month = $count_benefits['ctc_per_month'];
$salary_breakup=json_decode($count_benefits['salary_breakup'],true); 
//$basic = $count_benefits['basic'];

//echo $grosstotal;exit;
$gross_salary_breakup=json_decode($count_benefits['gross_salary_breakup'],true); 
//echo $gross_salary_breakup;exit;
$total = $count_benefits['total'];
//$deduction=json_decode($count_benefits['deduction'],true); 
 //$deduction_length=sizeof([$deduction]);
//echo $deduction;exit;
$retirement_benefits=json_decode($count_benefits['retirement_benefits'],true); 
$respective_emp_data=json_decode($count_benefits['respective_emp_data'],true); 
$tds_per_month = $count_benefits['tds_per_month'];
//echo $tds_per_month;exit;
$salary_payout = $count_benefits['salary_payout'];
$payabledays = $count_benefits['payabledays'];
									?> 
									 <tr>									
										<td>  <?php echo $employee_id;?></td>
										<td>  <?php echo $employee_name;?></td> 
										<td>  <?php echo $basic;?></td> 
									
										<?php
						  unset($salary_breakup['CTC_grand_total']);
							unset($salary_breakup['Gratuity']);
							unset($salary_breakup['SuperAnnuation']);
							unset($salary_breakup['FoodSubsidy']);
							unset($salary_breakup['LoanSubsidy']);
							unset($salary_breakup['CTC_Per_Month']);
							unset($salary_breakup['MedicalInsurance']);
							unset($salary_breakup['GradePay']);
							unset($salary_breakup['ESI_total']);				
							foreach($salary_breakup as $key1 => $value){
												
								//print_r ($salary_breakup); exit;
									?> 
								<!--<td><?php echo $value; ?></td>-->
										<td>
										<?php
                if($value=="")
								{
								echo "0";
								}
								else{
									echo $value;
								}
								?>
									</td>
							
								
								<?php } ?> 
								
										
<td> <?php  echo $ctc_per_month; ?></td> 

										 
										<?php
										
				
						  unset($gross_salary_breakup['GrossTotal']);
						  unset($gross_salary_breakup['Employer_ESI']);
							unset($gross_salary_breakup['Employee_ESI']);
						  unset($gross_salary_breakup['Employer_PF']);
					    unset($gross_salary_breakup['Gratuity_Monthly']);
							unset($gross_salary_breakup['Gratuity_Cumulative']);
							unset($gross_salary_breakup['SuperAnnuation_monthly']);
						  unset($gross_salary_breakup['Employee_PF']);
						  unset($gross_salary_breakup['GradePay']);    
										 // if($gross_salary_breakup['Employee_ESI']==''){echo "<td>0</td>";}
								    foreach($gross_salary_breakup as $key2 => $value11){
										
								
									?> 
							
									<td><?php
                                if($value11=="")
								{
								echo "0";
								}
								else{
									echo $value11;
								}
								?></td> 
						 
									
									

								
								<?php } ?>
					<td> <?php  echo $total; ?></td> 
					
					
					
<?php
							
						     foreach($deduction as $key => $value25){ 
											 //print_r($deduction);exit;  
											 echo "<td>".($deduction[key])."</td>";  
											 } ?> 
						 
									
									
									
									

								<td> <?php  echo $tds_per_month; ?></td> 
								
								
								
								<?php
							
						  unset($retirement_benefits['Employer_ESI']);
							unset($retirement_benefits['PT']);
							unset($retirement_benefits['Employer_PF']);	
							unset($retirement_benefits['Gratuity_Monthly']);
							unset($retirement_benefits['VPF']);
							unset($retirement_benefits['SuperAnnuation_monthly']);
							unset($retirement_benefits['Gratuity_Cumulative']);
							
							
							
						     foreach($retirement_benefits as $key4=> $value13) {
							 
								 //print_r( $key4);
					?>
											
										<td>
										<?php
               if($value13=="")
								{
								echo "0";
								}
								else{
									echo $value13;
								}
								?>
									</td>
									
									<?php } ?>
						
	<?php						
							
	unset($respective_emp_data['worked_hrs']);
	unset($respective_emp_data['force']);
	unset($respective_emp_data['designation']);
	unset($respective_emp_data['earily']);
	unset($respective_emp_data['Onduty']);
	unset($respective_emp_data['employee_id']);
	unset($respective_emp_data['nightshift_allowance']);
	unset($respective_emp_data['date_from']);
	unset($respective_emp_data['holiday_compoff']);
	unset($respective_emp_data['late']);
	unset($respective_emp_data['gid']);
	unset($respective_emp_data['paidleave']);
	unset($respective_emp_data['department']);
	unset($respective_emp_data['holiday']);
	unset($respective_emp_data['email']);
	unset($respective_emp_data['working_day']);
	unset($respective_emp_data['permission']);
	unset($respective_emp_data['date_to']);
	unset($respective_emp_data['payable_day']);
	unset($respective_emp_data['doj']);
	unset($respective_emp_data['absent']);
	unset($respective_emp_data['force_day']);
	unset($respective_emp_data['name']);
	unset($respective_emp_data['ontime']);
	unset($respective_emp_data['sl']);
							
foreach($respective_emp_data as $key6=> $value15){ 
							//print_r($gross_salary_breakup);exit;
										?>
									<!--<td><?php echo $value15; ?></td>-->
										<td>
										<?php
               if($value15=="")
								{
								echo "0";
								}
								else{
									echo $value15;
									//print_r $value15;exit;
									
								}
								?>
									</td>
									<?php } ?>
									
								<td><?php echo $salary_payout;?></td> 
								<td> <?php echo $payabledays;?></td> 	
								
							 </tr>		
										
				<?php  }?> 
			         			
</tbody>
</table>

</div>
</div>
 </div>	
</div>
	
<!-- </div>
</div> -->
</div>
</div> 

<?php  //include "master_view.php"; ?>		
<?php //include "footer.php"; ?>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.18/datatables.min.css"/>
 
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.18/datatables.min.js"></script>
		
<script src="assets/demo/default/custom/crud/forms/widgets/select2.js" type="text/javascript"></script> 
<script src="assets/demo/datatable/dataTables.bootstrap.js" type="text/javascript"></script>   
<script src="assets/demo/datatable/jquery.dataTables.js" type="text/javascript"></script>   

<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places&key=AIzaSyANqJobRvzfZ-hl4nUcKSCWTCrPOeo4diQ" type="text/javascript"></script>
<script src="jquery.tabletoCSV.js"></script>
<script src="assets/demo/default/custom/crud/forms/widgets/select2.js" type="text/javascript"></script>   
<!--<script src="/itr-decider/main-itr-decider/main-page.php?workidauthjs=1.0" type="text/javascript"></script>-->
<script src="https://code.jquery.com/jquery-3.3.1.js" type="text/javascript"></script> 
	<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js" type="text/javascript"></script> 
	<script src="https://cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js" type="text/javascript"></script> 
	<script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.flash.min.js" type="text/javascript"></script> 
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js" type="text/javascript"></script> 
	<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js" type="text/javascript"></script> 
	<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js" type="text/javascript"></script> 
	<script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.html5.min.js" type="text/javascript"></script> 
	<script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.print.min.js" type="text/javascript"></script> 
<script>
  $(function () {

        var localTable =$('#example').dataTable({
            "bProcessing": true,
            "iDisplayLength": 10,
			"spaging": false,
		   "sPaginationType": "full_numbers",
			  "aLengthMenu": [ [10, 25, 50, -1], [10, 25, 50, "All"] ],
           "scrollY": 200,
        "scrollX": true
           
             
        }); 
		
	  /* 	$(document).ready(function() {
  $('#gross').DataTable( {
        "scrollY": 200,
        "scrollX": true
    } );
} ); */
var localTable;
      localTable = $('#example').dataTable();

      $('#m_form_type').change( function() { 
            localTable.fnFilter( $(this).val() ); 
       });
	   
	   $('#m_form_status').change( function() { 
            localTable.fnFilter( $(this).val() ); 
       });
    });

$(document).ready(function() {
    $('#example').DataTable( {
        dom: 'Bfrtip',
        buttons: [
            'copyHtml5',
            'excelHtml5',
            'csvHtml5',
            'pdfHtml5'
        ]
    } );
} );


</script>
</body>
<!-- end::Body -->
</html>
							