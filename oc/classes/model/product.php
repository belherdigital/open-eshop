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
}