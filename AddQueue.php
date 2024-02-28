<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
    <title>aomezzz</title>
    <style type="text/css">
        img {
            transition: transform 0.25s ease;
        }

        img:hover {
            -webkit-transform: scale(1.5);
            transform: scale(1.5);
        }
    </style>
</head>

<body>
    <?php
    require 'conn.php';

    function getNextQNumber()
    {
        require 'conn.php';
        $sql = "SELECT MAX(QNumber) AS maxQNumber FROM queue";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch();
        return $result['maxQNumber'] + 1;
    }

    function getPatients()
    {
        require 'conn.php';
        $sql = "SELECT Pid, Pname FROM patient";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $patients = $stmt->fetchAll();
        return $patients;
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (!empty($_POST['Qdate']) && !empty($_POST['Pid'])) {
            $QNumber = getNextQNumber();

            $sql = "INSERT INTO queue (Qdate, QNumber, pid, QStatus) VALUES (:Qdate, :QNumber, :pid, 'ยังไม่ได้รักษา')";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':Qdate', $_POST['Qdate']);
            $stmt->bindParam(':QNumber', $QNumber);
            $stmt->bindParam(':pid', $_POST['Pid']);

            try {
                if ($stmt->execute()) {
                    echo '
                    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
                        <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>
                        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">
                        <script type="text/javascript">        
                            $(document).ready(function(){
                                swal({
                                    title: "Success!",
                                    text: "Successfully added customer",
                                    type: "success",
                                    timer: 2500,
                                    showConfirmButton: false
                                }, function(){
                                    window.location.href = "index.php";
                                });
                            });                    
                        </script>
                    ';
                } else {
                    $message = 'Failed to add new Queue';
                }
            } catch (PDOException $e) {
                echo 'Fail! I Repeat Fail!' . $e->getMessage();
            }
            $conn = null;
        }
    }
    ?>

    <div class="container">
        <div class="row">
            <div class="col-md-4"> <br>
                <h3>ฟอร์มเพิ่มข้อมูลคิว</h3>
                <br><br>
                <form action="AddQueue.php" method="POST" enctype="multipart/form-data">
                    <input type="date" placeholder="วันที่" name="Qdate" class="form-control" required>
                    <br>

                    <label for="Pid">เลือกรหัสบัตรประชาชน</label>
                    <select name="Pid" class="form-control" required>
                        <option value="">เลือกรหัสบัตรประชาชน</option>
                        <?php
                        $patients = getPatients();
                        foreach ($patients as $patient) {
                            echo "<option value=\"{$patient['Pid']}\">{$patient['Pname']} ({$patient['Pid']})</option>";
                        }
                        ?>
                    </select>
                    <br>

                    <input type="submit" value="Submit" name="submit" class="btn btn-primary" />
                </form>
            </div>
        </div>
    </div>

    <script type="text/javascript" charset="utf8" src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.js"></script>
    <script>
        $(document).ready(function() {
            $('#customerTable').DataTable();
        });
    </script>

</body>

</html>
