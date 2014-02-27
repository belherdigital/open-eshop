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
                $license = Model_License::get($license);
                if ($license->loaded())
                    $license->order->download();
            }
        }

        //by default return false since downlaod could not be done
        $this->response->headers('Content-type','application/javascript');
        $this->response->body(json_encode(FALSE));
    }

    public function action_products()
    {
        $this->auto_render = FALSE;
        $seo_category = $this->request->param('id');

       
       $items = array();

        //last products, you can modify this value at: general.feed_elements
        $products = new Model_Product();
        $products = $products 
                ->where('status','=',Model_Product::STATUS_ACTIVE)
                ->order_by('id_category','asc')
                ->order_by('price','asc')
                ->limit(Core::config('general.feed_elements'));

        //filter by category 
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
            $url= Route::url('product',  array('seotitle'=>$p->seotitle,'category'=>$p->category->seoname));
            $urlmin= Route::url('product-minimal',  array('seotitle'=>$p->seotitle,'category'=>$p->category->seoname));

            $items[] = array(
                                'id_product'    => $p->id_product,
                                'order'         => $i,
                                'title'         => $p->title,
                                'seoname'       => $p->seotitle,
                                'skins'         => $p->skins,
                                'url_more'      => $url,
                                'url_buy'       => $url,//$urlmin,// in case you want embed buy
                                'url_demo'      => (!empty($p->url_demo))?Route::url('product-demo', array('seotitle'=>$p->seotitle,'category'=>$p->category->seoname)):'',
                                'url_screenshot'=> URL::base().$p->get_first_image(),
                                'type'          => $p->category->seoname,
                                'price'         => $p->price,
                                'price_offer'   => $p->price_offer,
                                'offer_valid'   => $p->offer_valid,
                                'status'        => $p->status,
                                'created'       => $p->created,
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
