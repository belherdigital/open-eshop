<?php

/**
 * google charts wrapper class
 *
 * @package    OC
 * @category   Chart
 * @author     Oliver <oliver@open-classifieds.com>
 * @copyright  (c) 2009-2015 Open Classifieds Team
 * @license    GPL v3
 * @see 	   http://www.chartjs.org/docs
 */

class Chart {
	
	/**
     * @var array Default colors
     */
	private static $default_colors = array(
		array('fill' => 'rgba(54, 190, 112, 0.2)', 'stroke' => '#36BE70', 'point' => '#36BE70', 'pointStroke' => '#36BE70'),
		array('fill' => 'rgba(112, 76, 161, 0.2)', 'stroke' => '#704CA1', 'point' => '#704CA1', 'pointStroke' => '#704CA1'),
		array('fill' => 'rgba(72, 183, 203, 0.2)', 'stroke' => '#48B7CB', 'point' => '#48B7CB', 'pointStroke' => '#48B7CB'),
		array('fill' => 'rgba(238, 197, 106, 0.2)', 'stroke' => '#EEC56A', 'point' => '#EEC56A', 'pointStroke' => '#EEC56A'),
		array('fill' => 'rgba(223, 97, 114, 0.2)', 'stroke' => '#DF6172', 'point' => '#DF6172', 'pointStroke' => '#DF6172')
	);

	/**
	 * 
	 * generates a google chart, with the given information
	 * @param string $chart_type
	 * @param array  $data
	 * @param array  $options
	 * @param array  $colors
	 * @param array  $attributes
	 * @return mixed boolean|string 
	 */
	public static function corechart($chart_type='Bar', $data, $options = NULL, $colors, $attributes = NULL)
	{
		//list of availables charts
		$corecharts = array('Bar','Line','Radar','PolarArea','Pie','Doughnut');
			
		if (!in_array($chart_type, $corecharts) OR !is_array($data))
			return FALSE;
		
		if (!is_array($data) OR empty($data)) 
			return FALSE;
	
		//Defaults in case options are not set
		if ($options == NULL)
		{
			$options['height']               = 600;
			$options['width']                = 600;
			$options['fill']                 = 'rgba(220,220,220,0.2)';
			$options['stroke']               = 'rgba(220,220,220,1)';
			$options['point']                = 'rgba(220,220,220,1)';
			$options['pointStroke']          = '#fff';
		}

		if ( ! in_array($chart_type, array('Pie', 'Doughnut')))
		{
			foreach ($data as $j => $d)
			{
				$i = 0;
				foreach ($d as $k => $v)
				{
					if ($i == 0)
					{
						$chart_data['labels'][] = $v;
					}
					else
					{
						if ( ! isset($array_data_labels) OR ! in_array($k, $array_data_labels))
					        $array_data_labels[] = $k;

						$array_values[($i-1)][] = $v;
					}

					$i++;
				}
			}

			foreach ($array_values as $key => $value)
			{
				if (isset($colors[$key]))
					$chart_data['datasets'][$key] = $colors[$key];
				else
					$chart_data['datasets'][$key] = self::$default_colors[0];

				$chart_data['datasets'][$key]['data'] = $value;
				$chart_data['datasets'][$key]['label'] = $array_data_labels[$key];
			}
		}
		else
		{
			foreach ($data as $key => $value)
			{
				if (isset($colors[$key]))
				{
					$chart_data['datasets'][0]['backgroundColor'][$key]      = $colors[$key]['backgroundColor'];
					$chart_data['datasets'][0]['hoverBackgroundColor'][$key] = $colors[$key]['hoverBackgroundColor'];
				}
				else
				{
					$chart_data['datasets'][0]['backgroundColor'][$key]      = self::$default_colors[0]['fill'];
					$chart_data['datasets'][0]['hoverBackgroundColor'][$key] = self::$default_colors[0]['stroke'];
				}

				$chart_data['datasets'][0]['data'][$key] = $value['value'];
				$chart_data['labels'][$key] = $value['label'];
			}
		}

		//name for the div where the chart appears
		$chart_div        = $chart_type.'_'.md5(uniqid(mt_rand(), false));

		$chart_width      = $options['width'] ? ' width="' . $options['width'] . '"' : NULL;
		$chart_height     = $options['height'] ? ' height="' . $options['height'] . '"' : NULL;
		$chart_data       = ' data-data=\'' . json_encode($chart_data) . '\'';
		$chart_options    = isset($options['options']) ? ' data-options=\'' . json_encode($options['options']) . '\'' : NULL;
		$chart_attributes = NULL;

		if ($attributes)
		{
			foreach ($attributes as $attribute => $value)
			{
	            $chart_attributes .= ' ' . $attribute . '="' . $value . '"';
	        }
	    }

		$canvas = '<canvas id="' . $chart_div . '" data-chartjs="' . strtolower($chart_type) . '"' . $chart_width . $chart_height . $chart_attributes . $chart_data . $chart_options . '></canvas>';
		
		return $canvas;
	}
			
