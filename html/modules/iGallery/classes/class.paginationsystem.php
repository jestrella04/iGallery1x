<?php
# ################################################################################### #
# PHP-NUKE: Advanced Content Management System	copy 2002 by Francisco Burzi  	      #
# =================================================================================== #
# Modulo Pagination Class                                    Last Updated: 30/09/2008 #
# Copyright (c) 2003 by Vecchio Joedeve essere nella cartella                         #
# [ webmaster@vecchiojoe.it  http://www.vecchiojoe.it ]                               #
# =================================================================================== #
# This program is free software. You can redistribute it and/or modify                #
# it under the terms of the GNU General Public License as published by                #
# the Free Software Foundation; either version 2 of the License.                      #
# ################################################################################### #

class paginationSystem {
	//==============================
	//DEFAULT CONFIGURATION
	//==============================
	/*Use CSS style*/
	var $use_style = true;

	/*Show the page position label: "Page N of TOT"*/
	var $positon_label = false;

	/*Show first and last page links*/
	var $show_limits = true;

	/*Page url: "index.php?page=name&op=example&pg={{N}}&salute=bye..."*/
	var $url = '';

	/*Num items per page*/
	var $items = 10;

	/*Indicate manually the pages number*/
	var $override_query = true;

	/*Style*/
	var $box_style = 'pagenavi';
	var $box_align = 'center';

	//==============================
	//DO NOT EDIT
	//==============================
	var $actpg;
	var $query;
	var $tot_items;

	//PHP5 Constructor
	function __construct() {
		$this->actpg = 0;
		$this->query = '';
		$this->pages = 0;
	}
	//PHP4 Constructor
	function paginationSystem() {
		$this->actpg = 0;
		$this->query = '';
		$this->pages = 0;
	}

	function show() {
  		global $db;
		$numitems = ($this->override_query) ? $this->tot_items : $db->sql_numrows($db->sql_query($this->query));
		$numpages = ceil($numitems/$this->items);
	  	if ($numpages>1) {
			echo '<div id="pagination">'.PHP_EOL;
			//echo '	<tr>'.PHP_EOL;
			//echo '		<td height="20">'.PHP_EOL;
			$first = 1;
			$prev_big = $this->actpg-10;
			$prev = $this->actpg-1;
			$next = $this->actpg+1;
			$next_big = $this->actpg+10;
			$last = $numpages;
			// Label
		   	if ($this->positon_label) {
		 		echo '			<span class="'.$this->box_style.'">'._PAGE.' '.$this->actpg.'/'.$numpages.'</span>'.PHP_EOL;
			}

			//First page
			if ($this->show_limits AND $first<$prev) {
				echo '			<span class="'.$this->box_style.'"><a href="'.str_replace('{{N}}',$first,$this->url).'" title="'._PAGE.' '.$first.'">|&lt;</a></span>'.PHP_EOL;
			}

			//-10
			if ($prev_big>0) {
				echo '			<span class="'.$this->box_style.'"><a href="'.str_replace('{{N}}',$prev_big,$this->url).'" title="'._PAGE.' '.$prev_big.'">&lt;&lt;</a></span>'.PHP_EOL;
			}

			//-1
			if ($prev>0) {
				echo '			<span class="'.$this->box_style.'"><a href="'.str_replace('{{N}}',$prev,$this->url).'" title="'._PAGE.' '.$prev.'">&lt;</a></span>'.PHP_EOL;
			}

			//Previous pages
			for ($i=3;$i>0;$i--) {
				$item = $this->actpg-$i;
				if ($item>0) {
					echo '			<span class="'.$this->box_style.'"><a href="'.str_replace('{{N}}',$item,$this->url).'" title="'._PAGE.' '.$item.'">'.$item.'</a></span>'.PHP_EOL;
				}
			}

			//Actual page
			echo '			<span class="'.$this->box_style.'"><a class="current" href="'.str_replace('{{N}}',$this->actpg,$this->url).'" title="'._PAGE.' '.$this->actpg.'"><b>'.$this->actpg.'</b></a></span>'.PHP_EOL;

			//Next pages
			for ($i=1;$i<=3;$i++) {
				$item = $this->actpg+$i;
				if ($item<=$last) {
					echo '			<span class="'.$this->box_style.'"><a href="'.str_replace('{{N}}',$item,$this->url).'" title="'._PAGE.' '.$item.'">'.$item.'</a></span>'.PHP_EOL;
				}
			}

			//+1
			if ($next<=$last) {
				echo '			<span class="'.$this->box_style.'"><a href="'.str_replace('{{N}}',$next,$this->url).'" title="'._PAGE.' '.$next.'">&gt;</a></span>'.PHP_EOL;
			}

			//+10
			if ($next_big<=$last) {
				echo '			<span class="'.$this->box_style.'"><a href="'.str_replace('{{N}}',$next_big,$this->url).'" title="'._PAGE.' '.$next_big.'">&gt;&gt;</a></span>'.PHP_EOL;
			}

			//Last page
			if ($this->show_limits AND $last>$next) {
				echo '			<span class="'.$this->box_style.'"><a href="'.str_replace('{{N}}',$last,$this->url).'" title="'._PAGE.' '.$last.'">&gt;|</a></span>'.PHP_EOL;
			}
			//echo '		</td>'.PHP_EOL;
			//echo '	</tr>'.PHP_EOL;
			echo '</div>'.PHP_EOL;
		}
	}
}
?>