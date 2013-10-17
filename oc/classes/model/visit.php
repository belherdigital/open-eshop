<?php defined('SYSPATH') or die('No direct script access.');
/**
 * description...
 *
 * @author		Chema <chema@garridodiaz.com>
 * @package		OC
 * @copyright	(c) 2009-2013 Open Classifieds Team
 * @license		GPL v3
 * *
 */
class Model_Visit extends ORM {
	
    /**
     * Table name to use
     *
     * @access	protected
     * @var		string	$_table_name default [singular model name]
     */
    protected $_table_name = 'visits';

    /**
     * Column to use as primary key
     *
     * @access	protected
     * @var		string	$_primary_key default [id]
     */
    protected $_primary_key = 'id_visit';

    /**
     * Rule definitions for validation
     *
     * @return array
     */
    public function rules()
    {
    	return array(
			        'id_visit'	=> array(array('numeric')),
			        'id_product'	=> array(array('numeric')),
			        'id_user'	=> array(array('numeric')),
			    );
    }

    /**
     * Label definitions for validation
     *
     * @return array
     */
    public function labels()
    {
    	return array(
			        'id_visit'		=> 'Id visit',
			        'id_product'		    => 'Id Product',
			        'id_user'		=> 'Id user',
			        'created'		=> 'Created',
			        'ip_address'	=> 'Ip address',
			    );
    }

    public function get_id()
    {
      $id_visit = $this->id_visit;
      return $id_visit;
    }
    /**
     * get popular ads
     * @param  integer $days number of days to calculate
     * @return array        id_ad and count
     */
    public static function popular_ads($days = 30)
    {
        $query = DB::select('id_ad',DB::expr('COUNT(id_visit) count'))
                        ->from('visits')
                        ->where('created','between',array(date('Y-m-d',strtotime('-'.$days.' day')),date::unix2mysql()))
                        ->group_by(DB::expr('id_ad'))
                        ->order_by('count','asc')
                        ->execute();

        return $query->as_array('id_ad');
    }


} // END Model_Visit
