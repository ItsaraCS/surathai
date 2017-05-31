<?php
	class Paginator {
		public $queryParams;
		public $page;
		public $perPage;
		public $splitPage;
		public $total;
		public $numPage;

		public function __construct() {
			$data = json_decode(file_get_contents('php://input'), true);
			
			$this->queryParams = $data['queryParams'];
			$this->page = $data['page'];
			$this->perPage = $data['perPage'];
			$this->splitPage = $data['splitPage'];
			$this->total = $data['total'];
			$this->numPage = ceil($this->total / $this->perPage);

			$this->createPagination();
		}

		public function createPagination() {
			if($this->perPage == 'all') {
				return '';
			}
			
			$html = '<div class="col-md-3 text-left" style="display: inline-block; margin: 20px 0;">';
			$html .= '<p style="display: inline-block; text-align: left;">พบข้อมูลทั้งสิ้น '.$this->total.' รายการ</p>';
			$html .= '</div>';

			$html .= '<div class="col-md-9 text-right">';
			$html .= '<div style="display: inline-block;">';

			if($this->page == 'all')
				$html .= '<p style="display: inline-block;">ทั้งหมด '.$this->numPage.' หน้า</p>';
			else
				$html .= '<p style="display: inline-block;">หน้าที่ '.$this->page.' จาก '.$this->numPage.' หน้า</p>';
			
			$html .= '</div>';
			$html .= '<div style="display: inline-block;">';
			$html .= '<input type="text" class="form-control input-sm text-center" id="pageGoto" value="'.$this->page.'" style="width: 70px; display: inline-block; margin: 0 10px;">';
			$html .= '</div>';
			$html .= '<div style="display: inline-block;">';
			$html .= '<ul class="pagination pagination-sm" data-num-page="'.$this->numPage.'">';

			$disabled = ($this->page == 1) ? 'disabled': '';
			$html .= '<li class="'.$disabled.'"><a style="float: none;" href="'.$this->queryParams.'page=1" class="'.$disabled.'">&laquo;</a></li>';

			$start = (($this->page - $this->splitPage) > 0) ? ($this->page - $this->splitPage): 1;
			$end = (($this->page + $this->splitPage) < $this->numPage) ? ($this->page + $this->splitPage): $this->numPage;

			if($start > 1) {
				$html .= '<li><a style="float: none;" href="'.$this->queryParams.'page=1">1</a></li>';
				$html .= '<li class="disabled"><a style="float: none;"><span>...</span></a></li>';
			}

			if($start == 2)
				$start += 1;

			if($end == ($this->numPage - 1))
				$end -= 1;
			
			for($i=$start; $i<=$end; $i++) {
				$active = ($this->page == $i) ? 'active': '';
				$disabled = ($this->page ==  $i) ? 'disabled': '';
				$html .= '<li class="'.$active.'"><a style="float: none;" href="'.$this->queryParams.'page='.$i.'" class="'.$disabled.'">'.$i.'</a></li>';
			}

			if($end < $this->numPage) {
				$html .= '<li class="disabled"><a style="float: none;"><span>...</span></a></li>';
				$html .= '<li><a style="float: none;" href="'.$this->queryParams.'page='.$this->numPage.'">'.$this->numPage.'</a></li>';
			}

			$disabled = ($this->page == $this->numPage) ? 'disabled': '';
			$html .= '<li class="'.$disabled.'"><a style="float: none;" href="'.$this->queryParams.'page='.$this->numPage.'" class="'.$disabled.'">&raquo;</a></li>';

			$html .= '</ul>';
			$html .= '<ul class="pagination pagination-sm" style="margin-left: 10px;">';
			$active = ($this->page == 'all') ? 'active': '';
			$html .= '<li class="'.$active.'"><a style="float: none;" href="'.$this->queryParams.'page=all">ทั้งหมด</a></li>';
			$html .= '</ul>';

			$html .= '</div>';
			$html .= '</div>';

			echo $html;
		}
	}

	new Paginator();
?>