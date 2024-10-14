## About Laravel-Dropbox-Integration

Connect Dropbox in Laravel and upload images directly through Dropbox. Below are some features highlighted:

- Connect Dropbox for each user
- Upload multiple images directly from Dropbox

## Steps to Setup:
- composer i
- php artisan migrate
- php artisan storage:link
- Insert below values in .env

1. DROPBOX_APP_KEY=
2. DROPBOX_APP_SECRET=
3. DROPBOX_REDIRECT_URI=

Here’s a revised step-by-step guide with clear formatting to help you get your **Dropbox App Key, Secret, and Redirect URI**:

---

## **Steps to Get Dropbox API Credentials**  

1. **Create or Log in to a Dropbox Account**  
   - Go to [Dropbox](https://www.dropbox.com/) and **log in** or **sign up** for an account.

2. **Access the Dropbox App Console**  
   - Navigate to the [Dropbox App Console](https://www.dropbox.com/developers/apps).

3. **Create a New App**  
   - Click **"Create App"** and choose:  
     - **Scoped Access** (the latest and more flexible API access type).  
     - **Permissions**: Choose either:  
       - **App folder**: Access only a specific folder your app creates.  
       - **Full Dropbox**: Access the entire user’s Dropbox.

4. **Name Your App**  
   - Enter a unique app name. If it’s already in use, you’ll need to modify it slightly.

5. **Retrieve App Key and App Secret**  
   - After creating the app, you’ll find the **App Key** and **App Secret** on the settings page. **Copy** these values and store them securely.

6. **Set Redirect URI**  
   - Scroll to the **OAuth 2** section in the app settings.  
     - Add your **Redirect URI**, which is the URL users will be redirected to after authentication.  
     - Example: `https://yourdomain.com/dropbox/callback`.

7. **Configure Permissions**  
   - Under the **Permissions** tab, enable required scopes like:
     - `files.metadata.read` (to read file metadata).
     - `files.content.write` (to upload files).
   - Click **Save** once done.

8. **Generate Access Token (Optional)**  
   - Use the **OAuth 2 Token Generator** to test authentication and API access. This helps confirm everything is working.

9. **Test Your App Integration**  
   - You can now use the **App Key, App Secret, and Redirect URI** to integrate Dropbox into your Laravel or other projects.

---

These steps will allow you to generate and configure your Dropbox API credentials. Let me know if you encounter any issues!

## License

The is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).