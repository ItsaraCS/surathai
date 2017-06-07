<?php
    require('../lib/fpdf/fpdf.php');
    define('FPDF_FONTPATH', '../lib/fpdf/font/');

    class exportAPI {
        public $pdf;

        public function __construct() {
            $this->pdf = new FPDF();
            $this->pdf->AddFont('angsana', '', 'angsa.php');

            $data = json_decode(file_get_contents('php://input'), true);
            //$this->exportSearchForPDF();

            $summaryTableData = [
                'header'=>['รายการ', 'ภาษี'],
                'body'=>[
                    [
                        'title'=>'ก่อสร้าง',
                        'value'=>'0'
                    ],
                    [
                        'title'=>'ผลิต',
                        'value'=>'0'
                    ],
                    [
                        'title'=>'ขาย',
                        'value'=>'0'
                    ],
                    [
                        'title'=>'ขน',
                        'value'=>'0'
                    ],
                    [
                        'title'=>'แสตมป์',
                        'value'=>'197741.1'
                    ]
                ],
                'footer'=>[
                    'title'=>'รวมทั้งสิ้น',
                    'value'=>'197741.1'
                ]
            ];
            $this->exportSearchForPDF('ระบบข้อมูลผู้ประกอบการสุราชุมชน', 'ค้นหางานภาษี', $summaryTableData);
        }

        public function exportSearchForPDF($title = '', $menu = '', $summaryTableData = [], $detailTableData = [], $mapImage = '') {
            $title = (!empty($title)) ? $title : 'ระบบข้อมูลผู้ประกอบการสุราชุมชน';
            $menu = (!empty($menu)) ? $menu : 'ค้นหา';
            $mapImage = (!empty($mapImage)) ? $mapImage : '../img/noimages.png';

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

            if(count($summaryTableData) != 0) {
                $this->pdf->Ln(10);
                $this->pdf->Cell(10);
                $this->pdf->SetFillColor(101, 115, 126);
                $this->pdf->SetTextColor(255, 255, 255);
                $this->pdf->SetFont('angsana', '', 14);

                foreach($summaryTableData['header'] as $header) {
                    $this->pdf->Cell(85, 8, iconv('utf-8', 'tis-620', $header), 1, 0, 'C', true);
                }
                
                $fill = false;
                $this->pdf->SetFillColor(249, 249, 249);
                $this->pdf->SetTextColor(51, 51, 51);

                foreach($summaryTableData['body'] as $body) {
                    $this->pdf->Ln();
                    $this->pdf->Cell(10);
                    $this->pdf->Cell(85, 6, iconv('utf-8', 'tis-620', $body['title']), 1, 0, 'L', $fill);
                    $this->pdf->Cell(85, 6, iconv('utf-8', 'tis-620', number_format($body['value'], 2)), 1, 0, 'R', $fill);
                    $fill = !$fill;
                }

                $this->pdf->SetFillColor(101, 115, 126);
                $this->pdf->SetTextColor(255, 255, 255);

                foreach($summaryTableData['footer'] as $footer) {
                    $this->pdf->Ln();
                    $this->pdf->Cell(10);
                    $this->pdf->Cell(85, 6, iconv('utf-8', 'tis-620', $footer['title']), 1, 0, 'C', true);
                    $this->pdf->Cell(85, 6, iconv('utf-8', 'tis-620', number_format($body['value'], 2)), 1, 0, 'R', true);
                    $fill = !$fill;
                }
            }

            /*if(!empty($mapImage)) {
                $this->pdf->AddPage();
                $this->pdf->Image($mapImage, 20, 100, 170, 70);
                //$this->pdf->Ln(35);
            }*/

            $this->pdf->Output();
        }
    }

    new exportAPI;
?>