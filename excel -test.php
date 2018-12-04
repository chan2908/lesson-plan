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
 //echo $year1;exit;

 $sel_sql="select  a.employee_id,a.employee_name, a.basic ,a.salary_breakup->>'Hra' as hra,a.salary_breakup->>'ConveyanceAllowance' as  ca,a.salary_breakup->>'MedicalAllowance' as  ma,a.salary_breakup->>'SpecialAllowance' as  sa,
a.salary_breakup->>'CTC_Per_Month' as  total,


c.basic as basic_gross,
c.gross_salary_breakup->>'Hra' as  hraa,  

c.gross_salary_breakup ->>'ConveyanceAllowance 'as  caa,
c.gross_salary_breakup ->>'MedicalAllowance' as  maa,
c.gross_salary_breakup ->>'SpecialAllowance' as  saa,
c.gross_salary_breakup ->>'GrossTotal' as  totall,d.deduction ->> 'SECURITY-DEPOSIT' as sd ,d.retirement_benefits->>'Employee_PF'as pf , d. retirement_benefits->> 'Employee_ESI' as esi ,
d.tds_per_month  as tds, d.salary_payout as takehome ,f.payable_days as payabledays
from employee_basic_ctc_details 
a inner join employee_overall_gross_detail c on a.employee_id=c.employee_id inner join employee_monthly_salary_detail d on c.employee_id=d.employee_id inner join  employee_monthly_attendance_detail f on f.employee_id=d.employee_id  where a.bid='$bid' 

";
 
 
 if($month1 && $year1){
	 $sel_sql="select  a.employee_id,a.employee_name, a.basic ,a.salary_breakup->>'Hra' as hra,a.salary_breakup->>'ConveyanceAllowance' as  ca,a.salary_breakup->>'MedicalAllowance' as  ma,a.salary_breakup->>'SpecialAllowance' as  sa,
a.salary_breakup->>'CTC_Per_Month' as  total,


c.basic as basic,
c.gross_salary_breakup->>'Hra' as  hra,  

c.gross_salary_breakup ->>'ConveyanceAllowance 'as  caa,
c.gross_salary_breakup ->>'MedicalAllowance' as  maa,
c.gross_salary_breakup ->>'SpecialAllowance' as  saa,
c.gross_salary_breakup ->>'GrossTotal' as  totall,d.deduction ->> 'SECURITY-DEPOSIT' as sd ,d.retirement_benefits->>'Employee_PF'as pf , d. retirement_benefits->> 'Employee_ESI' as esi ,
d.tds_per_month  as tds, d.salary_payout as takehome ,f.payable_days as payabledays
from employee_basic_ctc_details
a inner join employee_overall_gross_detail c on a.employee_id=c.employee_id inner join employee_monthly_salary_detail d on c.employee_id=d.employee_id inner join  employee_monthly_attendance_detail f on f.employee_id=d.employee_id  where bid='$bid' and month='$month1' and year='$year1' ";

  }
 
 
 
//$sel_sql="select  distinct a.employee_id,a.employee_name,a.gross_salary_breakup->>'PF' as pf,a.month,a.year, c.salary_breakup->>'PF'as pff from employee_overall_gross_detail a INNER JOIN employee_basic_ctc_details c on a.employee_id=c.employee_id where a.bid='$bid' and c.bid='$bid' AND a.gross_salary_breakup->>'PF'!='0' AND c.salary_breakup->>'PF'!='0'";


 // if($month1 && $year1){$sel_sql="select distinct a.employee_id,a.employee_name,a.gross_salary_breakup->>'PF' as pf,a.month,a.year, c.salary_breakup->>'PF'as pff from employee_overall_gross_detail a INNER JOIN employee_basic_ctc_details c on a.employee_id=c.employee_id where a.bid='$bid' and c.bid='$bid' and a.month='$month1' and a.year='$year1' AND a.gross_salary_breakup->>'PF'!='0' AND c.salary_breakup->>'PF'!='0'";

 
	
