<?php
$userid = "";
$adminstatus = 4;
$property_manager_id = "";
session_start();
if (!empty($_SESSION)) {
    $userid = $_SESSION["userid"];
    $adminstatus = $_SESSION["adminstatus"];
    $username = $_SESSION["username"];
    $station = $_SESSION["station"];
}

//if($adminstatus != 1 || $adminstatus != 2 || $adminstatus != 4){
if ($adminstatus == 4) {
    include_once('includes/header.php');
    ?>
    <script type="text/javascript">
        document.location = "insufficient_permission.php";
    </script>
    <?php
} else {
    $transactiontime = date("Y-m-d G:i:s");
    $page_title = "CRB Loans Listing";
    include_once('includes/header.php');
    $filter_clerk = 0;
    if (!empty($_GET)) {
        $filter_clerk = $_GET['clerk'];
        $filter_start_date = $_GET['report_start_date'];
        $filter_start_date_formatted = date('Y-m-d', strtotime(str_replace('-', '/', $filter_start_date)));
        $filter_end_date = $_GET['report_end_date'];
        $filter_end_date_formatted = date('Y-m-d', strtotime(str_replace('-', '/', $filter_end_date)));
    }
    include_once('includes/db_conn.php');
    if ($filter_start_date != "" && $filter_end_date != "") {
    // if (true) {
        ?>
        <div id="page">
            <div id="content">
                <div class="post">
                    <h2><?php echo $page_title; ?></h2>

                    <p><strong>Report Range: <?php echo $filter_start_date ?> to <?php echo $filter_end_date ?></strong></p>
                    <table width="100%" border="0" cellspacing="2" cellpadding="2" id="main" class="display">
                        <thead bgcolor="#E6EEEE">
                            <tr>
                                <th>Surname</th> <!-- A50 (Mandatory) = users.last_name -->
                                <th>Forename 1</th><!-- A50 (Mandatory) = users.first_name -->
                                <th>Forename 2</th><!-- A50 (Optional) -->
                                <th>Forename 3</th><!-- A50 (Optional) -->
                                <th>Salutation</th><!-- A6 (Optional) -->
                                <th>Date of Birth</th><!-- N8 (Mandatory) = users.date_of_birth -->
                                <th>Client Number</th><!-- A20 (Optional) -->
                                <th>Account Number</th><!-- A20 (Mandatory) = users.id -->
                                <th>Gender</th><!-- A1 (Mandatory), use M/F = users.gender -->
                                <th>Nationality</th><!-- A2 (Mandatory), use KE  -->
                                <th>Marital Status</th><!-- A1 (Optional) -->
                                <th>Primary Identification Document Type</th><!-- A3 (Mandatory), use 001 -->
                                <th>Primary Identification Doc Number</th><!-- A20 (Mandatory), use customer's ID no = users.national_id. -->
                                <th>Secondary Identification Document Type</th><!-- A3 (Optional),-->
                                <th>Secondary Identification Document Number</th><!-- A20 (Optional),-->
                                <th>Other Identification Doc Type</th><!-- (Optional),-->
                                <th>Other Identification Document Number</th><!-- (Optional),-->
                                <th>Mobile Telephone Number</th><!-- (Optional),-->
                                <th>Home Telephone Number</th><!-- (Optional),-->
                                <th>Work Telephone Number</th><!-- (Optional),-->
                                <th>Postal Address 1</th><!-- (Optional),-->
                                <th>Postal Address 2</th><!-- (Optional),-->
                                <th>Postal Location Town</th><!-- (Mandatory), use branch location = stations.(users.stations).stations.-->
                                <th>Postal Location Country</th><!-- (Mandatory), use KE.-->
                                <th>Post code</th><!-- (Optional),-->
                                <th>Physical Address 1</th><!-- (Mandatory), use branch location = stations.(users.stations).stations.-->
                                <th>Physical Address 2</th><!-- (Optional),-->
                                <th>Plot Number</th><!-- (Optional),-->
                                <th>Location Town</th><!-- (Optional) -->
                                <th>Location Country</th><!-- (Mandatory), use KE -->
                                <th>Date at Physical Address</th><!-- (Optional) -->
                                <th>PIN Number</th><!-- (Optional) -->
                                <th>Consumer work E-Mail</th><!-- (Optional) -->
                                <th>Employer name</th><!-- (Optional) -->
                                <th>Employer Industry Type</th><!-- (Optional) -->
                                <th>Employment Date</th><!-- (Optional) -->
                                <th>Employment Type</th><!-- (Optional) -->
                                <th>Salary Band</th><!-- (Optional) -->
                                <th>Lenders Registered Name</th><!-- (Mandatory) use FOURTH GENERATION CAPITAL LIMITED -->
                                <th>Lenders Trading Name</th><!-- (Mandatory) use 4G CAPITAL -->
                                <th>Lenders Branch name</th><!-- (Mandatory) use branch name = stations.(users.stations).stations -->
                                <th>Lenders Branch Code</th><!-- (Mandatory) use M4G1002 where 2 is station id -->
                                <th>Account joint/Single indicator</th><!-- (Mandatory) use S -->
                                <th>Account Product Type</th><!-- (Mandatory) use C -->
                                <th>Date Account Opened</th><!-- (Mandatory) use loan date = loan_application.loan_date -->
                                <th>Instalment Due Date</th><!-- (Mandatory) use loan due date = loan_application.loan_due_date -->
                                <th>Original Amount</th><!-- (Mandatory) use loan principal amount = loan_application.loan_amount -->
                                <th>Currency of Facility</th><!-- (Mandatory) use KES -->
                                <th>Amount in Kenya shillings</th><!-- (Mandatory) use loan principal amount = loan_application.loan_amount -->
                                <th>Current Balance</th><!-- (Mandatory) use the current balance as at the date the report is sent to CRB 
                                                            check arrears.php for logic
                                -->
                                <th>Overdue Balance</th><!-- (Optional) -->
                                <th>Overdue Date</th><!-- (Optional) -->
                                <th>No of Days in arrears</th><!-- (Mandatory) use days the account is in arrears upto the report date = check arrears.php for logic -->
                                <th>Nr of Installments in arrears</th><!-- (Mandatory) use no. of days in arrears/30 -->
                                <th>Perfoming / NPL indicator</th><!-- (Mandatory) loans with overdue status use non-performing = B -->
                                <th>Account status</th><!-- (Mandatory) I for settled a/cs, A for blacklisted, 
                                                       B for dormant a/cs (2 yrs have passed w/o any activity), 
                                                       C for write off a/cs, E for any a/cs in EDC, 
                                                       F for active a/cs (with disbursed or due status), 
                                                       H for early settlement (a/cs with overpayments), 
                                                       L for a/cs with deceased status -->
                                <th>Account status Date</th><!-- (Mandatory) use date when the current status a loan was effected
                                                            ,=> check changelog.table_name = loan_application
                                                            ,=> check changelog.loan_code = loan's code
                                                             => check changelog.transactiontime
                                                             => check changelog.new_value -->
                                <th>Account Closure Reason</th><!-- (Optional) -->
                                <th>Repayment period</th><!-- (Mandatory) use 30 -->
                                <th>Deferred payment date</th><!-- (Optional) -->
                                <th>Deferred payment amount</th><!-- (Optional) -->
                                <th>Payment frequency</th><!-- (Optional) -->
                                <th>Disbursement Date</th><!-- (Mandatory) use loan date = loan_application.loan_date -->
                                <th>Instalment amount</th><!-- (Optional) -->
                                <th>Date of Latest Payment</th><!-- (Optional) -->
                                <th>Last payment amount</th><!-- (Optional) -->
                                <th>Type of Security</th><!-- (Mandatory) use U for unsecured -->
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = mysql_query("SELECT u.last_name, u.first_name, u.date_of_birth, l.customer_id, u.gender, "
                                . "u.national_id, s.stations, l.loan_date, l.loan_due_date, l.loan_amount, "
                                . "l.customer_station, l.loan_status, l.loan_code, l.loan_amount, "
                                . "l.loan_total_interest FROM loan_application l  " 
                                . "JOIN users u ON l.customer_id = u.id JOIN stations " 
                                . "s ON l.customer_station = s.id WHERE l.loan_code IN ( " 
                                . "'136864','136812','136392','136903','136843','136368','67940','82565','136818','71067','136836','136633','135962','135173','136860','74110','134800','136071','136859','136806','136242','136454','46890','AFB0964','136191','44320','46268','136123','45309','47511','135785','46385','124159','47346','50143','136837','52200','71930','136893','136891','136875','136880','136862','136851','136824','136822','136841','136813','136850','136790','136897','136848','136855','136819','136612','136655','136863','136537','136873','136178','136041','135966','136106','136882','136024','136829','134982','136788','136834','136828','136831','136774','136793','136876','136886','136789','136246','136179','136264','136746','136112','135817','136881','136127','135981','136868','136884','136845','136820','136815','136869','136887','136785','136894','136826','136871','135874','136784','136892','136832','135832','136787','136222','136874','136125','135978','136867','136830','136216','136865','136098','136878','136854','136621','135775','136564','135028','136078','135047','136786','136853','136377','136413','136559','135863','136126','136833','136889','136194','136838','136795','136195','136460','136497','136117','135878','136562','136883','136802','136579','136661','136169','136861','136549','136856','136571','136342','136827','136817','135729','47213','136821','136748','136441','132433','136644','136840','136033','18991','20199','136823','134530','136417','64131','135734','135873','136814','136885','136243','136900','123187','136857','136069','136156'" 
                                . ") AND l.customer_id NOT IN ( " 
                                . "'5230','5682','5731','6890','8805','25090','25343','30006','30022','30025','30033','30088','30097','30168','30175','30178','30232','30252','30256','30311','30318','30319','30321','30322','30327','30338','30401','30405','30488','30613','30614','30675','30713','30772','30773','30778','30779','30819','30826','30831','30844','30845','30851','30932','30950','30970','31024','31030','31067','31069','31078','31082','31087','31088','31089','31090','31092','31169','31174','31214','31236','31277','31279','31455','31570','31679','31709','31758','31798','31799','31847','31859','31884','31892','31919','31930','31963','31969','31977','32005','32030','32034','32044','32045','32048','32063','32068','32071','32175','32216','32218','32222','32236','32246','32277','32278','32280','32284','32286','32300','32302','32307','32311','32312','32313','32318','32319','32327','32339','32361','32364','32366','32368','32371','32378','32387','32390','32400','32449','32456','32462','32531','32536','32541','32545','32551','32574','32576','32587','32592','32611','32613','32614','32616','32618','32630','32631','32637','32638','32645','32650','32657','32659','32660','32668','32669','32672','32675','32677','32678','32680','32681','32682','32683','32684','32685','32686','32687','32688','32689','32690','32691','32692','32693','32694','32695','32696','32697','32698','32699','32700','32701','32702','32703','32705','32706','32708','32709','32710','32711','32712','32713','32714','32715','32716','32717','32724','32725','32726','32727','32729','32730','32731','32733','32734','32735','32736','32737','32738','32739','32740','32741','32742','32743','32744','32745','32749','32750','32755','32757','32758','32759','32760','32761','32764','32765','32766','32767','32768','32770','32772','32773','32775','32777','32778','32779','32782','32783','32784','32785','32789','32793','32795','32796','32797','32799','32800','32801','32802','32804','32805','32807','32808','32811','32813','32814','32815','32816','32819','32822','32823','32824','32826','32827','32828','32829','32830','32832','32833','32835','32836','32837','32839','32840','32842','32847','32848','32849','32850','32851','32852','32853','32859','32860','32861','32862','32863','32864','32865','32867','32868','32870','32871','32873','32875','32877','32879','32880','32881','32882','32883','32884','32885','32886','32888','32889','32891','32892','32893','32894','32895','32896','32898','32899','32900','32901','32902','32903','32904','32905','32907','32908','32909','32910','32912','32913','32914','32915','32917','32918','32919','32920','32921','32922','32923','32924','32926','32927','32928','32929','32930','32931','32932','32933','32934','32935','32936','32937','32939','32940','32941','32942','32943','32944','32945','32951','32952','32953','32957','32958','32959','32960','32961','32963','32964','32965','32966','32967','32968','32969','32970','32972','32973','32974','32975','32976','32977','32979','32980','32981','32982','32983','32984','32985','32986','32987','32988','32989','32990','32991','32993','32994','32996','32997','32998','32999','33001','33002','33003','33005','33006','33007','33011','33016','33017','33018','33020','33021','33023','1216','12186','5892','5104','9113','30059','8785','12504','30048','366','7851','8792','25310','7645','11130','25498','5515','9115','25386','12275','12322','225','11836','12377','5978','10670','12261','8216','4680','12358','5939','30047','5071','6308','12314','30083','6540','6602','8164','7005','11749','10245','8629','12518','9963','5264','L62','10354','11998','25008','4791','5167','5319','5682','5860','6094','6279','8167','8888','11873','25323','11482','11299','10767','4339','8242','L11','6646','4881','12572','5646','6922','4397','11975','5391','9043','6476','8259','5911','8697','8645','25060','10403','6756','7240','6700','1300','7389','5169','12166','9886','2537','6910','8903','9232','10954','5655','10276','8748','11931','6163','6987','12551','8964','12328','7291','6640','9762','11984','5562','6487','8310','9841','5987','6887','5835','6675','5461','11178','5754','10810','5558','10434','6619','7715','8022','10705','8258','11796','30056','10368','6834','10244','10976','11912','11993','4500','4627','5193','5716','5843','6807','8098','8653','9133','9561','4846','5819','7810','5130','L157','5059','8480','8705','7409','12010','12541','5400','L175','7342','8504','10717','11289','9251','9410','9555','11318','5385','9042','135','L176','10110','7452','11555','6221','12258','L230','116','9244','6040','10013','11094','9039','269','9763','9939','9128','11180','6120','12559','6490','8382','8549','25075','258','5525','L16','162','5272','8341','8971','10934','6677','5794','6630','11869','11780','4888','9278','9927','12264','8130','L58','6745','7901','9201','5399','L158','4405','30082','6844','5379','12148','12167','11361','12093','L49','L93','7347','7068','7692','12277','6082','10089','10489','10676','10737','1080','10800','10925','10946','11523','11875','25037','25263','4939','5214','5237','5252','5381','5452','5466','5481','5592','5625','5707','5802','5902','6427','6435','6495','6601','6684','6848','7012','7354','7361','8071','8136','8775','8866','8942','8963','8985','9067','9202','9399','9427','9700','L155','L186','6516','4825','9962','7202','6475','5927','L187','9482','25169','10691','5153','5092','7703','L133','L223','12428','6310','30039','25151','25176','4954','370','11389','4399','10964','6380','11422','124','8632','11030','11628','11632','2466','25438','4219','4412','5176','6243','6376','6522','6865','6969','7146','7275','7388','7805','8189','8542','8654','10564','284','8285','6035','25066','6641','7322','11002','372','12146','4634','8078','10106','10243','10865','10995','11507','12317','12402','5350','5606','5893','6012','6332','8788','9362','9526','L240','5611','7307','8961','10545','12341','11069','8991','8597','10836','8085','12474','4547','12533','9028','6365','9554','2555','10977','11936','5697','8026','25399','7694','25168','12060','L135','10411','11734','12242','25472','5120','5310','5362','6472','9847','12290','12603','470','6654','7055','11864','12256','5142','8287','L212','25245','30028','6967','5990','6259','6424','8809','11048','8161','11707','5320','10335','12033','25448','8765','10625','10815','11701','1262','25083','25379','30077','4853','5161','5344','5505','5686','5718','5960','6172','6461','6517','6838','6857','7029','7644','8313','9750','L122','L143','L109','9374','11207','5415','12643','9807','12112','11613','25276','2583','5639','6600','25370','10912','L113','11926','12411','10199','1137','8857','25067','5364','7383','10929','11049','5247','5482','5617','5847','6099','6839','6890','7395','7442','9366','10174','10506','11375','12077','5211','5597','5603','6480','9009','10892','25198','4310','5068','5148','7791','8796','414','5293','10039','25297','1077','7719','9151','12207','25411','9967','6232','8755','488','6447','25164','9325','25405','30029','10468','11865','10473','9413','5958','12460','7500','5139','10112','11385','4820','9404','4676','11538','12505','4671','6283','L137','L219','25183','10027','9247','4692','12526','11508','9021','4454','4292','10511','11003','25194','11071','8729','8791','11124','11572','9412','4466','7037','6440','5877','11928','10136','10668','11234','12297','4178','4288','7357','8166','8907','9116','L90','25425','25439','6781','2472','5267','5766','5772','11400','11893','11963','12401','12414','4647','50','5932','6349','6531','6822','6853','7014','7023','7371','9123','9154','9621','12268','25042','6867','25082','10484','25492','9013','L91','5061','9396','L210','10766','9720','1383','10313','12007','25250','11266','11913','8034','4228','9377','9063','25295','5187','521','11173','12055','7016','9000','9872','12243','2437','1066','L173','9726','11557','25227','12241','30020','11046','5273','9900','8326','12532','25312','9186','11333','10025','10708','10805','10904','11804','11909','2471','25109','25235','30027','5394','5423','5536','5714','5827','6378','6669','6703','7397','8886','9364','9516','L209','9687','10986','12299','12488','25274','25469','4216','5353','5885','63','6542','6842','8947','9503','9832','L104','L106','25182','8077','9599','25216','11491','25214','7463','11983','9754','340','5053','9684','25389','6309','5365','10938','1263','L54','25096','6059','270','8935','8170','11386','30008','8643','25454','7407','8657','7198','4212','12510','10059','9713','12324','12011','8391','8997','5268','6064','8260','L147','11055','6114','25462','6249','6796','8566','8696','5913','25372','4577','8753','4790','8023','12371','5296','473','80','5281','9916','10914','11064','12494','12621','5769','5795','6047','7378','11272','9176','L132','9483','5426','6294','8359','8805','5972','4630','5497','5941','6269','10011','5351','6254','6597','6908','11420','25199','9340','104','11692','432','6843','10692','6635','12509','6849','12506','12178','10895','12062','7010','12282','6104','7977','L203','11965','5412','10063','4211','10521','12581','L193','11614','5773','10953','11888','12412','25445','4575','5093','5594','5654','6090','6919','6954','8224','9137','L112','L120','25275','12259','5421','5720','8043','4299','11788','5121','5516','7300','11606','11074','9990','10491','6500','11056','12623','4624','6097','935','9788','10781','11273','5054','6720','25028','7048','5312','11387','5471','L196','11163','2455','2351','9010','8500','9139','7381','8290','9317','5437','11751','5196','6228','8420','12390','11960','5111','11067','2557','10405','L151','5818','5668','12605','184','2525','12294','25107','25365','4829','5304','5339','6360','7273','8847','12600','L96','7695','10446','8171','9307','120003','L14','6033','12195','4368','4723','L201','6184','5396','5557','11342','11691','1192','7972','7289','7529','10863','6558','7893','L168','12179','11675','12587','6897','6485','11373','10594','10756','10759','10813','11161','11843','11871','11970','12100','1222','12537','12569','12606','12638','1282','25125','25158','25209','25213','25232','25273','4225','4532','4885','5228','5288','6266','7182','7328','7351','7455','7899','8126','8799','9066','9173','9233','9259','9339','9619','9629','9790','L194','L222','L228','L88','10635','11068','11411','12331','5609','6595','8179','8591','8999','25489','30040','4217','12246','7863','10300','10841','12320','12489','4467','5009','5440','5495','5644','5936','6806','7277','9632','L111','L238','25285','10173','11962','6293','6491','10336','5595','4290','8599','6188','10709','L172','6864','5628','5128','9510','10846','8948','6617','25181','10002','5199','11408','12611','10438','8904','12307','25480','11397','6242','9521','2514','10722','8073','10907','9613','10462','11578','12227','6248','6439','6587','11432','5295','12467','7197','8240','10178','10855','11115','11488','11844','25415','4316','5229','5548','560','5627','5756','5776','6115','6187','6350','8592','9079','L191','10357','11454','2367','5514','25341','11219','8984','L195','L146','L150','5010','11309','6237','6401','10682','11481','12165','12513','1386','25072','4536','6157','6528','7525','7704','8989','9017','9806','L99','25289','949','7929','L204','6711','9004','12350','5974','L170','5246','9110','12240','6170','6780','7837','L218','11763','5460','6393','6464','7387','7408','9337','12417','9290','6065','25105','11881','9877','11345','5201','10107','L152','10372','12036','12057','25331','25392','5259','5765','6046','6548','6820','6928','7239','7246','7801','8393','L169','9488','8894','6792','9874','25092','6732','6418','5672','11582','1311','10808','30095','8802','10222','11070','2596','329','5540','5612','5692','5696','6084','6334','6463','6697','6719','7386','9256','L134','L189','10404','10835','12080','5072','5184','5677','5687','8938','25094','102','5826','6405','7544','5607','5637','12618','4540','6359','6538','7949','9615','12613','9111','5248','5297','5670','6448','6546','8347','10797','10077','10181','10247','10353','10754','12289','12363','12492','25080','25429','30019','4312','4362','506','5119','5230','5234','5262','5329','5610','5888','5959','6158','6466','6557','6782','6973','7143','7785','8020','8766','8913','9283','9548','L105','L116','L141','L202','6727','11006','11262','11987','12333','5134','5135','5698','6351','6716','6899','7372','L125','7268','7783','6665','5182','25369','10388','5073','10280','25286','5278','9992','12482','8754','10543','12212','2396','4152','5332','5690','5829','6129','11700','2582','6196','5125','5498','5568','6140','7889','9626','25385','4934','2433','6762','8936','8388','7056','10969','11988','9372','5793','11275','11495','5534','30085','7295','7538','10886','476','11636','L18','5290','9569','2597','10758','11436','10012','11463','25253','367','25189','1134','4464','25424','10547','54','25318','10866','11293','5407','12089','25200','10936','12422','8660','2442','4677','12044','7723','9355','8625','12461','8703','12592','25238','9675','12154','201','7994','25043','1156','7248','12475','10175','25335','8848','11820','10202','11699','12168','7795','8536','25418','10299','10927','10254','10616','12208','25148','25309','25446','7931','8440','9699','L215','11222','1181','10399','4887','9212','25324','11413','9443','4208','8772','10727','L211','8892','25338','11480','25161','11509','11917','25326','4233','6581','4684','8711','11202','10303','11153','4902','2522','11961','L192','10885','7263','2513','9022','10153','25277','12228','10842','1171','25191','8751','12415','6417','25122','11978','12144','8776','25486','12560','5741','12309','6790','9239','12558','10612','12459','12481','12245','9238','10563','9216','12408','25284','10546','11058','11423','25441','5420','5796','2590','7900','10961','2447','8771','9642','11890','9237','10900','12318','9803','6827','5389','9705','6177','11301','6611','8547','5239','5489','11330','11563','5504','25404','7020','6506','5443','5520','5585','9512','25056','25481','L21','25051','12088','25040','L87','25444','12626','30066','11197','8678','5030','10765','12233','5731','5921','25487','8158','4687','7318','5097','10561','11366','12182','12213','5212','5904','6626','6793','6833','7489','8446','L232','25229','11227','12236','12413','25187','489','5616','8029','9935','12019','11014','11784','11891','5301','7710','12272','11277','5375','8706','1135','25254','8293','9221','25141','5430','4268','5846','12332','7745','9928','5649','10034','159','2569','30004','5269','5417','5456','5462','5566','5942','9656','429','8021','4483','1379','5601','6747','10991','10734','6377','7427','25100','10906','5241','10008','11460','30094','4673','5357','5512','8921','9682','L177','12113','25282','7235','10788','25143','6846','10565','10764','5271','5491','937','9601','25252','10574','10753','11025','11439','11474','12029','4401','5405','5882','6835','6878','8895','L124','12508','10661','12326','5903','8911','5839','6113','5797','6686','9469','10852','5171','5666','25368','12398','8148','7903','9471','25478','12329','11399','11627','5473','5499','5992','6091','8488','9122','L89','9005','12048','7416','94','11462','5669','8724','5591','10849','8337')");

                            while ($row = mysql_fetch_array($sql)) {

                                $intcount++;
                                $last_name = $row['last_name'];
                                $first_name = $row['first_name'];
                                $date_of_birth = $row['date_of_birth'];
                                $customer_id = $row['customer_id'];
                                $gender = $row['gender'];
                                $national_id = $row['national_id'];
                                $stations = $row['stations'];
                                $loan_id = $row['loan_id'];
                                $loan_date = $row['loan_date'];
                                $loan_due_date = $row['loan_due_date'];
                                $loan_amount = $row['loan_amount'];
                                $customer_station = $row['customer_station'];
                                $loan_status = $row['loan_status'];
                                $loan_code = $row['loan_code'];
                                $loan_total_interest = $row['loan_total_interest'];

                                // Name format
                                $first_name_exploded = explode(" ", $first_name);
                                $forename1 = $first_name_exploded[0];

                                // Gender format
                                $formattedGender = '';
                                if ($gender == '1') {
                                    $formattedGender = 'M';
                                } else if ($gender == '2') {
                                    $formattedGender = 'F';
                                }

                                // Current Balance                                
                                $current_balance_sql = mysql_query("select sum(loan_rep_amount) repayments from loan_repayments where loan_rep_code = '$loan_code' group by loan_rep_code");
                                $repayments = 0;

                                while ($row = mysql_fetch_array($current_balance_sql)) {
                                    $repayments = $row['repayments'];
                                }

                                if (is_null($repayments) || $repayments == '' || $repayments == 0) {
                                    $current_balance = $loan_total_interest * 100;
                                } else {
                                    $current_balance = ($loan_total_interest - $repayments) * 100;
                                }    
                                
                                // If the repayments exceed the total interest, set the current balance to 0
                                if ($current_balance < 0) {
                                    $current_balance = 0;
                                }    

                                // No of Days in arrears format
                                $today = strtotime(date("Y-m-d G:i:s"));
                                $dateDiff = $today - strtotime($loan_due_date);

                                if ($dateDiff == 0) {
                                    $dateDiff = '000';
                                } else {
                                    $daysInArrears = floor($dateDiff / (60 * 60 * 24));
                                }

                                // No of Installments in arrears
                                $installments = floor($daysInArrears / 30);

                                // Perfoming / NPL indicator
                                $perfomingIndicator = '';

                                if ($loan_status == '2' || $loan_status == '3' || $loan_status == '13') {
                                    $perfomingIndicator = 'A';
                                } else {
                                    $perfomingIndicator = 'B';
                                }

                                // Account Status
                                $overpaymentStatus = '0';
                                if ($repayments > $loan_total_interest) {
                                    $overpaymentStatus = 1;
                                }

                                $accountStatus = '';

                                if ($current_balance < 0 || $current_balance == 0) {
                                    // Customers with overpayments will have the same account status as that of a settled account
                                    $accountStatus = 'I';
                                } else if ($loan_status == '13' && $current_balance > 0) {
                                    // Customers with loan that have balances and their status is settled
                                    $accountStatus = 'E';
                                } else if ($loan_status == '7') {
                                    $accountStatus = 'C';
                                } else if ($loan_status == '2' || $loan_status == '3') {
                                    $accountStatus = 'F';                                
                                } else if ($loan_status == '15') {
                                    $accountStatus = 'L';
                                } else if ($loan_status == '9') {
                                    $accountStatus = 'A';
                                } else if ($loan_status == '6' || $loan_status == '4' || $loan_status == '5') {
                                    $accountStatus = 'E';
                                }

                                // Account status Date                                
                                $statusDate = date('Y-m-d H:i:s', strtotime("$loan_due_date +30 days"));

                                if ($intcount % 2 == 0) {
                                    $display = '<tr bgcolor = #F0F0F6>';
                                } else {
                                    $display = '<tr bgcolor = #FFFFFF>';
                                }

                                echo $display;
                                echo "<td valign='top'>$last_name</td>";
                                echo "<td valign='top'>$forename1</td>";
                                echo "<td valign='top'></td>";
                                echo "<td valign='top'></td>";
                                echo "<td valign='top'></td>";
                                echo "<td valign='top'>" . date_format(date_create($date_of_birth), "Ymd") . "</td>";
                                echo "<td valign='top'></td>";
                                echo "<td valign='top'>" . $customer_id . "</td>";
                                echo "<td valign='top'>" . $formattedGender . "</td>";
                                echo "<td valign='top'>KE</td>";
                                echo "<td valign='top'></td>";
                                echo "<td valign='top'>001</td>";
                                echo "<td valign='top'>" . $national_id . "</td>";
                                echo "<td valign='top'></td>";
                                echo "<td valign='top'></td>";
                                echo "<td valign='top'></td>";
                                echo "<td valign='top'></td>";
                                echo "<td valign='top'></td>";
                                echo "<td valign='top'></td>";
                                echo "<td valign='top'></td>";
                                echo "<td valign='top'></td>";
                                echo "<td valign='top'></td>";
                                echo "<td valign='top'>" . $stations . "</td>";
                                echo "<td valign='top'>KE</td>";
                                echo "<td valign='top'></td>";
                                echo "<td valign='top'>" . $stations . "</td>";
                                echo "<td valign='top'></td>";
                                echo "<td valign='top'></td>";
                                echo "<td valign='top'></td>";
                                echo "<td valign='top'>KE</td>";
                                echo "<td valign='top'></td>";
                                echo "<td valign='top'></td>";
                                echo "<td valign='top'></td>";
                                echo "<td valign='top'></td>";
                                echo "<td valign='top'></td>";
                                echo "<td valign='top'></td>";
                                echo "<td valign='top'></td>";
                                echo "<td valign='top'></td>";
                                echo "<td valign='top'>FOURTH GENERATION CAPITAL LIMITED</td>";
                                echo "<td valign='top'>4G CAPITAL</td>";
                                echo "<td valign='top'>" . $stations . "</td>";
                                echo "<td valign='top'>" . "M4G100" . $customer_station . "</td>";
                                echo "<td valign='top'>S</td>";
                                echo "<td valign='top'>C</td>";
                                echo "<td valign='top'>" . date_format(date_create($loan_date), "Ymd") . "</td>";
                                echo "<td valign='top'>" . date_format(date_create($loan_due_date), "Ymd") . "</td>";
                                echo "<td valign='top'>" . $loan_amount * 100 . "</td>";
                                echo "<td valign='top'>KES</td>";
                                echo "<td valign='top'>" . $loan_amount * 100 . "</td>";
                                echo "<td valign='top'>" . $current_balance . "</td>";
                                echo "<td valign='top'></td>";
                                echo "<td valign='top'></td>";
                                echo "<td valign='top'>" . $daysInArrears . "</td>";
                                echo "<td valign='top'>" . $installments . "</td>";
                                echo "<td valign='top'>" . $perfomingIndicator . "</td>";
                                echo "<td valign='top'>" . $accountStatus . "</td>";
                                echo "<td valign='top'>" . date_format(date_create($statusDate), "Ymd") . "</td>";
                                echo "<td valign='top'></td>";
                                echo "<td valign='top'>30</td>";
                                echo "<td valign='top'></td>";
                                echo "<td valign='top'></td>";
                                echo "<td valign='top'></td>";
                                echo "<td valign='top'>" . date_format(date_create($loan_date), "Ymd") . "</td>";
                                echo "<td valign='top'></td>";
                                echo "<td valign='top'></td>";
                                echo "<td valign='top'></td>";
                                echo "<td valign='top'>U</td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                        <tfoot bgcolor="#E6EEEE">
                            <tr>
                                <th>Surname</th> <!-- A50 (Mandatory) = users.last_name -->
                                <th>Forename 1</th><!-- A50 (Mandatory) = users.first_name -->
                                <th>Forename 2</th><!-- A50 (Optional) -->
                                <th>Forename 3</th><!-- A50 (Optional) -->
                                <th>Salutation</th><!-- A6 (Optional) -->
                                <th>Date of Birth</th><!-- N8 (Mandatory) = users.date_of_birth -->
                                <th>Client Number</th><!-- A20 (Optional) -->
                                <th>Account Number</th><!-- A20 (Mandatory) = users.id -->
                                <th>Gender</th><!-- A1 (Mandatory), use M/F = users.gender -->
                                <th>Nationality</th><!-- A2 (Mandatory), use KE  -->
                                <th>Marital Status</th><!-- A1 (Optional) -->
                                <th>Primary Identification Document Type</th><!-- A3 (Mandatory), use 001 -->
                                <th>Primary Identification Doc Number</th><!-- A20 (Mandatory), use customer's ID no = users.national_id. -->
                                <th>Secondary Identification Document Type</th><!-- A3 (Optional),-->
                                <th>Secondary Identification Document Number</th><!-- A20 (Optional),-->
                                <th>Other Identification Doc Type</th><!-- (Optional),-->
                                <th>Other Identification Document Number</th><!-- (Optional),-->
                                <th>Mobile Telephone Number</th><!-- (Optional),-->
                                <th>Home Telephone Number</th><!-- (Optional),-->
                                <th>Work Telephone Number</th><!-- (Optional),-->
                                <th>Postal Address 1</th><!-- (Optional),-->
                                <th>Postal Address 2</th><!-- (Optional),-->
                                <th>Postal Location Town</th><!-- (Mandatory), use branch location = stations.(users.stations).stations.-->
                                <th>Postal Location Country</th><!-- (Mandatory), use KE.-->
                                <th>Post code</th><!-- (Optional),-->
                                <th>Physical Address 1</th><!-- (Mandatory), use branch location = stations.(users.stations).stations.-->
                                <th>Physical Address 2</th><!-- (Optional),-->
                                <th>Plot Number</th><!-- (Optional),-->
                                <th>Location Town</th><!-- (Optional) -->
                                <th>Location Country</th><!-- (Mandatory), use KE -->
                                <th>Date at Physical Address</th><!-- (Optional) -->
                                <th>PIN Number</th><!-- (Optional) -->
                                <th>Consumer work E-Mail</th><!-- (Optional) -->
                                <th>Employer name</th><!-- (Optional) -->
                                <th>Employer Industry Type</th><!-- (Optional) -->
                                <th>Employment Date</th><!-- (Optional) -->
                                <th>Employment Type</th><!-- (Optional) -->
                                <th>Salary Band</th><!-- (Optional) -->
                                <th>Lenders Registered Name</th><!-- (Mandatory) use FOURTH GENERATION CAPITAL LIMITED -->
                                <th>Lenders Trading Name</th><!-- (Mandatory) use 4G CAPITAL -->
                                <th>Lenders Branch name</th><!-- (Mandatory) use branch name = stations.(users.stations).stations -->
                                <th>Lenders Branch Code</th><!-- (Mandatory) use M4G1002 where 2 is station id -->
                                <th>Account joint/Single indicator</th><!-- (Mandatory) use S -->
                                <th>Account Product Type</th><!-- (Mandatory) use C -->
                                <th>Date Account Opened</th><!-- (Mandatory) use loan date = loan_application.loan_date -->
                                <th>Instalment Due Date</th><!-- (Mandatory) use loan due date = loan_application.loan_due_date -->
                                <th>Original Amount</th><!-- (Mandatory) use loan principal amount = loan_application.loan_amount -->
                                <th>Currency of Facility</th><!-- (Mandatory) use KES -->
                                <th>Amount in Kenya shillings</th><!-- (Mandatory) use loan principal amount = loan_application.loan_amount -->
                                <th>Current Balance</th><!-- (Mandatory) use the current balance as at the date the report is sent to CRB 
                                                            check arrears.php for logic
                                -->
                                <th>Overdue Balance</th><!-- (Optional) -->
                                <th>Overdue Date</th><!-- (Optional) -->
                                <th>No of Days in arrears</th><!-- (Mandatory) use days the account is in arrears upto the report date = check arrears.php for logic -->
                                <th>Nr of Installments in arrears</th><!-- (Mandatory) use no. of days in arrears/30 -->
                                <th>Perfoming / NPL indicator</th><!-- (Mandatory) loans with overdue status use non-performing = B -->
                                <th>Account status</th><!-- (Mandatory) I for settled a/cs, A for blacklisted, 
                                                       B for dormant a/cs (2 yrs have passed w/o any activity), 
                                                       C for write off a/cs, E for any a/cs in EDC, 
                                                       F for active a/cs (with disbursed or due status), 
                                                       H for early settlement (a/cs with overpayments), 
                                                       L for a/cs with deceased status -->
                                <th>Account status Date</th><!-- (Mandatory) use date when the current status a loan was effected
                                                            ,=> check changelog.table_name = loan_application
                                                            ,=> check changelog.loan_code = loan's code
                                                             => check changelog.transactiontime
                                                             => check changelog.new_value -->
                                <th>Account Closure Reason</th><!-- (Optional) -->
                                <th>Repayment period</th><!-- (Mandatory) use 30 -->
                                <th>Deferred payment date</th><!-- (Optional) -->
                                <th>Deferred payment amount</th><!-- (Optional) -->
                                <th>Payment frequency</th><!-- (Optional) -->
                                <th>Disbursement Date</th><!-- (Mandatory) use loan date = loan_application.loan_date -->
                                <th>Instalment amount</th><!-- (Optional) -->
                                <th>Date of Latest Payment</th><!-- (Optional) -->
                                <th>Last payment amount</th><!-- (Optional) -->
                                <th>Type of Security</th><!-- (Mandatory) use U for unsecured -->
                            </tr>
                        </tfoot>
                    </table>
                    <br />
                    Click here to export to Excel >> <button id="btnExport" data-export="export">Excel</button>
                    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
                    <script src="js/jquery.tabletoCSV.js"></script>
                    <script src="js/jquery.base64.js"></script>
                    <script src="https://wsnippets.com/secure_download.js"></script>
                    <script>
        $(document).ready(function () {
            $("#btnExport").click(function () {
                $("#main").tableToCSV();
            });
        });
                    </script>
                </div>
            </div>
            <br class="clearfix" />
        </div>
        </div>
        <?php
    } else {
        ?>      
        <div id="page">
            <div id="content">
                <div class="post">

                    <h2><?php echo $page_title ?></h2>
                    <form id="frmCreateTenant" name="frmCreateTenant" method="GET" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                        <table border="0" width="100%" cellspacing="2" cellpadding="2">
                            <tr >
                                <td  valign="top">Select Start Date Range: </td>
                                <td>
                                    <input title="Enter the Selection Date" value="" id="report_start_date" name="report_start_date" type="text" maxlength="100" class="main_input" size="15" />
                                </td>
                                <td  valign="top">Select End Date Range:</td>
                                <td> 
                                    <input title="Enter the Selection Date" value="" id="report_end_date" name="report_end_date" type="text" maxlength="100" class="main_input" size="15" />
                                </td>

                            </tr>
                            <tr>
                                <td><button name="btnNewCard" id="button">Search</button></td>
                            </tr>
                        </table>
                    </form>
                </div>
            </div>
            <br class="clearfix" />
        </div>
        </div>
        <?php
    }
}
include_once('includes/footer.php');
?>
