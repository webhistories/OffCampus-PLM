<?php
 	
 	include 'config2.php';
	require('fpdf181/fpdf.php');
	//include 'config.php';
 include "facultyDashboard.php";
include 'egradesEDIT.php';

 $subject_code = $_SESSION['subject_code'];
   $block_id  =   $_SESSION['block_id'];
   $fac_id  =   $_SESSION['fac_id'] ;
global $k,$j;

class PDF extends FPDF{
	function Header()
	{
		
		$this->Setx(30);
		$this->Image('seal PLM.png');
		$this->SetXY(-57,10);
		$this->Image('Manila2.png');
		$this->SetXY(53,10);
		$this->SetFont('Times','',12);
		$this->Cell(100,10,'PAMANTASAN NG LUNGSOD NG MAYNILA',0,0,'C');
		$this->Setxy(53,15);
		$this->SetFont('Helvetica','I',10);
		$this->Cell(100,10,'University of the City of Manila',0,0,'C');
		$this->Setxy(53,20);
		$this->SetFont('Times','',10);
		$this->Cell(100,10,'Intramuros, Manila',0,0,'C');
		$this->Setxy(53,35);
		$this->SetFont('Arial','B',15);
		$this->Cell(100,10,'REPORT OF GRADES',0,0,'C');
		$this->Setxy(53,42);
		$this->SetFont('Arial','',10);
		$this->Cell(100,10,'COLLEGE OF BUSINESS AND GOVERNMENT MANAGEMENT',0,0,'C');
		$this->Setxy(53,47);
		$this->Cell(100,10,'Institute fo Business Administration and Entrepreneurship',0,0,'C');
		$this->Setxy(53,58);
		$this->Cell(100,10,'Sample Agency (Batch 1)',0,0,'C');

	}
	function Footer()
	{
	// Position at 1.5 cm from bottom
		$this->SetY(-15);
		$this->SetFont('Arial','I',8);
		$this->SetTextColor(128);
		$this->Cell(0,10,'Page '.$this->PageNo(),0,0,'C');
	}

}

$pdf = new PDF();
$pdf->AddPage();
$title = 'Grades';
$pdf->SetTitle($title);
$pdf->SetFont('Arial','B',10);

//1st term

//$classes = mysqli_query($connect, "SELECT * FROM classes WHERE syear = '2017-2018' and semester = '1st' and term = '1st' and block_id = 'PICPA1' ");

$pdf->Ln(15);
$pdf->Setx(24);
$pdf->Cell(165,6,'1st Trimester, SY 2017-2018 (First Term)',0,0,'C');
$pdf->Ln();
$pdf->Setx(22);
$pdf->Cell(165,6,'Saturday/8:00am-7:00pm',1,0,'C');

//for Columns
$pdf->Ln();
$pdf->Setx(22);
$pdf->Cell(30,6,'Student Number',1,0,'C');
$pdf->Cell(75,6,'Student Name',1,0,'C');
$pdf->Cell(30,6,'Remarks',1,0,'C');
$pdf->Cell(30,6,'Grade',1,0,'C');

$pdf->SetFont('Arial','',10);



$Grades = mysqli_query($conn, "SELECT * FROM students_grades WHERE subj_code = '$subject_code' AND faculty_id = '$fac_id' AND block_id = '$block_id' ORDER BY name ASC");

        if(!$Grades)
        {
        die('SQL Error: ' .mysqli_error($conn));
        }

        $row_count = 91;
 while ($row = mysqli_fetch_array($Grades)) 
        {

        	$pdf->Setxy(22,$row_count);
        	$pdf->Cell(30,6,$row['student_id'],1,1,'C');
        	$pdf->Setxy(52,$row_count);
        	$pdf->Cell(75,6,$row['name'],1,0,'C');
        	$pdf->Setxy(127,$row_count);
        	$pdf->Cell(30,6,$row['remarks'],1,0,'C');
        	$pdf->Setxy(157,$row_count);
        	$pdf->Cell(30,6,$row['grade'],1,0,'C');
        	$row_count = $row_count +6;

//$student_id = array($row['block_id']);

//$name = array($row['unit']);

	}

$column = 88;


$pdf->Output();



?>