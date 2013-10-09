<?php
class pagination{
		/*Default values*/
		var $total_pages2 = -1;//items
		var $limit2 = null;
		var $target2 = ""; 
		var $page2 = 1;
		var $adjacents2 = 2;
		var $showCounter2 = false;
		var $className2 = "pagination";
		var $parameterName2 = "page2";
		var $urlF2 = false;//urlFriendly

		/*Buttons next and previous*/
		var $nextT2 = "Next";
		var $nextI2 = "&#187;"; //&#9658;
		var $prevT2 = "Previous";
		var $prevI2 = "&#171;"; //&#9668;

		/*****/
		var $calculate2 = false;
		
		function InicioPersonal($IdPersonal){$this->InicioPersonal = (int) $IdPersonal;}
		
		#Total items
		function items2($value2){$this->total_pages2 = (int) $value2;}
		
		#how many items to show per page
		function limit2($value2){$this->limit2 = (int) $value2;}
		
		#Page to sent the page value
		function target2($value2){$this->target2 = $value2;}
		
		#Current page
		function currentPage2($value2){$this->page2 = (int) $value2;}
		
		#How many adjacent pages should be shown on each side of the current page?
		function adjacents2($value2){$this->adjacents2 = (int) $value2;}
		
		#show counter?
		function showCounter2($value2=""){$this->showCounter2=($value2===true)?true:false;}

		#to change the class name of the pagination div
		function changeClass2($value2=""){$this->className2=$value2;}

		function nextLabel2($value2){$this->nextT2 = $value2;}
		function nextIcon2($value2){$this->nextI2 = $value2;}
		function prevLabel2($value2){$this->prevT2 = $value2;}
		function prevIcon2($value2){$this->prevI2 = $value2;}

		#to change the class name of the pagination div
		function parameterName2($value2=""){$this->parameterName2=$value2;}

		#to change urlFriendly
		function urlFriendly2($value2="%"){
				if(eregi('^ *$',$value2)){
						$this->urlF2=false;
						return false;
					}
				$this->urlF2=$value2;
			}
		
		var $pagination2;

		function pagination2(){}
		function show2(){
				if(!$this->calculate2)
					if($this->calculate2())
						echo "<div class=\"$this->className2\">$this->pagination2</div>\n";
			}
		function get_pagenum_link2($id2){
				if(strpos($this->target2,'?')===false)
						if($this->urlF2)
								return str_replace($this->urlF2,$id2,$this->target2);
							else
								return "onclick=\"paginacion($id2,".$this->InicioPersonal.")\"";
					else
						return "onclick=\"paginacion($id2,".$this->InicioPersonal.")\"";
			}
		
