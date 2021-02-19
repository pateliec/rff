<?php
$url = parse_url($_SERVER['REQUEST_URI']);
$tokenId = ltrim($url['query'], '?tokenId=');
?>
<div class="view" style="margin-top: 16%;">
    <div class="container">
        
  <main class="content wrap">
    <section class="registrationpage reset-pwdpage">
     
              
      <div class="active">
        <div class="modal_content boxcontent">
          <span>
            <h3 class="modal_title">
             RESET PASSWORD
            </h3>
          </span>
          <form method="post" action="<?= base_url().'account/resetAction' ?>" class="haslabels registerform">

            <ul class="form form-group">
              <li class="expand active">
                <input type="hidden" name="token_id" class="form-control modal-textfield" placeholder="Email" value="<?= $tokenId ?>"/>
              </li>
              <li class="expand active">
              <input type="password" name="password" value="" class="form-control modal-textfield" placeholder="Enter Password"/>
              </li>
              <li class="expand active">
              <input type="password" name="password" value="" class="form-control modal-textfield" placeholder="Confirm Password"/>
              </li>
              </ul>

               <button type="submit" class="btn btn-primary register-button "> RESET PASSWORD</button>
             
              

          </form>
        </div>
      </div>
    </section>
  </main>
</div>
</div>
</div>


