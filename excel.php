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
 
 
 
$sel_sql="select distinct on (a.employee_id) a.employee_id ,a.employee_name, a.basic ,a.salary_breakup->>'Hra' as hra,
a.salary_breakup->>'ConveyanceAllowance' as ca,a.salary_breakup->>'MedicalAllowance' as ma,
a.salary_breakup->>'SpecialAllowance' as sa, a.salary_breakup ->>'ESI' as esi, a.salary_breakup ->>'PF' as pff, 
a.salary_breakup->>'CTC_Per_Month' as total, c.basic as basic_gross, c.gross_salary_breakup->>'Hra' as hraa, 
c.gross_salary_breakup ->>'ConveyanceAllowance'as caa, c.gross_salary_breakup ->>'MedicalAllowance' as maa, 
c.gross_salary_breakup ->>'SpecialAllowance' as saa, c.gross_salary_breakup ->>'Employee_ESI' as esi, 
c.gross_salary_breakup ->>'Employee_PF' as pfff, c.gross_salary_breakup ->>'GrossTotal' as totall, 
d.deduction ->> 'SECURITY-DEPOSIT' as sd ,d.retirement_benefits->>'Employee_PF'as pf , 
d. retirement_benefits->> 'Employee_ESI' as esii , d.tds_per_month as tds, d.deduction ->> 'FOOD-DEDUCTION' as food,
f.respective_emp_data->>'worked_days' as workeddays, f.respective_emp_data->>'holiday_present' as holidaypresent,
f.respective_emp_data->>'leave' as leave, f.respective_emp_data->>'cl' as casualleave , 
f.respective_emp_data->>'earily' as earnedleave , d.salary_payout as takehome ,
f.payable_days as payabledays from employee_basic_ctc_details a inner join employee_overall_gross_detail c on 
a.employee_id=c.employee_id AND a.stage=c.stage inner join employee_monthly_salary_detail d on c.month=d.current_month inner 
join employee_monthly_attendance_detail f on f.gid=d.gid where a.bid='$bid'";

  //echo $sel_sql;exit;
	$res1=pg_query($db,$sel_sql);
	
	//echo $res1;exit;
  while($count_benefits=pg_fetch_assoc($res1)){
 	//echo $count_benefits;exit;
 
/*over all*/
 $employee_id = $count_benefits['employee_id'];
//echo  $employee_id; 
 $employee_name = $count_benefits['employee_name'];
 
// echo  $employee_name;exit;
 
 //ctc 
 
 $basic = $count_benefits['basic'];
 $hra = $count_benefits['hra'];
 $ca = $count_benefits['ca'];
 $ma = $count_benefits[' ma'];
 $sa = $count_benefits['sa'];
  $esi = $count_benefits['esi'];
  $pff = $count_benefits['pff'];
 $total = $count_benefits['total'];
 //gross
 $basic_gross = $count_benefits['basic_gross'];
 $hraa = $count_benefits['hraa'];
 $caa = $count_benefits['caa'];
  $maa = $count_benefits['maa'];
  $saa = $count_benefits['saa'];
	 $esii = $count_benefits['esii'];
  $pfff = $count_benefits['pfff'];
	
  $totall = $count_benefits['totall'];
	//deduction
	 $sd = $count_benefits['sd'];
	 $pf = $count_benefits['pf'];
	 $esi = $count_benefits['esi'];
	 $tds = $count_benefits['tds'];
	 $food = $count_benefits['food'];	
	 
	/*attance */ 
	  $workeddays = $count_benefits['workeddays'];	
	  $holidaypresent = $count_benefits['holidaypresent'];	
	  $leave = $count_benefits['leave'];	
	  $casualleave = $count_benefits['casualleave'];	
		$earnedleave = $count_benefits['earnedleave'];	
//total	 
	  $takehome = $count_benefits['takehome'];
		$payabledays = $count_benefits['payabledays'];

	}


	?>
	
	
	
	
