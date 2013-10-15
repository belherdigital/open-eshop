<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Map extends Controller {

	public function action_index()
	{
        require_once Kohana::find_file('vendor', 'php-googlemap/GoogleMap','php');
  		
        $this->before('/pages/maps');

        $this->template->title  = __('Map');

        $height = Core::get('height','100%');
        $width  = Core::get('width','100%');

        $map = new GoogleMapAPI();
        $map->setWidth($width);
        $map->setHeight($height);
        $map->disableSidebar();
        $map->setMapType('map');
        $map->setZoomLevel(Core::get('zoom',core::config('advertisement.map_zoom')));
        
        //$map->mobile = TRUE;
        $atributes = array("target='_blank'");
        if ( core::get('controls')==0 )
        {
            $map->disableMapControls();
            $map->disableTypeControls();
            $map->disableScaleControl();
            $map->disableZoomEncompass();
            $map->disableStreetViewControls();
            $map->disableOverviewControl();
        }
        
        //only 1 marker
        if ( core::get('address')!='' )
        {
            $map->addMarkerByAddress(core::get('address'), core::get('address'));
        }
        else
        {

            //last ads, you can modify this value at: general.feed_elements
            $ads = DB::select('a.seotitle')
                    ->select(array('c.seoname','category'),'a.title','a.published','a.address')
                    ->from(array('ads', 'a'))
                    ->join(array('categories', 'c'),'INNER')
                    ->on('a.id_category','=','c.id_category')
                    ->where('a.status','=',Model_Ad::STATUS_PUBLISHED)
                    ->where('a.address','IS NOT',NULL)
                    ->order_by('published','desc')
                    ->limit(Core::config('general.map_elements'))
                    ->as_object()
                    ->cached()
                    ->execute();

                    
            foreach($ads as $a)
            {
                //d($a);
                if (strlen($a->address)>3)
                {
                    $url= Route::url('ad',  array('category'=>$a->category,'seotitle'=>$a->seotitle));
                    $map->addMarkerByAddress($a->address, $a->title, HTML::anchor($url, $a->title, $atributes) );
                }
            }

            //only center if not a single ad
            $map->setCenterCoords(Core::get('lon',core::config('advertisement.center_lon')),Core::get('lat',core::config('advertisement.center_lat')));
        }

        $this->template->map = $map;
	
	}



} // End map
