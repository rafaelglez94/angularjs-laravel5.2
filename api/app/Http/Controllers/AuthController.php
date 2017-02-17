<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use JWTAuth;
use JWTFactory;
use App\User;
use Storage;
use File;
class AuthController extends Controller
{
	protected $user;
	function __construct(User $user)
	{
		$this->user = $user;
	}
	public function login(Request $request)
	{
		$credentials = $request->only('username', 'password');
		return $this->loginJwt($credentials);
	}
	private function loginJwt(Array $credentials)
	{
		try {
			if (! $token = JWTAuth::attempt($credentials)) {
				return response()->json(['error' => 'invalid_credentials'], 422);
			}
		} catch (JWTException $e) {
			return response()->json(['error' => 'could_not_create_token'], 400);
		}
		return response()->json(compact('token'));
	}
	public function register(UserStoreRequest $request)
	{
		if ($this->user->create($request->all())) {
			$credentials = $request->only('email', 'password');
			return $this->loginJwt($credentials);
		};
		return response()->json(['error' => 'could_not_create_user'], 400);
	}
	public function getUserAuth()
	{
		if (! $user = $this->getUser()) {
            return response()->json(['user_not_found'], 404);
        }
		return response()->json(compact('user'));
	}
	public function updateUser(UserUpdateRequest $request)
	{
		if (! $user = $this->getUser()) {
            return response()->json(['user_not_found'], 404);
        }
		$attributes = $request->except('image');
		if ($request->hasFile('image')) {
			$name = 'images/profiles/'. $this->getUser()->username . $request->file('image')->getClientOriginalName();
			if ($this->getUser()->image) {
				Storage::disk('local')->delete($this->getUser()->image);
			}
			Storage::disk('local')->put($name,File::get($request->file('image')->getRealPath()));
			$attributes['image'] = $name;
		}
		$user->fill($attributes);
		$user->save();
		return response()->json(compact('user'));
	}
	private function getUser()
	{
		return  JWTAuth::parseToken()->authenticate();
	}
}
