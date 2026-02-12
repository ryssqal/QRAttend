# Migration Plan: Move Image Handling from public/storage to public/uploads

## Information Gathered
- Event images are already saved to public/uploads with media_path = 'uploads/filename', but views incorrectly use asset('storage/' . $event->media_path).
- User avatars are saved to storage/app/public/avatars and displayed with asset('storage/' . avatar).
- ProfileController has buggy upload logic for profile photos.
- PengurusMajlisController uses Storage::disk('public') in destroy method.
- Views: settings.blade.php and client/home.blade.php use asset('storage/...').

## Plan
1. Update ProfileController: Fix upload logic to save to public/uploads/avatars, set avatar = 'uploads/avatars/filename', use direct file operations.
2. Update PengurusMajlisController: Change destroy method to use file_exists and unlink instead of Storage::disk('public').
3. Update views: Change asset('storage/' . ...) to appropriate asset for uploads.
   - For events: asset($event->media_path)
   - For avatars: asset('uploads/avatars/' . Auth::user()->avatar)
4. Ensure subfolders: avatars, events (events already use uploads/).
5. Remove dependency on storage:link and Storage::disk('public').

## Dependent Files
- app/Http/Controllers/ProfileController.php
- app/Http/Controllers/PengurusMajlisController.php
- resources/views/settings.blade.php
- resources/views/client/home.blade.php

## Followup Steps
- Test image uploads and displays.
- Ensure shared hosting compatibility (direct access to public/uploads).
