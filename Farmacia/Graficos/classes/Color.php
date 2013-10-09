<?
	class Color
	{
		/**
		* Creates a new color
		*
		* @access	public
		* @param	integer		red [0,255]
		* @param	integer		green [0,255]
		* @param	integer		blue [0,255]
		* @param	integer		alpha [0,255]
		*/
		
		function Color($red, $green, $blue, $alpha = 0)
		{
			$this->red = (int)$red;
			$this->green = (int)$green;
			$this->blue = (int)$blue;
			$this->alpha = (int)round($alpha * 127.0 / 255);
			
			$this->gdColor = null;
		}
		
		/**
		* Get GD color
		*
		* @access	public
		* @param	$img		GD image resource
		*/
		
		function getColor($img)
		{
			// Checks if color has already been allocated
			
			if(!$this->gdColor)
			{
				if($this->alpha == 0 || !function_exists('imagecolorallocatealpha'))
					$this->gdColor = imagecolorallocate($img, $this->red, $this->green, $this->blue);
				else
					$this->gdColor = imagecolorallocatealpha($img, $this->red, $this->green, $this->blue, $this->alpha);
			}
			
			// Returns GD color
			
			return $this->gdColor;
		}
	}
?>