<!DOCTYPE html>
<html lang="en" >
	<head>
		<meta charset="utf-8" />
		<title>
			Ovarall Detail
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
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

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
		
							
							
								<div class="col-md-4" style="padding-right: 455px;margin-top: -14px;">
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
															
													<div class="col-md-4" style="padding-right: 455px;margin-top: -14px;">
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
								<input type="button" onclick="tableToExcel('testTable', 'Export HTML Table to Excel')" value="Export to Excel" />
							          	</div>
								
							
							
							<div class="m-portlet__body" style='overflow:auto;'>
               <table class="table table-striped- table-bordered table-hover table-checkable" id="testTable" style="width:100%;overflow-x: scroll;">
									<thead>									
				 
										<tr>
										<th colspan="2"></th>
								    <th colspan="8" style="text-align: center">CTC</th>
										<th colspan="8" style="text-align: center" >Gross Earnings</th>
										<th colspan="5" style="text-align: center" >Deduction</th>
										<th colspan="5" style="text-align: center" >ATTENDANCE</th>
										<th colspan="2" style="text-align: center" >Total</th>
										</tr>
                    <tr>
                    <th style="background-color:#ded0e8">Employee_id  </th>
									  <th style="background-color:#ded0e8"> Employee_Name</th>
										
									  <th style="background-color:#83c3b6">BASIC</th>
										<th style="background-color:#83c3b6">HOUSE RENT ALLOWANCE</th>
										<th style="background-color:#83c3b6">CONVEYENCE ALLOWANCE </th>
										<th style="background-color:#83c3b6">MEDICAL ALLOWANCE</th>
										<th style="background-color:#83c3b6">SPECIAL ALLOWANCE</th>
										<th style="background-color:#83c3b6">EMPLOYER ESI</th>
										<th style="background-color:#83c3b6">EMPLOYER PF</th>
									  <th style="background-color:#83c3b6"> TOTAL</th>
										
										<th style="background-color:#b97782">BASIC</th>
										<th style="background-color:#b97782">HOUSE RENT ALLOWANCE</th>
										<th style="background-color:#b97782">CONVEYENCE ALLOWANCE </th>
										<th style="background-color:#b97782">MEDICAL ALLOWANCE</th>
										<th style="background-color:#b97782">SPECIAL ALLOWANCE</th>
										<th style="background-color:#b97782">EMPLOYEE ESI</th>
										<th style="background-color:#b97782">EMPLOYEE PF</th>
									  <th style="background-color:#b97782"> TOTAL</th>
										
										<th style="background-color:#007bff75"> SD</th>
										<th style="background-color:#007bff75"> PF</th>
										<th style="background-color:#007bff75"> ESI</th>
										<th style="background-color:#007bff75"> TDS</th>
										<th style="background-color:#007bff75"> FOOD ALLOWANCE</th>
										
										<th style="background-color:#6c757d85"> WORKED DAYS</th>
										<th style="background-color:#6c757d85"> HOLIDAYS</th>
										<th style="background-color:#6c757d85"> LEAVE</th>
										<th style="background-color:#6c757d85"> CASUAL LEAVE</th>
										<th style="background-color:#6c757d85"> EARNED LEAVE</th>
										
										
										
										<th style="background-color:#5867dd9e">TAKE HOME</th>
										<th style="background-color:#5867dd9e">PAYABLE</th>
										
										</tr>
								</thead>
			<tbody>		
                              
	<?php								
	
			?>	
							
									
								
							<?php 
		$sel_sql="select distinct on (a.employee_id) a.employee_id ,a.employee_name, a.basic ,a.salary_breakup->>'Hra' as hra,
