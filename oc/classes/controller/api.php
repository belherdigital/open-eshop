<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Api extends Controller {




    public function action_license()
    {
        $this->auto_render = FALSE;
        $license = $this->request->param('id');
        $domain  = Core::post('domain');
        if ($license!=NULL AND $domain!=NULL)
            $result = Model_License::verify($license,$domain); 
        else
            $result = FALSE;

        $this->response->headers('Content-type','application/javascript');
        $this->response->body(json_encode($result));
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
                ->order_by('price','desc')
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
            $url= Route::url('product-minimal',  array('seotitle'=>$p->seotitle));

            $items[] = array(
                                'id_product'    => $p->id_product,
                                'order'         => $i,
                                'title'         => $p->title,
                                'seoname'       => $p->seotitle,
                                'skins'         => $p->skins,
                                'url_more'      => $url,
                                'url_buy'       => $url,
                                'url_demo'      => $p->url_demo,
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
