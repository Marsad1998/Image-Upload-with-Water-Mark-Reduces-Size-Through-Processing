<?php 
if(!empty($_FILES)){
// Path configuration 
$targetDir = "../img/vehicles_images/"; 
$watermarkImagePath = '../img/uploads/logo.png'; 
$statusMsg = ''; 
// foreach ($_FILES["file"]["name"] as $index => $value) { 	
    if(!empty($_FILES["file"]["name"])){ 
        // File upload path 
		$vehicle_id = $_POST['vehicle_id'];
        
        $ext = explode(".", $_FILES["file"]["name"]);
		$extension = end($ext);
		$fileName = uniqid(rand()).".".$extension;

        $q = mysqli_query($dbc,"INSERT INTO vehicle_images (vehicle_image_name, vehicle_id) VALUES ('$fileName', '$vehicle_id')");
        $targetFilePath = $targetDir.$fileName; 
        $fileType = pathinfo($targetFilePath,PATHINFO_EXTENSION); 
    	$file_type = $_FILES["file"]["type"];
	    $file_size = $_FILES["file"]["size"];
    	$error = $_FILES["file"]["error"];
        // Allow certain file formats 
        $allowTypes = array('jpg','png','jpeg', 'JPG', 'PNG', 'JPEG'); 
        if(in_array($extension, $allowTypes)){ 
            // Upload file to the server 
            if(move_uploaded_file($_FILES["file"]["tmp_name"], $targetFilePath)){ 
                // Load the stamp and the photo to apply the watermark to 
                $watermarkImg = imagecreatefrompng($watermarkImagePath); 
                switch($fileType){ 
                    case 'jpg': 
                        $im = imagecreatefromjpeg($targetFilePath); 
                        break; 
                    case 'jpeg': 
                        $im = imagecreatefromjpeg($targetFilePath); 
                        break; 
                    case 'png': 
                        $im = imagecreatefrompng($targetFilePath); 
                        break; 
                    case 'JPG': 
                        $im = imagecreatefromjpeg($targetFilePath); 
                        break; 
                    case 'JPEG': 
                        $im = imagecreatefromjpeg($targetFilePath); 
                        break; 
                    case 'PNG': 
                        $im = imagecreatefrompng($targetFilePath); 
                        break; 
                    default: 
                        $im = imagecreatefromjpeg($targetFilePath); 
                } 
				  
                $width = 	1200;	//imagesx($im); 
				$height = 	600;	//imagesy($im); 
				$spacing = 15;
		        $spacing_double = $spacing  * 2;
				
				// Get image dimensions 
				list($width_orig, $height_orig) = getimagesize($targetFilePath); 
				
				// Resample the image 
				$im2 = imagecreatetruecolor($width, $height); 
				if (imagecopyresized($im2, $im, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig)) {

                // Set the margins for the watermark 
                $marge_right = 10; 
                $marge_bottom = 10; 
                 
                // Get the height/width of the watermark image 
                $sx = imagesx($watermarkImg);  // 429
                $sy = imagesy($watermarkImg);  // 112

                $offsetX = (imagesx($im2) - ($sx + $spacing)) / 2;
                $offsetY = (imagesy($im2) - ($sy + $spacing)) / 2;
                
                // Copy the watermark image onto our photo using the margin offsets and  
                // the photo width to calculate the positioning of the watermark. 
				imagecopymerge($im2, $watermarkImg, $offsetX, $offsetY, 0, 0, $sx, $sy, 25);
				// imagecopy($im2, $watermarkImg, $offsetX, $offsetY, 20, 20, $sx, $sy); 
                

                // Save image and free memory 
                imagejpeg($im2, $targetFilePath); 
                imagedestroy($im2);
     
                if(file_exists($targetFilePath)){ 
                    $statusMsg = "The image with watermark has been uploaded successfully."; 
                }else{ 
                    $statusMsg = "Image upload failed, please try again."; 
                }  
            	}
            }else{ 
                $statusMsg = "Sorry, there was an error uploading your file."; 
            } 
        }else{ 
            $statusMsg = 'Sorry, only JPG, JPEG, and PNG files are allowed to upload.'; 
        } 
    }else{ 
        $statusMsg = 'Please select a file to upload.'; 
    } 
  // }
	echo $statusMsg;
// Display status message 
} 

?>