//select a.employee_id,a.employee_name,a.gross_salary_breakup->>'PF' as pf,a.month,a.year, c.salary_breakup->>'PF'as pff from employee_overall_gross_detail a INNER JOIN employee_basic_ctc_details c on a.employee_id=c.employee_id where a.bid='10653721' and c.bid='10653721' and a.month='$month1' and a.year='$year1' AND a.gross_salary_breakup->>'PF'!='0' AND c.salary_breakup->>'PF'!='0'


$res1=pg_query($db,$sel_sql);
while($result=pg_fetch_assoc($res1)){
$required_data[]=$result;

}

$trimmed = json_encode($required_data,true); 

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
										Overall  Details
									</div>
								</div>
							</div>
							<div class="m-portlet__body">

								<!--begin: Search Form -->
								<div class="m-form m-form--label-align-right m--margin-top-20 m--margin-bottom-30">
									<div class="row align-items-center">
										<div class="col-xl-12 order-2 order-xl-1  m--align-right">
								<!--			<div class="form-group m-form__group row align-items-center" >
												 <div class="col-md-3">
													<div class="m-form__group m-form__group--inline">
												
													
														
														<div class="m-form__label">
															<label <?php if($month1 && $year1){echo "style='display:none';";} ?>>
																Month
															</label>
														</div>
														<div class="m-form__control" <?php if($month1 && $year1){echo "style='display:none';";} ?>>
															<select class="form-control m-input" id="m_form_type">
																<option value="">All</option>
																<?php
													  
														
														
                          $query2   = "SELECT distinct month FROM employee_overall_gross_detail ORDER BY month ASC;";
                		  $res11=pg_query($db,$query2);							
                          while($res=pg_fetch_assoc($res11)){
						 echo "<option value='" . $res['month'] . "'>" . $res['month'] . "</option>";
																	 
                            

                                        }
																
																
																
																
																	?>
																	
															</select>
															 
														</div>
														
														
													</div>
													
													
													<div class="d-md-none m--margin-bottom-10"></div>
												</div>
												
												<div class="col-md-3">
													<div class="m-form__group m-form__group--inline">
														<div class="m-form__label">
															<label <?php if($month1 && $year1){echo "style='display:none';";} ?>>
																Year
															</label>
														</div>
														<div class="m-form__control" <?php if($month1 && $year1){echo "style='display:none';";} ?>>
															<select class="form-control m-input" id="m_form_status">
																<option value="">All</option>
																	<?php
															$select="SELECT distinct year FROM employee_overall_gross_detail ORDER BY year ASC;";
                                                           $res12=pg_query($db,$select);
                                                          while($res1=pg_fetch_assoc($res12)){
																	 
															echo "<option value='" . $res1['year'] . "'>" . $res1['year'] . "</option>";
																	 
                           
                                      }
													
																	?>
																	
																	
																	
																	
															</select>
														</div>
													</div>
													<div class="d-md-none m--margin-bottom-10"></div>
												</div>
												
												
												
												
												
												<div class="col-md-3">
													<div class="m-input-icon m-input-icon--left">
														<input type="text" class="form-control m-input m-input--solid" placeholder="Search..." id="generalSearch" style="border-color: #f4f5f8;">
														<span class="m-input-icon__icon m-input-icon__icon--left">
															<span>
																<i class="la la-search"></i>
															</span>
														</span>
													</div>
												</div>-->
												<div class="col-md-2" style="margin-left:1000px;">
													<a href="#" data-export="export"  id="export" class="btn  m-btn--air btn-success m-btn" data-toggle="modal" data-target="#it_record">
													<script src="jquery.tabletoCSV.js"></script>

													<span class="excel">GENERATE EXCEL</span> &nbsp <i class="fa fa-download"></i>
													</a>
												</div>
											</div>
										</div>
									</div>
								</div>

								<!--end: Search Form -->

								<!--begin: Datatable -->
								<div class="m_datatable" id="local_data"></div>

								<!--end: Datatable -->
							</div>
						</div>
					</div>
					
	
<!-- </div>
</div> -->



<?php  //include "master_view.php"; ?>		
<?php //include "footer.php"; ?>		

