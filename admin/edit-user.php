<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['alogin'])==0)
	{
header('location:index.php');
}
else{

if(isset($_GET['edit']))
	{
		$editid=$_GET['edit'];
	}



if(isset($_POST['submit']))
  {
	// $file = $_FILES['image']['name'];
	// $file_loc = $_FILES['image']['tmp_name'];
	// $folder="../images/";
	// $new_file_name = strtolower($file);
	// $final_file=str_replace(' ','-',$new_file_name);

	$name=$_POST['name'];
	$email=$_POST['email'];
	$gender=$_POST['gender'];
	$mobileno=$_POST['mobileno'];
	$designation=$_POST['designation'];
	$idedit=$_POST['idedit'];
	// $image=$_POST['image'];

	// if(move_uploaded_file($file_loc,$folder.$final_file))
	// 	{
	// 		$image=$final_file;
	// 	}

		$sql = "UPDATE users SET
		denumirea_agentului_economic=(:denumirea_agentului_economic),
		idno_cod_fiscal=(:idno_cod_fiscal),
		adresa_juridica=(:adresa_juridica),
		nume_prenumele_conducator=(:nume_prenumele_conducator),
		contacte=(:contacte),
		email=(:email),
		tip_unitate_de_comert=(:tip_unitate_de_comert),
		gen_de_activitate=(:gen_de_activitate),
		adresa_amplasarii_unitatilor=(:adresa_amplasarii_unitatilor),
		program_de_activitate=(:program_de_activitate),
		suprafata_comerciala=(:suprafata_comerciala),
		nr_de_locuri=(:nr_de_locuri),
		nr_notificarii=(:nr_notificarii),
		additional=(:additional) WHERE id=(:idedit)";
        $query = $dbh->prepare($sql);
        $query->bindParam(':denumirea_agentului_economic', $_POST['denumirea_agentului_economic'], PDO::PARAM_STR);
        $query->bindParam(':idno_cod_fiscal', $_POST['idno_cod_fiscal'], PDO::PARAM_STR);
        $query->bindParam(':adresa_juridica', $_POST['adresa_juridica'], PDO::PARAM_STR);
        $query->bindParam(':nume_prenumele_conducator', $_POST['nume_prenumele_conducator'], PDO::PARAM_STR);
        $query->bindParam(':contacte', $_POST['contacte'], PDO::PARAM_STR);
        $query->bindParam(':email', $_POST['email'], PDO::PARAM_STR);
        $query->bindParam(':tip_unitate_de_comert', $_POST['tip_unitate_de_comert'], PDO::PARAM_STR);
        $query->bindParam(':gen_de_activitate', $_POST['gen_de_activitate'], PDO::PARAM_STR);
        $query->bindParam(':adresa_amplasarii_unitatilor', $_POST['adresa_amplasarii_unitatilor'], PDO::PARAM_STR);
        $query->bindParam(':program_de_activitate', $_POST['program_de_activitate'], PDO::PARAM_STR);
        $query->bindParam(':suprafata_comerciala', $_POST['suprafata_comerciala'], PDO::PARAM_STR);
        $query->bindParam(':nr_de_locuri', $_POST['nr_de_locuri'], PDO::PARAM_STR);
        $query->bindParam(':nr_notificarii', $_POST['nr_notificarii'], PDO::PARAM_STR);
        $query->bindParam(':additional', $_POST['additional'], PDO::PARAM_STR);
        $query->bindParam(':idedit', $_POST['idedit'], PDO::PARAM_INT);
        $query->execute();

        $msg = "Information Updated Successfully";
}
?>

<!doctype html>
<html lang="en" class="no-js">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
	<meta name="description" content="">
	<meta name="author" content="">
	<meta name="theme-color" content="#3e454c">

	<title>Edit User</title>

	<!-- Font awesome -->
	<link rel="stylesheet" href="css/font-awesome.min.css">
	<!-- Sandstone Bootstrap CSS -->
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<!-- Bootstrap Datatables -->
	<link rel="stylesheet" href="css/dataTables.bootstrap.min.css">
	<!-- Bootstrap social button library -->
	<link rel="stylesheet" href="css/bootstrap-social.css">
	<!-- Bootstrap select -->
	<link rel="stylesheet" href="css/bootstrap-select.css">
	<!-- Bootstrap file input -->
	<link rel="stylesheet" href="css/fileinput.min.css">
	<!-- Awesome Bootstrap checkbox -->
	<link rel="stylesheet" href="css/awesome-bootstrap-checkbox.css">
	<!-- Admin Stye -->
	<link rel="stylesheet" href="css/style.css">

	<script type= "text/javascript" src="../vendor/countries.js"></script>
	<style>
.errorWrap {
    padding: 10px;
    margin: 0 0 20px 0;
	background: #dd3d36;
	color:#fff;
    -webkit-box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
    box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
}
.succWrap{
    padding: 10px;
    margin: 0 0 20px 0;
	background: #5cb85c;
	color:#fff;
    -webkit-box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
    box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
}
		</style>
</head>

<body>
<?php
		$sql = "SELECT * from users where id = :editid";
		$query = $dbh -> prepare($sql);
		$query->bindParam(':editid',$editid,PDO::PARAM_INT);
		$query->execute();
		$result=$query->fetch(PDO::FETCH_OBJ);
		$cnt=1;
?>
	<?php include('includes/header.php');?>
	<div class="ts-main-content">
	<?php include('includes/leftbar.php');?>
		<div class="content-wrapper">
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-12">
						<h3 class="page-title">Edit User : <?php echo htmlentities($result->name); ?></h3>
						<div class="row">
							<div class="col-md-12">
								<div class="panel panel-default">
									<div class="panel-heading">Edit Info</div>
<?php if($error){?><div class="errorWrap"><strong>ERROR</strong>:<?php echo htmlentities($error); ?> </div><?php }
				else if($msg){?><div class="succWrap"><strong>SUCCESS</strong>:<?php echo htmlentities($msg); ?> </div><?php }?>

									<div class="panel-body">
	<form method="post" class="form-horizontal" enctype="multipart/form-data" name="imgform">
				<input type="hidden" name="idedit" value="<?php echo htmlentities($result->id);?>">

                <div class="form-group">
                    <label class="col-sm-4 control-label">Denumirea Agentului Economic<span class="text-danger">*</span></label>
                    <div class="col-sm-8">
                        <input type="text" name="denumirea_agentului_economic" class="form-control" required value="<?php echo htmlentities($result->denumirea_agentului_economic);?>">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-4 control-label">IDNO / Cod Fiscal<span class="text-danger">*</span></label>
                    <div class="col-sm-8">
                        <input type="text" name="idno_cod_fiscal" class="form-control" required value="<?php echo htmlentities($result->idno_cod_fiscal);?>">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-4 control-label">Adresa Juridică<span class="text-danger">*</span></label>
                    <div class="col-sm-8">
                        <input type="text" name="adresa_juridica" class="form-control" required value="<?php echo htmlentities($result->adresa_juridica);?>">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-4 control-label">Nume/Prenume Conducător<span class="text-danger">*</span></label>
                    <div class="col-sm-8">
                        <input type="text" name="nume_prenumele_conducator" class="form-control" required value="<?php echo htmlentities($result->nume_prenumele_conducator);?>">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-4 control-label">Contacte<span class="text-danger">*</span></label>
                    <div class="col-sm-8">
                        <input type="text" name="contacte" class="form-control" value="<?php echo htmlentities($result->contacte);?>">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-4 control-label">Email<span class="text-danger">*</span></label>
                    <div class="col-sm-8">
                        <input type="email" name="email" class="form-control" required value="<?php echo htmlentities($result->email);?>">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-4 control-label">Tip Unitate de Comerț<span class="text-danger">*</span></label>
                    <div class="col-sm-8">
                        <input type="text" name="tip_unitate_de_comert" class="form-control" value="<?php echo htmlentities($result->tip_unitate_de_comert);?>">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-4 control-label">Gen de Activitate<span class="text-danger">*</span></label>
                    <div class="col-sm-8">
                        <input type="text" name="gen_de_activitate" class="form-control" value="<?php echo htmlentities($result->gen_de_activitate);?>">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-4 control-label">Adresa Unitatii<span class="text-danger">*</span></label>
                    <div class="col-sm-8">
                        <input type="text" name="adresa_amplasarii_unitatilor" class="form-control" value="<?php echo htmlentities($result->adresa_amplasarii_unitatilor);?>">
                    </div>
                </div>

				<div class="form-group">
                    <label class="col-sm-4 control-label">Suprafata Comerciala<span class="text-danger">*</span></label>
                    <div class="col-sm-8">
                        <input type="text" name="suprafata_comerciala" class="form-control" value="<?php echo htmlentities($result->suprafata_comerciala);?>">
                    </div>
                </div>

				<div class="form-group">
                    <label class="col-sm-4 control-label">Nr locuri<span class="text-danger">*</span></label>
                    <div class="col-sm-8">
                        <input type="text" name="nr_de_locuri" class="form-control" value="<?php echo htmlentities($result->nr_de_locuri);?>">
                    </div>
                </div>


				<div class="form-group">
                    <label class="col-sm-4 control-label">Nr Notificari<span class="text-danger">*</span></label>
                    <div class="col-sm-8">
                        <input type="text" name="nr_notificarii" class="form-control" value="<?php echo htmlentities($result->nr_notificarii);?>">
                    </div>
                </div>

				<div class="form-group">
                    <label class="col-sm-4 control-label">Aditional<span class="text-danger">*</span></label>
                    <div class="col-sm-8">
                        <input type="text" name="additional" class="form-control" value="<?php echo htmlentities($result->additional);?>">
                    </div>
                </div>

				<div class="form-group">
                    <label class="col-sm-4 control-label">Program de activitate<span class="text-danger">*</span></label>
                    <div class="col-sm-8">
                        <input type="text" name="program_de_activitate" class="form-control" value="<?php echo htmlentities($result->program_de_activitate);?>">
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-offset-4 col-sm-8">
                        <button type="submit" class="btn btn-success" name="submit">Salvează modificările</button>
                    </div>
                </div>

</form>
									</div>
								</div>
							</div>
						</div>



					</div>
				</div>



			</div>
		</div>
	</div>

	<!-- Loading Scripts -->
	<script src="js/jquery.min.js"></script>
	<script src="js/bootstrap-select.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/jquery.dataTables.min.js"></script>
	<script src="js/dataTables.bootstrap.min.js"></script>
	<script src="js/Chart.min.js"></script>
	<script src="js/fileinput.js"></script>
	<script src="js/chartData.js"></script>
	<script src="js/main.js"></script>
	<script type="text/javascript">
				 $(document).ready(function () {
					setTimeout(function() {
						$('.succWrap').slideUp("slow");
					}, 3000);
					});
	</script>

</body>
</html>
<?php } ?>