<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;

class SsoAuthController extends Controller
{
    /**
     * Redirect user to SSO Service login page.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login()
    {
        $client_id = env('CLIENT_ID');
        // $redirect_uri = env('REDIRECT_URI');

        // Redirect ke SSO Service dengan client_id dan redirect_uri
        return redirect(env('SSO_SERVICE_URL') . '/sso/login?client_id=' . $client_id);
    }

    /**
     * Handle callback from SSO Service.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function callback(Request $request)
    {
        $token = $request->get('token');

        if (!$token) {
            return redirect('/')->withErrors('Token is missing.');
        }

        $httpClient = new Client();

        try {
            // Mengirim permintaan POST ke SSO Service untuk menukar token dengan informasi pengguna
            $response = $httpClient->post(env('SSO_SERVICE_URL') . '/api/sso/token', [
                'form_params' => [
                    'token' => $token,
                ],
            ]);

            $userData = json_decode((string) $response->getBody(), true);

            // Simpan informasi pengguna ke session
            session(['user' => $userData]);

            return redirect('/home');
        } catch (\Exception $e) {
            return redirect('/')->withErrors('Failed to exchange token.');
        }
    }

    /**
     * Display home page with user information.
     *
     * @return \Illuminate\View\View
     */
    public function home()
    {
        $user = session('user');

        if (!$user) {
            return redirect('/')->withErrors('You are not logged in.');
        }

        return view('home', compact('user'));
    }

    /**
     * Logout user from the client application.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout()
    {
        session()->forget('user');

        return redirect('/');
    }
}