	/**
	 *
	 * Wrappers for self::corechart
	 *
	 * usage example common for all of them:
	 * <?=Chart::pie($products, array('title'=>'Productos','width'=>700,'height'=>600))?>
	 *
	 * @param  array $data
	 * @param  array $options
	 * @param  array $colors
	 * @param  array $attributes
	 * @return mixed boolean|string 
	 */
	
	public static function pie ($data, $options = NULL, $colors = NULL, $attributes = NULL)
	{
		if ($colors == NULL)
			$colors = self::$default_colors;

		foreach ($colors as $k => $color)
		{
			$chart_colors[$k]['color']     = $color['fill'];
			$chart_colors[$k]['highlight'] = $color['stroke'];
		}

		return self::corechart('Pie', $data, $options, $chart_colors, $attributes);
	}	
	
	public static function line ($data, $options = NULL, $colors = NULL, $attributes = NULL)
	{
		if ($colors == NULL)
			$colors = self::$default_colors;

		foreach ($colors as $k => $color)
		{
			$chart_colors[$k]['backgroundColor']           = $color['fill'];
			$chart_colors[$k]['borderColor']               = $color['stroke'];
			$chart_colors[$k]['pointBackgroundColor']      = $color['point'];
			$chart_colors[$k]['pointBorderColor']          = $color['pointStroke'];
			$chart_colors[$k]['pointHoverBackgroundColor'] = $color['point'];
			$chart_colors[$k]['pointHoverBorderColor']     = $color['pointStroke'];
			$chart_colors[$k]['lineTension']               = '.25';
			$chart_colors[$k]['borderWidth']               = '2';
			$chart_colors[$k]['pointRadius']               = '0';
		}
		
		return self::corechart('Line', $data, $options, $chart_colors, $attributes);
	}
	
	public static function bar ($data, $options = NULL, $colors = NULL, $attributes = NULL)
	{
		if ($colors == NULL)
			$colors = self::$default_colors;

		foreach ($colors as $k => $color)
		{
			$chart_colors[$k]['fillColor']       = $color['fill'];
			$chart_colors[$k]['strokeColor']     = $color['stroke'];
			$chart_colors[$k]['highlightFill']   = $color['fill'];
			$chart_colors[$k]['highlightStroke'] = $color['stroke'];
		}

		return self::corechart('Bar', $data, $options, $chart_colors, $attributes);
	}
	
	public static function radar ($data, $options = NULL, $colors = NULL, $attributes = NULL)
	{
		if ($colors == NULL)
			$colors = self::$default_colors;

		foreach ($colors as $k => $color)
		{
			$chart_colors[$k]['fillColor']            = $color['fill'];
			$chart_colors[$k]['strokeColor']          = $color['stroke'];
			$chart_colors[$k]['pointColor']           = $color['point'];
			$chart_colors[$k]['pointStrokeColor']     = $color['pointStroke'];
			$chart_colors[$k]['pointHighlightFill']   = $color['point'];
			$chart_colors[$k]['pointHighlightStroke'] = $color['pointStroke'];
		}

		return self::corechart('Radar', $data, $options, $chart_colors, $attributes);
	}

	public static function polar ($data, $options = NULL, $colors = NULL, $attributes = NULL)
	{
		if ($colors == NULL)
			$colors = self::$default_colors;

		foreach ($colors as $k => $color)
		{
			$chart_colors[$k]['color']     = $color['fill'];
			$chart_colors[$k]['highlight'] = $color['stroke'];
		}

		return self::corechart('PolarArea', $data, $options, $chart_colors, $attributes);
	}

	public static function doughnut ($data, $options = NULL, $colors = NULL, $attributes = NULL)
	{
		if ($colors == NULL)
			$colors = self::$default_colors;

		foreach ($colors as $k => $color)
		{
			$chart_colors[$k]['backgroundColor']      = $color['fill'];
			$chart_colors[$k]['hoverBackgroundColor'] = $color['stroke'];
		}

		return self::corechart('Doughnut', $data, $options, $chart_colors, $attributes);
	}
	
}