a.salary_breakup->>'ConveyanceAllowance' as ca,a.salary_breakup->>'MedicalAllowance' as ma,
a.salary_breakup->>'SpecialAllowance' as sa, a.salary_breakup ->>'ESI' as esi, a.salary_breakup ->>'PF' as pff, 
a.salary_breakup->>'CTC_Per_Month' as total, c.basic as basic_gross, c.gross_salary_breakup->>'Hra' as hraa, 
c.gross_salary_breakup ->>'ConveyanceAllowance'as caa, c.gross_salary_breakup ->>'MedicalAllowance' as maa, 
c.gross_salary_breakup ->>'SpecialAllowance' as saa, c.gross_salary_breakup ->>'Employee_ESI' as esi, 
c.gross_salary_breakup ->>'Employee_PF' as pfff, c.gross_salary_breakup ->>'GrossTotal' as totall, 
d.deduction ->> 'SECURITY-DEPOSIT' as sd ,d.retirement_benefits->>'Employee_PF'as pf , 
d. retirement_benefits->> 'Employee_ESI' as esii , d.tds_per_month as tds, d.deduction ->> 'FOOD-DEDUCTION' as food,
f.respective_emp_data->>'worked_days' as workeddays, f.respective_emp_data->>'holiday_present' as holidaypresent,
f.respective_emp_data->>'leave' as leave, f.respective_emp_data->>'cl' as casualleave , 
f.respective_emp_data->>'earily' as earnedleave , d.salary_payout as takehome ,
f.payable_days as payabledays from employee_basic_ctc_details a inner join employee_overall_gross_detail c on 
a.employee_id=c.employee_id AND a.stage=c.stage inner join employee_monthly_salary_detail d on c.month=d.current_month inner 
join employee_monthly_attendance_detail f on f.gid=d.gid where a.bid='$bid' 
";



	
	$res1=pg_query($db,$sel_sql);
	//echo $res1;exit;
  while($count_benefits=pg_fetch_array($res1)){
 //echo $count_benefits;
 
/*over all*/
 $employee_id = $count_benefits['employee_id'];
//echo  $employee_id; exit;
 $employee_name = $count_benefits['employee_name'];
 //ctc 
 
  
 //ctc 
 
 $basic = $count_benefits['basic'];
 $hra = $count_benefits['hra'];
 $ca = $count_benefits['ca'];
 $ma = $count_benefits['ma'];
 $sa = $count_benefits['sa'];
  $esi = $count_benefits['esi'];
  $pff = $count_benefits['pff'];
 $total = $count_benefits['total'];
 //gross
 $basic_gross = $count_benefits['basic_gross'];
 $hraa = $count_benefits['hraa'];
 $caa = $count_benefits['caa'];
 $maa = $count_benefits['maa'];
 $saa = $count_benefits['saa'];
 $esii = $count_benefits['esii'];
 $pfff = $count_benefits['pfff'];
	
  $totall = $count_benefits['totall'];
	//deduction
	 $sd = $count_benefits['sd'];
	 $pf = $count_benefits['pf'];
	 $esi = $count_benefits['esii'];
	 $tds = $count_benefits['tds'];
	 $food = $count_benefits['food'];	
	 
	/*attance */ 
	  $workeddays = $count_benefits['workeddays'];	
	  $holidaypresent = $count_benefits['holidaypresent'];	
	  $leave = $count_benefits['leave'];	
	  $casualleave = $count_benefits['casualleave'];	
		$earnedleave = $count_benefits['earnedleave'];	
//total	 
	  $takehome = $count_benefits['takehome'];
		$payabledays = $count_benefits['payabledays'];
	
		   ?>
  <tr>
<td style="background-color:#ded0e8">
<?php echo $employee_id; ?></td>	
<td style="background-color:#ded0e8"><?php echo $employee_name; ?></td>

<td style="background-color:#83c3b6" ><?php echo $basic;  ?></td>
<td style="background-color:#83c3b6"><?php echo $hra; ?></td> 
<td style="background-color:#83c3b6"><?php echo $ca; ?></td>
<td style="background-color:#83c3b6"><?php echo $ma; ?></td>
<td style="background-color:#83c3b6"><?php echo $sa; ?></td>
<!--<td><?php echo $esi; ?></td>-->

<td style="background-color:#83c3b6"> <?php
                                if($esi!=null)
								{
								echo $esi;
								}
								else{
									echo "0";
								}
								?></td> 
								
<td style="background-color:#83c3b6"><?php echo $pff; ?></td>
<td style="background-color:#83c3b6"><?php echo $total; ?></td>
	
<td style="background-color:#b97782"><?php echo $basic_gross; ?></td>
<!--<td><?php echo $hraa; ?></td>-->
<td style="background-color:#b97782"><?php
                                if($hraa!=null)
								{
								echo $hraa;
								}
								else{
									echo "0";
								}
								?></td> 
								

<!--<td><?php echo $caa; ?></td>-->
<td style="background-color:#b97782"><?php
                                if($caa!=null)
								{
								echo $caa;
								}
								else{
									echo "0";
								}
								?></td> 

<!--<td><?php echo $maa; ?></td>		-->
<td style="background-color:#b97782"><?php
                                if($maa!=null)
								{
								echo $maa;
								}
								else{
									echo "0";
								}
								?></td> 




<!--<td><?php echo $saa; ?></td>-->
<td style="background-color:#b97782"><?php
                                if($saa!=null)
								{
								echo $saa;
								}
								else{
									echo "0";
								}
								?></td> 



<!--<td><?php echo $esii; ?></td>	-->
<td style="background-color:#b97782"><?php
                                if($esii!=null)
								{
								echo $esii;
								}
								else{
									echo "0";
								}
								?></td> 

	
<!--<td><?php echo $pfff; ?></td>-->
<td style="background-color:#b97782"><?php
                                if($pfff!=null)
								{
								echo $pfff;
								}
								else{
									echo "0";
								}
								?></td> 



<!--<td><?php echo $totall; ?></td>-->
<td style="background-color:#b97782"><?php
                                if($totall!=null)
								{
								echo $totall;
								}
								else{
									echo "0";
								}
								?></td> 




<!--<td><?php echo $sd; ?></td>-->

<td  style="background-color:#007bff75"><?php
                                if($sd!=null)
								{
								echo $sd;
								}
								else{
									echo "0";
								}
								?></td> 






<!--<td><?php echo $pf; ?></td>-->


<td style="background-color:#007bff75"><?php
                                if($pf!=null)
								{
								echo $pf;
								}
								else{
									echo "0";
								}
								?></td> 



<!--<td><?php echo $esi; ?></td>-->



<td style="background-color:#007bff75"><?php
                                if($esi!=null)
								{
								echo $esi;
								}
								else{
									echo "0";
								}
								?></td> 



<td style="background-color:#007bff75"><?php echo $tds; ?></td>

<!--<td><?php echo $food; ?></td>-->

<td style="background-color:#007bff75"><?php
                                if($food!=null)
								{
								echo $food;
								}
								else{
									echo "0";
								}
								?></td> 



<td style="background-color:#6c757d85"><?php echo $workeddays; ?></td>
<td style="background-color:#6c757d85"><?php echo $holidaypresent; ?></td>
<td style="background-color:#6c757d85"><?php echo $leave; ?></td>
<td style="background-color:#6c757d85"><?php echo $casualleave; ?></td>
<td style="background-color:#6c757d85"><?php echo $earnedleave; ?></td>

<td style="background-color:#5867dd9e"><?php echo $takehome; ?></td>
<td style="background-color:#5867dd9e"><?php echo $payabledays; ?></td>


											
					</tr>
						<?php }  ?>
						
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
<script src="assets/demo/default/custom/crud/forms/widgets/select2.js" type="text/javascript"></script>   
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

        var localTable =$('#testTable').dataTable({
            "bProcessing": true,
            "iDisplayLength": 10,
			"spaging": false,
		   "sPaginationType": "full_numbers",
			  "aLengthMenu": [ [10, 25, 50, -1], [10, 25, 50, "All"] ],
           
           
             
        });
