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

/* SELECT id, gid, bid, employee_id, employee_name, employee_category, financial_year, month, year, earnings_in_current_fy, incometax_deduction_current_fy, organization, stage, created_by, created_date
	FROM public.prior_earnings;
 */
 
  
/*  $sel_sql="select  a.employee_id,  a.employee_name,a.gross_salary_breakup->>'Employee_PF' as pf, a.gross_salary_breakup->>'Employer_PF' as pff, c.vpf_amount ,a.month,a.year from employee_overall_gross_detail a FULL JOIN vpf_percentage c on a.employee_id=c.employee_id where a.bid='$bid' AND a.gross_salary_breakup->>'Employee_PF'!='0' AND a.gross_salary_breakup->>'Employer_PF'!='0' 

"; */

								
  
								$sel_sql1=pg_query($db,"select  employee_id, employee_name,earnings_in_current_fy,month FROM public.prior_earnings where employee_id='1'");
								//echo "select employee_id, employee_name,earnings_in_current_fy FROM public.prior_earnings";exit;
								 //$row=pg_fetch_array($sel_sql);
								 
								  

								
							
								
								
								 


//print_r ($trimmed); exit;
 
?>
<!DOCTYPE html>
<html lang="en" >
	<head>
		<meta charset="utf-8" />
		<title>
			ConqHR | Master Salary
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
</head>
	<?php include "HR-left-header-1.php"; ?>	
		<div class="m-grid__item m-grid__item--fluid m-grid m-grid--ver-desktop m-grid--desktop m-body">
	
			<button class="m-aside-left-close  m-aside-left-close--skin-dark " id="m_aside_left_close_btn">
				<i class="la la-close"></i>
			</button>
			<?php include "left-menu.php"; ?>
			<!-- END: Left Aside -->
			<div class="m-grid__item m-grid__item--fluid m-wrapper">
			<div class="modal fade" id="view_breakup" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
			
							<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
							
								<div class="modal-content">
								<div class="modal-header">
										<h5 class="modal-title" id="exampleModalLabel">
											
										</h5>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">
												×
											</span>
										</button>
									</div>
									<div class="modal-body">
									
									
									<div class="m-portlet m-portlet--brand m-portlet--head-solid-bg m-portlet--bordered">
										<div class="m-portlet__head">
										<div class="m-portlet__head-caption">
										<div class="m-portlet__head-title">
										<h3 class="m-portlet__head-text">
										Employee Details
										</h3>
										</div>
										</div>
										</div>
										<div class="m-portlet__body">
											<table class="table m-table m-table--head-bg-brand">
											<tbody>
											<tr>
											<th>Employee Id</th>
											<td class="employee_id" ></td>
											<th>Employee Name</th>
											<td class="employee_name" ></td>
											</tr>
											<tr>
											<th>PF-NO</th>
											<td class="PF-NO"></td>
											<th>Pf-employee</th>
											<td class="Pf-employee"></td>
											</tr>
											<tr>
											<th>Pf-employer</th>
											<td class="Pf-employer"></td>
											<th>VPF </th>
											<td class="VPF"></td>
											</tr>
											</tbody>
											</table>
										</div>
									</div>
									<div class="m-portlet m-portlet--brand m-portlet--head-solid-bg m-portlet--bordered">
									
										<div class="m-portlet__head">
										<div class="m-portlet__head-caption">
										<div class="m-portlet__head-title">
										<h3 class="m-portlet__head-text">
										Gross Salary
										</h3>
										</div>
										
										</div>
										</div>
										<form id="gross_breakup">
										 <input type="hidden" class="show_emp_id1" name="get_empl_record" value="" />
										<button type="button" class="btn btn-brand edit" style="margin-left:625px;margin-bottom:-725px;" value="Edit">edit</button>
										<input type="submit" class="btn btn-brand gross_breakup1" id="change_gross_salary" style="margin-left:625px;margin-bottom:-725px;display:none;" value="SAVE"/>
										<div class="m-portlet__body append_data" id="contentid">
										 
										</div>
										</form>
									</div>
									
									
								
								</div>
								</div>
							</div>
						</div>
						
						<div class="m-content">
						<div class="m-portlet m-portlet--mobile">
							<div class="m-portlet__head">
								<div class="m-portlet__head-caption">
									<div class="m-portlet__head-title">
										<h3 class="m-portlet__head-text" style="color:#34bfa3;">
										Overall PF Details
									</div>
								</div>
							</div>
							
							<div class="m-portlet__body">

								<!--begin: Search Form -->
								<div class="m-form m-form--label-align-right m--margin-top-20 m--margin-bottom-30">
									<div class="row align-items-center">
										<div class="col-xl-12 order-2 order-xl-1  m--align-right">
											<div class="form-group m-form__group row align-items-center" >
												 <div class="col-md-3">
													<div class="m-form__group m-form__group--inline">
												
													
														 <div class="m-portlet__body">
                <!--begin: Datatable -->
		
				
				<div class="m-portlet m-portlet--mobile" style="width: 800px">
						<div class="m-portlet__head">
						<div class="m-portlet__head-caption">
						<div class="m-portlet__head-title">
						
						
						</div>
						</div>
						</div>
						<style>
						#view
						{
							 border-collapse: collapse;
							width: 100%;
						}
						#view td, #view th{
							 border: 1px solid #ddd;
							padding: 5px;
						}
						#view tr:nth-child(even)
						{
							background-color:#f2f2f2;
						}
						#view tr:hover
						{
							background-color:#ddd;
						}
						#view th {
							padding-top: 12px;
							padding-bottom: 12px;
							text-align: left;
							background-color: #f2f2f2;
							font:color:#575962;
							
						}
						</style>
						<div class="m-portlet__body">

						<div class="m_datatable m-datatable m-datatable--default m-datatable--loaded  " id="local_data" style="">
						<table id="view">
						<thead class="m-datatable__head">
						<tr class="m-datatable__row">
						<th data-field="RecordID" class="m-datatable__cell--center m-datatable__cell">
						<span style="width: 110px;">Employee_Id</span>
						</th>
						<th data-field="RecordID" class="m-datatable__cell--center m-datatable__cell">
						<span style="width: 110px;">Employee_Name</span>
						</th>
						
						<?php 
						$sel_sql=pg_query($db,"select  month FROM prior_earnings where employee_id='1'");

												while($res=pg_fetch_array($sel_sql)){
												 //echo $res['month'];exit;
												 $result=$res['month'];
												 
												 //print_r($sel_sql);exit;
													//echo $result;exit;
								?>	
								<th data-field="RecordID" class="m-datatable__cell--center m-datatable__cell">
						<span style="width: 110px;"><?php echo $result;?></span>
						</th>					
							<?php $month=pg_query($db,"select earnings_in_current_fy from prior_earnings where month='$result' and employee_id='1'");
							//echo "select earnings_in_current_fy from prior_earnings where month='$result' and employee_id='1'";exit;
                                     while($res=pg_fetch_assoc($month)){
										 //echo $res;exit;
												 //echo $res['month'];exit;
												 $result2=$res['earnings_in_current_fy'];
												// echo $result2;exit;
                    							?>
								<?php }?>
								
								<td> <?php echo $result2;?>
							</td>
											
						
						
						
						
						<?php }?>
						
					
						
						</tr>
						</thead>
						<tbody>
						
						
						<?php
							while($result1=pg_fetch_array($sel_sql1)){
								//echo $result;exit;
						?>
                  
								<?php echo "<tr>";?>
							<td>
                                 <?php echo $result1['employee_id'];?>
							</td>
						    <td>
								 <?php echo $result1['employee_name']; ?>
							</td>
							
								<?php
						  } 
						
						?>	
                        
   							
						 <?php echo "</tr>";?>

							
						
						</tbody>
						</table>
						</div>
						</div>

						</div>
				  <!-- end:: Body -->
											</div>
																									
											</div>
										</div>
									</div>
								</div>
								
						</div>
					</div>
			</div>		
	
 </div>
</div> 
</div>
</div>

        <!-- end:: Body -->
<?php  //include "master_view.php"; ?>		
<?php include "footer.php"; ?>		

<script src="assets/demo/default/custom/crud/forms/widgets/select2.js" type="text/javascript"></script>   
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places&key=AIzaSyANqJobRvzfZ-hl4nUcKSCWTCrPOeo4diQ" type="text/javascript"></script>
<script src="jquery.tabletoCSV.js"></script>
<script src="assets/demo/default/custom/crud/forms/widgets/select2.js" type="text/javascript"></script>   
<!--<script src="/itr-decider/main-itr-decider/main-page.php?workidauthjs=1.0" type="text/javascript"></script>-->




<script>


</script>
</body>
<!-- end::Body -->
</html>
							