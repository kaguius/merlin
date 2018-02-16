<?php
	$userid = "";
	$adminstatus = "";
	session_start();
	if (!empty($_SESSION)){
		$userid = $_SESSION["userid"] ;
		$adminstatus = $_SESSION["adminstatus"] ;
	}
	if($adminstatus == 3){
		include_once('includes/header.php');
		?>
		<script type="text/javascript">
			document.location = "insufficient_permission.php";
		</script>
		<?php
	}
	else{
		include_once('includes/db_conn.php');
		if (!empty($_GET)){		
			$status = $_GET['status'];
		}
		$transactiontime = date("Y-m-d G:i:s");
		if (!empty($_GET)){	
			$action = $_GET['action'];
			$id = $_GET['id'];
			if ($action=='edit'){
				$page_title = "Edit User Title Detail";
				$result = mysql_query("select id, title, active, transactiontime from title where id = '$id'");
				while ($row = mysql_fetch_array($result))
				{
					$id = $row['id'];
					$title = $row['title'];
					$active = $row['active'];
					if($active == '1'){
						$active_name = 'No';
					}
					else{
						$active_name = 'Yes';
					}
				}
			}
		}
		else{
			$page_title = "Create New User Title";
		}
		include_once('includes/header.php');
	?>
	<div id="page">
			<div id="content">
				<div class="post">
				<h2><?php echo $page_title ?></h2>
					<form id="frmExecPosition" name="frmExecPosition" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">

						<input type="hidden" name="page_status" id="page_status" value="<?php echo $action ?>" />
						<input type="hidden" name="id" id="id" value="<?php echo $id ?>" />
						<table border="0" width="100%">
							<tr bgcolor = #F0F0F6>
								<td>title *</td>
								<td>
									<input title="Enter Title" value="<?php echo $title ?>" id="title" name="title" type="text" maxlength="100" class="main_input" size="30" />
								</td>
							</tr>
							<tr >
								<td valign='top' >Active *</td>
								<td valign='top' >
									<select name='active' id='active'>
										<?php
										if($action == 'edit'){
										?>
										<option value="<?php echo $active ?>"><?php echo $active_name ?></option>	
										<?php
										}
										else{
										?>
										<option value=''> </option>
										<?php
										}
										?>
										<option value=''> </option>
										<option value='0'>Yes</option>
										<option value='1'>No</option>
									</select>
								</td>
							</tr>
							<?php if($status == 'title_exists'){ ?>
								<tr>
									<td colspan="2">
										<font color="red">The title specified already exists, enter another one.</font></a>	
									</td>
								</tr>
							<?php } ?>		
						</table>			
						<table border="0" width="100%">
							<tr>
								<td valign="top">
									<button name="btnNewCard" id="button">Save</button>
								</td>
								<td align="right">
									<button name="reset" id="button2" type="reset">Reset</button>
								</td>		
							</tr>
						</table>
						<script  type="text/javascript">
							var frmvalidator = new Validator("frmExecPosition");
							frmvalidator.addValidation("title","req","Please enter the title Name");
							frmvalidator.addValidation("active","req","Please enter Status of the Title");
						</script>
					</form>
			</div>
			</div>
			<br class="clearfix" />
			</div>
		</div>
		<?php
			if (!empty($_POST)) {
				$title = $_POST['title'];
				$active = $_POST['active'];
				
				$page_status = $_POST['page_status'];
				$id = $_POST['id'];
				
				if($page_status == 'edit'){
					$sql3="
					update title set title='$title', active= '$active', transactiontime = '$transactiontime' WHERE ID  = '$id'";
					$result = mysql_query($sql3);
					//echo $sql3;
				}
				else{
					$result_tender = mysql_query("select distinct title from title where title = '$title'");
					while ($row = mysql_fetch_array($result_tender))
					{
						$title_exists = $row['title'];
					}
				
					$title_exists = strtolower($title_exists);
					$title = strtolower($title);

					if(($title_exists != $title)) {
						$title = ucwords(strtolower($title));
						$sql="
						INSERT INTO title (title, active, transactiontime)
						VALUES('$title', '$active', '$transactiontime')";

						//echo $sql;
						$result = mysql_query($sql);
					}				
					else{

						$title_exists = MD5(title_exists);
						$query = "update_title.php?status=title_exists&title_status=$title_exists";
						//echo $query;
						?>
						<script type="text/javascript">
						<!--
							/*alert("Either the Email Address or the Password do not match the records in the database or you have been disabled from the system, please contact the system admin at www.e-kodi.com/contact.php");*/
							document.location = "<?php echo $query ?>";
						//-->
						</script>
						<?php
					}	
				}

				?>
				<script type="text/javascript">
				<!--
					document.location = "title.php";
				//-->
				</script>
				<?php
			}				
	}
?>
<?php
	include_once('includes/footer.php');
?>
