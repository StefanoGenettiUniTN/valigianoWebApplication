<?php
require_once("DBconfig.php");
if(isset($_POST["submit"])){
    if(isset($_POST["societa"]) && isset($_POST["categoria"]) && isset($_FILES["fileToUpload"])){
        $societa = $_POST["societa"];
        $categoria = $_POST["categoria"];

        $file_name = $_FILES["fileToUpload"]["name"]; //Dati atleti - Foglio1.csv
        $file_size = $_FILES["fileToUpload"]["size"]; //691
        $file_tmp = $_FILES["fileToUpload"]["tmp_name"];
        $file_type = $_FILES["fileToUpload"]["type"]; //text/csv
        $file_ext = pathinfo($file_name,PATHINFO_EXTENSION); //csv
        $file_dirname = pathinfo($file_name,PATHINFO_DIRNAME); //.
        $file_basename = pathinfo($file_name,PATHINFO_BASENAME); //Dati atleti - Foglio1.csv
        $file_filename = pathinfo($file_name,PATHINFO_FILENAME); //Dati atleti - Foglio1

        $extensions= array("csv");

        if(in_array($file_ext,$extensions) === true){

            // open the file
            $f = fopen($file_name, 'r');

            if ($f === false) {
                die('Cannot open the file ' . $file_name);
            }

            // read each line in CSV file at a time
            $counter = 0;
            $dataSet = [];
            while (($row = fgetcsv($f)) !== false) {
                if($counter>0) { //prima riga (header) non considerata

                    $atleta = [];

                    $atleta_nome = $row[0];
                    $atleta_cognome = $row[1];
                    $atleta_sesso = $row[2];
                    $atleta_data = $row[3];
                    $atleta_pettorina = $row[4];

                    $atleta["name"] = $atleta_nome;
                    $atleta["surname"] = $atleta_cognome;
                    $atleta["gender"] = $atleta_sesso;
                    $atleta["birthday"] = $atleta_data;
                    $atleta["raceNumber"] = $atleta_pettorina;

                    array_push($dataSet, $atleta);
                }
                $counter++;
            }

            // close the file
            fclose($f);

            // Start transaction
            $conn->begin_transaction();

            // Prepare statement
            $stmt = $conn->prepare('INSERT INTO utente (nome, cognome, data_nascita, sesso, n_pettorina, id_societa, id_categoria) VALUES (?,?,?,?,?,?,?)');

            foreach ($dataSet as $data) {
                $stmt->bind_param('ssssiii', $data['name'], $data['surname'], $data['birthday'], $data['gender'], $data['raceNumber'], $societa, $categoria);
                $stmt->execute();
            }

            // Commit the data into the database
            if($conn->commit()){
                echo "<h3>Sono stati inseriti i seguenti atleti: </h3>";
                echo "<table style='border-collapse: collapse; border: 1px solid;'>";
                echo "<tr>
                        <th style='border: 1px solid; text-align: center;'>Nome</th>
                        <th style='border: 1px solid; text-align: center;'>Cognome</th>
                        <th style='border: 1px solid; text-align: center;'>DataNascita</th>
                        <th style='border: 1px solid; text-align: center;'>Sesso</th>
                        <th style='border: 1px solid; text-align: center;'>Pettorina</th>
                      </tr>";
                foreach ($dataSet as $data) {
                    echo "<tr>";
                    echo "<td style='border: 1px solid;'>".$data['name']."</td>";
                    echo "<td style='border: 1px solid;'>".$data['surname']."</td>";
                    echo "<td style='border: 1px solid;'>".$data['birthday']."</td>";
                    echo "<td style='border: 1px solid;'>".$data['gender']."</td>";
                    echo "<td style='border: 1px solid;'>".$data['raceNumber']."</td>";
                    echo "</tr>";
                }
                echo "</table>";
            }else{
                echo "Qualcosa e' andato storto";
            }
        }else{
            echo "<script>alert(\"File non valido, perfavore scegli un file con estensione csv.\");</script>";
        }
        echo "<br><br><a href='index.php'>Torna alla pagina inserimento dati</a>";
    }else{
        echo "<script>alert(\"Errore, nessuna categoria, societa o file selezionati\");</script>";
    }
}
?>