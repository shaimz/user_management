<?php

require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

$filepath = realpath(dirname(__FILE__));
// Load the spreadsheet
$spreadsheet = IOFactory::load($filepath.'/agents.xlsx');


// Define column headers
$columnHeaders = [
    'denumirea_agentului_economic' => 'Denumirea agentului economic',
    'idno_cod_fiscal' => 'IDNO/ Cod fiscal',
    'adresa_juridica' => 'Adresa juridică',
    'nume_prenumele_conducator' => 'Numele/ Prenumele Conducător',
    'contacte' => 'Contacte',
    'email' => 'e-mail',
    'tip_unitate_de_comert' => 'Tip unitate de comerț',
    'gen_de_activitate' => 'Gen de activitate',
    'adresa_amplasarii_unitatilor' => 'Adresa amplasării unităților de comerț/prestări servicii',
    'program_de_activitate' => 'Program de activitate',
    'suprafata_comerciala' => 'Suprafața comercială',
    'nr_de_locuri' => 'Nr. de locuri',
    'nr_notificarii' => 'Nr. Notificării'
];


// Serialize the headers for creating columns in the database table
$serializedHeaders = array_keys($columnHeaders);

$tableName = 'users';
$checkTableExists = $dbh->query("SHOW TABLES LIKE '$tableName'")->rowCount();

$createTableQuery = "CREATE TABLE IF NOT EXISTS $tableName (
    id INT AUTO_INCREMENT PRIMARY KEY,
    " . implode(" VARCHAR(255), ", array_map(function($header) {
        return str_replace(' ', '_', $header);
    }, $serializedHeaders)) . " VARCHAR(255)
)";

$dbh->exec($createTableQuery);

try {
    // Get existing columns from the table
    $existingColumnsQuery = $dbh->query("SHOW COLUMNS FROM $tableName");
    $existingColumns = $existingColumnsQuery->fetchAll(PDO::FETCH_COLUMN);
    $existingColumnNames = array_map(function($col){
        return strtolower($col); // Making it case-insensitive
    }, $existingColumns);

    // Loop through headers to check & add any new columns
    foreach ($serializedHeaders as $header) {
        $header = str_replace(' ', '_', $header); // Prepare header as column name
        if (!in_array(strtolower($header), $existingColumnNames)) { // Check if the column exists, ignore case
            $addColumnQuery = "ALTER TABLE $tableName ADD COLUMN $header VARCHAR(255)";
            $dbh->exec($addColumnQuery);
        }
    }
} catch (PDOException $e) {
    die("Error updating table: " . $e->getMessage());
}
finally {
    // refresh page
    header("Location: /admin/index.php");
}


// Iterate through all sheets
foreach ($spreadsheet->getWorksheetIterator() as $sheet) {
    // Get the sheet name
    $sheetName = $sheet->getTitle();
    echo "Processing sheet: $sheetName\n";

    // Your existing code for processing rows within a sheet goes here

    // Example: Find the header row
    // Find the row where the column headers are located
    $headerRow = null;

    foreach ($sheet->getRowIterator() as $row) {
        $cellIterator = $row->getCellIterator();
        $cellIterator->setIterateOnlyExistingCells(false);

        $rowData = [];
        foreach ($cellIterator as $cell) {
            if (is_null($cell->getValue())) continue;
            $rowData[] = $cell->getValue();
        }

        // Check if the row matches the column headers
        if (count($rowData) == count($columnHeaders)) {
            $headerRow = $row->getRowIndex();
            break;
        }
    }

    if ($headerRow !== null) {
        // Iterate through the rows starting from the row after the header
        for ($rowIndex = $headerRow + 1; $rowIndex <= $sheet->getHighestDataRow(); $rowIndex++) {
            $headers = array(...$serializedHeaders);
            $rowData = [];
            $row = $sheet->getRowIterator($rowIndex)->current(); // Get the current row
            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false);

            // Check if the row is empty and break the loop if so
            if (empty(iterator_to_array($cellIterator))) {
                $headerRow = null;
                break;
            }

            // Read data from each cell
            foreach ($cellIterator as $cell) {

                $rowData[] = $cell->getValue();
            }

            // Process the data
            $agentName = $rowData[0];
            $fiscalCode = $rowData[1];
            $legalAddress = $rowData[2];

            // Check if it's a RichText object
            if ($agentName instanceof PhpOffice\PhpSpreadsheet\RichText\RichText) {
                // Extract plain text from the RichText object
                $rowData[0] = $agentName->getPlainText();
            } else {
                // If it's not a RichText object, use the original value
                $rowData[0] = $agentName;
            }

            // Check if it's a RichText object
            if ($fiscalCode instanceof PhpOffice\PhpSpreadsheet\RichText\Run) {
                // Extract plain text from the RichText object
                $rowData[1] = $fiscalCode->getText();
            } else {
                // If it's not a RichText object, use the original value
                $rowData[1] = $fiscalCode;
            }

            // Check if it's a RichText object
            if ($legalAddress instanceof PhpOffice\PhpSpreadsheet\RichText\Run) {
                // Extract plain text from the RichText object
                $rowData[2] = $legalAddress->getText();
            } else {
                // If it's not a RichText object, use the original value
                $rowData[2] = $legalAddress;
            }
            // ... Process other columns as needed

            // Categorize by the type of economic agent
            // You may need to implement your own logic here based on the data
            $type = determineEconomicAgentType($rowData);

            $rowData = array_pad($rowData, count($headers), null);
            $rowData = array_slice($rowData, 0, count($headers));

            // Process the data
            if (count($headers) !== count($rowData)) {
                $headers[] = "Additional";
            }

            if (is_null($rowData[1]) && is_null($rowData[6]) && is_null($rowData[2]) && is_null($rowData[3]) && is_null($rowData[4]) && is_null($rowData[8])) {
                continue;
            }
            // var_export('------');
            // var_export(count($rowData));
            // var_export('------');
            $agentData = array_combine($headers, $rowData);
            var_export($agentData);
            // var_dump($serializedHeaders);

            // Insert data into the database
            $stmt = $dbh->prepare("INSERT INTO users (" . implode(", ", array_keys($agentData)) . ") VALUES (" . implode(", ", array_fill(0, count($agentData), "?")) . ")");
            $stmt->execute(array_values($agentData));

            // Print or process the categorized data
            // echo "Type: $type\n";
            // echo "Agent Name: $agentName\n";
            // echo "Fiscal Code: $fiscalCode\n";
            // echo "Legal Address: $legalAddress\n";
            // ... Print or process other data as needed
            // echo "-----------------\n";
        }
    } else {
        echo "Column headers not found in the spreadsheet.\n";
    }
    break;
}

// Implement your own logic to determine the economic agent type
function determineEconomicAgentType($rowData)
{
    // You need to customize this function based on your specific requirements
    // For example, check certain conditions to determine the type
    // and return the appropriate type (PIEȚE, MAGAZINE ȘI CENTRE COMERCIALE, etc.)

    // For demonstration purposes, let's assume the type is in the fourth column
    return $rowData[6]; // Adjust the index based on your actual data
}