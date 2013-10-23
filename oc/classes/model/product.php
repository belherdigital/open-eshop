<?php defined('SYSPATH') or die('No direct script access.');
/**
 * User products
 *
 * @author      Chema <chema@garridodiaz.com>
 * @package     Core
 * @copyright   (c) 2009-2013 Open Classifieds Team
 * @license     GPL v3
 */

class Model_Product extends ORM {

     /**
     * status constants
     */
    const STATUS_NOACTIVE = 0; //not displayed
    const STATUS_ACTIVE   = 1; 
  
    /**
     * @var  string  Table name
     */
    protected $_table_name = 'products';

    /**
     * @var  string  PrimaryKey field name
     */
    protected $_primary_key = 'id_product';


        protected $_belongs_to = array(
        'user'       => array('foreign_key' => 'id_user'),
        'category'   => array('foreign_key' => 'id_category'),
    );

    public function form_setup($form)
    {
       
    }

    public function exclude_fields()
    {
    
    }

    /**
     * Filters to run when data is set in this model. The password filter
     * automatically hashes the password when it's set in the model.
     *
     * @return array Filters
     */
    public function filters()
    {
        return array(
                'seoname' => array(
                                array(array($this, 'gen_seotitle'))
                              )
        );
    }


    /**
     * returns if the product is in offer
     * @return bool 
     */
    public function has_offer()
    {
        //check if theres an offer for the product
        if (is_numeric($this->price_offer) AND Date::mysql2unix($this->offer_valid)>time())
            return TRUE;
        else
            return FALSE;
    }
    
    /**
     * returns the prce of the product checking if there's an offer
     * @return float 
     */
    public function final_price()
    {
        return ($this->has_offer())? $this->price_offer : $this->price;
    }

    /**
     * return the title formatted for the URL
     *
     * @param  string $title
     * 
     */
    public function gen_seotitle($seotitle)
    {
        //in case seotitle is really small or null
        if (strlen($seotitle)<3)
            $seotitle = $this->title;

        
        $seotitle = URL::title($seotitle);

        if ($seotitle != $this->seotitle)
        {
            $cat = new self;
            //find a user same seotitle
            $s = $cat->where('seotitle', '=', $seotitle)->limit(1)->find();

            //found, increment the last digit of the seotitle
            if ($s->loaded())
            {
                $cont = 2;
                $loop = TRUE;
                while($loop)
                {
                    $attempt = $seotitle.'-'.$cont;
                    $cat = new self;
                    unset($s);
                    $s = $cat->where('seotitle', '=', $attempt)->limit(1)->find();
                    if(!$s->loaded())
                    {
                        $loop = FALSE;
                        $seotitle = $attempt;
                    }
                    else
                    {
                        $cont++;
                    }
                }
            }
        }
        

        return $seotitle;
    }

    /**
     * returns allowed Paypal currencies
     * @return array currencies
     */
    public static function get_currency()
    {
        return array(
                        'Australian Dollars'                                =>  'AUD',
                        'Canadian Dollars'                                  =>  'CAD',
                        'Euros'                                             =>  'EUR',
                        'Pounds Sterling'                                   =>  'GBP',
                        'Yen'                                               =>  'JPY',
                        'U.S. Dollars'                                      =>  'USD',
                        'New Zealand Dollar'                                =>  'NZD',
                        'Swiss Franc'                                       =>  'CHF',
                        'Hong Kong Dollar'                                  =>  'HKD',
                        'Singapore Dollar'                                  =>  'SGD',
                        'Swedish Krona'                                     =>  'SEK',
                        'Danish Krone'                                      =>  'DKK',
                        'Polish Zloty'                                      =>  'PLN',
                        'Norwegian Krone'                                   =>  'NOK',
                        'Hungarian Forint'                                  =>  'HUF',
                        'Czech Koruna'                                      =>  'CZK',
                        'Israeli Shekel'                                    =>  'ILS',
                        'Mexican Peso'                                      =>  'MXN',
                        'Brazilian Real (only for Brazilian users)'         =>  'BRL',
                        'Malaysian Ringgits (only for Malaysian users)'     =>  'MYR',
                        'Philippine Pesos'                                  =>  'PHP',
                        'Taiwan New Dollars'                                =>  'TWD',
                        'Thai Baht'                                         =>  'THB'
        );

    }

