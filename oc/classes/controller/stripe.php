<?php defined('SYSPATH') or die('No direct script access.');

/**
* Stripe class
*
* @package Open Classifieds
* @subpackage Core
* @category Helper
* @author Chema Garrido <chema@open-classifieds.com>
* @license GPL v3
*/

class Controller_Stripe extends Controller{
	

        /**
     * [action_form] generates the form to pay at paypal
     */
    public function action_pay()
    { 
        $this->auto_render = FALSE;

        $id_order = $this->request->param('id');

        //retrieve info for the item in DB
        $order = new Model_Order();
        $order = $order->where('id_order', '=', $id_order)
                       ->where('status', '=', Model_Order::STATUS_CREATED)
                       ->limit(1)->find();

        if ($order->loaded())
        {

            if ( isset( $_POST[ 'stripeToken' ] ) ) 
            {
                //its a fraud...lets let him know
                if ( $order->is_fraud() === TRUE )
                {
                    Alert::set(Alert::ERROR, __('We had, issues with your transaction. Please try paying with another paymethod.'));
                    $this->redirect(Route::url('default', array('controller'=>'product','action'=>'checkout','id'=>$order->id_order)));
                }

                // include class vendor
                require Kohana::find_file('vendor/stripe', 'init');

                // Set your secret key: remember to change this to your live secret key in production
                // See your keys here https://manage.stripe.com/account
                \Stripe\Stripe::setApiKey(Core::config('payment.stripe_private'));

                // Get the credit card details submitted by the form
                $token = Core::post('stripeToken');

                // email
                $email = Core::post('stripeEmail');

                // Create the charge on Stripe's servers - this will charge the user's card
                try 
                {
                    $charge = \Stripe\Charge::create(array(
                                                        "amount"    => StripeKO::money_format($order->amount), // amount in cents, again
                                                        "currency"  => $order->currency,
                                                        "card"      => $token,
                                                        "description" => $order->product->title)
                                                    );

                    //mark as paid
                    $order->confirm_payment('stripe',Core::post('stripeToken'), NULL, NULL, NULL,StripeKO::calculate_fee($order->amount) );
                }
                catch(Stripe_CardError $e) 
                {
                    // The card has been declined
                    Kohana::$log->add(Log::ERROR, 'Stripe The card has been declined');
                    Alert::set(Alert::ERROR, 'Stripe The card has been declined');
                    $this->redirect(Route::url('default', array('controller'=>'product','action'=>'checkout','id'=>$order->id_order)));
                }

                //redirect him to the goal
                Alert::set(Alert::SUCCESS, __('Thanks for your payment!'));
                $this->redirect(Route::url('default', array('controller'=>'product','action'=>'goal','id'=>$order->id_order)));
                                
            }
            else
            {
                Alert::set(Alert::INFO, __('Please fill your card details.'));
                $this->redirect(Route::url('default', array('controller'=>'product','action'=>'checkout','id'=>$order->id_order)));
            }
            
        }
        else
        {
            Alert::set(Alert::INFO, __('Order could not be loaded'));
            $this->redirect(Route::url('default', array('controller'=>'product','action'=>'checkout','id'=>$order->id_order)));
        }
    }

    /**
     * [action_form] generates the js for stripe not in use see controller product-action single
     */
    // public function action_javascript()
    // { 
    //     $this->auto_render = FALSE;

    //     $seotitle = $this->request->param('id');

    //     $product = new Model_product();
    //     $product->where('seotitle','=',$seotitle)
    //         ->where('status','=',Model_Product::STATUS_ACTIVE)
    //         ->limit(1)->find();

    //     if ($product->loaded())
    //     {
    //         $this->template = View::factory('js');
    //         $this->template->content = View::factory('pages/stripe/js',array('product'=>$product));
    //     }
    //     else
    //     {
    //         Alert::set(Alert::INFO, __('Product could not be loaded'));
    //         $this->redirect(Route::url('product', array('seotitle'=>$product->seotitle,'category'=>$product->category->seoname)));
    //     }
    // }

}
