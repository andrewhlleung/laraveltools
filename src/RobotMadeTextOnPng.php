<?php

namespace Andrewhlleung\Laraveltools;


use Illuminate\Console\Command;

class RobotMadeTextOnPng extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'robot:mtop {in_line1=-} {in_line2=-} {in_pngpath=-}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'made text on png';
    protected $default_titles = ['順天知命','蔣權天下'];

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function countChar($chars){
        $str_count=0;
        $nums = ['0','1','2','3','4','5','6','7','8','9'];
        while (mb_strlen($chars)>0) {
            $singlechar = mb_substr($chars, 0, 1);
            if (in_array($singlechar, $nums)) {
                // echo "T => ";
                $str_count = $str_count+1;
            } else {
                // echo "F => ";
                $str_count = $str_count+2;
            }
            // echo $str_count.' => '.$singlechar;
            // echo "\n";
            $chars = mb_substr($chars, 1, mb_strlen($chars));
        }
        $result = ($str_count/2);
        return ceil($result);
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Robot::checkNMake(storage_path('combinemedia'));
        Robot::checkNMake(storage_path('combinemedia/pending'));
        Robot::checkNMake(storage_path('combinemedia/inprog'));
        Robot::checkNMake(storage_path('combinemedia/done'));
        Robot::checkNMake(storage_path('combinemedia/png'));
        Robot::checkNMake(storage_path('combinemedia/png_sample'));
        Robot::checkNMake(storage_path('combinemedia/sample'));
        Robot::checkNMake(storage_path('combinemedia/final'));

        $in_line1 = $this->argument('in_line1');
        $in_line2 = $this->argument('in_line2');
        $in_pngpath = $this->argument('in_pngpath');



        /// get pending file
        $defaultIndex=0;
        $mp3_arr = Robot::getStorageDirFiles('combinemedia/pending');
        $line1='';
        $line2='';
        if (count($mp3_arr)>0) {
            $in_mp3path = $mp3_arr[$defaultIndex];
            $this->info($in_mp3path);
            $mp3path_arr = explode(' ',$in_mp3path);
            $line2flag=false;
            $titles = $this->default_titles;
            $thistitle = '';
            foreach( $mp3path_arr as $mp3path){
                foreach($titles as $title){
                    // echo $mp3path.", ".$title;
                    // echo "\n";
                    if (mb_strpos($mp3path, $title)!=false) {
                        $thistitle = $title;
                        // echo $thistitle." <= ".$title;
                        // echo "\n";
                    }
                }

                if( mb_strpos($mp3path,'主持')!=false ){
                    $line2 = mb_substr($mp3path,mb_strpos($mp3path,'主持'));
                    $line2flag = true;
                    continue;
                }
                if ( $line2flag ) {
                    $line1 = str_replace('.mp3','',$mp3path);
                    break;
                }
            }
        }


        if ($thistitle=='') {
            $thistitle = $this->choice('Please input Episode Name.', $this->default_titles);
        }

        // $default_max_len=13;
        // if( mb_strlen($line1)>$default_max_len ){
        //     $line1 = mb_substr($line1,0,$default_max_len).'...';
        // }

$linearr = explode(' ',$mp3_arr[0]);
        /// get 2 lines string
        if ($in_line1=='-') {
            // $in_line1 = $this->ask('Please input first line text. (total 2 lines, sample "鏗鏘集：文革五十年" )',$line1);
            $in_line1 = $this->choice('Please input first line text. (total 2 lines, sample "鏗鏘集：文革五十年" )', $linearr);
        }
        if ($in_line2=='-') {
            // $in_line2 = $this->ask('Please input second line text. (total 2 lines, sample "主持：李再唐、蔡浩樑" )',$line2);
            $in_line2 = $this->choice('Please input second line text. (total 2 lines, sample "主持：李再唐、蔡浩樑" )', $linearr);
        }

        $str = $in_line1."\n".$in_line2;
        

        /// get Overlay Image
        $defaultIndex = 0;
        $png_arr=array();
        $pngtmp_arr = Robot::getStorageDirFiles('combinemedia/png_sample');
        foreach ($pngtmp_arr as $pngtmp) {
            $pngkey = str_replace('.png', '', $pngtmp);
            $png_arr[$pngkey]=$pngtmp;
            if (mb_strpos($pngkey, $thistitle)!=false) {
                $defaultIndex = $pngkey;
            }
        }

            $this->info('this title: '.$thistitle);

        if( count($png_arr)>0 ){
            // $array = $png_arr;
            // reset($array);
            // $png_input = key($array);
            // $defaultIndex = $png_input;
            // foreach ($pngtmp_arr as $pngtmp) {
            //     $pngkey = str_replace('.png', '', $pngtmp);
            //     if(){

            //     }
            // }
        }else{
            $this->info('Please provide Png Sample files(in storage combinemedia/png_sample folder).');
            return;
        }

        if ($in_pngpath=='-') {
            $in_pngpath = $this->choice('Png Sample File Name(in storage folder)?', $png_arr, $defaultIndex);
            $in_pngpath = $png_arr[$in_pngpath];
        } else {
            if (!isset($png_arr[$in_pngpath])) {
                $this->error('Png File not exists!');
                return;
            }
            $in_pngpath = $png_arr[$in_pngpath];
        }
        $png_path = storage_path('combinemedia/png_sample'.'/'.$in_pngpath);

        $output = $in_line1." ".$in_line2.date("_YmdHis").'.png';
        $out_png_path = storage_path('combinemedia/png/'.$output);


        $this->info('********************************************');
        $this->info('  in_line1: '.$in_line1);
        $this->info('  in_line2: '.$in_line2);
        $this->info(' Png Overlay: '.$in_pngpath);
        $this->info('  Output Mp4: '.$output);
        $this->info('********************************************');
        $this->info('');
        if ( !$this->confirm('Do you wish to continue?') ) {
            return;
        }

        $ttf_font_path = storage_path('ttf/CYanHei-TCHK-Bold-all.ttf');
        $maxlinelen=14;
        $this->do1280x720v1($in_line1,$in_line2, $png_path, $out_png_path,$maxlinelen);
    }

    public function do1280x720v1($line1,$line2,$in_png_path,$out_png_path,$maxlinelen=14)
    {
        
        $ttf_font_path = storage_path('ttf/CYanHei-TCHK-Bold-all.ttf');

        $str_arr = explode("\n",$line1);
        foreach($str_arr as &$str_item){
            if(mb_strlen($str_item)>$maxlinelen){
                $str_item = mb_substr($str_item,0,$maxlinelen);
                $str_item = $str_item."...";
            }
        }
        $line1 = implode("\n",$str_arr);

        $str_arr = explode("\n", $line2);
        foreach ($str_arr as &$str_item) {
            if (mb_strlen($str_item)>$maxlinelen) {
                $str_item = mb_substr($str_item, 0, $maxlinelen);
                $str_item = $str_item."...";
            }
        }
        $line2 = implode("\n", $str_arr);
        $this->info($line1);
        $this->info($line2);

        $originalImage = $in_png_path;

        if (file_exists($originalImage)) {
            $im = imagecreatefrompng($originalImage);
            imagesavealpha($im, true);
            if (!$im) {
                echo "im is null";
                return;
            }
            $black = imagecolorallocate($im, 0, 0, 0);            
            $white = imagecolorallocate($im, 255, 255, 255);
            $yellow = imagecolorallocate($im, 244, 235, 66);
            if ( isset($line1) && isset($line1) ) {
                // imagettftext($im, 60, 0, 20, 550, $white, $ttf_font_path, $str);
                imagettftext($im, 60, 0, 20, 490, $yellow, $ttf_font_path, $line1);
                imagettftext($im, 60, 0, 20, 590, $white, $ttf_font_path, $line2);
            }
            imagepng($im, $out_png_path, 0);
            imagedestroy($im);
        }

    }

    // public function do1280x720($str,$font,$maxlinelen,$in_png_path,$out_png_path)
    // {
    //     $str_arr = explode("\n",$str);
    //     foreach($str_arr as &$str_item){
    //         if(mb_strlen($str_item)>$maxlinelen){
    //             $str_item = mb_substr($str_item,0,$maxlinelen);
    //             $str_item = $str_item."...";
    //         }
    //     }
    //     $str = implode("\n",$str_arr);
    //     $originalImage = $in_png_path;

    //     if (file_exists($originalImage)) {
    //         $im = imagecreatefrompng($originalImage);
    //         imagesavealpha($im, true);
    //         if (!$im) {
    //             die("im is null");
    //         }
    //         $black = imagecolorallocate($im, 0, 0, 0);            
    //         $white = imagecolorallocate($im, 255, 255, 255);
    //         $black = imagecolorallocate($im, 0, 0, 0);
    //         if (isset($str)) {
    //             imagettftext($im, 60, 0, 20, 550, $white, $font, $str);
    //         }
    //         imagepng($im, $out_png_path, 0);
    //         imagedestroy($im);
    //     }

    // }
}
