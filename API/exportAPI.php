<?php
    require('../lib/fpdf/fpdf.php');
    require('../lib/php-word/PHPWord.php');
    define('FPDF_FONTPATH', '../lib/fpdf/font/');

    class ExportAPI {
        public $pdf;
        public $word;

        public function __construct() {
            //--Create instant object for PDF
            $this->pdf = new FPDF();
            $this->pdf->AddFont('angsana', '', 'angsa.php');
            $this->pdf->AddFont('THSarabun', '', 'THSarabun.php');
            $this->pdf->AddFont('THSarabunBold', 'B', 'THSarabunBold.php');

            //--Create instant object for Word
            $this->word = new PHPWord();

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

            if(!file_exists('../export/map')) {
                mkdir('../export/map', 0777, true);
            }

            if(!empty($params['mapImage']['map'])) {
                $mapImagePath = '../export/map/map_img_'.rand().'.png';
                file_put_contents($mapImagePath, base64_decode(str_replace('data:image/png;base64,', '', $params['mapImage']['map'])));
                $mapImage = $mapImagePath;
            } else 
                $mapImage = '../img/noimages.png';

            if(!empty($params['chartImage'])) {
                $chartImagePath = '../export/map/chart_img_'.rand().'.png';
                file_put_contents( $chartImagePath, base64_decode(str_replace('data:image/png;base64,', '', $params['chartImage'])));
                $chartImage =  $chartImagePath;
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
            
            $filePath = 'export/map/map_'.rand().'.pdf';
            $this->pdf->Output('../'.$filePath, 'F');

            echo json_encode([
                'pdf'=>$filePath,
                'map'=>$mapImagePath,
                'chart'=>$chartImagePath
            ], JSON_UNESCAPED_UNICODE);
        }

        public function exportSearchForPDF($params) {
            $title = (!empty($params['title'])) ? $params['title'] : 'ระบบฐานข้อมูลผู้ประกอบการสุราชุมชน';
            $menu = (!empty($params['menu'])) ? $params['menu'] : 'ค้นหา';
            $year = (!empty($params['year'])) ? $params['year'] : '';
            $region = (!empty($params['region'])) ? $params['region'] : '';
            $province = (!empty($params['province'])) ? $params['province'] : '';
            $summaryTableData = $params['summaryTableData'];
            $detailTableData = $params['detailTableData'];

            if(!file_exists('../export/search')) {
                mkdir('../export/search', 0777, true);
            }

            if(!empty($params['mapImage'])) {
                $mapImagePath = '../export/search/search_img_'.rand().'.png';
                file_put_contents($mapImagePath, base64_decode(str_replace('data:image/png;base64,', '', $params['mapImage'])));
                $mapImage = $mapImagePath;
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
                    
                    //$fill = !$fill;
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
                        if(preg_grep('/(img|label)/', $body))
                            $heightBody = 12;

                        foreach($body as $key=>$val) {
                            preg_match('/(img|label)/', $val, $matchesImage);
                            
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
            
            $filePath = 'export/search/search_'.rand().'.pdf';
            $this->pdf->Output('../'.$filePath, 'F');

            echo json_encode([
                'pdf'=>$filePath,
                'map'=>$mapImagePath
            ], JSON_UNESCAPED_UNICODE);
        }

        public function exportSearchForWord($params) {
            $title = (!empty($params['title'])) ? $params['title'] : 'ระบบฐานข้อมูลผู้ประกอบการสุราชุมชน';
            $menu = (!empty($params['menu'])) ? $params['menu'] : 'ค้นหา';
            $year = (!empty($params['year'])) ? $params['year'] : '';
            $region = (!empty($params['region'])) ? $params['region'] : '';
            $province = (!empty($params['province'])) ? $params['province'] : '';
            $summaryTableData = $params['summaryTableData'];
            $detailTableData = $params['detailTableData'];

            if(!file_exists('../export/search')) {
                mkdir('../export/search', 0777, true);
            }

            if(!empty($params['mapImage'])) {
                $mapImagePath = '../export/search/search_img_'.rand().'.png';
                file_put_contents($mapImagePath, base64_decode(str_replace('data:image/png;base64,', '', $params['mapImage'])));
                $mapImage = $mapImagePath;
            } else 
                $mapImage = '../img/noimages.png';

            $section = $this->word->createSection(['marginTop'=>1000]);
            
            $footer = $section->createFooter();
            $footer->addPreserveText('หน้า {PAGE}', ['name'=>'Tahoma', 'size'=>10, 'align'=>'center']);

            $this->word->addFontStyle('textBoldStyle', ['name'=>'Tahoma', 'bold'=>true, 'size'=>12]);
            $this->word->addFontStyle('textNormalStyle', ['name'=>'Tahoma', 'size'=>12]);
            $this->word->addFontStyle('textTitleTableStyle', ['name'=>'Tahoma', 'color'=>'FFFFFF', 'bold'=>true, 'size'=>12, 'align'=>'center']);
            $this->word->addFontStyle('textTitleDetailTableStyle', ['name'=>'Tahoma', 'color'=>'FFFFFF', 'bold'=>true, 'size'=>10, 'align'=>'center']);
            $this->word->addFontStyle('textDetailTableStyle', ['name'=>'Tahoma', 'size'=>10, 'align'=>'center']);
            $tableStyle = ['borderSize'=>6, 'borderColor'=>'DDDDDD', 'bold'=>true, 'align'=>'center', 'cellMargin'=>30];
            $cellStyle = ['valign'=>'center'];
            $textStyle = ['align'=>'center'];

            $table = $section->addTable('headerTable');
            $table->addRow();
            $table->addCell(1500, $cellStyle)->addImage('../img/logoheader.png', ['width'=>70, 'height'=>80, 'align'=>'left', 'colsNum' => 20]);
            $table->addCell(8000, $cellStyle)->addText($title, ['name'=>'Tahoma', 'size'=>18]);

            $section->addText($menu, ['name'=>'Tahoma', 'size'=>16]);
            $section->addTextBreak(1);

            $headerDetail = 'ปีงบประมาณ :  '.$year.'           ภาค :  '.$region.'           จังหวัด :  '.$province;
            $section->addText($headerDetail, 'textNormalStyle');
            $section->addTextBreak(1);

            if(count($summaryTableData) != 0) {
                $this->word->addTableStyle('searchDetailTable', $tableStyle, ['bgColor'=>'337AB7']);
                $table = $section->addTable('searchDetailTable');
                $table->addRow();
                foreach($summaryTableData['header'] as $key=>$val) {
                    $table->addCell(8000, $cellStyle)->addText($val, 'textTitleTableStyle', $textStyle);
                }

                foreach($summaryTableData['body'] as $body) {
                    $table->addRow();
                    foreach($body as $key=>$val) {
                        $table->addCell(8000, $cellStyle)->addText($val, 'textNormalStyle', $textStyle);
                    }
                }

                $this->word->addTableStyle('searchDetailTableSummary', $tableStyle, ['bgColor'=>'4CAE4C']);
                $table = $section->addTable('searchDetailTableSummary');
                $table->addRow();
                foreach($summaryTableData['footer'] as $key=>$val) {
                    $table->addCell(8000, $cellStyle)->addText($val, 'textTitleTableStyle', $textStyle);
                }
            }

            $section->addTextBreak(2);
            $section->addImage($mapImage, ['width'=>600, 'height'=>200, 'align'=>'center']);
            
            if(count($detailTableData) != 0) {
                foreach($detailTableData as $page) {
                    $section = $this->word->createSection(['orientation'=>'landscape']);

                    $this->word->addTableStyle('searchDetailTable', $tableStyle, ['bgColor'=>'337AB7']);
                    $table = $section->addTable('searchDetailTable');
                    $table->addRow();
                    foreach($page['header'] as $key=>$val) {
                        $table->addCell(8000, $cellStyle)->addText($val, 'textTitleDetailTableStyle', $textStyle);
                    }
                    
                    foreach($page['body'] as $body) {
                        $table->addRow();
                        foreach($body as $key=>$val) {
                            preg_match('/(img|label)/', $val, $matchesImage);
                            
                            if(count($matchesImage) > 0) {
                                $table->addCell(8000, $cellStyle)->addImage('../'.$val, ['width'=>50, 'height'=>50, 'align'=>'center']);
                            } else
                                $table->addCell(8000, $cellStyle)->addText($val, 'textDetailTableStyle');
                        }
                    }
                }
            }

            $filePath = 'export/search/search_'.rand().'.docx';
            $createWriter = PHPWord_IOFactory::createWriter($this->word, 'Word2007');
            $createWriter->save('../'.$filePath);

            echo json_encode([
                'word'=>$filePath,
                'map'=>$mapImagePath
            ], JSON_UNESCAPED_UNICODE);
        }

        public function exportReportForWord($params) {
            $title = (!empty($params['title'])) ? $params['title'] : 'ระบบฐานข้อมูลผู้ประกอบการสุราชุมชน';
            $menu = (!empty($params['menu'])) ? $params['menu'] : 'ค้นหา';
            $year = (!empty($params['year'])) ? $params['year'] : '';
            $region = (!empty($params['region'])) ? $params['region'] : '';
            $province = (!empty($params['province'])) ? $params['province'] : '';
            $detailTableData = $params['detailTableData'];

            if(!file_exists('../export/search')) {
                mkdir('../export/search', 0777, true);
            }

            if(!empty($params['chartImage'])) {
                $chartImagePath = '../export/search/search_img_'.rand().'.png';
                file_put_contents($chartImagePath, base64_decode(str_replace('data:image/png;base64,', '', $params['chartImage'])));
                $chartImage = $chartImagePath;
            } else 
                $chartImage = '../img/noimages.png';

            $section = $this->word->createSection(['marginTop'=>1000]);
            
            $footer = $section->createFooter();
            $footer->addPreserveText('หน้า {PAGE}', ['name'=>'Tahoma', 'size'=>10, 'align'=>'center']);

            $this->word->addFontStyle('textBoldStyle', ['name'=>'Tahoma', 'bold'=>true, 'size'=>12]);
            $this->word->addFontStyle('textNormalStyle', ['name'=>'Tahoma', 'size'=>12]);
            $this->word->addFontStyle('textTitleTableStyle', ['name'=>'Tahoma', 'color'=>'FFFFFF', 'bold'=>true, 'size'=>12, 'align'=>'center']);
            $this->word->addFontStyle('textTitleDetailTableStyle', ['name'=>'Tahoma', 'color'=>'FFFFFF', 'bold'=>true, 'size'=>10, 'align'=>'center']);
            $this->word->addFontStyle('textDetailTableStyle', ['name'=>'Tahoma', 'size'=>10, 'align'=>'center']);
            $tableStyle = ['borderSize'=>6, 'borderColor'=>'DDDDDD', 'bold'=>true, 'align'=>'center', 'cellMargin'=>30];
            $cellStyle = ['valign'=>'center'];
            $textStyle = ['align'=>'center'];

            $table = $section->addTable('headerTable');
            $table->addRow();
            $table->addCell(1500, $cellStyle)->addImage('../img/logoheader.png', ['width'=>70, 'height'=>80, 'align'=>'left', 'colsNum' => 20]);
            $table->addCell(8000, $cellStyle)->addText($title, ['name'=>'Tahoma', 'size'=>16]);

            $section->addText($menu, ['name'=>'Tahoma', 'size'=>14]);
            $section->addTextBreak(1);

            $headerDetail = 'ปีงบประมาณ :  '.$year.'           ภาค :  '.$region.'           จังหวัด :  '.$province;
            $section->addText($headerDetail, 'textNormalStyle');
            $section->addTextBreak(1);
            
            if(count($detailTableData) != 0) {
                $this->word->addTableStyle('detailTable', $tableStyle, ['bgColor'=>'337AB7']);
                $table = $section->addTable('detailTable');
                $table->addRow();
                foreach($detailTableData['header'] as $key=>$val) {
                    $table->addCell(8000, $cellStyle)->addText($val, 'textTitleDetailTableStyle', $textStyle);
                }
                    
                foreach($detailTableData['body'] as $body) {
                    $table->addRow();
                    foreach($body as $key=>$val) {
                        $table->addCell(8000, $cellStyle)->addText($val, 'textDetailTableStyle');
                    }
                }
            }
            
            $section = $this->word->createSection();
            $section->addImage($chartImage, ['width'=>600, 'height'=>150, 'align'=>'center']);

            $filePath = 'export/search/search_'.rand().'.docx';
            $createWriter = PHPWord_IOFactory::createWriter($this->word, 'Word2007');
            $createWriter->save('../'.$filePath);

            echo json_encode([
                'word'=>$filePath,
                'chart'=>$chartImage
            ], JSON_UNESCAPED_UNICODE);
        }

        public function removeFilePath($params) {
            if(count($params) != 0) {
                $status = false;

                foreach($params as $key=>$val) {
                    $status = unlink($val);
                }

                echo $status;
            }
        }
    }

    new ExportAPI;
?>