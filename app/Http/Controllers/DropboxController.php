<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Spatie\Dropbox\Client as DropboxClient;

class DropboxController extends Controller
{
    // Redirect user to Dropbox OAuth page
    public function connect()
    {
        $authUrl = "https://www.dropbox.com/oauth2/authorize?" . http_build_query([
            'client_id'     => config('services.dropbox.client_id'),
            'redirect_uri'  => route('dropbox.callback'),
            'response_type' => 'code',
        ]);

        return redirect($authUrl);
    }

    // Handle the callback after Dropbox login
    public function callback(Request $request)
    {
        $code = $request->get('code');

        // Exchange the authorization code for an access token
        $response = Http::asForm()->post('https://api.dropboxapi.com/oauth2/token', [
            'code'          => $code,
            'grant_type'    => 'authorization_code',
            'client_id'     => config('services.dropbox.client_id'),
            'client_secret' => config('services.dropbox.client_secret'),
            'redirect_uri'  => route('dropbox.callback'),
        ]);

        $tokenData = $response->json();

        // Store access token in the user's profile
        $user = Auth::user();
        $user->dropbox_token = $tokenData['access_token'];
        $user->dropbox_refresh_token = $tokenData['refresh_token'];
        $user->dropbox_token_expires_in = now()->addSeconds($tokenData['expires_in']);
        $user->save();

        return redirect()->route('dashboard')->with('success', 'Dropbox connected successfully.');
    }

    // Disconnect Dropbox account
    public function disconnect()
    {
        // Get the authenticated user
        $user = Auth::user();

        // Clear Dropbox tokens from the database
        $user->dropbox_token = null;
        // $user->dropbox_refresh_token = null;
        // $user->dropbox_token_expires_in = null;
        $user->save();

        // Redirect back with a success message
        return redirect()->route('dashboard')->with('success', 'Dropbox disconnected.');
    }

    public function uploadDropboxFiles(Request $request)
{
    $files = json_decode($request->input('files'), true);
    $user = auth()->user();

    foreach ($files as $fileUrl) {
        // Modify the Dropbox URL for direct download
        $downloadUrl = str_replace('www.dropbox.com', 'dl.dropboxusercontent.com', $fileUrl);

        // Get file contents
        $fileContents = file_get_contents($downloadUrl);

        // Extract the file name without any query parameters
        $parsedUrl = parse_url($downloadUrl);
        $fileName = basename($parsedUrl['path']); // Extract the base file name

        // Save the file to the 'public' storage disk
        Storage::disk('public')->put("uploads/{$fileName}", $fileContents);

        // Save file details to the database
        $user->uploads()->create([
            'file_name' => "uploads/{$fileName}",
            'file_url' => $fileUrl,
        ]);
    }

    return redirect()->back()->with('success', 'Files uploaded successfully!');
}

}
