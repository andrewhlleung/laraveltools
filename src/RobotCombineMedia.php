<?php

namespace Andrewhlleung\Laraveltools;

use Illuminate\Console\Command;
//use App\MyPusher;

class RobotCombineMedia extends Command
{
    protected $signature = 'robot:cm {in_mp3path=-} {in_mp4path=-} {in_pngkey=-} {in_outputpath=-} {dolast=-}';

    protected $description = 'combine media';

    public function __construct()
    {
        parent::__construct();
    }

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

        $dolast = $this->argument('dolast');
        if($dolast==1){
            $this->dolast();
        }else{
            $this->do();
        }
    }
    
    public function dolast()
    {
        // $this->info('dolast()');
        date_default_timezone_set('Asia/Hong_Kong');

        $in_mp3path = $this->argument('in_mp3path');
        $in_mp4path = $this->argument('in_mp4path');
        $in_pngkey = $this->argument('in_pngkey');
        $in_outputpath = $this->argument('in_outputpath');

        $this->info('');

        $this->info('Combine Audio(mp3), Video(mp4), Overlay Image(png) to one Video(mp4).');

        $this->info('Begin '.date('Y-m-d H:i:s'));
        $this->info('');


        /// get pending file
        $defaultIndex=0;
        $mp3_arr = Robot::getStorageDirFiles('combinemedia/pending');
        if( count($mp3_arr)==0 ){
            $this->info('No Pending File. ');
            $this->info('');
            $this->info('End '.date('Y-m-d H:i:s'));
            return;
        }

        $in_mp3path = $mp3_arr[$defaultIndex];
        $mp3path = storage_path('combinemedia/pending'.'/'.$in_mp3path);
        $inprog_mp3path = storage_path('combinemedia/inprog'.'/'.$in_mp3path);
        $done_mp3path = storage_path('combinemedia/done'.'/'.$in_mp3path);
        
        $this->info('mp3path: '.$mp3path);
        if( !file_exists($mp3path) ){
            $this->error('Mp3 File not exists!');
            return;
        }

        /// get Video Sample File
        $defaultIndex=0;
        $mp4_arr = Robot::getStorageDirFiles('combinemedia/sample');
        $in_mp4path = $mp4_arr[$defaultIndex];
        $mp4path = storage_path('combinemedia/sample'.'/'.$in_mp4path);
        if( !file_exists($mp4path) ){
            $this->error('Mp4 Sample File not exists!');
            return;
        }

        /// get Overlay Image
        $png_arr=array();
        $pngtmp_arr = Robot::getStorageDirFiles('combinemedia/png');
        foreach($pngtmp_arr as $pngtmp){
            $pngkey = str_replace('.png','',$pngtmp);
            $png_arr[$pngkey]=$pngtmp;
        }
        $array = $png_arr;
        reset($array);
        $png_input = key($array);
        $defaultIndex = $png_input;
        if($in_pngkey=='-'){
            $defaultIndex_guess = Robot::getPngKey($png_arr,$in_mp3path);
            if( isset($defaultIndex_guess) ){
                $defaultIndex = $defaultIndex_guess;
                echo "defaultIndex_guess: ";
                echo $defaultIndex_guess;
                echo "\n";
                echo "defaultIndex: ";
                echo $defaultIndex;
                echo "\n";
            }
            $in_pngkey = $defaultIndex;
            $in_pngpath = $png_arr[$in_pngkey];
        }else{
            if ( !isset($png_arr[$in_pngkey]) ) {
                $this->error('Png File not exists!');
                return;
            }
            $in_pngpath = $png_arr[$in_pngkey];
        }
        $pngpath = storage_path('combinemedia/png'.'/'.$in_pngpath);

        // $defaultmp4 = str_replace('.png','',$in_pngpath).'_'.date('Ymd_His').'.mp4';
        $defaultmp4 = str_replace('.mp3','',$in_mp3path).'_'.date('Ymd_His').'.mp4';
        $defaultmp4 = str_replace(' ','_',$defaultmp4);
        $in_outputpath = $defaultmp4;
        $outputpath = storage_path($in_outputpath);
        $finalpath = storage_path('combinemedia/final'.'/'.$in_outputpath);
        if (file_exists($outputpath)) {
            \Illuminate\Support\Facades\Storage::delete(storage_path($in_outputpath));
        }

        $tmpoutputpath = $outputpath.'.tmp.mp4';

        $this->info('********************************************');
        $this->info('         Mp3: '.$in_mp3path);
        $this->info('  Mp4 Sample: '.$in_mp4path);
        $this->info(' Png Overlay: '.$in_pngpath);
        $this->info('  Output Mp4: '.$in_outputpath);
        $this->info('********************************************');
        $this->info('');

        $start = '';
            $start = ''.date('Y-m-d H:i:s');
            $mp3_duration = Robot::getMp3Duration($mp3path);
            Robot::moveFile($mp3path,$inprog_mp3path);
            Robot::combineMp4NPng($mp4path, $pngpath, $mp3_duration, $tmpoutputpath);
            Robot::combinMp4NMp3($tmpoutputpath, $inprog_mp3path, $outputpath);
            Robot::moveFile($inprog_mp3path,$done_mp3path);
            Robot::moveFile($outputpath, $finalpath);
        $this->info($start.' - '.date('Y-m-d H:i:s'));
        $this->info('End '.date('Y-m-d H:i:s'));

            Robot::removeFile($pngpath);

    }

    public function do()
    { 
        // $this->info('do()');
        date_default_timezone_set('Asia/Hong_Kong');

        $in_mp3path = $this->argument('in_mp3path');
        $in_mp4path = $this->argument('in_mp4path');
        $in_pngkey = $this->argument('in_pngkey');
        $in_outputpath = $this->argument('in_outputpath');

        $this->info('');

        $this->info('Combine Audio(mp3), Video(mp4), Overlay Image(png) to one Video(mp4).');

        $this->info('Begin '.date('Y-m-d H:i:s'));
        $this->info('');


        /// get pending file
        $defaultIndex=0;
        $mp3_arr = Robot::getStorageDirFiles('combinemedia/pending');
        if( count($mp3_arr)==0 ){
            $this->info('No Pending File. ');
            $this->info('');
            $this->info('End '.date('Y-m-d H:i:s'));
            return;
        }

        if($in_mp3path=='-'){
            $in_mp3path = $this->choice('Mp3 File Name(in storage folder)?', $mp3_arr, $defaultIndex);
        }
        $mp3path = storage_path('combinemedia/pending'.'/'.$in_mp3path);
        $inprog_mp3path = storage_path('combinemedia/inprog'.'/'.$in_mp3path);
        $done_mp3path = storage_path('combinemedia/done'.'/'.$in_mp3path);
        if( !file_exists($mp3path) ){
            $this->error('Mp3 File not exists!');
            return;
        }

        /// get Video Sample File
        $defaultIndex=0;
        $mp4_arr = Robot::getStorageDirFiles('combinemedia/sample');        
        if ($in_mp4path=='-') {
            $in_mp4path = $this->choice('Mp4 Sample File Name(in storage folder)?', $mp4_arr, $defaultIndex);
        }
        $mp4path = storage_path('combinemedia/sample'.'/'.$in_mp4path);
        if( !file_exists($mp4path) ){
            $this->error('Mp4 Sample File not exists!');
            return;
        }

        /// get Overlay Image
        $png_arr=array();
        $pngtmp_arr = Robot::getStorageDirFiles('combinemedia/png');
        foreach($pngtmp_arr as $pngtmp){
            $pngkey = str_replace('.png','',$pngtmp);
            $png_arr[$pngkey]=$pngtmp;
        }
        $array = $png_arr;
        reset($array);
        $png_input = key($array);
        $defaultIndex = $png_input;
        if($in_pngkey=='-'){
            $defaultIndex_guess = Robot::getPngKey($png_arr,$in_mp3path);
            if( isset($defaultIndex_guess) ){
                $defaultIndex = $defaultIndex_guess;
                echo "defaultIndex_guess: ";
                echo $defaultIndex_guess;
                echo "\n";
                echo "defaultIndex: ";
                echo $defaultIndex;
                echo "\n";
            }
            $in_pngkey = $this->choice('Png File Name(in storage folder)?', $png_arr, $defaultIndex);
            $in_pngpath = $png_arr[$in_pngkey];
        }else{
            if ( !isset($png_arr[$in_pngkey]) ) {
                $this->error('Png File not exists!');
                return;
            }
            $in_pngpath = $png_arr[$in_pngkey];
        }
        $pngpath = storage_path('combinemedia/png'.'/'.$in_pngpath);

        // $defaultmp4 = str_replace('.png','',$in_pngpath).'_'.date('Ymd_His').'.mp4';
        $defaultmp4 = str_replace('.mp3','',$in_mp3path).'_'.date('Ymd_His').'.mp4';
        $defaultmp4 = str_replace(' ','_',$defaultmp4);
        if ($in_outputpath=='-') {
            $in_outputpath = $this->ask('Output Mp4 File Name(in storage folder)?', $defaultmp4);
        }
        $outputpath = storage_path($in_outputpath);
        $finalpath = storage_path('combinemedia/final'.'/'.$in_outputpath);

        if (file_exists($outputpath)) {
            if ($this->confirm('Output File Exists, Remove It?')) {
                \Illuminate\Support\Facades\Storage::delete(storage_path($in_outputpath));
            }
        }

        $tmpoutputpath = $outputpath.'.tmp.mp4';

        $this->info('********************************************');
        $this->info('         Mp3: '.$in_mp3path);
        $this->info('  Mp4 Sample: '.$in_mp4path);
        $this->info(' Png Overlay: '.$in_pngpath);
        $this->info('  Output Mp4: '.$in_outputpath);
        $this->info('********************************************');
        $this->info('');

$in_outputpath2 = $in_outputpath;
mb_regex_encoding('utf-8');
$char = "。、！？：；﹑•＂…‘’“”〝〞∕¦‖—　〈〉﹞﹝「」‹›〖〗】【»«』『〕〔》《﹐¸﹕︰﹔！¡？¿﹖﹌﹏﹋＇´ˊˋ―﹫︳︴¯＿￣﹢﹦﹤‐­˜﹟﹩﹠﹪﹡﹨﹍﹉﹎﹊ˇ︵︶︷︸︹︿﹀︺︽︾ˉ﹁﹂﹃﹄︻︼（）";
$in_outputpath2 = mb_ereg_replace("[".$char."]", "_", $in_outputpath2, "UTF-8");
$in_outputpath2 = preg_replace("/[[:punct:]]/i", "_", $in_outputpath2);
$in_outputpath2 = preg_replace("/\s+/", "_", $in_outputpath2);


        $this->info('********************************************');
        $this->info(''.$in_outputpath2);
        $this->info('********************************************');
        $this->info('');
        

        $start = '';
        if ($this->confirm('Do you wish to continue?')) {
            $start = ''.date('Y-m-d H:i:s');
            $mp3_duration = Robot::getMp3Duration($mp3path);
            Robot::moveFile($mp3path,$inprog_mp3path);
            Robot::combineMp4NPng($mp4path, $pngpath, $mp3_duration, $tmpoutputpath);
            Robot::combinMp4NMp3($tmpoutputpath, $inprog_mp3path, $outputpath);
            Robot::moveFile($inprog_mp3path,$done_mp3path);
            Robot::moveFile($outputpath, $finalpath);
        }
        $this->info($start.' - '.date('Y-m-d H:i:s'));
        $this->info('End '.date('Y-m-d H:i:s'));

        if ($this->confirm('Delete Png Overlay?')) {
            Robot::removeFile($pngpath);
        }
    }
    

}
