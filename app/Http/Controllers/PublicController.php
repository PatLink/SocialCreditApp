<?php namespace SocialCreditPointsApp\Http\Controllers;


use Auth;
use Authenticatable;
use SocialCreditPointsApp\User;
use SocialCreditPointsApp\Students;
use Hash;
use DB;

/**
 * Class PublicController
 * @package SocialCreditPointsApp\Http\Controllers
 */
class PublicController extends Controller {

	/**
	 * @return \Illuminate\View\View
	 */
	public function index()
	{
        if (Auth::check())
        {
            return redirect('user');
        }

        // error_reporting(E_ALL);
        // ini_set("display_errors", 1);

        # Klasse laden
        require_once('C:/xampp/htdocs/SocialCreditApp/app/Http/AuthenticationComponents/class.DhbwSSOAgent.php');


        #### Konfiguration vornehmen (ANPASSEN!)
        $sigssoAgentConfig = new \DhbwSSOAgentConfig();

        # Handelt es sich um einen Windows-Server?
        $sigssoAgentConfig->setWindowsServer(false);

        # Pfad zum Public-Key um die Signatur zu prüfen
        $sigssoAgentConfig->setPublicSSLKey('C:/xampp/htdocs/SocialCreditApp/app/Http/AuthenticationComponents/var/sigsso_public.key');

        # Pfad zum Tokenfile, wo benutze Token gesperrt werden
        $sigssoAgentConfig->setTokensfile('C:/xampp/htdocs/SocialCreditApp/app/Http/AuthenticationComponents/var/usedtokens.txt');

        # Pfad zum Logfile (Loglevel beachten)
        $sigssoAgentConfig->setLogfile('C:/xampp/htdocs/SocialCreditApp/app/Http/AuthenticationComponents/tmp/sigsso.log');

        # Pfade für externes OpenSSL (nur falls PHP kein openSSL unterstützt)
        $sigssoAgentConfig->setTmpSignatureDir('C:/xampp/htdocs/SocialCreditApp/app/Http/AuthenticationComponents/tmp');
        $sigssoAgentConfig->setTmpSignaturePrefix('sign_');
        $sigssoAgentConfig->setExternalOpenSSL(false);


        # Loglevel
        #       0: no logging
        #       1: errors (tpa_id + user)
        #       2: success and errors (tpa_id + user)
        #       3: errors (tpa_id + user + expires + signature)
        #       4: success and errors (tpa_id + user + expires + signature)
        $sigssoAgentConfig->setLoglevel(4);

        # Adapter hinzufÃ¼gen (in diesem Fall Defaultadapter fÃ¼r PHP)
        $sigssoAgentConfig->addAdapter("scpapp", new \DhbwSSOAdapter());

        # Zum Debuggen (nicht fÃ¼r Produktiveinsatz)
        # $sigssoAgentConfig->setErrorhandler(new DhbwSSODebugErrorhandler());

        #### Klasse Initialisieren
        $sigsso = new \DhbwSSOAgent($sigssoAgentConfig);

        # Wenn Execute erfolgreich dann ist der Username authentifiziert.
        if (!$sigsso->execute()) {
            #echo $sigsso->getConfig()->getErrorhandler()->getErrorMessage();

            return view('frontend.index');
        }

        $user_first_name = $sigsso->getUserdata()['first_name'];
        $user_last_name = $sigsso->getUserdata()['last_name'];
        $user_email = $sigsso->getUserdata()['email'];
        $user_password = Hash::make('dhbwMosbach2015!#2015');
        $user_userrole = 1;
        $user_dhbw_login = 1;

        $user = DB::table('users')->where('email', $user_email)->first();

        if(!$user){
            # Einfügen des Benutzers in die Datenbank
            # => Nur einfügen, wenn dieser noch nicht existiert
            $user = new User;
            $user->first_name = $user_first_name;
            $user->last_name = $user_last_name;
            $user->email = $user_email;
            $user->password = $user_password;
            $user->userrole = $user_userrole;
            $user->dhbw_login = $user_dhbw_login;
            $user->save();


            DB::table('students')->insert(
                array(
                    'user_id' => $user->id,
                    'matriculation_number' => rand(),
                    'course_id' => DB::table('courses')->where('enrollment_year', '2013')->pluck('id')
                )
            );
        }

        if (Auth::loginUsingId($user->id))
        {
            return redirect()->intended('user');
        }
	}

    public function about()
    {
        return view('frontend.about');
    }

    public function privacy()
    {
        return view('frontend.privacy');
    }

    public function impress()
    {
        return view('frontend.impress');
    }
}
