<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AuthController extends Controller
{
// 
    public function register(Request $request){
        $validatedData = $request->validate([
            'unit' => 'required|string',
            'name' => 'required|string',
            'password' => 'required|string',
            'username' => 'required|string',
            'approver' => 'required'
        ]);
        try {
            $user = User::create([
                'unitcode' => $validatedData["unit"],
                'name' => encrypt($validatedData["name"]),
                'password' => Hash::make($validatedData["password"]),
                'repas' => encrypt($validatedData["password"]),
                'username' => $validatedData["username"],
                'approver' => $validatedData["approver"],
                'email' => $request->email,
                'office' => $request->restriction,
                'log' => json_encode(array())
            ]);
    
            return response()->json(['user' => $user, 'token' => $user->createToken('web')->plainTextToken]);
        } catch (\Exception $e) {
            return response()->json(['error'=>'Failed' . $e->getMessage()],500);
        }
        
     }

// 
    public function getLogin(Request $request)
    {
        try {
            // Validate the incoming request
            $validatedData = $request->validate([
                'username' => 'required|string',
                'password' => 'required|string'
            ]);

            // Authenticate the user using username and password
            if (Auth::attempt(['username' => $validatedData['username'], 'password' => $validatedData['password']])) {
                $user = $request->user();
                
                // Retrieve user data and handle logic
                $data = User::all();
                $result = [];

                foreach ($data as $value) {
                    $unit = new UnitController;
                    $obj = $unit->unitRelation($value->unitcode);

                    // Decrypt necessary user data
                    $value->username = $value->username;
                    $value->password = $value->password; // Not required if you already decrypt password in the database
                    $value->repas = decrypt($value->repas);
                    $value->name = decrypt($value->name);
                    $value->offices = $obj->office;
                    $value->restrict = $obj->restrict;
                    $value->unitRights = $obj->rights;
                    $value->hpn = $obj->hpn;
                    $value->org = $obj->org;
                    $value->categorycode = $obj->categorycode;
                    $value->category = $obj->category;
                    $value->departmentcode = $obj->departmentcode;
                    $value->department = $obj->department;
                    $value->departmentabr = $obj->departmentabr;
                    $value->departmentaddress = $obj->departmentaddress;
                    $value->sectioncode = $obj->sectioncode;
                    $value->section = $obj->section;
                    $value->sectionabr = $obj->sectionabr;
                    $value->teamcode = $obj->teamcode;
                    $value->team = $obj->team;
                    $value->teamabr = $obj->teamabr;

                    // Check if username and password match
                    if ($value->username == $validatedData["username"] && $value->repas == $validatedData["password"]) {
                        if ($value->departmentcode == $request->logoCode) {
                            $encryptedData = base64_encode(json_encode($value));
                            // Generate token
                            $token = $user->createToken('web')->plainTextToken;
                            return response()->json([
                                "user" => $encryptedData,
                                "token" => $token
                            ], 200);
                        } else {
                            return response()->json(['error' => 'You cannot log in to this unit'], 403);
                        }
                    }
                }

                // If no match is found
                return response()->json(['error' => 'Username and password do not match'], 401);
            }

            // If authentication fails
            return response()->json(['error' => 'Invalid credentials'], 401);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed: ' . $e->getMessage()], 500);
        }
    }

// 
    public function validateCaptcha(Request $request)
    {
        // Add your custom captcha validation logic here
        $captchaData = $request->input('captchaData');
        $validateData = $request->input('validateData');
        
        // Implement your captcha validation logic here
        if ($captchaData === $validateData) {
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false]);
        }
    }
// 
    public function insertLog(Request $request) {
        $clientIP = \Request::ip();
        // Validate the request
        $validatedData = $request->validate([
            'id' => 'required|integer',
            'log' => 'required|string'
        ]);
        // Fetch the user record using parameter binding
        $data = User::select('log')
            ->where('id', $validatedData['id'])
            ->first();
           
        if ($data) {
            
            // Decode the JSON string into a PHP array
            $logArray = json_decode($data->log, true);

            // Add the new object to the array
            $newObject = [
                'Login' => $validatedData['log'],
                'Logout' => '',
                'ipaddress' => $clientIP
            ];
            $logArray[] = $newObject;

            // Encode the array back to JSON
            $updatedLog = json_encode($logArray);

            // Update the database with the modified JSON array
                User::where('id', $validatedData['id'])
                ->update(['log' => $updatedLog]);
        } else {
            // Handle the case where the user record is not found
            return response()->json(['error' => 'User not found'], 404);
        }

        return response()->json(['success' => true]);
    }
// 
    public function logout(Request $request){
        $user = $request->user();
    
        // $data = User::select('log')
        // ->where('id', $request->id)
        // ->first();
        if ($user) {
        $data = User::find($user->id);
        $logArray = json_decode($user->log, true);
        foreach ($logArray as $key => $value) {
            if($value["Login"] && $value["Logout"] == ""){
               // Update the existing object in the array
                $logArray[$key]["Logout"] = $request->log;

                // Encode the array back to JSON
                $updatedLog = json_encode($logArray);
                 // Update the database with the modified JSON array
                    
                 $data->log = $updatedLog;
                 $data->save();
                //  User::where('id', $request->id)
                // ->update(['log' => $updatedLog]);
            }
            $tokenValue = $request->header('Authorization');                        //getting generated token
            $tokenId = explode('|', str_replace('Bearer ', '', $tokenValue))[0];    // explode token id
            $request->user()->tokens()->where('id', $tokenId)->delete();            // delete token in in personal access token table
            return response()->json(['message' => 'Tokens revoked']);
        }

        }
        return response()->json(['message' => 'User not authenticated'], 401);
        // return redirect('/login');
    }
}
