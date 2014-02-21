<?php defined('SYSPATH') or die('No direct script access.');
/**
 * User tickets
 *
 * @author      Chema <chema@open-classifieds.com>
 * @package     Core
 * @copyright   (c) 2009-2013 Open Classifieds Team
 * @license     GPL v3
 */

class Model_Ticket extends ORM {

    /**
     * Status constants
     */
    const STATUS_CREATED        = 0;   // just created
    const STATUS_READ           = 1;   // support read the ticket
    const STATUS_HOLD           = 5;   // waiting for an answered, support anwered
    const STATUS_CLOSED         = 10;  //we closed the ticket

    /**
     * @var  array  Available statuses array
     */
    public static $statuses = array(
        self::STATUS_CREATED      =>  'New',
        self::STATUS_READ         =>  'Read',
        self::STATUS_HOLD         =>  'Hold',
        self::STATUS_CLOSED       =>  'Closed',
    );
  
    /**
     * @var  string  Table name
     */
    protected $_table_name = 'tickets';

    /**
     * @var  string  PrimaryKey field name
     */
    protected $_primary_key = 'id_ticket';

    
    /**
     * @var  array  ORM Dependency/hirerachy
     */
    protected $_belongs_to = array(
        'product' => array(
                'model'       => 'product',
                'foreign_key' => 'id_product',
            ),
        'user' => array(
                'model'       => 'user',
                'foreign_key' => 'id_user',
            ),
        'agent' => array(
                'model'       => 'user',
                'foreign_key' => 'id_user_support',
            ),
        'order' => array(
                'model'       => 'order',
                'foreign_key' => 'id_order',
            ),
        'parent' => array(
                'model'       => 'ticket',
                'foreign_key' => 'id_ticket_parent',
            ),
    );

    public function form_setup($form)
    {
       
    }

    public function exclude_fields()
    {
    
    }
}