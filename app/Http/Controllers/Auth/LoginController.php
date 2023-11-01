<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;


    protected $username = 'email';
    protected $model = \App\User::class;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/test-connection';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        //dd(1);
    }

    public function login(Request $request)
    {
        // Lógica de autenticação do usuário na primeira tabela (seu código existente).
        $credentials = $request->only('email', 'password');

        if (auth()->attempt($credentials)) {
            // Autenticação bem-sucedida
            
            //faz relacionamento para verificar a terceira tabela.
            $userProfile = auth()->user()->userProfile;
            //dd($userProfile);

            // Crie a configuração para a conexão com o outro banco de dados, usando relaciomento criado na model.
            $connection = $userProfile->databaseConnection;

            //dd($connection);

            $databaseConfig = [
                'driver' => 'mysql',
                'host' => $connection->DBservidor,
                'database' => $connection->DBbancoDados,
                'username' => $connection->DBusuario,
                'password' => $connection->DBsenha,
            ];

            // Configure a conexão dinâmica com o outro banco de dados.

            Config::set('database.connections.dynamic_db', $databaseConfig);
            DB::setDefaultConnection('dynamic_db');

            //conectado no banco dinamico.

            $results = DB::connection('dynamic_db')->select('SELECT * FROM personal_submenus');

            $i = 0;

            foreach ($results as $row) {

                if ($i < 0) {
                    $i = 0;
                }

                $i++;

                // Faça algo com os resultados, por exemplo, imprimir os valores
                echo "linha:" . $i . " - " . $row->ps_link . ", - " . $row->ps_description . '</br>';
            }
            exit;
            return redirect()->intended($this->redirectPath()); // Redireciona para a página de destino após o login.
        }

        // Autenticação falhou
        return back()->withErrors(['message' => 'Usuário ou senha incorretos']);
    }
}
