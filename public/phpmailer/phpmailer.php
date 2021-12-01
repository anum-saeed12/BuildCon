<html>
<body>
<h1>Auto Email Sending Tool</h1><br/>
<h3>Fill the form.</h3>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
    <input type="file" name="file" size="60" />
    <a>Receiver:</a> <input type="text" name="receiver" value="testerxss100@gmail.com" required />
    <a>Subject:</a> <input type="text" name="sub" value="DNS Test Failed." required />
    <a>Message:</a> <input type="text" name="msg" value="This is a testing email." required />
    <input type="submit" value="Send Email" />

</form>
</body>
</html>

<?php

function print1($a)
{
    echo "Sending email from ".$a."<br/>";
}

function send_email($email1,$receiver1,$sub1,$msg1)
{
    $to = $receiver1;
    $subject = $sub1;
    $message = $msg1;
    $headers = "From: ".$email1;

    if(mail($to,$subject,$message,$headers))
    {
        echo "Email Sent"."<br/>";
    }
    else
    {
        echo "mail not sent";
    }
}

if ($_FILES) {
    //Checking if file is selected or not
    if ($_FILES['file']['name'] != "") {

        //Checking if the file is plain text or not
        if (isset($_FILES) && $_FILES['file']['type'] != 'text/plain') {
            echo "<span>File could not be accepted ! Please upload any '*.txt' file.</span>";
            exit();
        }
        $n=0;
        $receiver=$_POST["receiver"];
        $sub=$_POST["sub"];
        $msg=$_POST["msg"];
        echo "<center><span id='Content'>Processing content of ".$_FILES['file']['name']." File</span></center>";

        //Getting and storing the temporary file name of the uploaded file
        $fileName = $_FILES['file']['tmp_name'];

        //Throw an error message if the file could not be open
        $file = fopen($fileName,"r") or exit("Unable to open file!");

        // Reading a .txt file line by line
        while(!feof($file)) {
            $email = fgets($file);
            $n=$n+1;
            echo $n.". ";
            print1($email);
            send_email($email,$receiver,$sub,$msg);
        }

        fclose($file);
    } else {
        if (isset($_FILES) && $_FILES['file']['type'] == '')
            echo "<span>Please Choose a file by click on 'Browse' or 'Choose File' button.</span>";
    }
}
?>