		function calculate2(){
				$this->pagination2 = "";
				$this->calculate2 == true;
				$error2 = false;
				if($this->urlF2 and $this->urlF2 != '%' and strpos($this->target2,$this->urlF2)===false){
						//Es necesario especificar el comodin para sustituir
						echo "Especificaste un wildcard para sustituir, pero no existe en el target<br />";
						$error2 = true;
					}elseif($this->urlF2 and $this->urlF2 == '%' and strpos($this->target2,$this->urlF2)===false){
						echo "Es necesario especificar en el target el comodin % para sustituir el número de página<br />";
						$error2 = true;
					}

				if($this->total_pages2 < 0){
						echo "It is necessary to specify the <strong>number of pages</strong> (\$class->items(1000))<br />";
						$error2 = true;
					}
				if($this->limit2 == null){
						echo "It is necessary to specify the <strong>limit of items</strong> to show per page (\$class->limit(10))<br />";
						$error2 = true;
					}
				if($error2)return false;
				
				$n2 = trim($this->nextT2.' '.$this->nextI2);
				$p2 = trim($this->prevI2.' '.$this->prevT2);
				
				/* Setup vars for query. */
				if($this->page2) 
					$start2 = ($this->page2 - 1) * $this->limit2;             //first item to display on this page
				else
					$start2 = 0;                                //if no page var is given, set start to 0
			
				/* Setup page vars for display. */
				$prev2 = $this->page2 - 1;                            //previous page is page - 1
				$next2 = $this->page2 + 1;                            //next page is page + 1
				$lastpage2 = ceil($this->total_pages2/$this->limit2);        //lastpage is = total pages / items per page, rounded up.
				$lpm12 = $lastpage2 - 1;                        //last page minus 1
				
				/* 
					Now we apply our rules and draw the pagination object. 
					We're actually saving the code to a variable in case we want to draw it more than once.
				*/
				
				if($lastpage2 > 1){
						if($this->page2){
								//anterior button
								if($this->page2 > 1)
										$this->pagination2 .= "<a ".$this->get_pagenum_link2($prev2).">$p2</a>";
									else
										$this->pagination2 .= "<span class=\"disabled\">$p2</span>";
							}
						//pages	
						if ($lastpage2 < 7 + ($this->adjacents2 * 2)){//not enough pages to bother breaking it up
								for ($counter2 = 1; $counter2 <= $lastpage2; $counter2++){
										if ($counter2 == $this->page2)
												$this->pagination2 .= "<span class=\"current\">$counter2</span>";
											else
												$this->pagination2 .= "<a ".$this->get_pagenum_link2($counter2)."\">$counter2</a>";
									}
							}
						elseif($lastpage2 > 5 + ($this->adjacents2 * 2)){//enough pages to hide some
								//close to beginning; only hide later pages
								if($this->page2 < 1 + ($this->adjacents2 * 2)){
										for ($counter2 = 1; $counter2 < 4 + ($this->adjacents2 * 2); $counter2++){
												if ($counter2 == $this->page2)
														$this->pagination2 .= "<span class=\"current\">$counter2</span>";
													else
														$this->pagination2 .= "<a ".$this->get_pagenum_link2($counter2)."\">$counter2</a>";
											}
										$this->pagination2 .= "...";
										$this->pagination2 .= "<a ".$this->get_pagenum_link2($lpm12)."\">$lpm12</a>";
										$this->pagination2 .= "<a ".$this->get_pagenum_link2($lastpage2)."\">$lastpage2</a>";
									}
								//in middle; hide some front and some back
								elseif($lastpage2 - ($this->adjacents2 * 2) > $this->page2 && $this->page2 > ($this->adjacents2 * 2)){
										$this->pagination2 .= "<a ".$this->get_pagenum_link2(1)."\">1</a>";
										$this->pagination2 .= "<a ".$this->get_pagenum_link2(2)."\">2</a>";
										$this->pagination2 .= "...";
										for ($counter2 = $this->page2 - $this->adjacents2; $counter2 <= $this->page2 + $this->adjacents2; $counter2++)
											if ($counter2 == $this->page2)
													$this->pagination2 .= "<span class=\"current\">$counter2</span>";
												else
													$this->pagination2 .= "<a ".$this->get_pagenum_link2($counter2)."\">$counter2</a>";
										$this->pagination2 .= "...";
										$this->pagination2 .= "<a ".$this->get_pagenum_link2($lpm12)."\">$lpm12</a>";
										$this->pagination2 .= "<a ".$this->get_pagenum_link2($lastpage2)."\">$lastpage2</a>";
									}
								//close to end; only hide early pages
								else{
										$this->pagination2 .= "<a ".$this->get_pagenum_link2(1)."\">1</a>";
										$this->pagination2 .= "<a ".$this->get_pagenum_link2(2)."\">2</a>";
										$this->pagination2 .= "...";
										for ($counter2 = $lastpage2 - (2 + ($this->adjacents2 * 2)); $counter2 <= $lastpage2; $counter2++)
											if ($counter2 == $this->page2)
													$this->pagination2 .= "<span class=\"current\">$counter2</span>";
												else
													$this->pagination2 .= "<a ".$this->get_pagenum_link2($counter2)."\">$counter2</a>";
									}
							}
						if($this->page2){
								//siguiente button
								if ($this->page2 < $counter2 - 1)
										$this->pagination2 .= "<a ".$this->get_pagenum_link2($next2)."\">$n2</a>";
									else
										$this->pagination2 .= "<span class=\"disabled\">$n2</span>";
									if($this->showCounter2)$this->pagination2 .= "<div class=\"pagination_data\">($this->total_pages2 Pages)</div>";
							}
					}

				return true;
			}
	}
?>