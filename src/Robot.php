<?php

namespace Andrewhlleung\Laraveltools;

class Robot {

    public static function getMp3Info($mp3file){
$run= <<<EOF
    sox $mp3file -n stat  2>&1 1> /dev/null
EOF;
        $result = shell_exec($run);
        return $result;
    }

    public static function combinMp4NMp3($mp4file,$mp3file,$finalmp4file){
echo $run= <<<EOF
ffmpeg -y -i '$mp4file' -i '$mp3file' -filter_complex "[1:0]apad" -shortest  '$finalmp4file'
EOF;
        $result = shell_exec($run);
        shell_exec("rm $mp4file");

        return $result;
    }

    public static function combineMp4NPng($mp4path,$pngpath,$seconds,$finalmp4file){
echo $run= <<<EOF
ffmpeg -y -ss 0 -t $seconds -i '$mp4path' -i '$pngpath' -filter_complex "[0:v][1:v] overlay=0:0" -pix_fmt yuv420p -c:a copy '$finalmp4file'
EOF;
        $result = shell_exec($run);
        return $result;
    }

    public static function getMp3Duration($audiofile){
$run= <<<EOF
ffmpeg -i '$audiofile' 2>&1 | grep "Duration"| cut -d ' ' -f 4 | sed s/,// | sed 's@\..*@@g' | awk '{ split($1, A, ":"); split(A[3], B, "."); print 3600*A[1] + 60*A[2] + B[1] }'
EOF;
        $result = shell_exec($run);

        $result = trim(preg_replace('/\s\s+/', ' ', $result));

        return $result;
    }

    public static function moveFile($from,$to){
        $result = shell_exec("mv '$from' '$to'");
        return $result;
//        \Illuminate\Support\Facades\Storage::move($from,$to);
    }

    public static function removeFile($file){
        $result = shell_exec("rm '$file'");
        return $result;
//        \Illuminate\Support\Facades\Storage::move($from,$to);
    }

    public static function getStorageDirFiles($foldername){
        $dir    = storage_path($foldername);
        $files1 = scandir($dir);
        $dirs=array();
        foreach ($files1 as $file) {
            if ($file=='.' || $file=='..') {
                continue;
            }
            if (substr($file,0,1)=='.' ) {
                continue;
            }
            $dirs[] = $file;
        }
        return $dirs;
    }

    public static function getPngKey($png_arr,$in_mp3path){
        foreach($png_arr as $key=>$val){
            if( strpos($in_mp3path,$key) ){
                return $key;
            }
        }
        return null;
    }

    public static function checkNMake($destination_path){
        if (!\File::exists($destination_path)) {
            $old = umask(0);
            \File::makeDirectory($destination_path, 0777, true, true);
            umask($old);
        }
    }
}
