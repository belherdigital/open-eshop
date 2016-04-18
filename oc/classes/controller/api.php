<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Api extends Controller {



    /**
     * action to verify a license name with a domain as post
     * @return [type] [description]
     */
    public function action_license()
    {
        $this->auto_render = FALSE;
        $license = $this->request->param('id');

        //getting domain from referrer
        if ( ($domain = $this->request->referrer())!==NULL) 
        {
            $domain = parse_url($domain, PHP_URL_HOST);
        }
        else//TODO remove in few versions, we should use only referrer
            $domain  = Core::request('domain');

        if ($license!=NULL AND $domain!=NULL)
            $result = Model_License::verify($license,$domain); 
        else
            $result = FALSE;

        $this->response->headers('Content-type','application/javascript');
        $this->response->body(json_encode($result));
    }

    /**
     * action to download a zip file directly form the API
     * @return [type] [description]
     */
    public function action_download()
    {
        $this->auto_render = FALSE;
        $license = $this->request->param('id');
        $domain  = Core::request('domain');

        if ($license!=NULL AND $domain!=NULL)
        {
            //ok, let's download the zip file if validated license
            if (Model_License::verify($license,$domain) === TRUE)
            {
                $license = Model_License::get_license($license);
                if ($license->loaded())
                    $license->order->download();
            }
        }

        //by default return false since downlaod could not be done
        $this->response->headers('Content-type','application/javascript');
        $this->response->body(json_encode(FALSE));
    }

    /**
     * API that return the products.
     * Allows api/products/<category_optional>?order1=rate&sort=asc&order2=version&sort=desc
     * @return [type] [description]
     */
    public function action_products()
    {
        $this->auto_render = FALSE;
        
        $sort_allowed   = array('asc','desc');
        $order_allowed  = array('id_category','created','updated','price','title','rate');

        $order1 = Core::get('order1');
        //default value
        if (!in_array($order1, $order_allowed) OR $order1===NULL)
            $order1 = 'id_category';

        $sort1 = Core::get('sort1');
        //default value
        if (!in_array($sort1, $sort_allowed) OR $sort1===NULL)
            $sort1 = 'asc';

        $order2 = Core::get('order2');
        //default value
        if (!in_array($order2, $order_allowed) OR $order2===NULL)
            $order2 = 'price';

        $sort2 = Core::get('sort2');
        //default value
        if (!in_array($sort2, $sort_allowed) OR $sort2===NULL)
            $sort2 = 'asc';
       
        $items = array();

        //products filtered
        $products = new Model_Product();
        $products = $products 
                ->where('status','=',Model_Product::STATUS_ACTIVE)
                ->order_by($order1,$sort1)
                ->order_by($order2,$sort2);

        //filter by category 
        $seo_category = $this->request->param('id');
        if ($seo_category!==NULL)
        {
            $category = new Model_Category();
            $category->where('seoname','=',$seo_category)->limit(1)->find();
            if ($category->loaded())
                $products->where('id_category','=',$category->id_category);
        }

       
        $products = $products->cached()->find_all();
        $i = 0;
        foreach($products as $p)
        {
            $url      = Route::url('product',  array('seotitle'=>$p->seotitle,'category'=>$p->category->seoname));
            $urlmin   = Route::url('product-minimal',  array('seotitle'=>$p->seotitle,'category'=>$p->category->seoname));

            $in_offer = (Date::mysql2unix($p->offer_valid)>time())? TRUE : FALSE;

            $items[] = array(
                                'id_product'    => $p->id_product,
                                'order'         => $i,
                                'title'         => $p->title,
                                'seoname'       => $p->seotitle,
                                'skins'         => $p->skins,
                                'url_more'      => $url,
                                'url_buy'       => $url,//$urlmin,// in case you want embed buy
                                'url_demo'      => (!empty($p->url_demo))?Route::url('product-demo', array('seotitle'=>$p->seotitle,'category'=>$p->category->seoname)):'',
                                'url_screenshot'=> URL::base().$p->get_first_image('image'),
                                'type'          => $p->category->seoname,
                                'price'         => $p->price,
                                'currency'      => $p->currency,
                                'price_offer'   => ($in_offer===TRUE)?$p->price_offer:NULL,
                                'offer_valid'   => ($in_offer===TRUE)?$p->offer_valid:NULL,
                                'rate'          => $p->rate,
                                'created'       => $p->created,
                                'updated'       => $p->updated,
                                'version'       => $p->version,
                                'description'   => Text::removebbcode(preg_replace('/&(?!\w+;)/', '&amp;',$p->description)),
                          );
            $i++;
        }
  

        $this->response->headers('Content-type','application/javascript');
        $this->response->body(json_encode($items));
    }

    /**
     * after does nothing since we send an XML
     */
    public function after(){}


} // End feed
