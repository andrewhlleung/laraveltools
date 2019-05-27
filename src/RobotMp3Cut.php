<?php

namespace Andrewhlleung\Laraveltools;

use Illuminate\Console\Command;
//use App\MyPusher;

class RobotMp3Cut extends Command
{
    protected $signature = 'robot:mp3cut';

    protected $description = 'Command description';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {

        $group = $this->ask('Group name of this process?');
        $mp3file = $this->ask('mp3 file name?');
        $foldername = $this->mp3CutBySecond($mp3file);

        echo "BEGIN";
        echo "\n";
        // $dir    = storage_path($foldername);
        $dir    = $foldername;
        $files1 = scandir($dir);
        foreach($files1 as $file){
            if ($file=='.' || $file=='..') {
                continue;
            }
            $fullpath = $dir."/".$file;
            $result = $this->getMp3Info($fullpath);
            $lines = explode("\n",$result);
            $mp3 = new \App\Mp3();
            $mp3->group = $group;
            $mp3->name = $fullpath;
            echo $fullpath;
            echo "\n";
            foreach($lines as $line){
                $parts = explode(':',$line);
                if(count($parts)>1){
                    $value = trim($parts[1]);

                    $parts[0] = str_replace(' ', '_', $parts[0]);
                    $parts[0] = str_replace('(', '', $parts[0]);
                    $parts[0] = str_replace(')', '', $parts[0]);
                    $parts[0] = str_replace('__', '_', $parts[0]);
                    $parts[0] = str_replace('__', '_', $parts[0]);
                    $parts[0] = str_replace('__', '_', $parts[0]);
                    $parts[0] = str_replace('__', '_', $parts[0]);
                    $parts[0] = str_replace('__', '_', $parts[0]);
                    echo $parts[0];
                    echo " => ";
                    echo $value;
                    echo "\n";
                    $mp3->{$parts[0]} = $value;
                }
            }
                $mp3->save();
                echo $mp3->id;
                echo "\n";
            echo "\n";
        }
        echo "END";
        echo "\n";


    }

    public function mp3CutBySecond($mp3file){
        $in_path = storage_path('mp3');
        $destination_path = storage_path('mp3/'.str_replace('.mp3','',$mp3file));
        if (!\File::exists($destination_path)) {
            $old = umask(0);
            \File::makeDirectory($destination_path, 0777, true, true);
            umask($old);
        }
$run= <<<EOF
     ffmpeg -i $in_path/$mp3file -f segment -segment_time 3 -c copy $destination_path/%03d.mp3

    sox $mp3file -n stat  2>&1 1> /dev/null
EOF;
        $result = shell_exec($run);
        // return $result;
        return $destination_path;
    }

    public function getMp3Info($mp3file){
$run= <<<EOF
    sox $mp3file -n stat  2>&1 1> /dev/null
EOF;
        $result = shell_exec($run);
        return $result;
    }

}
