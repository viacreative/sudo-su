<?php

namespace VIACreative\SudoSu;

use Illuminate\Auth\AuthManager;
use Illuminate\Support\Facades\Config;
use Illuminate\Foundation\Application;
use Illuminate\Session\SessionManager;
use Symfony\Component\HttpFoundation\Response;

class SudoSu
{
    protected $app;
    protected $auth;
    protected $session;
    protected $sessionKey = 'sudosu.original_id';
    protected $usersCached = null;
    protected $currentGuard;

    public function __construct(Application $app, AuthManager $auth, SessionManager $session)
    {
        $this->app = $app;
        $this->currentGuard = $this->getCurrentGuard();
        $this->auth = $auth->guard($this->currentGuard);
        $this->session = $session;
    }

    /**
     * Stores the ID of the current user in the session so we can return
     * back to the original account later, then logs the user in
     * as the user with the given ID.
     *
     * @param integer $userId
     * @param integer $originalUserId
     * @return void
     */
    public function loginAsUser($userId, $originalUserId)
    {
        $this->session->put('sudosu.has_sudoed', true);
        $this->session->put($this->sessionKey, $originalUserId);

        $this->auth->loginUsingId($userId);
    }

    /**
     * Logs the user out of the current authenticated account, then returns
     * the user back to their original account using the ID stored in the
     * session (if it exists).
     *
     * @return bool
     */
    public function logout()
    {
        if (!$this->hasSudoed()) {
            return false;
        }

        $this->auth->logout();

        $originalUserId = $this->session->get($this->sessionKey);

        if ($originalUserId) {
            $this->auth->loginUsingId($originalUserId);
        }

        $this->session->forget($this->sessionKey);
        $this->session->forget('sudosu.has_sudoed');

        return true;
    }

    public function injectToView(Response $response)
    {
        $packageContent = view('sudosu::user-selector', [
            'users' => $this->getUsers(),
            'hasSudoed' => $this->hasSudoed(),
            'originalUser' => $this->getOriginalUser(),
            'currentUser' => $this->auth->user()
        ])->render();

        $responseContent = $response->getContent();

        $response->setContent($responseContent . $packageContent);
    }

    public function getOriginalUser()
    {
        if (!$this->hasSudoed()) {
            return $this->auth->user();
        }

        $userId = $this->session->get($this->sessionKey);

        return $this->getUsers()->where('id', $userId)->first();
    }

    public function hasSudoed()
    {
        return $this->session->has('sudosu.has_sudoed');
    }

    public function getUsers()
    {
        if ($this->usersCached) {
            return $this->usersCached;
        }

        $user = $this->getUserModel();

        return $this->usersCached = $user->get();
    }

    protected function getUserModel()
    {
        $userModel = Config::get('sudosu.user_model');
        return $this->app->make($userModel);
    }
    
    public function getCurrentGuard()
    {
        return Config::get('sudosu.current_guard');
    }
}
