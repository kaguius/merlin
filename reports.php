<?php
$userid = "";
$adminstatus = 4;
$property_manager_id = "";
session_start();
if (!empty($_SESSION)) {
    $userid = $_SESSION["userid"];
    $adminstatus = $_SESSION["adminstatus"];
    $property_manager_id = $_SESSION["property_manager_id"];
    $station = $_SESSION["station"];
    $title = $_SESSION["title"];
}

//if($adminstatus != 1 || $adminstatus != 2 || $adminstatus != 4){
if ($adminstatus == 3) {
    include_once('includes/header.php');
    ?>
    <script type="text/javascript">
        document.location = "insufficient_permission.php";
    </script>
    <?php
} else {
    include_once('includes/db_conn.php');
    $transactiontime = date("Y-m-d G:i:s");
    $page_title = "Reports";
    include_once('includes/header.php');
    ?>		
    <div id="page">
        <div id="content">
            <div class="post">
                <h2>Reports</h2>
                Which report would you like to view?
                <?php if ($station == '1' || $station == '2' || $station == '5' || $station == '6' || $station == '7' || $station == '8' || $station == '9' || $station == '11' || $station == '12' || $station == '13' || $station == '14' || $station == '16' || $station == '17' || $station == '18' || $station == '19' || $station == '20' || $station == '21' || $station == '22' || $station == '23') { ?>
                    <hr>
                    <table width="100%" border="0" cellspacing="2" cellpadding="2" class="display">
                        <tr>
                            <td width="50%" valign="top">
                                <h3>Branch Staff</h3>
                                <p>
                                <table width="100%" border="0" cellspacing="2" cellpadding="2" class="display">
                                    <tr>
                                        <td width="50%" valign="top">
                                            Loans Due <strong>TODAY</strong> <a href="reports/due_today.php">View</a><br />
                                        </td>
                                        <td width="50%" valign="top">
                                        	Loans Disbursed <strong>TODAY</strong> <a href="reports/disbursed_today.php">View</a><br />
                                            
                                        </td>
                                    </tr>
                                    <tr>
                                    	<td width="50%" valign="top">
                                            Loans Disbursed Report with Date Range <a href="reports/business_disbursed_today.php">View</a><br />
                                        </td>
                                        <td width="50%" valign="top">
                                            Loans Due Report with Date Range <a href="reports/business_due.php">View</a><br />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="50%" valign="top">
                                            Loans DD+1 to DD+14 <a href="reports/due_after_today.php">View</a><br />
                                        </td>
                                        <td width="50%" valign="top">
                                        	Loans DD+1 to DD+30 <a href="reports/due_after_today+15.php">View</a><br />
                                            
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="50%" valign="top">
                                            Loans Due in the next 15 Days <a href="reports/due_next_15_days.php">View</a><br />
                                        </td>

                                        <td width="50%" valign="top">
                                            Repayments Report <a href="reports/mobile_money_repayments.php">View</a><br />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="50%" valign="top">
                                            Individual performance – disbursements, collections, disbursement collection ratio <a href="reports/disbursements_branch_ind.php">View</a><br />
                                        </td>

                                        <td width="50%" valign="top">
                                            Branch disbursement and collections <a href="reports/disbursements.php">View</a><br />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="50%" valign="top">
                                            Number of Active customers per pair and for the branch <a href="reports/active_customers.php">View</a><br />
                                        </td>
                                        <td width="50%" valign="top">
                                            Daily PTPs against what has been collected per pair and for the branch <a href="reports/daily_promises.php">View</a><br />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="50%" valign="top">
                                            Dormant customers per pair and for the branch <a href="reports/dormant_customers.php">View</a><br />
                                        </td>
                                        <td width="50%" valign="top">
                                            Defaulter aging (number and amount) per pair and for the branch <a href="reports/defaulter_aging.php">View</a><br />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="50%" valign="top">
                                            Customer retention rate per month per pair and for the branch <a href="reports/customer_retention.php">View</a><br />
                                        </td>
                                        <td width="50%" valign="top">
                                            Number of leads per pair and for the branch <a href="reports/leads.php">View</a><br />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="50%" valign="top">
                                            Number of rejected customers per pair and for the branch <a href="reports/rejected_customers.php">View</a><br />
                                        </td>
                                        <td width="50%" valign="top">
                                            New customer report  <a href="reports/new_customers.php">View</a><br />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="50%" valign="top">
                                            Customer Location Information <a href="reports/customer_location.php">View</a><br />
                                        </td>
                                        <td width="50%" valign="top">
                                            Branch Customer Mapping  <a href="reports/maps.php">View</a><br />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="50%" valign="top">
                                            Deleted Loans <a href="reports/deleted_loans.php">View</a><br />
                                        </td>
                                        <td width="50%" valign="top">
                                            Customized Loan Report <a href="reports/custom_business_disbursed_today.php">View</a><br />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="50%" valign="top">
                                            BLC/ BLC Question Set Report <a href="reports/bfc_question_report.php">View</a><br />
                                        </td>
                                        <td width="50%" valign="top">
                                            Weekly Cycle Customers Report <a href="reports/weekly_payment_cycles.php">View</a><br />
                                        </td>
                                    </tr>
                                    <?php if($title == '3' || $userid == '8' ){ ?>
                                    <tr>
                                        <td width="50%" valign="top">
                                            Branch Customer List <a href="reports/mgt_targeting.php">View</a><br />
                                        </td>
                                        <td width="50%" valign="top">
                                            &nbsp;<br />
                                        </td>
                                    </tr>
                                    <?php } ?>
                                </table>
                                </p>
                            </td>
                        </tr>
                    </table>
                <?php } else if ($station == '3') {
                    if ($title == '12') { ?>
                        <hr>
                         <table width="100%" border="0" cellspacing="2" cellpadding="2" class="display">
                            <tr>
                                <td width="50%" valign="top">
                                    Branch disbursement and collections <a href="reports/disbursements.php">View</a><br />
                                </td>
                                <td width="50%" valign="top">
                                     Business and branch disbursement and repayments against daily and monthly targets <a href="reports/business_disbursements.php">View</a><br />
                                </td>
                            </tr>
                            <tr>
                                <td width="50%" valign="top">
                                    Active & New customers per branch and for the business <a href="reports/active_customers_business.php">View</a><br />
                                </td>
                                <td width="50%" valign="top">
                                    Average loan size per branch and for the business <a href="reports/average_loan_size.php">View</a><br />
                                </td>
                            </tr>
                            <tr>
                                <td width="50%" valign="top">
                                    PAR per Branch <a href="reports/par_branch.php">View</a>
                                </td>
                                <td width="50%" valign="top">
                                     Income Report <a href="reports/finance_report.php">View</a>
                                </td>
                            </tr>
                            <tr>
                                <td width="50%" valign="top">
                                    Income Report per Branch <a href="reports/finance_report_branch.php">View</a>
                                </td>
                                <td width="50%" valign="top">
                                    Write Offs Report <a href="reports/write_offs.php">View</a>
                                </td>
                            </tr>
                        </table>
                    <?php } else { ?>
                    <hr>
                    <table width="100%" border="0" cellspacing="2" cellpadding="2" class="display">
                        <tr>
                            <td width="50%" valign="top">
                                <h3>Branch Staff</h3>
                                <p>
                                <table width="100%" border="0" cellspacing="2" cellpadding="2" class="display">
                                    <tr>
                                        <td width="50%" valign="top">
                                            Loans Due <strong>TODAY</strong> <a href="reports/due_today.php">View</a><br />
                                        </td>
                                        <td width="50%" valign="top">
                                        	Loans Disbursed <strong>TODAY</strong> <a href="reports/disbursed_today.php">View</a><br />
                                            
                                        </td>
                                    </tr>
                                    <tr>
                                    	<td width="50%" valign="top">
                                            Loans Disbursed Report with Date Range <a href="reports/business_disbursed_today.php">View</a><br />
                                        </td>
                                        <td width="50%" valign="top">
                                            Loans Due Report with Date Range <a href="reports/business_due.php">View</a><br />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="50%" valign="top">
                                            Loans DD+1 to DD+14 <a href="reports/due_after_today.php">View</a><br />
                                        </td>
                                        <td width="50%" valign="top">
                                        	Loans DD+1 to DD+30 <a href="reports/due_after_today+15.php">View</a><br />
                                            
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="50%" valign="top">
                                            Loans Due in the next 15 Days <a href="reports/due_next_15_days.php">View</a><br />
                                        </td>

                                        <td width="50%" valign="top">
                                            Repayments Report <a href="reports/mobile_money_repayments.php">View</a><br />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="50%" valign="top">
                                            Individual performance – disbursements, collections, disbursement collection ratio <a href="reports/disbursements_branch_ind.php">View</a><br />
                                        </td>

                                        <td width="50%" valign="top">
                                            Branch disbursement and collections <a href="reports/disbursements.php">View</a><br />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="50%" valign="top">
                                            Number of Active customers per pair and for the branch <a href="reports/active_customers.php">View</a><br />
                                        </td>
                                        <td width="50%" valign="top">
                                            Daily PTPs against what has been collected per pair and for the branch <a href="reports/daily_promises.php">View</a><br />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="50%" valign="top">
                                            Dormant customers per pair and for the branch <a href="reports/dormant_customers.php">View</a><br />
                                        </td>
                                        <td width="50%" valign="top">
                                            Defaulter aging (number and amount) per pair and for the branch <a href="reports/defaulter_aging.php">View</a><br />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="50%" valign="top">
                                            Customer retention rate per month per pair and for the branch <a href="reports/customer_retention.php">View</a><br />
                                        </td>
                                        <td width="50%" valign="top">
                                            Number of leads per pair and for the branch <a href="reports/leads.php">View</a><br />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="50%" valign="top">
                                            Number of rejected customers per pair and for the branch <a href="reports/rejected_customers.php">View</a><br />
                                        </td>
                                        <td width="50%" valign="top">
                                            New customer report  <a href="reports/new_customers.php">View</a><br />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="50%" valign="top">
                                            Customer Location Information <a href="reports/customer_location.php">View</a><br />
                                        </td>
                                        <td width="50%" valign="top">
                                            Branch Customer Mapping  <a href="reports/maps.php">View</a><br />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="50%" valign="top">
                                            Deleted Loans <a href="reports/deleted_loans.php">View</a><br />
                                        </td>
                                        <td width="50%" valign="top">
                                            Customized Loan Report <a href="reports/custom_business_disbursed_today.php">View</a><br />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="50%" valign="top">
                                            BLC/ BLC Question Set Report <a href="reports/bfc_question_report.php">View</a><br />
                                        </td>
                                        <td width="50%" valign="top">
                                            Weekly Cycle Customers Report <a href="reports/weekly_payment_cycles.php">View</a><br />
                                        </td>
                                    </tr>
                                     <tr>
                                        <td width="50%" valign="top">
                                            Branch Customer List <a href="reports/mgt_targeting.php">View</a><br />
                                        </td>
                                        <td width="50%" valign="top">
                                            &nbsp;<br />
                                        </td>
                                    </tr>
                                </table>
                                </p>
                            </td>
                        </tr>
                    </table>
                    <hr />
                    <table width="100%" border="0" cellspacing="2" cellpadding="2" class="display">
                        <tr>
                            <td width="50%" valign="top">
                                <h3>Surge Team HQ Staff</h3>
                                <table width="100%" border="0" cellspacing="2" cellpadding="2" class="display">
                                    <tr>
                                        <td width="50%" valign="top">
                                            Business and branch disbursement and repayments against daily and monthly targets <a href="reports/business_disbursements.php">View</a><br />
                                        </td>
                                        <td width="50%" valign="top">
                                            Number of loans extended/restructured daily and monthly <a href="reports/extended_loans.php">View</a><br />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="50%" valign="top">
                                            Active & New customers per branch and for the business <a href="reports/active_customers_business.php">View</a><br />
                                        </td>
                                        <td width="50%" valign="top">
                                            Customer retention rate per branch and for the business <a href="reports/customer_retention.php">View</a><br />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="50%" valign="top">
                                            Customer demographics and trends <a href="#">View</a><br />
                                        </td>
                                        <td width="50%" valign="top">
                                            Variations by season, geography <a href="#">View</a><br />
                                        </td>

                                    </tr>
                                    <tr>
                                        <td width="50%" valign="top">
                                            Defaulter aging (number and amount) per pair, per branch and for the business <a href="reports/defaulter_aging_business.php">View</a><br />
                                        </td>
                                        <td width="50%" valign="top">

                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                    <hr>
                    <table width="100%" border="0" cellspacing="2" cellpadding="2" class="display">
                        <tr>
                            <td width="50%" valign="top">
                                <h3>HoL/Ops Manager</h3>
                                <table width="100%" border="0" cellspacing="2" cellpadding="2" class="display">
                                    <tr>
                                        <td width="50%" valign="top">
                                            Business and branch disbursement and repayments against daily and monthly targets <a href="reports/business_disbursements.php">View</a><br />
                                        </td>
                                        <td width="50%" valign="top">
                                            Individual disbursement and collections against daily and monthly targets <a href="reports/disbursements_branch_ind.php">View</a><br />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="50%" valign="top">
                                            Loans Disbursed Report <a href="reports/business_disbursed_today.php">View</a><br />
                                        </td>
                                        <td width="50%" valign="top">
                                            Loans Due Report <a href="reports/business_due.php">View</a><br />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="50%" valign="top">
                                            Collection Rates per sector <a href="reports/collections_sector.php">View</a><br />
                                        </td>
                                        <td width="50%" valign="top">
                                            Collection Rates by Marital Status <a href="reports/collections_gender.php">View</a><br />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="50%" valign="top">
                                            Collection rates for early settlement <a href="reports/early_settlement.php">View</a><br />
                                        </td>
                                        <td width="50%" valign="top">
                                            Number of loans extended/restructured daily and monthly <a href="reports/extended_loans.php">View</a><br />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="50%" valign="top">
                                            Active & New customers per branch and for the business <a href="reports/active_customers_business.php">View</a><br />
                                        </td>
                                        <td width="50%" valign="top">
                                            Customer retention rate per branch and for the business <a href="#">View</a><br />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="50%" valign="top">
                                            Average loan size per branch and for the business <a href="reports/average_loan_size.php">View</a><br />
                                        </td>
                                        <td width="50%" valign="top">
                                            Customer demographics and trends <a href="#">View</a><br />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="50%" valign="top">
                                            Deceased Status Report <a href="reports/deceased_report.php">View</a><br />
                                        </td>
                                        <td width="50%" valign="top">
                                            Portfolio At Risk by CD weekly vintages as per credit policy <a href="reports/defaulter_aging_business.php">View</a><br />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="50%" valign="top">
                                            Individual performance Call Centre, EDC and commissions based agents<a href="reports/total_collections.php">View</a><br />
                                        </td>
                                        <td width="50%" valign="top">
                                            Total collections from the NPL at (Branch level, CC Level, EDC level and Write off level for the Commission based agents) <a href="reports/total_collections.php">View</a><br />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="50%" valign="top">
                                            Mobile Money Reconciliation: Loan Application <a href="reports/mobile_money_loans.php">View</a><br />
                                        </td>
                                        <td width="50%" valign="top">
                                            Mobile Money Reconciliation: Loan Repayments <a href="reports/mobile_money_repayments.php">View</a><br />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="50%" valign="top">
                                            Loans Listing <a href="reports/loans_listing.php">View</a><br />
                                        </td>
                                        <td width="50%" valign="top">
                                            Branch Customer Mapping <a href="reports/customer_mapping.php">View</a><br />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="50%" valign="top">
                                            System Change Log <a href="reports/change_log.php">View</a><br />
                                        </td>
                                        <td width="50%" valign="top">
                                            Marketing Drive <a href="reports/marketing.php">View</a><br />
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                    <hr>
                    <table width="100%" border="0" cellspacing="2" cellpadding="2" class="display">
                        <tr>
                            <td width="50%" valign="top">
                                <h3>Collections Team</h3>
                                <table width="100%" border="0" cellspacing="2" cellpadding="2" class="display">

                                    <tr>
                                        <td width="50%" valign="top">
                                            PAR per Customer <a href="reports/par_customer.php">View</a>
                                        </td>
                                        <td width="50%" valign="top">
                                            PAR per Branch <a href="reports/par_branch.php">View</a>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td width="50%" valign="top">
                                            Average collections per loan size per branch <a href="reports/average_loan_size.php">View</a>
                                        </td>
                                        <td width="50%" valign="top">
                                            Collection Rates per sector <a href="reports/collections_sector.php">View</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="50%" valign="top">
                                            Collection Rates by Marital Status <a href="reports/collections_gender.php">View</a>
                                        </td>
                                        <td width="50%" valign="top">
                                            Collection rates for early settlement <a href="reports/early_settlement.php">View</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="50%" valign="top">
                                            Total collections from the NPL at (Branch level, CC Level, EDC level and Write off level for the Commission based agents) <a href="reports/non_performing.php">View</a>
                                        </td>
                                        <td width="50%" valign="top">
                                            Portfolio At Risk by CD weekly vintages as per credit policy table above <a href="reports/defaulter_aging_business.php">View</a>
                                        </td>

                                    </tr>
                                    <tr>
                                        <td width="50%" valign="top">
                                            Collected amount per agent against PTP projections (upto 2015-09-06) <a href="reports/agent_collections_old.php">View</a>
                                        </td>
                                        <td width="50%" valign="top">
                                            Collected amount per agent against PTP projections (from 2015-08-31) <a href="reports/agent_collections_new.php">View</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="50%" valign="top">
                                            Call outcome reports per agent <a href="reports/call_outcomes.php">View</a>
                                        </td>
                                        <td width="50%" valign="top">
                                            Portfolio At Risk by Branch <a href="reports/defaulter_aging_business_branch.php">View</a>
                                        </td>
                                    </tr>
                                    <tr>                                        
                                        <td width="50%" valign="top">
                                            Collected amount per agent grouping (upto 2015-09-06) <a href="reports/agent_collections_group_old.php">View</a>
                                        </td>
                                        <td width="50%" valign="top">
                                            Collected amount per agent grouping (from 2015-08-31) <a href="reports/agent_collections_group_new.php">View</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="50%" valign="top">
                                            CC/ FA Assigned Accounts <a href="reports/field_accounts.php">View</a>
                                        </td>
                                        <td width="50%" valign="top">
                                            EDC Assigned Accounts <a href="reports/edc_accounts.php">View</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="50%" valign="top">
                                            CRB Assigned Accounts <a href="reports/crb_accounts.php">View</a>
                                        </td>  
                                        <td width="50%" valign="top">
                                            Agent Dispositions Report <a href="reports/agent_dispositions.php">View</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        
                                        <td width="50%" valign="top">
                                            Arrears Accounts Assigned <a href="reports/assigned_accounts.php">View</a>
                                        </td>
                                        <td width="50%" valign="top">
                                          	Field Agent Report <a href="reports/field_agent_report.php">View</a>
                                        </td>
                                    </tr>
                                </table>
                                </p>
                            </td>
                        </tr>
                    </table>
                    <hr>
                    <table width="100%" border="0" cellspacing="2" cellpadding="2" class="display">
                        <tr>
                            <td width="50%" valign="top">
                                <h3>Finance Team</h3>
                                <table width="100%" border="0" cellspacing="2" cellpadding="2" class="display">
                                    <tr>
                                        <td widtgh="50%" valign="top">
                                            Income Report <a href="reports/finance_report.php">View</a>
                                        </td>
                                        <td>
                                            Income Report per Branch <a href="reports/finance_report_branch.php">View</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="50%" valign="top">
                                            Write Offs Report <a href="reports/write_offs.php">View</a>

                                        </td>
                                        <td width="50%" valign="top">
                                            PAR <a href="reports/par.php">View</a>
                                        </td>
                                    </tr>
				    <tr>
                                        <td widtgh="50%" valign="top">
                                            Payments Suspense Report <a href="reports/suspence_account.php">View</a>
                                        </td>
                                        <td>
                                            &nbsp;
                                        </td>
                                    </tr>
                                </table>
                                </p>
                            </td>
                        </tr>
                    </table>
                    <?php } ?>
                <?php } else if ($station == '4') {
                    ?>
                    <hr>
                    <table width="100%" border="0" cellspacing="2" cellpadding="2" class="display">
                        <tr>
                            <td width="50%" valign="top">
                                <h3>Collections Team</h3>
                                <p>
                                <table width="100%" border="0" cellspacing="2" cellpadding="2" class="display">
                                    <tr>
                                        <td width="50%" valign="top">
                                            Average collections per loan size per branch <a href="reports/average_loan_size.php">View</a>
                                        </td>
                                        <td width="50%" valign="top">
                                            Collection Rates per sector <a href="reports/collections_sector.php">View</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="50%" valign="top">
                                            Collection Rates by Marital Status <a href="reports/collections_gender.php">View</a>
                                        </td>
                                        <td width="50%" valign="top">
                                            Collection rates for early settlement <a href="reports/early_settlement.php">View</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="50%" valign="top">
                                            Total collections from the NPL at (Branch level, CC Level, EDC level and Write off level for the Commission based agents) <a href="reports/non_performing.php">View</a>
                                        </td>
                                        <td width="50%" valign="top">
                                            Portfolio At Risk by CD weekly vintages as per credit policy table above <a href="reports/defaulter_aging_business.php">View</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="50%" valign="top">
                                            Collected amount per agent against PTP projections (upto 2015-09-06) <a href="reports/agent_collections_old.php">View</a>
                                        </td>
                                        <td width="50%" valign="top">
                                            Collected amount per agent against PTP projections (from 2015-08-31) <a href="reports/agent_collections_new.php">View</a>
                                        </td>
                                    </tr>
                                    <tr>                                        
                                        <td width="50%" valign="top">
                                            Collected amount per agent grouping (upto 2015-09-06) <a href="reports/agent_collections_group_old.php">View</a>
                                        </td>
                                        <td width="50%" valign="top">
                                            Collected amount per agent grouping (from 2015-08-31) <a href="reports/agent_collections_group_new.php">View</a>
                                        </td>
                                    </tr>
                                    <tr>                                        
                                        <td width="50%" valign="top">
                                            Call outcome reports per agent <a href="reports/call_outcomes.php">View</a>
                                        </td>
                                        <td width="50%" valign="top">
                                            Portfolio At Risk by Branch <a href="reports/defaulter_aging_business_branch.php">View</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="50%" valign="top">
                                            CC/ FA Assigned Accounts <a href="reports/field_accounts.php">View</a>
                                        </td>
                                        <td width="50%" valign="top">
                                            EDC Assigned Accounts <a href="reports/edc_accounts.php">View</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="50%" valign="top">
                                            CRB Assigned Accounts <a href="reports/crb_accounts.php">View</a>
                                        </td>  
                                        <td width="50%" valign="top">
                                            Agent Dispositions Report <a href="reports/agent_dispositions.php">View</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        
                                        <td width="50%" valign="top">
                                            Arrears Accounts Assigned <a href="reports/assigned_accounts.php">View</a>
                                        </td>
                                        <td width="50%" valign="top">
                                          	Field Agent Report <a href="reports/field_agent_report.php">View</a>
                                        </td>
                                    </tr>
                                </table>
                                </p>
                            </td>
                        </tr>
                    </table>
                <?php } ?>
            </div>
        </div>
        <br class="clearfix" />
    </div>
    </div>
    <?php
}
include_once('includes/footer.php');
?>
