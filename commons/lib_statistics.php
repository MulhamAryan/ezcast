<?php
/*
* EZCAST EZadmin
* Copyright (C) 2016 Université libre de Bruxelles
*
* Written by Michel Jansens <mjansens@ulb.ac.be>
* 		    Arnaud Wijns <awijns@ulb.ac.be>
*                   Antoine Dewilde
*                   Thibaut Roskam
* This library is written by Mulham Aryan <Mulham.aryan@ulb.be>
*
* This software is free software; you can redistribute it and/or
* modify it under the terms of the GNU Lesser General Public
* License as published by the Free Software Foundation; either
* version 3 of the License, or (at your option) any later version.
*
* This software is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
* Lesser General Public License for more details.
*
* You should have received a copy of the GNU Lesser General Public
* License along with this software; if not, write to the Free Software
* Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

Class GenerateStatistics{

    public $startdate;
    public $enddate;
    public $recordStatus = array("processed" => 0, "failed" => 0);
    public $recordType = array("camslide" => 0,"cam" => 0, "slide" => 0, "SUBMIT" => 0);
    public $auditoireRecordNumber = array();
    public $auditoireRecordtimer  = array();
    public $usersList = array();
    public $coursesList = array();
    public $failedListCourses = array();
    public $submitCourseList = array();
    public $auditoireCourseList = array();
    public $userSubmitList = array();
    public $userRecordList = array();

    /*
     * The objectives of the __construct is Calculating the number of recorded session depends of Course type, user of course etc ...
     * @param String date $startdate
     * @param String date $enddate
     * @return array
    */

    function __construct($startdate,$enddate){

        global $repository_basedir;

        $excludedCourses = array("PODC-I");

        $repository_basedir = "/var/lib/ezcast";
        $this->startdate = $startdate;
        $this->enddate = $enddate;
        $this->dir = $repository_basedir . "/repository/";

        $startdate = strtotime($this->startdate);
        $enddate    = strtotime($this->enddate);

        $folders = new DirectoryIterator($this->dir);
        foreach ($folders as $folder) {
            $search  = ["-priv","-pub"];
            $replace = ["",""];
            $courseName = str_replace($search,$replace,$folder->getFilename());
            $matches  = preg_grep ('/^' . $courseName . '/i', $excludedCourses);
            if($folder->getCTime() >= $startdate
                && $folder->getCTime() <= $enddate
                && $folder->isDir()
                && !$folder->isDot()
                && empty($matches))
            {
                $iterator = new DirectoryIterator($this->dir . $folder);
                foreach ($iterator as $fileinfo) {

                    if ($fileinfo->isDir() && !$fileinfo->isDot() ){
                        $getfolderyear = explode("_", $fileinfo->getFilename());
                        $convertold = strtotime($getfolderyear[0] . "-" . $getfolderyear[1] . "-" . $getfolderyear[2]);
                    }

                    if($fileinfo->isDir() && !$fileinfo->isDot() && $convertold >= $startdate){
                        if(file_exists($this->dir . $folder . "/" . $fileinfo->getFilename() . "/_metadata.xml")){
                            $metadata = $this->metadata2assoc_array($this->dir . $folder . "/" . $fileinfo->getFilename() . "/_metadata.xml");
                        }
                        if($metadata["status"] == "failed"){
                            $this->failedListCourses[$folder->getFilename()][] = array("asset" => $fileinfo->getFilename(),"origin" => $metadata["origin"], "user" => $metadata["author"]);
                        }
                        if($metadata["origin"] == "SUBMIT"){
                            $this->userSubmitList[$metadata["author"]]++;
                            $this->submitCourseList[$courseName]++;
                            $this->recordType["SUBMIT"]++;
                        }
                        else{
                            if(!empty($metadata["author"]))
                                $this->userRecordList[$metadata["author"]]++;

                            if(!empty($courseName))
                                $this->auditoireCourseList[$courseName]++;
                        }
                        $this->auditoireRecordNumber[$metadata["origin"]]++;
                        $this->auditoireRecordtimer[$metadata["origin"]] += $metadata["duration"];
                        $this->usersList[$metadata["author"]]++;
                        $this->coursesList[$courseName]++;
                        $this->recordStatus[$metadata["status"]]++;
                        $this->recordType[$metadata["record_type"]]++;
                    }
                }

            }
        }
    }

    function metadata2assoc_array($meta_path)
    {
        if(file_exists($meta_path))
            @ $xml = simplexml_load_file($meta_path);

        else {
            return false;
        }
        $assoc_array = array();
        foreach ($xml as $key => $value) {
            $assoc_array[$key] = (string) $value;
        }
        return $assoc_array;
    }

    /*
     * Convert time from timestamp to (hh:mm:ss)
    */

    function friendlyTime($value){
        $hours = floor($value / 3600);
        $mins = floor($value / 60 % 60);
        $secs = floor($value % 60);

        return $hours . ":" . $mins . ":" . $secs;
    }

    /*
     * This function is used to get the number of recorded and submitted PODCAST
     * Using the stat and the type of the PODCAST possible $type variables are :
     * total : get the sum of processed and failed
     * processed : get of successfully processed PODCAST
     * failed : get the sum of failed PODCAST
     * type of record are : camslide, cam, slide, submit
    */

    function getRecords($type = ""){

        $array = array(
            "total" => $this->recordStatus["processed"] +  $this->recordStatus["failed"],
            "processed" => $this->recordStatus["processed"],
            "failed" => $this->recordStatus["failed"],
            "camslide" => $this->recordType["camslide"],
            "cam" => $this->recordType["cam"],
            "slide" => $this->recordType["slide"],
            "submit" => $this->recordType["SUBMIT"],
        );

        if(array_key_exists($type,$array))
            $value = $array[$type];

        else
            $value = $array["total"];

        return $value;
    }

    /*
     * This function is used to get the number of recorded sessions (PODCAST) in an auditorium or list of all of auditorium
     * $name : possible options are the name of auditorium ex (s-ud2-218) or empty to get the full list
    */

    function getRecordByAuditorium($name = ""){

        if(array_key_exists($name,$this->auditoireRecordNumber))
            $value = $this->auditoireRecordNumber[$name];

        else
            $value = 0;

        return $value;
    }

    /*
     * This function is used to get used time in of recorded sessions (PODCAST) in an auditorium or list of all of auditorium
     * $name : possible options are the name of auditorium ex (s-ud2-218) or empty to get the full list
    */

    function getRecordTimeByAuditoire($name){

        if(array_key_exists($name,$this->auditoireRecordtimer))
            $value = $this->friendlyTime($this->auditoireRecordtimer[$name]);

        else
            $value = 0;

        return $value;
    }

    /*
     * This function is used to get record type by user options are
     * $user : if AllUsers get the list of the full user or Username of a user ex ("Mulham Aryan")
     * $type : auditorium the number of recorded sessions in an auditorium, submit for submited video and sum is auditorium + submit
    */

    function getRecordByUser($user,$type){
        if($user == "AllUsers") {
            if($type == "auditorium"){
                $value = $this->userRecordList;
            }
            elseif($type == "submit") {
                $value = $this->userSubmitList;
            }
            else{
                $value = $this->usersList;
            }
        }
        else{
            if($type == "auditorium"){
                if (array_key_exists($user, $this->userRecordList)) {
                    $value = $this->userRecordList[$user];
                }
                else {
                    $value = 0;
                }
            }
            elseif($type == "submit") {
                if (array_key_exists($user, $this->userSubmitList))
                    $value = $this->userSubmitList[$user];
                else
                    $value = 0;
            }
            else {
                if (array_key_exists($user, $this->usersList)){
                    $array =
                        array(
                            "user"=>$user,
                            array(
                                "auditorium" => $this->getRecordByUser($user,"auditorium"),
                                "submit" => $this->getRecordByUser($user,"submit"),
                                "sum" => $this->usersList[$user]
                            )
                        );
                    $value = $array;
                }

                else
                    $value = 0;
            }
        }
        return $value;
    }

    /*
     * This function is used to get the recorded session by course and type
     * $course : if value is AllCourses list all the courses or name of a course ex ("PSYC-E-2003) with auditorium, submit or sum as type
     * $type : are the type of recorded session auditorium, submit or sum (auditorium + submit)
    */

    function getRecordedCourses($course,$type){
        if($course == "AllCourses"){
            if($type == "auditorium"){
                $value = $this->auditoireCourseList;
            }
            elseif($type == "submit"){
                $value = $this->submitCourseList;
            }
            else{
                $value = $this->coursesList;
            }
        }
        else{
            if($type == "auditorium") {
                if (array_key_exists($course, $this->auditoireCourseList)) {
                    $value = $this->auditoireCourseList[$course];
                }
                else{
                    $value = 0;
                }
            }
            elseif($type == "submit"){
                if (array_key_exists($course, $this->submitCourseList)) {
                    $value = $this->submitCourseList[$course];
                }
                else{
                    $value = 0;
                }
            }
            else{
                if (array_key_exists($course, $this->coursesList)) {
                    $value = $this->coursesList[$course];
                }
                else{
                    $value = 0;
                }
            }
        }
        return $value;
    }

    /*
     * This function is used to get the list of failed processed record
     * Note : this array is a multi dimensional array ("PSYC-D-402-priv"=> "asset":"2019_09_25_14h10","origin","SUBMIT","user":"Michel Sylin")
     * Get the album the asset and the username of the failed PODCAST
    */

    function getFailedSubmit(){
        return $this->failedListCourses;
    }

}
// $stat = new GeneratStatistics("2018-09-25","2020-07-02"); // To start Generating statistics put the between date start and end date.
// $stat->getRecords("processed"); // Get Statistics by Type or Status ("total", "processed", "failed", "camslide", "cam", "slide", "submit")
// $stat->getRecordByAuditorium("s-r42-5-110"); // Get Number of Record  in one Auditorium
// $stat->getRecordTimeByAuditoire(); // Get Record by Time in Auditorium
// $stat->getRecordByUser("AllUsers","sum") // Get Record by User name (["AllUsers"=>"auditorium","submit","sum"],["AllUsers'ex':Mulham Aryan"=>"auditorium","submit","sum"])
// $stat->getRecordedCourses("AllCourses","sum")); // Get Number of record by Course options : (["AllCourses"=>"auditorium","submit","sum"],["course_name'ex':PSYC-E-2005"=>"auditorium","submit","sum"])
// $stat->getFailedSubmit()); // List all the failed recorded sessions and get album, asset, origin and username (as a multi dimensional array)
?>