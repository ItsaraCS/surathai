<?php
    require('../lib/fpdf/fpdf.php');
    define('FPDF_FONTPATH', '../lib/fpdf/font/');

    class ExportAPI {
        public $pdf;

        public function __construct() {
            $this->pdf = new FPDF();
            $this->pdf->AddFont('angsana', '', 'angsa.php');

            $data = json_decode(file_get_contents('php://input'), true);
            $funcName = $data['funcName'];
            $params = $data['params'];
            $this->$funcName($params);
        }

        public function exportSearchForPDF($params) {
            $title = (!empty($params['title'])) ? $params['title'] : 'ระบบฐานข้อมูลผู้ประกอบการสุราชุมชน';
            $menu = (!empty($params['menu'])) ? $params['menu'] : 'ค้นหา';
            $year = (!empty($params['year'])) ? $params['year'] : '';
            $region = (!empty($params['region'])) ? $params['region'] : '';
            $province = (!empty($params['province'])) ? $params['province'] : '';
            $summaryTableData = $params['summaryTableData'];
            $detailTableData = $params['detailTableData'];

            if(!empty($params['mapImage'])) {
                file_put_contents('../export/search/map/'.$menu.'.png', base64_decode(str_replace('data:image/png;base64,', '', $params['mapImage'])));
                $mapImage = '../export/search/map/'.$menu.'.png';
            } else 
                $mapImage = '../img/noimages.png';
                
            $this->pdf->AddPage();
            $this->pdf->SetFont('angsana', '', 24);
            $this->pdf->Image('../img/logoheader.png', 18, 15, 14, 18);
            $this->pdf->Ln(10);
            $this->pdf->Cell(25);
            $this->pdf->Cell(0, 0, iconv('utf-8', 'tis-620', $title), 0, 'L');
            $this->pdf->SetFont('angsana', '', 18);
            $this->pdf->Ln(9);
            $this->pdf->Cell(25);
            $this->pdf->Cell(0, 0, iconv('utf-8', 'tis-620', $menu), 0, 'L');

            $this->pdf->SetFont('angsana', '', 14);
            
            $this->pdf->Line(20, 45, 210-20, 45);
            $this->pdf->Ln(12);
            $this->pdf->Cell(10);
            $headerDetail = 'ปีงบประมาณ : '.$year.'         ภาค : '.$region.'         จังหวัด : '.$province;
            $this->pdf->Cell(0, 0, iconv('utf-8', 'tis-620', $headerDetail), 0, 'L');

            if(count($summaryTableData) != 0) {
                $this->pdf->Ln(10);
                $this->pdf->Cell(10);
                $this->pdf->SetFillColor(101, 115, 126);
                $this->pdf->SetTextColor(255, 255, 255);
                $this->pdf->SetFont('angsana', '', 12);

                foreach($summaryTableData['header'] as $key=>$val) {
                    $this->pdf->Cell($summaryTableData['sizeWidth'][$key], 6, iconv('utf-8', 'tis-620', $val), 1, 0, 'C', true);
                }
                
                $fill = false;
                $this->pdf->SetFillColor(249, 249, 249);
                $this->pdf->SetTextColor(51, 51, 51);

                foreach($summaryTableData['body'] as $body) {
                    $this->pdf->Ln();
                    $this->pdf->Cell(10);

                    foreach($body as $key=>$val) {
                        if($key == 0)
                            $this->pdf->Cell($summaryTableData['sizeWidth'][$key], 6, iconv('utf-8', 'tis-620', $val), 1, 0, 'L', $fill);
                        else {
                            $this->pdf->Cell($summaryTableData['sizeWidth'][$key], 6, iconv('utf-8', 'tis-620', $val), 1, 0, 'C', $fill);
                        }
                    }
                    
                    $fill = !$fill;
                }

                $this->pdf->Ln();
                $this->pdf->Cell(10);
                $this->pdf->SetFillColor(76, 174, 76);
                $this->pdf->SetTextColor(255, 255, 255);

                foreach($summaryTableData['footer'] as $key=>$val) {
                    $this->pdf->Cell($summaryTableData['sizeWidth'][$key], 6, iconv('utf-8', 'tis-620', $val), 1, 0, 'C', true);
                }
            }

            if(!empty($mapImage)) {
                $this->pdf->Ln(12);
                $this->pdf->Image($mapImage, 20, null, 170, 40);
            }

            if(count($detailTableData) != 0) {
                foreach($detailTableData as $page) {
                    $this->pdf->AddPage('L');
                    $this->pdf->SetFillColor(101, 115, 126);
                    $this->pdf->SetTextColor(255, 255, 255);
                    $this->pdf->SetFont('angsana', '', 12);
                    $this->pdf->Ln();
                    $this->pdf->Cell(10);
                    
                    foreach($page['header'] as $key=>$val) {
                        $this->pdf->Cell($page['sizeWidth'][$key], 6, iconv('utf-8', 'tis-620', $val), 1, 0, 'C', true);
                    }

                    $fill = false;
                    $this->pdf->SetFillColor(249, 249, 249);
                    $this->pdf->SetTextColor(51, 51, 51);
                    $bodyIndex = 0;

                    foreach($page['body'] as $body) {
                        $this->pdf->Ln();
                        $this->pdf->Cell(10);

                        foreach((array)$body as $key=>$val) {
                            $this->pdf->Cell($page['sizeWidth'][$key], 6, iconv('utf-8', 'tis-620', $val), 1, 0, $page['align'][$bodyIndex][$key], $fill);
                        }

                        $fill = !$fill;
                        $bodyIndex++;
                    }
                }
            }
            
            $this->pdf->Output('../export/search/'.$menu.'.pdf', 'F');
            $pathFile = 'export/search/'.$menu.'.pdf';
            echo $pathFile;
        }
    }

    new ExportAPI;
?>