# TODO: Create Account Settings Page for Event Manager

## Migration
- [ ] Create migration to add 'profile_photo' column to users table

## Model
- [ ] Update User model to include 'profile_photo' in fillable array

## Controller
- [ ] Create ProfileController with update method
- [ ] Add validation for name (required, string) and image (image, mimes:jpeg,png,jpg, max:2048)
- [ ] Implement storage logic: store in storage/app/public/profiles, delete old photo if new one uploaded

## Route
- [ ] Add route for profile settings in web.php (under pengurusMajlis middleware)

## View
- [ ] Create Blade view for profile settings page with:
  - Circular profile picture preview
  - File input for 'Profile Picture' (JPEG, PNG, max 2MB)
  - Text input for 'Name'
  - 'Update Profile' button

## Global Display
- [ ] Update navbar/sidebar to display Auth::user()->name and profile picture

## Storage
- [ ] Run php artisan storage:link if necessary

## Testing
- [ ] Test the functionality: upload photo, update name, check display in navbar
