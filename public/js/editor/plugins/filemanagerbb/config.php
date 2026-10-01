<?php
if($_SESSION["verify"] != "FileManager4TinyMCE") die('forbidden');

//Specifies where the root of your webpage sits on disk. Usually it is best to
//leave it as DOCUMENT_ROOT
$root = rtrim($_SERVER['DOCUMENT_ROOT'],'/');

//**********************
//Path configuration
//**********************
// The default configuration uses the following setup
// | - root
// | | - uploads <- Directory where files will be uploaded
// | | - thumbs  <- Directory containing auto-generated thumbnails
// | | - tinymce
// | | | - plugins
// | | | | - filemanager

$base_url=""; //url base of site if you want only relative url leave empty
$upload_dir = 'uploads/files'; // path from the base_url to the uploads folder
$thumbs_dir = 'uploads/thumbs'; // path from the base_url to thumbs folder

$MaxSizeUpload=100; //Mb

//**********************
//Image config
//**********************
//set max width pixel or the max height pixel for all images
//If you set dimension limit, automatically the images that exceed this limit are convert to limit, instead
//if the images are lower the dimension is maintained
//if you don't have limit set both to 0
$image_max_width=0;
$image_max_height=0;

//Automatic resizing //
//If you set true $image_resizing the script convert all images uploaded to image_width x image_height resolution
//If you set width or height to 0 the script calcolate automatically the other size
$image_resizing=false;
$image_width=600;
$image_height=0;

//Thumbnail Size//
$thumbnail_width=122;
$thumbnail_height=91;

//******************
//Permissions config
//******************
$delete_file=true;
$create_folder=true;
$delete_folder=true;
$upload_files=true;


//**********************
//Allowed extensions
//**********************
$ext_img = array('jpg', 'jpeg', 'png', 'gif', 'bmp', 'tiff'); //Images
$ext_file = array('doc', 'docx', 'pdf', 'xls', 'xlsx', 'txt', 'csv','html','psd','sql','log','fla','xml','ade','adp','ppt','pptx'); //Files
$ext_video = array('mov', 'mpeg', 'mp4', 'avi', 'mpg','wma'); //Videos
$ext_music = array('mp3', 'm4a', 'ac3', 'aiff', 'mid'); //Music
$ext_misc = array('zip', 'rar','gzip'); //Archives


$ext=array_merge($ext_img, $ext_file, $ext_misc, $ext_video,$ext_music); //allowed extensions

?>