<script src="assets/demo/default/custom/crud/forms/widgets/select2.js" type="text/javascript"></script>   
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places&key=AIzaSyANqJobRvzfZ-hl4nUcKSCWTCrPOeo4diQ" type="text/javascript"></script>
<script src="jquery.tabletoCSV.js"></script>
<script src="assets/demo/default/custom/crud/forms/widgets/select2.js" type="text/javascript"></script>   
<!--<script src="/itr-decider/main-itr-decider/main-page.php?workidauthjs=1.0" type="text/javascript"></script>-->
<script>
var DatatableDataLocalDemo= {
    init:function() {
        var json_data, data_string, obj,
        a;
        json_data=<?php echo $trimmed;?>,   

				
		//alert(JSON.stringify(json_data));
        a=$(".m_datatable").mDatatable( {
			
            data: {
                type: "local", source: json_data, pageSize: 10
            }
            , layout: {
                theme: "default", class: "", scroll: !1, footer: !1
            }
            , sortable:!0, pagination:!0, 
			
			toolbar: {
                items: {
                    pagination: {
                        pageSizeSelect:  [ [5], [10], [25], [<?php echo sizeof($required_data); ?>] ]
        
                    }
                }
            },
			
			search: {
                input: $("#generalSearch")
            },
						
			columns: [
			
			{
				
				field:"employee_id",
			   title: "Employee_id",
			    "style": {
                 width:200"
                  },
				/* {
				
				 view: function(c)
				{
					var employee_id=c.CurrentItem[c]
					return "<span style='color :#212529'>" +employee_id+ "</span>";
				} 
				} 
				 */
				 
			},
			{
				field: "employee_name",
				title: "Employee-Name",
				width:200,
              		
			},
			  
			{
       field: "basic",
			 title: "BASIC"
			},
			{
			 field: "hra",
				title: "HOUSE RENT ALLOWANCE"
				
			},
			{
			 field: "ca",
				title: "CONVEYANCE ALLOWANCE"
			},
				{
			 field: "ma",
				title: "MEDICAL ALLOWANCE"
				},		
{
			 field: "sa",
				title: "SPECIAL ALLOWANCE"
				},		


				{
			 field: "total",
				title: "TOTAL"
				},		

			
				//gross
				
				
				{
       field: "basic_gross",
			 title: "BASIC"
			},
			{
			 field: "hraa",
				title: "HOUSE RENT ALLOWANCE"
				
			},
			{
			 field: "caa",
				title: "CONVEYANCE ALLOWANCE"
			},
				{
			 field: "maa",
				title: "MEDICAL ALLOWANCE"
				},		
{
			 field: "saa",
				title: "SPECIAL ALLOWANCE"
				},		


				{
			 field: "totall",
				title: "TOTAL"
				},		

			
				//deduction
				{
       field: "sd",
			 title: "SD"
			},
			{
			 field: "pf",
				title: "PF"
				
			},
			{
			 field: "esi",
				title: "ESI"
			},
				{
			 field: "tds",
				title: "TDS"
				},	



//OVER ALL				
{
			 field: "takehome",
				title: "TAKE HOME"
			},
				{
			 field: "payabledays",
				title: "PAYABLE DAYS"
				},	
				
		
				
	/*	{
				
				field: "month",
				title: "Month"
				}
			,			
			{
				field: "year",
				title: "Year"
			}
			*/
			]
        }
        )
		
         $("#m_form_type").on("change", function() {
            a.search($(this).val(), "month")
        }
        ),
				 $("#m_form_status").on("change", function() {
            a.search($(this).val(), "year")
        }
        ),
				
				
				
				//a.columns("designation").visible(!1);
        $("#m_form_type").selectpicker() 
        $("#m_form_status").selectpicker() 
    }
}

;
jQuery(document).ready(function() {
    DatatableDataLocalDemo.init()
	
}

);

//excel generate										
$("#export").click(function(){
$(".m_datatable").tableToCSV();
//filename: 'table.csv'

});
	

/* $(document).ready(function (){
    var table = $('#local_data').DataTable();

    table.rows().every( function ( rowIdx, tableLoop, rowLoop ) {
        var cell = table.cell({ row: rowIdx, column: 1 }).node();
        $(cell).addClass('warning');
    });
}); */



</script>
</body>
<!-- end::Body -->
</html>
							