<?php
	class Paginator {
		public $page;
		public $perPage;
		public $splitPage;
		public $total;
		public $numPage;

		public function __construct() {
			$data = json_decode(file_get_contents('php://input'), true);
			
			$this->page = $data['page'];
			$this->perPage = $data['perPage'];
			$this->splitPage = $data['splitPage'];
			$this->total = $data['total'];
			$this->numPage = ceil($this->total / $this->perPage);

			$this->createPagination();
		}

		public function createPagination() {
			$html = '<div class="col-md-3 text-left" style="display: inline-block; margin: 0;">';
			$html .= '<p style="display: inline-block; text-align: left;">พบข้อมูลทั้งสิ้น '.number_format($this->total).' รายการ</p>';
			$html .= '</div>';

			$html .= '<div class="col-md-9 text-right">';
			$html .= '<div style="display: inline-block;">';
			$html .= '<p style="display: inline-block;">หน้าที่ '.number_format($this->page).' จาก '.number_format($this->numPage).' หน้า</p>';
			$html .= '</div>';
			$html .= '<div style="display: inline-block;">';
			$html .= '<input type="text" class="form-control input-sm text-center page-go-to" value="'.number_format($this->page).'" style="width: 70px; display: inline-block; margin: 0 10px;">';
			$html .= '</div>';
			$html .= '<div style="display: inline-block;">';
			$html .= '<ul class="pagination pagination-sm" style="margin: 0;" data-per-page="'.$this->perPage.'" data-split-page="'.$this->splitPage.'" data-total="'.$this->total.'" data-num-page="'.$this->numPage.'">';

			$disabled = ($this->page == 1) ? 'disabled': '';
			$html .= '<li class="'.$disabled.'"><a style="float: none;" href="#" class="'.$disabled.' set-pagination" data-page="1">&laquo;</a></li>';

			$start = (($this->page - $this->splitPage) > 0) ? ($this->page - $this->splitPage): 1;
			$end = (($this->page + $this->splitPage) < $this->numPage) ? ($this->page + $this->splitPage): $this->numPage;

			if($start > 1) {
				$html .= '<li><a style="float: none;" href="#" class="set-pagination" data-page="1">1</a></li>';
				$html .= '<li class="disabled"><a style="float: none;"><span>...</span></a></li>';
			}

			if($start == 2)
				$start += 1;

			if($end == ($this->numPage - 1))
				$end -= 1;
			
			for($i=$start; $i<=$end; $i++) {
				$active = ($this->page == $i) ? 'active': '';
				$disabled = ($this->page ==  $i) ? 'disabled': '';
				$html .= '<li class="'.$active.'"><a style="float: none;" href="#" class="'.$disabled.' set-pagination" data-page="'.$i.'">'.number_format($i).'</a></li>';
			}

			if($end < $this->numPage) {
				$html .= '<li class="disabled"><a style="float: none;"><span>...</span></a></li>';
				$html .= '<li><a style="float: none;" href="#" class="set-pagination" data-page="'.$this->numPage.'">'.number_format($this->numPage).'</a></li>';
			}

			$disabled = ($this->page == $this->numPage) ? 'disabled': '';
			$html .= '<li class="'.$disabled.'"><a style="float: none;" href="#" class="'.$disabled.' set-pagination" data-page="'.$this->numPage.'">&raquo;</a></li>';

			$html .= '</ul>';
			$html .= '</div>';
			$html .= '</div>';

			echo $html;
		}
	}

	new Paginator;
?>