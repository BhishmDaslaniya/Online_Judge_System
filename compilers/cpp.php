<?php
    $con=mysqli_connect("localhost","root","");
    session_start();

    $t = $_SESSION['pid'];
        mysqli_select_db($con,"reg");
		$q=mysqli_query($con,"select * from archieve where id=$t");
        $qq=mysqli_fetch_row($q);
        
	$CC="c++";
	$out="a.exe";
	$code=$_POST["code"];
	$input=$qq[4];
	$filename_code="main.cpp";
	$filename_in="input.txt";
	$filename_error="error.txt";
	$executable="a.exe";
	$command=$CC." -lm ".$filename_code;	
	$command_error=$command." 2>".$filename_error;

	//if(trim($code)=="")
	//die("The code area is empty");
	
	$file_code=fopen($filename_code,"w+");
	fwrite($file_code,$code);
	fclose($file_code);
	$file_in=fopen($filename_in,"w+");
	fwrite($file_in,$input);
	fclose($file_in);
	exec("chmod 777 $executable"); 
	exec("chmod 777 $filename_error");	

	shell_exec($command_error);
	$error=file_get_contents($filename_error);

	if(trim($error)=="")
	{
		if(trim($input)=="")
		{
			$output=shell_exec($out);
		}
		else
		{
			$out=$out." < ".$filename_in;
			$output=shell_exec($out);
		}
		//echo "<pre>$output</pre>";
        
        if($qq[5]==$output){
        	include("../user/submissiony.php");
            echo "Accepted";
//        echo "<pre>$output</pre>";
        }
        else{
        	include("../user/submissionn.php");
            echo "Wrong Answer!";
//            echo "<pre>$output</pre>";
        
        }
	}
	else if(!strpos($error,"error"))
	{
		echo "<pre>$error</pre>";
		if(trim($input)=="")
		{
			$output=shell_exec($out);
		}
		else
		{
			$out=$out." < ".$filename_in;
			$output=shell_exec($out);
		}
		//echo "<pre>$output</pre>";
	}
	else
	{
		echo "<pre>$error</pre>";
	}
	exec("rm $filename_code");
	exec("rm *.o");
	exec("rm *.txt");
	exec("rm $executable");
?>
