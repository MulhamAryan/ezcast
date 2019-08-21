<?php
// test link http://localhost/sshmng/?ip=164.15.43.32&album=2019_03_22_14h52_PODC-I-000
//grep -oE 'repository_path=.*";' common.inc | tail -1 | sed 's/repository_path="//g;s/";//g;'
class ssh_connect
{
    public $ip_master;

    public $ip_slave;

    public $album;

    function __construct($ip_master,$client, $album = null, $ip_slave = null)
    {
        $this->ssh = "ssh -o ConnectTimeout=10 -o BatchMode=yes  $client@$ip_master ";
        $this->problem_list = array();
        $this->movies = '/Users/podclient/Movies/';
        $this->ezrecorder = '/Library/ezrecorder/';
        $this->client = $client;
        $this->ip_master = $ip_master;
        $this->album = $album;
        $this->ip_slave = $ip_slave;
    }
    function command($cmd, $mode)
    {
        $cmd = $this->ssh . $cmd;
        if ($mode == 1)
        {
            //exec($cmd, $output, $return_var);
            return $cmd;
        }
        elseif ($mode == 2)
        {
            return shell_exec($cmd);
        }
    }

    function get_album_directory()
    {
        global $album;
        return trim($this->command('"find \'' . $this->movies . '\' -type d -name \'' . $album . '\' -exec dirname {} \;"', 2));
    }

    function check_file_exists($file)
    {
    }

    function check_cut_list($file, $type)
    {
        $open_cut_list = $this->command("'cat $file'", 2);
        /*
        if($result == 1)
        return 1;
        elseif($result == 2)
        return 2;
        else
        return $type.'_cut_list_error_content';
        */
    }

    function check_ffmpeg_folder($file, $type)
    {
        $output2 = $this->command("'ls $file | grep \"_cut_list.txt\|ffmpegmovie_\"'", 1);
        $txt.= "<ul>";
        $i = 0;
        foreach($output2 as $ffmpeg_folder)
        {
            $count_ts = $this->command("'ls $file/$ffmpeg_folder/ | wc -l'", 2);
            if ($count_ts >= 6000) $too_many_files = '<font color="red">File count ' . $count_ts . ' </font>';
            if ($i == 0 && in_array('_cut_list.txt', $output2) == false)
            {
                $txt.= "<li>_cut_list.txt <font color=\"red\">file does not exist</font></li>";
                $this->problem_list[] = $type . '_cut_list';
            }
            elseif ($i == 0 && in_array('_cut_list.txt', $output2) == true)
            {
                $this->problem_list[] = $this->check_cut_list("$file/$ffmpeg_folder", $type);
            }

            $txt.= "<li>$ffmpeg_folder $too_many_files</li>";
            $i++;
        }

        $txt.= "</ul>";
        return $txt;
    }

    function open_album()
    {
        global $album;
        global $ip_master;
        $base_file = array(
            "_metadata.xml",
            "metadata.xml",
            "slide.mov",
            "cam.mov",
            "ffmpeg",
            "remoteffmpeg"
        );
        $base_ffmpeg = array(
            "_cut_list.txt"
        );
        $dir = $this->get_album_directory();

        if (empty($dir)) $txt.= "Album doesn't exist !";
        else
        {
            $repository = $dir . '/' . $album;
            $txt.= "This album <b>$album</b> exist in <b>$dir</b><br /><br />";
            $output = $this->command("'ls $dir/$album'", 1);
            $txt.= "<ul>";
            foreach($base_file as $file)
            {
                if (!in_array($file, $output))
                {
                    $exist = '<font color="red"> file does not exist </font>';
                    $this->problem_list[] = $file;
                }
                else $exist = '<font color="green"> OK </font>';
                $txt.= "<li>" . $file . " " . $exist . "</li>";
                if ($file == 'ffmpeg' && in_array('ffmpeg', $output) || $file == 'remoteffmpeg' && in_array('remoteffmpeg', $output))
                {
                    $txt.= $this->check_ffmpeg_folder("$repository/$file", $file);
                }
            }

            $txt.= "</ul>";
        }

        $txt.= var_dump($this->problem_list);
        $txt.= $this->repair_record($this->problem_list, $repository);
        return $txt;
    }

    function repair_record($problem_list, $repository)
    {
        $init_timestamp = 'init_timestamp';
        $int_date       = 'int_date';
        $stop_timestamp = 'stop_timestamp';
        $stop_date      = 'stop_date';

        // return var_dump($problem_list);
        // [0]=> "metadata.xml" [1]=> "slide.mov" [2]=> "cam.mov" [3]=> "ffmpeg_cut_list_error_content" [4]=> "remoteffmpeg_cut_list"

        if (in_array('metadata.xml', $problem_list))
        {
            $txt.= 'metadata created<br />';
            $cmd_groups.= " cp $repository/_metadata.xml $repository/metadata.xml";
        }

        if (in_array('ffmpeg_cut_list', $problem_list))
        {
            $cut_list_content.= "init:$init_timestamp:$int_date\n";
            $cut_list_content.= "play:$init_timestamp:$int_date\n";
            $cut_list_content.= "stop:$stop_timestamp:$stop_date\n";
            $cmd_groups.= " | echo \"$cut_list_content\" > $repository/ffmpeg/_cut_list.txt";
            $txt.= $repository . '/ffmpeg/_cut_list.txt created<br />';
        }

        if (in_array('remoteffmpeg_cut_list', $problem_list))
        {
            $cmd_groups.= " | echo \"$cut_list_content\" > $repository/remoteffmpeg/_cut_list.txt";
            $txt.= $repository . '/remoteffmpeg/_cut_list.txt created<br />';
        }
        $cmd_groups = "' $cmd_groups '";
        $this->command($cmd_groups,1);
        return $txt;
    }
}

?>
