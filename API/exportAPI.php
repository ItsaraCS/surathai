<?php
    require('../lib/fpdf/fpdf.php');
    define('FPDF_FONTPATH', '../lib/fpdf/font/');

    class ExportAPI {
        public $pdf;

        public function __construct() {
            $this->pdf = new FPDF();
            $this->pdf->AddFont('angsana', '', 'angsa.php');
            $this->pdf->AddFont('THSarabun', '', 'THSarabun.php');
            $this->pdf->AddFont('THSarabunBold', 'B', 'THSarabunBold.php');

            $data = json_decode(file_get_contents('php://input'), true);
            $funcName = $data['funcName'];
            $params = $data['params'];
            $this->$funcName($params);
        }

        public function exportMapForPDF($params) {
            $title = (!empty($params['title'])) ? $params['title'] : 'ระบบฐานข้อมูลผู้ประกอบการสุราชุมชน';
            $menu = (!empty($params['menu'])) ? $params['menu'] : 'แผนที่';
            $year = (!empty($params['year'])) ? $params['year'] : '';
            $region = (!empty($params['region'])) ? $params['region'] : '';
            $province = (!empty($params['province'])) ? $params['province'] : '';

            if(!empty($params['mapImage']['map'])) {
                file_put_contents('../export/map/map/'.$menu.'.png', base64_decode(str_replace('data:image/png;base64,', '', $params['mapImage']['map'])));
                $mapImage = '../export/map/map/'.$menu.'.png';
            } else 
                $mapImage = '../img/noimages.png';

            if(!empty($params['chartImage'])) {
                file_put_contents('../export/map/chart/'.$menu.'.png', base64_decode(str_replace('data:image/png;base64,', '', $params['chartImage'])));
                $chartImage = '../export/map/chart/'.$menu.'.png';
            } else 
                $chartImage = '../img/noimages.png';
                
            $this->pdf->AddPage('P', 'A4');
            $this->pdf->SetFont('THSarabun', '', 14);
            $this->pdf->Cell(0, 0, $this->pdf->PageNo(), 0, 0, 'R');

            $this->pdf->SetFont('THSarabunBold', 'B', 24);
            $this->pdf->Image('../img/logoheader.png', 18, 15, 14, 18);
            $this->pdf->Ln(10);
            $this->pdf->Cell(25);
            $this->pdf->Cell(0, 0, iconv('utf-8', 'tis-620', $title), 0, 'L');
            $this->pdf->SetFont('THSarabun', '', 18);
            $this->pdf->Ln(9);
            $this->pdf->Cell(25);
            $this->pdf->Cell(0, 0, iconv('utf-8', 'tis-620', $menu), 0, 'L');
            
            $this->pdf->Line(20, 38, 210-20, 38);
            $this->pdf->Ln(16);
            $this->pdf->Cell(10);
    
            $this->pdf->SetFont('THSarabunBold', 'B', 14);
            $headerDetail = 'ปีงบประมาณ : ';
            $this->pdf->Cell(22, 0, iconv('utf-8', 'tis-620', $headerDetail), 0, 'L');
            $this->pdf->SetFont('THSarabun', '', 14);
            $headerDetail = $year;
            $this->pdf->Cell(22, 0, iconv('utf-8', 'tis-620', $headerDetail), 0, 'L');
            $this->pdf->SetFont('THSarabunBold', 'B', 14);
            $headerDetail = 'ภาค : ';
            $this->pdf->Cell(10, 0, iconv('utf-8', 'tis-620', $headerDetail), 0, 'L');
            $this->pdf->SetFont('THSarabun', '', 14);
            $headerDetail = $region;
            $this->pdf->Cell(24, 0, iconv('utf-8', 'tis-620', $headerDetail), 0, 'L');
            $this->pdf->SetFont('THSarabunBold', 'B', 14);
            $headerDetail = 'จังหวัด : ';
            $this->pdf->Cell(14, 0, iconv('utf-8', 'tis-620', $headerDetail), 0, 'L');
            $this->pdf->SetFont('THSarabun', '', 14);
            $headerDetail = $province;
            $this->pdf->Cell(50, 0, iconv('utf-8', 'tis-620', $headerDetail), 0, 'L');

            if(!empty($mapImage)) {
                $this->pdf->Ln(12);
                $this->pdf->Image($mapImage, 20, null, 170, 100);
            }

            if(count($params['mapImage']['legend']) != 0) {
                $this->pdf->SetY(130);
                $this->pdf->SetFillColor(250, 250, 250);
                $this->pdf->SetFont('THSarabunBold', 'B', 16);
                $legendContent = 'สัญลักษณ์แผนที่';
                $this->pdf->Cell(14);
                $this->pdf->Cell(30, 10, iconv('utf-8', 'tis-620', $legendContent), 0, 1, 'C', true);
                $this->pdf->Ln(5);
                $this->pdf->SetFont('THSarabunBold', 'B', 12);
                
                foreach($params['mapImage']['legend'] as $legend) {
                    $this->pdf->SetFillColor($legend['colorR'], $legend['colorG'], $legend['colorB']);
                    $legendContent = $this->pdf->Cell(14).$this->pdf->Rect($this->pdf->GetX(), $this->pdf->GetY(), 4, 4, 'F').$this->pdf->Cell(6).$this->pdf->Cell(0, 4, iconv('utf-8', 'tis-620', $legend['value']), 0, 1, 'L');
                    $this->pdf->Cell(0, 0, $legendContent, 0, 0, 'L');
                    $this->pdf->Ln(2);
                }
            }

            if(count($params['mapImage']['layer']) != 0) {
                $this->pdf->SetY(130);
                $this->pdf->SetX(140);
                $this->pdf->SetFillColor(250, 250, 250);
                $this->pdf->SetFont('THSarabunBold', 'B', 16);
                $layerContent = 'ชั้นข้อมูล';
                $this->pdf->Cell(14);
                $this->pdf->Cell(30, 10, iconv('utf-8', 'tis-620', $layerContent), 0, 1, 'C', true);
                $this->pdf->Ln(5);
                $this->pdf->SetFont('THSarabunBold', 'B', 12);

                $layerImagePosition = 145;
                foreach($params['mapImage']['layer'] as $layer) {
                    $this->pdf->SetX(140);
                    
                    if($layer['status'])
                        $layerContent = $this->pdf->Cell(14).$this->pdf->Cell(0, 0, $this->pdf->Image('../img/check.png', null, $layerImagePosition, 3, 3), 0, 0, 'L').$this->pdf->SetX(158).$this->pdf->Cell(0, 3, iconv('utf-8', 'tis-620', $layer['text']), 0, 1, 'L');
                    else
                        $layerContent = $this->pdf->Cell(14).$this->pdf->Cell(0, 0, $this->pdf->Image('../img/uncheck.png', null, $layerImagePosition, 3, 3), 0, 0, 'L').$this->pdf->SetX(158).$this->pdf->Cell(0, 3, iconv('utf-8', 'tis-620', $layer['text']), 0, 1, 'L');

                    $this->pdf->Cell(0, 0, $layerContent, 0, 0, 'L');
                    $this->pdf->Ln(3);

                    $layerImagePosition += 6;
                }
            }

            $this->pdf->AddPage('L', 'A4');
            $this->pdf->SetFont('THSarabun', '', 14);
            $this->pdf->Cell(0, 0, $this->pdf->PageNo(), 0, 0, 'R');

            if(!empty($chartImage)) {
                $this->pdf->Ln(12);
                $this->pdf->Image($chartImage, 20, null, 250, 40);
            }
            
            $this->pdf->Output('../export/map/'.$menu.'.pdf', 'F');
            $pathFile = 'export/map/'.$menu.'.pdf';
            echo $pathFile;
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
                
            $this->pdf->AddPage('P', 'A4');
            $this->pdf->SetFont('THSarabun', '', 14);
            $this->pdf->Cell(0, 0, $this->pdf->PageNo(), 0, 0, 'R');

            $this->pdf->SetFont('THSarabunBold', 'B', 24);
            $this->pdf->Image('../img/logoheader.png', 18, 15, 14, 18);
            $this->pdf->Ln(10);
            $this->pdf->Cell(25);
            $this->pdf->Cell(0, 0, iconv('utf-8', 'tis-620', $title), 0, 'L');
            $this->pdf->SetFont('THSarabun', '', 18);
            $this->pdf->Ln(9);
            $this->pdf->Cell(25);
            $this->pdf->Cell(0, 0, iconv('utf-8', 'tis-620', $menu), 0, 'L');
            
            $this->pdf->Line(20, 38, 210-20, 38);
            $this->pdf->Ln(16);
            $this->pdf->Cell(10);
    
            $this->pdf->SetFont('THSarabunBold', 'B', 14);
            $headerDetail = 'ปีงบประมาณ : ';
            $this->pdf->Cell(22, 0, iconv('utf-8', 'tis-620', $headerDetail), 0, 'L');
            $this->pdf->SetFont('THSarabun', '', 14);
            $headerDetail = $year;
            $this->pdf->Cell(22, 0, iconv('utf-8', 'tis-620', $headerDetail), 0, 'L');
            $this->pdf->SetFont('THSarabunBold', 'B', 14);
            $headerDetail = 'ภาค : ';
            $this->pdf->Cell(10, 0, iconv('utf-8', 'tis-620', $headerDetail), 0, 'L');
            $this->pdf->SetFont('THSarabun', '', 14);
            $headerDetail = $region;
            $this->pdf->Cell(24, 0, iconv('utf-8', 'tis-620', $headerDetail), 0, 'L');
            $this->pdf->SetFont('THSarabunBold', 'B', 14);
            $headerDetail = 'จังหวัด : ';
            $this->pdf->Cell(14, 0, iconv('utf-8', 'tis-620', $headerDetail), 0, 'L');
            $this->pdf->SetFont('THSarabun', '', 14);
            $headerDetail = $province;
            $this->pdf->Cell(50, 0, iconv('utf-8', 'tis-620', $headerDetail), 0, 'L');

            if(count($summaryTableData) != 0) {
                $this->pdf->Ln(8);
                $this->pdf->Cell(10);
                $this->pdf->SetFillColor(51, 122, 183);
                $this->pdf->SetTextColor(255, 255, 255);
                $this->pdf->SetFont('THSarabunBold', 'B', 13);

                foreach($summaryTableData['header'] as $key=>$val) {
                    $this->pdf->Cell($summaryTableData['sizeWidth'][$key], 6, iconv('utf-8', 'tis-620', $val), 1, 0, 'C', true);
                }
                
                $fill = false;
                $this->pdf->SetFillColor(217, 237, 247);
                $this->pdf->SetTextColor(51, 51, 51);
                $this->pdf->SetFont('THSarabun', '', 12);

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
                $this->pdf->SetFont('THSarabunBold', 'B', 13);

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
                    $this->pdf->AddPage('L', 'A4');
                    $this->pdf->SetFont('THSarabun', '', 14);
                    $this->pdf->SetTextColor(51, 51, 51);
                    $this->pdf->Cell(0, 0, $this->pdf->PageNo(), 0, 0, 'R');

                    $this->pdf->SetFillColor(51, 122, 183);
                    $this->pdf->SetTextColor(255, 255, 255);
                    $this->pdf->SetFont('THSarabunBold', 'B', 13);
                    $this->pdf->Ln(10);
                    $this->pdf->Cell(10);
                    
                    foreach($page['header'] as $key=>$val) {
                        $this->pdf->Cell($page['sizeWidth'][$key], 6, iconv('utf-8', 'tis-620', $val), 1, 0, 'C', true);
                    }

                    $fill = false;
                    $this->pdf->SetFillColor(217, 237, 247);
                    $this->pdf->SetTextColor(51, 51, 51);
                    $this->pdf->SetFont('THSarabun', '', 12);
                    $bodyIndex = 0;

                    foreach($page['body'] as $body) {
                        $this->pdf->Ln();
                        $this->pdf->Cell(10);

                        $heightBody = 6;
                        if(preg_grep('/(img)/', $body))
                            $heightBody = 12;

                        foreach($body as $key=>$val) {
                            preg_match('/(img)/', $val, $matchesImage);
                            
                            if(count($matchesImage) > 0){
                                $this->pdf->Cell($page['sizeWidth'][$key], $heightBody, $this->pdf->Image('../'.$val, ($this->pdf->GetX() + (($page['sizeWidth'][$key] / 2) - 5)), ($this->pdf->GetY() + 1), 10, 10), 1, 0, $page['align'][$bodyIndex][$key], $fill);
                            }else
                                $this->pdf->Cell($page['sizeWidth'][$key], $heightBody, iconv('utf-8', 'tis-620', $val), 1, 0, $page['align'][$bodyIndex][$key], $fill);
                        }

                        //$fill = !$fill;
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