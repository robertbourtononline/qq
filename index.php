<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once "views/header.php";
include_once "FileUtility.php";
require_once "email/EmailApp.php";

if (isset($_COOKIE['price_changed'])) {
   // header("Location: https://www.biblegateway.com/passage/?search=Matthew%206%3A14-15&version=KJV");
   // exit;
} 





if (isset($_POST['send'])) {
   $name = $_POST['name'];
   $address = $_POST['address'];
   $email = $_POST['email'];


	$subject = 'test';
	$body = 'this is a test';
	$attachment = [];

	// Count # of uploaded files in array
	$total = count($_FILES['upload']['name']);

	// Loop through each file
	for( $i=0 ; $i < $total ; $i++ ) {
		//Get the temp file path
		$tmpFilePath = $_FILES['upload']['tmp_name'][$i];

		//Make sure we have a file path
		if ($tmpFilePath != ""){
			//Setup our new file path
			$newFilePath =  $_FILES['upload']['name'][$i];
			$attachment[] = $_FILES['upload']['name'][$i];
			//Upload the file into the temp dir
			if(move_uploaded_file($tmpFilePath, $newFilePath)) {

			//Handle other code here

			}
  		}
	}
    // naturemyhome@gmail.com
	
	$email = new Email("robertbourton777@gmail.com", $subject, $body, $attachment);
	if ($email->sendEmail()) {
		echo "Email was sent";
		
	} else {
		echo "Email was not sent";
	}
}



use FileUtility\FileUtility;
$path_to_trim_images = "assets/img/trim/";
$path_to_accessories_images = "assets/img/accessories/";

$trim_fileName = 'trim_price_list.csv';
$profile_panel_fileName = 'profile_panel_price.csv';
$color_fileName = 'color_data.csv';
$accessories_fileName = 'accessories.csv';

$util = new FileUtility();
$trim_list = $util->csv_to_array_array($trim_fileName, 2);
$profile_list = $util->csv_to_array_array($profile_panel_fileName, 2);
$color_names = $util->csv_first_column($color_fileName);

$accessories = $util->csv_to_array_array($accessories_fileName, 2);


?>


<div id="image">
    <img src="assets/img/logo.png" alt="..." />
</div>

	
<h1 id="app_title">Quick Quote</h1>


<section id='profile_section'>
   <div>
      <label id='profile_title' for="profile">Select Profile</label>
      <select id="profile">
         <option selected disabled>Pick a profile here</option>
         
         <?php foreach($profile_list as $name => $price) {  ?>
            <option data-price=<?= $price[0] ?>><?= $name ?></option>
         <?php } ?>
      </select>
   </div>
   <div id='profile_img_section'>
      <!-- We can auto load these or change them up depending on profile selected -->
      <img src="assets/img/tuffrib.png"   alt="">
      <div>Tuff Rib</div>
      <img src="assets/img/diamondrib.png"  alt="">
      <div>Diamond Rib</div>
   </div>
</section>




<section id='pick_color'>
   <div>
      <label for="color">Select Color</label>
      <select id="color">
      
         <?php foreach($color_names as $color) { 
            $color_strip = str_replace(' ', '', strtolower($color));   
         ?>
            <option value=<?= $color_strip ?>><?= $color ?></option>
         <?php } ?>

      </select> 
   </div>
      
   <div id='color_page'>
      <a href='color_samples.php'>Check out our color page</a>
   </div>
</section>

<section id='panel_section'>
   <h3>Tuff Rib Panels</h3>
   <h6>Panel Total: <span class='panel_sum'></span></h6>
   
   <div id='clone_parent'>
      <div id='clone_section' class='clone'>
         <div>
            <label for="qty">Qty</label>
            <input class='qty' type="text" id="panel_qty">
         </div>
         
         <div>
            <label for="feet">Feet</label>
            <input class='feet' type="text" id="panel_feet">	
         </div>
         
         <div>
            <label for="inches">Inches</label>
            <input class='inches' type="text" id='panel_inches'>	
         </div>

         <div>
            <div>
               <p class='panel_total dollar' id='panel_total_0'></p>
               <span class='panel_delete'></span>
            </div>
         </div>
         
         <div class='req'>
            <div>Qty and Feet field are required</div>
         </div>
         
      </div>
   </div>
