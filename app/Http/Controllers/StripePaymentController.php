<?php
   
namespace App\Http\Controllers;

use App\Models\TrainingPackage;
use Illuminate\Http\Request;
use App\Models\User;
use Session;
use Stripe;
   
class StripePaymentController extends Controller
{
    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function stripe()
    {
        $users = User::all();
        $packages = TrainingPackage::all();
        return view('buy_a_package.stripe', ['users' => $users, 'packages' => $packages]);
    }
  
    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function stripePost(Request $request)
    {

        $request->validate([
            'user_id' => ['required'],
            'package_id' => ['required'],
        ]);

        $user = User::find($request->user_id);
        $package = TrainingPackage::find($request->package_id);
        if(!$user || !$package) {
            return redirect()->back()->with('error', 'User or Package not found');
        }

        Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        Stripe\Charge::create ([
                "amount" => $package->price * 100,
                "currency" => "usd",
                "source" => $request->stripeToken,
                "description" => "Test payment from itsolutionstuff.com." 
        ]);
  
        Session::flash('success', 'Payment successful!');
          
        return back();
    }
}