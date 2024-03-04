<?php
session_start();
//error_reporting(0);
session_regenerate_id(true);
include('includes/config.php');

if(strlen($_SESSION['alogin'])==0)
{
    header("Location: /admin/index.php");
}
else
{
?>
<table border="1">
    <thead>
        <tr>
            <th>#</th>
            <th>Denumirea Agentului Economic</th>
            <th>IDNO/Cod Fiscal</th>
            <th>Adresa Juridica</th>
            <th>Nume Prenumele Conducator</th>
            <th>Contacte</th>
            <th>Email</th>
            <th>Tip Unitate de Comert</th>
            <th>Gen de Activitate</th>
            <th>Adresa Amplasarii Unitatilor</th>
            <th>Program de Activitate</th>
            <th>Suprafata Comerciala</th>
            <th>Nr de Locuri</th>
            <th>Nr Notificarii</th>
            <th>Additional</th>
        </tr>
    </thead>

<?php
$filename="Lista_Agenti_Economici";
$sql = "SELECT * from users"; // Ensure your table name is correct
$query = $dbh -> prepare($sql);
$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);
$cnt=1;
if($query->rowCount() > 0)
{
    foreach($results as $result)
    {
        echo '
        <tr>
            <td>'.$cnt.'</td>
            <td>'.$denumirea_agentului_economic = $result->denumirea_agentului_economic.'</td>
            <td>'.$idno_cod_fiscal = $result->idno_cod_fiscal.'</td>
            <td>'.$adresa_juridica = $result->adresa_juridica.'</td>
            <td>'.$nume_prenumele_conducator = $result->nume_prenumele_conducator.'</td>
            <td>'.$contacte = $result->contacte.'</td>
            <td>'.$email = $result->email.'</td>
            <td>'.$tip_unitate_de_comert = $result->tip_unitate_de_comert.'</td>
            <td>'.$gen_de_activitate = $result->gen_de_activitate.'</td>
            <td>'.$adresa_amplasarii_unitatilor = $result->adresa_amplasarii_unitatilor.'</td>
            <td>'.$program_de_activitate = $result->program_de_activitate.'</td>
            <td>'.$suprafata_comerciala = $result->suprafata_comerciala.'</td>
            <td>'.$nr_de_locuri = $result->nr_de_locuri.'</td>
            <td>'.$nr_notificarii = $result->nr_notificarii.'</td>
            <td>'.$additional = $result->additional.'</td>
        </tr>
        ';
        $cnt++;
    }
}
?>
</table>
<?php } ?>