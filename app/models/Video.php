<?php

class Video extends Eloquent {

    protected $table = 'videos';
    protected $allowed_extensions = array('mp4');

    public function user()
    {
        return $this->hasOne('User', 'id_member');
    }

    public static function deleteVideoFile($name)
    {
        $path = str_replace(URL::to('/'), base_path(), $name);
        if (File::exists($path)) {
            File::delete($path);
        }
    }

    public static function remove(Video $video)
    {
        if (!Session::get('is_admin')) {
            if ($video->user_id !== Auth::user()->id_member) {
                return false;
            }
        }

        Video::deleteVideoFile($video->name);
        $video->delete();

        return true;
    }

    /**
    * UPLOAD METHODS
    */
    public function handleFiles($temp_dir, $content)
    {
        // We cannot allow all kind of files to be uploaded
        $extension = explode('.', Input::get('flowFilename'));
        $extension = end($extension);
        if (!in_array($extension, $this->allowed_extensions)) {
            throw new Exception("Невалиден видео формат!", 1);
        }

        // And we cannot also allow tones of data to be uploaded to the server
        $size = Input::get('flowTotalSize');
        if ($size > 209715200) {
            throw new Exception("Файлът е по-голям от 200МБ!", 1);
        }

        // loop through files and move the chunks to a temporarily created directory
        foreach (Input::file() as $file) {
            // check the error status
            // init the destination file (format <filename.ext>.part<#chunk>
            // the file is stored in a temporary directory
            $dest_file = $temp_dir . '/' . Input::get('flowFilename') . '.part' . Input::get('flowChunkNumber');

            // create the temporary directory
            if (!is_dir($temp_dir)) {
                mkdir($temp_dir, 0777, true);
            }
            // move the temporary file
            if ($file->move($temp_dir, $dest_file)) {
                echo json_encode($this->createFileFromChunks(
                    Input::get('flowFilename'),
                    Input::get('flowChunkSize'),
                    Input::get('flowTotalSize'),
                    $temp_dir,
                    $content
                ));
            }
        }
    }

    /**
     *
     * Check if all the parts exist, and 
     * gather all the parts of the file together
     * @param string $dir - the temporary directory holding all the parts of the file
     * @param string $fileName - the original file name
     * @param string $chunkSize - each chunk size (in bytes)
     * @param string $totalSize - original file size (in bytes)
     */
    private function createFileFromChunks($fileName, $chunkSize, $totalSize, $temp_dir, $content)
    {
        // count all the parts of this file
        $total_files = 0;
        foreach(scandir($temp_dir) as $file) {
            if (stripos($file, $fileName) !== false) {
                $total_files++;
            }
        }

        // check that all the parts are present
        // the size of the last part is between chunkSize and 2*$chunkSize
        if ($total_files * $chunkSize >=  ($totalSize - $chunkSize + 1)) {
            $base_dir = base_path() . '/upload/videos/';
            // create the final destination file 
            if (($fp = fopen($base_dir . $fileName, 'w')) !== false) {
                for ($i=1; $i<=$total_files; $i++) {
                    fwrite($fp, file_get_contents($temp_dir . '/' . $fileName . '.part' . $i));
                }
                fclose($fp);

                $extension = explode('.', $fileName);
                $extension = end($extension);
                $new_name = Category::slug($fileName) . '-' . substr(md5(microtime()), 10) . '.' . $extension;
                rename($base_dir . $fileName, $base_dir . $new_name);

                // Insert a record in the database
                $video = new Video();
                $video->content_id = $content;
                $video->user_id = Auth::user()->id_member;
                $video->name = URL::to('/') . '/upload/videos/' . $new_name;
                $video->save();
            } else {
                return false;
            }

            // rename the temporary directory (to avoid access from other 
            // concurrent chunks uploads) and than delete it
            if (rename($temp_dir, $temp_dir.'_UNUSED')) {
                $this->rrmdir($temp_dir.'_UNUSED');
            } else {
                $this->rrmdir($temp_dir);
            }
            return $new_name;
        }
    }

    /**
     * 
     * Delete a directory RECURSIVELY
     * @param string $dir - directory path
     * @link http://php.net/manual/en/function.rmdir.php
     */
    private function rrmdir($dir)
    {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (filetype($dir . "/" . $object) == "dir") {
                        $this->rrmdir($dir . "/" . $object); 
                    } else {
                        unlink($dir . "/" . $object);
                    }
                }
            }
            reset($objects);
            rmdir($dir);
        }
    }

}
