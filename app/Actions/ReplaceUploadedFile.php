<?php

namespace App\Actions;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

/**
 * Action class = one class that does ONE job, reusable from any controller, command or job.
 *
 * This one stores an uploaded file and deletes the file it replaces, so no orphans stay on disk.
 * Used for exercise photos (public disk) and progress photos (private disk).
 */
class ReplaceUploadedFile
{
    /**
     * @param  string|null  $oldPath  the path currently saved in the database (null if none)
     * @param  string  $field  the form field name, used for the error message
     * @return string the new path to save in the database, e.g. "exercises/Xy7….jpg"
     */
    public function handle(UploadedFile $file, ?string $oldPath, string $folder, string $disk, string $field): string
    {
        // store() picks a unique random file name. It returns false if saving failed.
        $newPath = $file->store($folder, $disk)
            ?: throw ValidationException::withMessages([$field => 'The photo could not be saved, please try again.']);

        if ($oldPath) {
            Storage::disk($disk)->delete($oldPath); // only after the new file is safely stored
        }

        return $newPath;
    }

    /**
     * Delete a stored file, if there is one (when the exercise/workout itself is deleted).
     */
    public function delete(?string $path, string $disk): void
    {
        if ($path) {
            Storage::disk($disk)->delete($path);
        }
    }
}