var localTable;
      localTable = $('#testTable').dataTable();

      $('#m_form_type').change( function() { 
            localTable.fnFilter( $(this).val() ); 
       });
	   
	   $('#m_form_status').change( function() { 
            localTable.fnFilter( $(this).val() ); 
       });
    });


/* $("#export").click(function(){
  $("#testTable").tableToCSV();
   //filename: 'table.csv'
});
 */
//excel

$(document).ready(function() {
    $('#testTable').DataTable( {
        "scrollY": 50,
        "scrollX": true
    } );
} );

/* $(document).ready(function() { */
    /* $('#testTable').DataTable({ */
        //dom: 'Bfrtip',
		 //paging: false,
    //searching: false
       // buttons: [{
			//'copy',
           // { extend: 'excel', text: 'Save as Excel' },
            // extend: 'excelHtml5',
            // customize: function(xlsx) {
                // var sheet = xlsx.xl.worksheets['sheet1.xml'];
 
               // Loop over the cells in column `C`
				 // $('row c', sheet).each( function () {
                    //Get the value
					
                    
                        // $(this).attr( 's', '7' );
                    
					
                // });
				 // $('row c[r^="B"]', sheet).each( function () {
                    
                    
                        // $(this).attr( 's', '20' );
                    
					
                // });
				 // $('row c[r^="C"]', sheet).each( function () {
                    
					
                    
                        // $(this).attr( 's', '30' );
                    
					
                // });
                // $('row:eq(NO) c[r^="D"]', sheet).each( function () {
                   
					
                    
                        // $(this).attr( 's', '10' );
                    
					
                // });
				// $('row c[r^="E"]', sheet).each( function () {
                    
					
					
                        // $(this).attr( 's', '50' );
                    
                // });
				 // $('row c[r^="F"]', sheet).each( function () {
                   
					
                    
                        // $(this).attr( 's', '60' );
                    
					
                // });
            // }
        // }]
    // });
// });

</script>
	</body>
<!-- end::Body -->
</html>
							