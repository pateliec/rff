<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
$resource = $GetResourcesResult['Resources']; 
?>
<div class="giftcards">
	<div class="buyagftcard">
    <main class="content wrap">
        <section class="booking cards">
            <header class="title">
                <h1>Buy Gift Card</h1>
            </header>
            <form>
                <span></span>
                <div class="booking_data" id="table">
                    <ul class="booking_table passengers giftcards">
                          	<li class="head">
                                <ul>
                                    <li class="cardvalue">Select a Gift Card amount</li>
                                </ul>
                          	</li>
                          	<?php foreach($resource as $res): if($res['Description']): ?>
	                           	<li>
	                                <ul>
	                                    <li class="cardvalue"><?= $res['Description']; ?></li>
	                                </ul>
	                          	</li>
                          	<?php endif; endforeach; ?>
                    </ul>
                </div>
                <div class="actions">
                    <button class="button buycard">Buy Gift Card
                    </button>
                </div>
            </form>
        </section>
    </main>
</div></div>