    /**
     * save_image upload images with given path
     * 
     * @param  [array]  $image      [image $_FILE-s ]
     * @param  [string] $seotitle   [unique id, and folder name]
     * @return [bool]               [return true if 1 or more images uploaded, false otherwise]
     */
    public function save_image($image)
    {
        
        
         
        if ( 
        ! Upload::valid($image) OR
        ! Upload::not_empty($image) OR
        ! Upload::type($image, explode(',',core::config('image.allowed_formats'))) OR
        ! Upload::size($image, core::config('image.max_image_size').'M'))
        {  
            if ( Upload::not_empty($image) && ! Upload::type($image, explode(',',core::config('image.allowed_formats'))))
            {
                return Alert::set(Alert::ALERT, $image['name'].' '.__('Is not valid format, please use one of this formats '.core::config('image.allowed_formats')));
            } 
            if(!Upload::size($image, core::config('image.max_image_size').'M'))
            {
                return Alert::set(Alert::ALERT, $image['name'].' '.__('Is not of valid size. Size is limited on '.core::config('image.max_image_size').'MB per image'));
            }
        }
          
        if ($image !== NULL)
        {

            $id = $this->id_product;
            $seotitle = $this->seotitle;
            $obj_product = new self($id);
            if($obj_product->loaded())
                $created = $obj_product->created;
            else
                $created = NULL;

            $path           = $this->image_path($id , $created);
            $docroot        = DOCROOT;
            $directory      = $docroot.$path;
            $image_quality  = core::config('image.quality');
            $width          = core::config('image.width');
            $width_thumb    = core::config('image.width_thumb');
            $height_thumb   = core::config('image.height_thumb');
            $height         = core::config('image.height');

            if(!is_numeric($height)) // when installing this field is empty, to avoid crash we check here
                $height         = NULL;
            if(!is_numeric($height_thumb))
                $height_thumb   = NULL;    
            
            // count how many files are saved 
            if (glob($directory . "*.jpg") != false)
            {
                $filecount = count(glob($directory . "*.jpg"));

                $counter = ($filecount / 2) + 1;
                
                if(file_exists($directory.$seotitle.'_'.$counter.'.jpg')) // in case we update image, we have to find available number to replace
                {
                    for($i=1; $i<=core::config('advertisement.num_images'); $i++)
                    {
                        $counter = $i;
                        if(!file_exists($directory.$seotitle.'_'.$counter.'.jpg'))
                        {
                            break;
                        }
                    }
                }
            }
            else
                $counter = 1;
            
            if ($file = Upload::save($image, NULL, $directory))
            {
                $filename_thumb     = 'thumb_'.$seotitle.'_'.$counter.'.jpg';
                $filename_original  = $seotitle.'_'.$counter.'.jpg';

                //if original image is bigger that our constants we resize
                $image_size_orig    = getimagesize($file);
                
                
                    if($image_size_orig[0] > $width || $image_size_orig[1] > $height)
                    {
                        
                        Image::factory($file)
                            ->resize($width, $height, Image::AUTO)
                            ->save($directory.$filename_original,$image_quality);    
                    }
                    //we just save the image changing the quality and different name
                    else
                    {
                        Image::factory($file)
                            ->save($directory.$filename_original,$image_quality); 
                    }
                

                //creating the thumb and resizing using the the biggest side INVERSE
                Image::factory($directory.$filename_original)
                    ->resize($width_thumb, $height_thumb, Image::INVERSE)
                    ->save($directory.$filename_thumb,$image_quality);

                //check if the height or width of the thumb is bigger than default then crop
                if ($height_thumb!==NULL)
                {
                    $image_size_orig = getimagesize($directory.$filename_thumb);
                    if ($image_size_orig[1] > $height_thumb || $image_size_orig[0] > $width_thumb)
                    Image::factory($directory.$filename_thumb)
                                ->crop($width_thumb, $height_thumb)
                                ->save($directory.$filename_thumb); 
                }
                // Delete the temporary file
                unlink($file);
            }
        }   
        
    }

    /**
     * image_path make unique dir path with a given date and id
     * 
     * @param  [int] $id   [unique id, and folder name]
     * @return [string]             [directory path]
     */
    public function image_path($id, $created)
    { 
        if ($created !== NULL)
        {
            $obj_date = date_parse($created); // convert date to array 
        
            $year = $obj_date['year']; // take last 2 integers 
        
            // check for length, because original path is with 2 integers 
            if(strlen($obj_date['month']) != 2)
                $month = '0'.$obj_date['month'];
            else
                $month = $obj_date['month'];
            
            if(strlen($obj_date['day']) != 2)
                $day = '0'.$obj_date['day'];
            else
                $day = $obj_date['day'];

            $directory = 'images/'.$year.'/'.$month.'/'.$day.'/'.$id.'/';
           
            $directory;
        }
        else
        {
            $date = Date::format(time(), 'Y/m/d');

            $parse_data = explode("/", $date);          // make array with date values
        
            $directory = "images/"; // root upload folder

            for ($i=0; $i < count($parse_data); $i++) 
            { 
                $directory .= $parse_data[$i].'/';           // append, to create directory 
                
            }
                $directory .= $id.'/';
        }
        
        

        if(!is_dir($directory)){         // check if directory exists 
                mkdir($directory, 0755, TRUE);
            }

        return $directory;
    }
}