</section>
<hr>
<section class='products'>
   <h3 class='product_title'>Trim</h3>
   
   <div>
      <h6>Trim Total: <span class='trim_sum'></span></h6>
      <a href='#' id='trim_button'>Hide Trim Section</a>
   </div>
   <div class="container_products">
      <?php foreach ($trim_list as $name => $price ) { 
         $name_strip = str_replace(' ', '', strtolower($name));
         ?>
         
            <div class='sub_container_products'>
               <div>
                  <h6><?= $name ?></h6>
                  <img src= <?= $path_to_trim_images . $name_strip . ".jpg" ?>>
               </div>

               <div class='products_total'>
                  <label for="qty">Qty</label>
                  <input type="text" class="trim_qty"  placeholder="qty">	
                  <div>
                     <h6 data-price=<?= $price[0] ?> data-name=<?= "'" . $name ."'" ?> class='trim_total_amount dollar'></h6>
                  </div>
               </div>
            </div>
      <?php } ?>
   </div>
</section>

   <hr>
   <section class='products'>
      <h3 class='product_title'>Accessories</h3>
      <div>
         <h6>Accessories Total: <span class='accessories_sum'></span></h6>
         <a href='#' id='accessories_button'>Hide Accessories Section</a>
      </div>
      <div class="container_products">
         <?php foreach ($accessories as $name => $price ) { 
            $name_strip = str_replace(' ', '', strtolower($name));
            ?>
            
               <div class='sub_container_products'>
                  <div>
                     <h6 data-price=<?= $price[0] ?> data-name=<?= "'" . $name ."'" ?>  id=<?= $name_strip  ?>><?= $name ?> </h6>
                     <img src= <?= $path_to_accessories_images . $name_strip . ".jpg" ?> class=''>
                  </div>

                  <div class='products_total'>
                     <label for="qty">Qty</label>
                     <input type="text" class=""  placeholder="qty">	
                     <div>
                        <h6 data-price=<?= $price[0] ?> data-name=<?= "'" . $name ."'" ?> class='accessories_total_amount dollar'></h6>
                     </div>
                  </div>
               </div>
         <?php } ?>
      </div>
   </section>





<hr>











		
		<section>
			<div>
				<p><a href='#customer_info'>Send Us Your Custom Trim</a></p>
			</div>
		   
         <div class='img_enlarge'>
            <img src="" alt="Enlarged Image">
            <span></span>
         </div>
      </section>
	
	

	<table class="">
  <tbody>
    <tr>
      <th>Profile</th>
      <td id='profile_selected'>Tuff Rib</td>
    </tr>
    <tr>
      <th>Color</th>
      <td id='color_selected'>Stone Grey</td>
    </tr>
	<tr>
      <th>Panel Total</th>
      <td class='panel_sum'></td>
    </tr>

	<tr>
      <th>Trim Total</th>
      <td class='trim_sum'></td>
     
     
    </tr>
	<tr>
      <th>Sub Total</th>
      <td id='subtotal'></td>
     
     
    </tr>
    <tr>
      <th>Tax</th>
      <td id='tax'></td>
    </tr>
	<tr>
      <th>Total</th>
      <td id='grand-total'></td>
    </tr>
  </tbody>
</table>











<form action="" method='POST' enctype="multipart/form-data">
   <div class='container' id='customer_info'>
      <div class="mb-2">
         <label for="name" class="form-label">Name</label>
         <input type="name" class="form-control" id="admin_form_name" name='name' aria-describedby="nameHelp">	
      </div>
      
      <div class="mb-2">
         <label for="address" class="form-label">Address</label>
         <input type="text" class="form-control" id="admin_form_address" name='address' aria-describedby="addressHelp">
      </div>
      
      <div class="mb-2">
         <label for="phone" class="form-label">Phone</label>
         <input type="text" class="form-control" id="admin_form_phone" name='phone' aria-describedby="phoneHelp">
      </div>
      
      <div class="mb-2">
         <label for="email" class="form-label">Email</label>
         <input type="email" class="form-control" id="admin_form_email" name='email' aria-describedby="emailHelp">
      </div>

      <div>
         <h3>Send us your custom trim drawings</h3>
         <input name="upload[]" type="file" multiple />
      </div>
      <div>
         <input name='send' type="submit" value="Send">
      </div>
	</div>
</form>

	