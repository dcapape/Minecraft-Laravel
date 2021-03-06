<?php

/**
 * Users Controller
 */

class UsersController extends BaseController {
    public function __construct() {
        $this->beforeFilter('csrf', array('on' => 'post'));
    }
    public function getRegister() {
        return View::make('public.pages.register');
    }
    public function postCreate() {
      $MCAuth = new MCAuth\Api();

      if (@Input::get('premium') == true){
        try {
            $account = $MCAuth->sendAuth(Input::get('email'), Input::get('password'));
            $validator = Validator::make(Input::all(), User::$rulespremium);
            if ($validator->passes()) {
                $user = new User;
                $user->nick = $account->username;
                $user->email = Input::get('email');
                $user->password = Hash::make(Input::get('password'));
                $user->premium = true;
                $user->save();
                if (Auth::loginUsingId($user->id))
                    return Redirect::to('/')->with('message', 'Thanks for registering!');
                else
                    return Redirect::to('/login')->with('message', 'Thanks for registering!');
            } else {
                return Redirect::to('/register')->with('message', 'The following errors occurred')->withErrors($validator)->withInput();
            }
        } catch (Exception $e) {
            return Redirect::to('/register')->with('message', 'The following errors occurred: <br> ' . $e->getMessage());
        }
      }else{ // NO PREMIUM ACCOUNT
        $validator = Validator::make(Input::all(), User::$rules);
        if ($validator->passes()) {
            try {
            //var_dump($MCAuth->usernameToUuid(Input::get('nick')));
                //$uuid = @$MCAuth->usernameToUuid(Input::get('nick'));
                //throw new InvalidArgumentException('tripleInteger function only accepts integers. Input was: '.$uuid);
                //$uuid = null;
                //if (isset($uuid)){

                ///https://visage.surgeplay.com/full/400/nickname
                if ( User::isAvailable(Input::get('nick')) ) {
                    return Redirect::to('/register#non-premium')->with('message', 'The following errors occurred: <br> This nickname is already taken');
                }else{
                    $uuid = new UuidGen;
                    $user = new User;
                    $user->uuid = $uuid->generateUuid(Input::get('nick'));
                    $user->nick = Input::get('nick');
                    $user->email = Input::get('email');
                    $user->password = Hash::make(Input::get('password'));
                    $user->premium = false;
                    $user->confirmationCode = Convert::random(60);
                    $user->save();

                    Mail::send('emails.'.LaravelLocalization::getCurrentLocale().'.verify', ['confirmationCode' => $user->confirmationCode], function($message) {
                        $message->to(Input::get('email'), Input::get('nick'));
                        $message->subject(trans('auth.emailSubject'));
                    });

                    return Redirect::to('/login')->with('message', 'Thanks for signing up! Please check your email and follow the instructions to complete the sign up process');

                    //if (Auth::loginUsingId($user->id))
                    //    return Redirect::to('/')->with('message', 'Thanks for registering!');
                    //else
                    //    return Redirect::to('/login')->with('message', 'Thanks for registering!');
                }

            /*} catch (InvalidArgumentException $e) {
                $user = new User;
                $user->nick = Input::get('nick');
                $user->email = Input::get('email');
                $user->password = Hash::make(Input::get('password'));
                $user->premium = false;
                $user->save();
                if (Auth::loginUsingId($user->id))
                    return Redirect::to('/')->with('message', 'Thanks for registering!');
                else
                    return Redirect::to('/login')->with('message', 'Thanks for registering!');*/
            } catch (Exception $e){
              return Redirect::to('/register#non-premium')->with('message', 'The following errors occurred: <br> ' . $e->getMessage());
            }
        } else {
            return Redirect::to('/register#non-premium')->with('message', 'The following errors occurred: ')->withErrors($validator)->withInput();
        }
      }

    }
    public function getLogin() {
        if (Auth::guest())
          return View::make('public.pages.login');
        else
          return Redirect::to('/')->with('message', 'You are now logged in!');
    }
    public function postSignin() {
        $MCAuth = new MCAuth\Api();
        try {
          $account = $MCAuth->sendAuth(Input::get('nick'), Input::get('password'));
          //dd(Input::get('nick'));
          if (Auth::loginUsingId(User::getIdByNick($account->username)->id)){
              $user = Auth::user();
              $user->email = Input::get('nick');
              $user->premium = true;
              $user->save();
              
              if (Session::get('redirect'))
                return Redirect::to(Session::get('redirect'))->with('message', 'You are now logged in!');
              else
                return Redirect::to('/')->with('message', 'You are now logged in!');
          }else
              return Redirect::to('/login')->with('message', 'Something wrong happened ErrCode:0x00001');
        //var_dump($account); die;
        } catch (Exception $e) {
            if (Auth::attempt(array('nick' => Input::get('nick'), 'password' => Input::get('password')))) {
              if (Session::get('redirect'))
                return Redirect::to(Session::get('redirect'))->with('message', 'You are now logged in!');
              else
                return Redirect::to('/')->with('message', 'You are now logged in!');
            } else {
                return Redirect::to('/login')
                                ->with('message', 'Your username/password combination was incorrect '. $e->getMessage())
                                ->withInput();
            }
        }
    }
    public function getLogout() {
        Auth::logout();
        return Redirect::to('/login')->with('message', 'Your are now logged out!');
    }
    /**
     * Attempt to confirm a users account.
     *
     * @param $confirmation_code
     *
     * @throws InvalidConfirmationCodeException
     * @return mixed
     */
    public function getConfirm($confirmationCode)
    {
        if( ! $confirmationCode)
        {
            return Redirect::home();
        }
        $user = User::where('confirmationCode', $confirmationCode)->first();
        if ( ! $user)
        {
            return Redirect::home();
        }
        $user->checked = 1;
        $user->confirmationCode = null;
        $user->save();

        if (Auth::loginUsingId($user->id))
          return Redirect::to('/')->with('message', 'You have successfully verified your account.');
        else
          return Redirect::to('/login')->with('message', 'You have successfully verified your account. You can now login.');
    }
}
